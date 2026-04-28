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
        // Capture JSON payload as requested by InTouch Support
        $data = $request->isJson() ? $request->json()->all() : $request->all();
        Log::info('InTouch Callback Received:', $data);

        $status = $data['status'] ?? null; 
        $gatewayTransactionId = $data['transactionid'] ?? null; 
        $localRequestId = $data['requesttransactionid'] ?? null; 
        $errorMessage = $data['description'] ?? $data['message'] ?? null;

        if (!$gatewayTransactionId || !$localRequestId) {
            return response()->json([
                'message' => 'Invalid Callback Data',
                'success' => false,
                'request_id' => $localRequestId ?? 'unknown'
            ], 400);
        }

        // 1. Initial Success Check
        if (strtolower($status) == 'successfull' || strtolower($status) == 'success') {
            
            // Logic unchanged: find the order to associate everything
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

            // 2. Audit Trail
            PaymentLog::create([
                'user_id'        => $order->user_id,
                'tx_ref'         => $localRequestId,
                'transaction_id' => $gatewayTransactionId,
                'amount'         => $data['amount'] ?? $order->total_amount,
                'currency'       => 'RWF',
                'status'         => $status,
                'error_message'  => null,
                'raw_response'   => json_encode($data)
            ]);

            // 3. Process Business Logic
            if (!in_array($order->status, ['completed', 'processing'])) {
                DB::beginTransaction();
                try {
                    $order = Order::where('id', $order->id)->lockForUpdate()->first();

                    // A. Update Main Order
                    $order->update(['status' => 'processing']);

                    // B. Finalize Payment Model
                    $payment = Payment::updateOrCreate(
                        ['order_id' => $order->id],
                        [
                            'amount' => $order->total_amount,
                            'method' => 'intouchpay',
                            'transaction_reference' => $gatewayTransactionId,
                            'status' => 'successful',
                            'paid_at' => now()
                        ]
                    );

                    // C. Handle Order Items & Stock (Escrow Logic)
                    foreach ($order->orderItems as $item) {
                        if ($item->status != 'completed') {
                            Log::info("Setting Item #{$item->id} to pending.");
                            $item->status = 'pending';
                            $item->save(); 

                            if ($item->part) {
                                $item->part->decrement('stock_quantity', $item->quantity);
                            }
                        }
                    }

                    // D. Shipping
                    Shipping::updateOrCreate(
                        ['order_id' => $order->id],
                        ['status' => 'pending']
                    );

                    DB::commit();

                    // E. Post-Transaction Email
                    $this->sendInvoice($order);

                    // Updated to return the specific format required by InTouch Support
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
            } else {
                Log::info("InTouch Callback ignored: Order #{$localRequestId} already processed.");
                return response()->json([
                    'message' => 'success',
                    'success' => true,
                    'request_id' => $localRequestId
                ], 200);
            }
        }

        // 4. Handle Non-Success
        Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");
        return response()->json([
            'message' => $errorMessage ?? 'Payment failed',
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