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
        // 1. Log the full payload for audit trails
        Log::info('InTouch Callback Received:', $request->all());

        $status = $request->input('status'); // 'Successfull'
        $gatewayTransactionId = $request->input('transactionid'); // Gateway's numeric ID
        $localRequestId = $request->input('requesttransactionid'); // Your reference (e.g., AST-...)

        if (!$gatewayTransactionId || !$localRequestId) {
            return response()->json(['message' => 'Invalid Callback Data'], 400);
        }

        // 2. Process Success
        if (strtolower($status) === 'successfull' || strtolower($status) === 'success') {
            DB::beginTransaction();
            try {
                // Eager load everything needed to prevent N+1 queries during loop
                // Using lockForUpdate to prevent race conditions if multiple callbacks hit
                $order = Order::where('order_number', $localRequestId) 
                              ->orWhere('transaction_id', $gatewayTransactionId)
                              ->with(['orderItems.part', 'user'])
                              ->lockForUpdate()
                              ->first();

                // Validation: Only proceed if order exists and isn't already paid/processing
                if ($order && !in_array($order->status, ['completed', 'processing'])) {
                    
                    // A. Update Main Order Status
                    $order->update(['status' => 'processing']);

                    // B. Create Payment Record (Essential for your isPaid() helper)
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

                    Log::info('Payment Save Attempt:', [
    'was_successful' => $payment->wasRecentlyCreated || $payment->wasChanged(),
    'payment_id' => $payment->id,
    'errors' => $payment->getErrors() // If you use a validation trait
]);

                    // C. Handle Order Items & Stock Management
                    // This specifically triggers your OrderItemObserver->updated()
                    foreach ($order->orderItems as $item) {
                        
                        if ($item->status != 'completed') {
                            // 1. Update status (This triggers Vendor Payout logic)
                            Log::info("Processing Item #{$item->id} for Vendor Payout.");
                            $item->status = 'pending';
                            $item->save(); 

                            // 2. Decrement Stock
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

                    // E. Send Invoice Email (Safely outside the core transaction)
                    $this->sendInvoice($order);

                    Log::info("Order #{$order->order_number} successfully finalized via InTouch.");

                } else {
                    // Logic for already processed orders
                    DB::rollBack();
                    Log::info("InTouch Callback ignored: Order #{$localRequestId} already processed.");
                }

                return response()->json([
                    'status' => 'success',
                    'success' => true,
                    'requesttransactionid' => $localRequestId
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("InTouch Callback Fatal Error: " . $e->getMessage(), [
                    'order_id' => $localRequestId,
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json(['status' => 'error'], 500);
            }
        }

        // 3. Handle Non-Success Statuses
        Log::warning("InTouch Payment not successful for Order: {$localRequestId}. Status: {$status}");
        
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
            Log::error('InTouch Invoice Email Failed to Send: ' . $e->getMessage());
        }
    }
}