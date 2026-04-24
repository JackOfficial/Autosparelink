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
        Log::info('InTouch Callback Received:', $request->all());

        $status = $request->input('status'); 
        $gatewayTransactionId = $request->input('transactionid'); 
        $localRequestId = $request->input('requesttransactionid'); 
        $errorMessage = $request->input('description') ?? $request->input('message');

        if (!$gatewayTransactionId || !$localRequestId) {
            return response()->json(['message' => 'Invalid Callback Data'], 400);
        }

        // 1. Initial Success Check
        if (strtolower($status) === 'successfull' || strtolower($status) === 'success') {
            
            // We find the order first to associate the log entry
            $order = Order::where('order_number', $localRequestId) 
                          ->orWhere('transaction_id', $gatewayTransactionId)
                          ->with(['orderItems.part', 'user'])
                          ->first();

            if (!$order) {
                Log::error("InTouch Callback: Order not found for reference {$localRequestId}");
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 2. Audit Trail - Save this OUTSIDE the transaction so it persists even on failure
            PaymentLog::create([
                'user_id'        => $order->user_id,
                'tx_ref'         => $localRequestId,
                'transaction_id' => $gatewayTransactionId,
                'amount'         => $request->input('amount') ?? $order->total_amount,
                'currency'       => 'RWF',
                'status'         => $status,
                'error_message'  => null,
                'raw_response'   => json_encode($request->all())
            ]);

            // 3. Process Business Logic
            if (!in_array($order->status, ['completed', 'processing'])) {
                DB::beginTransaction();
                try {
                    // Lock for update to handle high-concurrency hits
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

                    return response()->json(['status' => 'success', 'success' => true], 200);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("InTouch Callback Fatal Error: " . $e->getMessage());
                    return response()->json(['status' => 'error'], 500);
                }
            } else {
                Log::info("InTouch Callback ignored: Order #{$localRequestId} already processed.");
                return response()->json(['status' => 'already_processed'], 200);
            }
        }

        // 4. Handle Non-Success
        Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");
        return response()->json(['status' => 'failed', 'success' => false], 200); 
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