<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Order, OrderItem, Payment, Shipping};
use App\Mail\OrderPaidInvoice;
use Illuminate\Support\Facades\{DB, Log, Mail};

class InTouchController extends Controller
{
    public function handleCallback(Request $request)
    {
        // 1. Log the full payload for traceability
        Log::info('InTouch Callback Received:', $request->all());

        $status = $request->input('status'); // 'Successfull'
        $gatewayTransactionId = $request->input('transactionid'); // Gateway ID
        $localRequestId = $request->input('requesttransactionid'); // AST-... reference

        if (!$gatewayTransactionId || !$localRequestId) {
            return response()->json(['message' => 'Invalid Callback Data'], 400);
        }

        // 2. Process Success
        if ($status === 'Successfull') {
            DB::beginTransaction();
            try {
                // Find order using the gateway ID stored during placement or local reference
                $order = Order::where('transaction_id', $gatewayTransactionId)
                              ->orWhere('order_id', $localRequestId)
                              ->with(['orderItems.part', 'user'])
                              ->first();

                // Only process if order exists and isn't already finalized
                if ($order && !in_array($order->status, ['completed', 'processing'])) {
                    
                    // A. Update Main Order Status
                    $order->update(['status' => 'processing']);

                    // B. Create Payment Record (Integrated from your old logic)
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

                    // C. Handle Order Items & Stock Management
                    $items = OrderItem::where('order_id', $order->id)
                                      ->lockForUpdate()
                                      ->get();

                    foreach ($items as $item) {
                        if ($item->status !== 'completed') {
                            // save() triggers your Vendor Payout Observer
                            $item->status = 'completed';
                            $item->save(); 

                            // Decrement Stock (Integrated logic)
                            if ($item->part) {
                                $item->part->decrement('stock_quantity', $item->quantity);
                            }
                        }
                    }

                    // D. Initialize Shipping record
                    Shipping::updateOrCreate(
                        ['order_id' => $order->id],
                        ['status' => 'pending']
                    );

                    DB::commit();

                    // E. Send Invoice Email (After commit to ensure data integrity)
                    $this->sendInvoice($order);

                } else {
                    DB::rollBack(); // Order already processed or not found
                }

                return response()->json([
                    'status' => 'success',
                    'success' => true,
                    'requesttransactionid' => $localRequestId
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('InTouch Callback Processing Error: ' . $e->getMessage());
                return response()->json(['status' => 'error'], 500);
            }
        }

        // 3. Handle Failures
        Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");
        
        return response()->json([
            'status' => 'failed',
            'success' => false,
            'requesttransactionid' => $localRequestId
        ], 200); 
    }

    /**
     * Send Invoice Email to User or Guest
     */
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