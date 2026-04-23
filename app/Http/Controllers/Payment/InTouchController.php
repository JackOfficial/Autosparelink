<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Order, OrderItem};
use Illuminate\Support\Facades\{DB, Log};

class InTouchController extends Controller
{
    public function handleCallback(Request $request)
    {
        // 1. Log full payload for debugging
        Log::info('InTouch Callback Received:', $request->all());

        // InTouch sends data directly in the root of the request
        $status = $request->input('status'); // 'Successfull' or 'failed'
        $gatewayTransactionId = $request->input('transactionid'); // Gateway's ID
        $localRequestId = $request->input('requesttransactionid'); // Your AST-... ID

        if (!$gatewayTransactionId || !$localRequestId) {
            return response()->json(['message' => 'Invalid Callback Data'], 400);
        }

        // 2. Process Success
        if ($status === 'Successfull') {
            DB::transaction(function () use ($gatewayTransactionId, $localRequestId) {
                
                // Find the main order using the gateway ID we stored during placeOrder
                $order = Order::where('transaction_id', $gatewayTransactionId)
                              ->orWhere('order_id', $localRequestId)
                              ->first();

                if ($order && $order->status !== 'completed') {
                    // Update main order status
                    $order->update(['status' => 'completed']);

                    // Find and update all items for this order
                    // Locking prevents double-processing if multiple callbacks arrive
                    $items = OrderItem::where('order_id', $order->id)
                                      ->lockForUpdate()
                                      ->get();

                    foreach ($items as $item) {
                        if ($item->status !== 'completed') {
                            $item->status = 'completed';
                            // save() is called individually to trigger your Vendor Payout Observers
                            $item->save(); 
                        }
                    }
                }
            });

            return response()->json([
                'status' => 'success',
                'success' => true,
                'requesttransactionid' => $localRequestId
            ], 200);
        }

        // 3. Handle Failures
        Log::warning("InTouch Payment failed for Order: {$localRequestId}. Status: {$status}");
        
        return response()->json([
            'status' => 'failed',
            'success' => false,
            'requesttransactionid' => $localRequestId
        ], 200); 
    }
}