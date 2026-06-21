<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Order, OrderItem, Part, Payment, PaymentLog, Shipping};
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
         */
        $data = isset($rawData['jsonpayload']) ? $rawData['jsonpayload'] : $rawData;

        // 3. Extract variables with flexible fallbacks for keys
        $status = $data['status'] ?? null; 
        $gatewayTransactionId = $data['transactionid'] ?? null; 
        $localRequestId = $data['requesttransactionid'] ?? null; 
        
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
         */
        if (in_array(strtolower($status), ['successfull', 'success'])) {
            
            // Find order cleanly by order_number first
            $order = Order::where('order_number', $localRequestId)
                          ->with(['orderItems.part', 'user'])
                          ->first();

            // Fallback lookup via Gateway Tx ID if needed
            if (!$order && $gatewayTransactionId) {
                $order = Order::where('transaction_id', $gatewayTransactionId)
                              ->with(['orderItems.part', 'user'])
                              ->first();
            }

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

            // 6. Process Business Logic inside an Isolated Transaction
            DB::beginTransaction();
            try {
                // LOCK FOR UPDATE FIRST before analyzing the state
                $order = Order::where('id', $order->id)->lockForUpdate()->first();

                // Idempotency check securely managed inside the row lock
                if (in_array($order->status, ['completed', 'processing'])) {
                    DB::rollBack();
                    Log::info("InTouch Callback: Order #{$localRequestId} was already processed by another concurrent request.");
                    return response()->json([
                        'message' => 'success',
                        'success' => true,
                        'request_id' => $localRequestId
                    ], 200);
                }

                // A. Update Main Order
                $order->update([
                    'status' => 'processing',
                    'transaction_id' => $gatewayTransactionId // Store reference if missing
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

                // C. Handle Items & Inventory
                foreach ($order->orderItems as $item) {
                    if ($item->status != 'completed') {
                        $item->status = 'processing';
                        $item->save(); 

                        if ($item->part) {
                            // Lock the individual parts table row to prevent negative inventory bugs
                            $securedPart = Part::where('id', $item->part_id)->lockForUpdate()->first();
                            if ($securedPart) {
                                $securedPart->decrement('stock_quantity', $item->quantity);
                            }
                        }
                    }
                }

                // D. Initialize Shipping
                Shipping::updateOrCreate(
                    ['order_id' => $order->id],
                    ['status' => 'pending']
                );

                DB::commit();

                // E. Send Invoice (Outside of the DB Transaction to prevent mail server delays from locking DB)
                $this->sendInvoice($order);

                return response()->json([
                    'message' => 'success',
                    'success' => true,
                    'request_id' => $localRequestId
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("InTouch Callback Fatal Error: " . $e->getMessage());
                return response()->json([
                    'message' => 'Internal processing error',
                    'success' => false,
                    'request_id' => $localRequestId
                ], 500);
            }
        }

        // 7. Handle Failed/Canceled Payments
        Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");

        // Find the pending order and update its status to failed
        $order = Order::where('order_number', $localRequestId)->first();
        if ($order && $order->status === 'pending') {
            $order->update(['status' => 'failed']);
        }

        // Return 200 OK so InTouch knows we acknowledged the failure and stops retrying
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