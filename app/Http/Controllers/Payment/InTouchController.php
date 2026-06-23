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
        $data = rawData['jsonpayload'] ?? $rawData;

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

        $isSuccessPayload = in_array(strtolower($status), ['successfull', 'success']);

        /**
         * 4. Find order cleanly by order_number first
         */
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

        /**
         * 5. Process Business Logic inside an Isolated Transaction
         */
        DB::beginTransaction();
        try {
            // LOCK THE ORDER ROW IMMEDIATELY
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            // IDEMPOTENCY CHECK: If already paid or processing, return success and halt execution
            if (in_array($order->status, ['completed', 'processing'])) {
                DB::rollBack();
                Log::info("InTouch Callback: Order #{$localRequestId} has already been securely processed.");
                return response()->json([
                    'message' => 'success',
                    'success' => true,
                    'request_id' => $localRequestId
                ], 200);
            }

            // Handle Successful Payments
            if ($isSuccessPayload) {
                
                // Log Audit Trail Inside the locked transaction space to guarantee uniqueness
                PaymentLog::create([
                    'user_id'        => $order->user_id,
                    'tx_ref'         => $localRequestId,
                    'transaction_id' => $gatewayTransactionId,
                    'amount'         => $data['amount'] ?? $order->total_amount,
                    'currency'       => $data['currency'] ?? 'RWF',
                    'status'         => $status,
                    'error_message'  => null,
                    'raw_response'   => $rawData
                ]);

                // A. Update Main Order State
                $order->update([
                    'status' => 'processing',
                    'transaction_id' => $gatewayTransactionId
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
                    // Critical safety: only decrement stock if it is currently 'pending'
                    if ($item->status === 'pending') {
                        $item->status = 'processing';
                        $item->save(); 

                        if ($item->part) {
                            $securedPart = Part::where('id', $item->part_id)->lockForUpdate()->first();
                            if ($securedPart) {
                                $securedPart->decrement('stock_quantity', $item->quantity);
                            }
                        }
                    }
                }

                // D. Initialize Shipping Container Pipeline
                Shipping::updateOrCreate(
                    ['order_id' => $order->id],
                    ['status' => 'pending']
                );

                DB::commit();

                // E. Send Invoice out-of-bounds from db locks
                $this->sendInvoice($order);

                return response()->json([
                    'message' => 'success',
                    'success' => true,
                    'request_id' => $localRequestId
                ], 200);

            } else {
                // Handle Failed / Cancelled Payments safely inside row isolation
                Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");
                
                if ($order->status === 'pending') {
                    $order->update(['status' => 'failed']);
                }

                DB::commit();

                return response()->json([
                    'message' => $errorMessage,
                    'success' => false,
                    'request_id' => $localRequestId
                ], 200);
            }

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