<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Order, OrderItem, Payment, PaymentLog, Shipping};
use App\Mail\OrderPaidInvoice;
use Illuminate\Support\Facades\{DB, Log, Mail};

class InTouchController extends Controller
{
    public function handleCallback(Request $request)
    {
        // 1. Capture and Log the raw hit
        $rawData = $request->all();
        Log::info('InTouch Callback Received:', $rawData);

        /**
         * 2. Handle the "jsonpayload" Wrapper
         * Based on Beeceptor testing, the real server wraps data in 'jsonpayload'.
         * If it's missing, we fallback to the flat $rawData.
         */
        $data = isset($rawData['jsonpayload']) ? $rawData['jsonpayload'] : $rawData;

        // 3. Extract variables with flexible fallbacks for keys
        $status = $data['status'] ?? null; 
        $gatewayTransactionId = $data['transactionid'] ?? null; 
        $localRequestId = $data['requesttransactionid'] ?? null; 
        
        // Error description can come in multiple formats
        $errorMessage = $data['statusdesc'] ?? $data['description'] ?? $data['message'] ?? 'Unknown Error';

        if (!$gatewayTransactionId || !$localRequestId) {
            Log::error('InTouch Callback: Missing IDs.', ['payload' => $data]);
            return response()->json([
                'message' => 'Invalid Callback Data',
                'success' => false,
                'request_id' => $localRequestId ?? 'unknown'
            ], 400);
        }

        /**
         * 4. Success Logic
         * Using strtolower to catch 'Successfull' (two l's) or 'success'
         */
        if (in_array(strtolower($status), ['successfull', 'success'])) {
            
            // Find order by order_number (localRequestId)
            $order = Order::where('order_number', $localRequestId) 
                          ->orWhere('transaction_id', $gatewayTransactionId)
                          ->with(['orderItems.part', 'user'])
                          ->first();

            if (!$order) {
                Log::error("InTouch Callback: Order not found for reference {$localRequestId}");
                return response()->json([
                    'message' => 'Order not found',
                    'success' => false,
                    'request_id' => $localRequestId
                ], 404);
            }

            // 5. Audit Trail - Log everything for troubleshooting
            PaymentLog::create([
                'user_id'        => $order->user_id, 
                'tx_ref'         => $localRequestId,
                'transaction_id' => $gatewayTransactionId,
                'amount'         => $data['amount'] ?? $order->total_amount,
                'currency'       => $data['currency'] ?? 'RWF',
                'status'         => $status,
                'error_message'  => null,
                'raw_response'   => json_encode($rawData)
            ]);

            // 6. Process Business Logic (Idempotency check included)
            if (!in_array($order->status, ['completed', 'processing'])) {
                DB::beginTransaction();
                try {
                    // Include 'user' here so the invoice method has it ready
                    $order = Order::with(['orderItems.part', 'user'])
                                  ->where('id', $order->id)
                                  ->lockForUpdate()
                                  ->first();

                    // A. Update Main Order
                    $order->update([
                        'total_amount' => $data['amount'] ?? $order->total_amount,
                        'status' => 'processing',
                    ]);

                    // B. Finalize Payment Record
                    Payment::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'amount' => $order->total_amount,
                            'method' => 'intouchpay',
                            'transaction_reference' => $gatewayTransactionId,
                            'status' => 'successful',
                            'paid_at' => now()
                        ]
                    );

                    // C. Handle Items & Inventory (Individual saves keep your observers happy)
                    // FIX: Chain lockForUpdate()->get() to prevent relation caching traps
                    foreach ($order->orderItems()->lockForUpdate()->get() as $item) {
                        if ($item->status != 'completed') {
                            $item->status = 'processing';
                            $item->save(); 

                            if ($item->part) {
                                $item->part->decrement('stock_quantity', $item->quantity);
                            }
                        }
                    }

                    // D. Initialize Shipping
                    Shipping::updateOrCreate(
                        ['order_id' => $order->id],
                        ['status' => 'pending']
                    );

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("InTouch Callback Fatal Error: " . $e->getMessage());
                    return response()->json([
                        'message' => 'Internal processing error',
                        'success' => false,
                        'request_id' => $localRequestId
                    ], 500);
                }
            } else {
                Log::info("InTouch Callback: Order #{$localRequestId} already processed.");
            }

            /**
             * FIX LOCATION: 
             * Executed outside of the state modifier blocks. Regardless of whether this thread 
             * changes the state or an alternate checkout redirection handles it first, the invoice is sent.
             */
            $this->sendInvoice($order);

            return response()->json([
                'message' => 'success',
                'success' => true,
                'request_id' => $localRequestId
            ], 200);
        }

        // 7. Handle Failed/Canceled Payments
        Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");
        return response()->json([
            'message' => $errorMessage,
            'success' => false,
            'request_id' => $localRequestId
        ], 200); 
    }

    private function sendInvoice($order)
    {
        try {
            $recipientEmail = $order->user ? $order->user->email : $order->guest_email;
            if ($recipientEmail) {
                Mail::to($recipientEmail)->send(new OrderPaidInvoice($order));
            }
        } catch (\Exception $e) {
            Log::error('InTouch Invoice Email Failed: ' . $e->getMessage());
        }
    }
}