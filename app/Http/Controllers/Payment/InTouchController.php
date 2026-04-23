<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InTouchController extends Controller
{
    public function handleCallback(Request $request)
    {
        // 1. Log the full payload for debugging and traceability
        Log::info('InTouch Callback Received:', $request->all());

        /**
         * Per documentation, InTouch submits data as 'jsonpayload' 
         * containing status and transaction details.
         */
        $payload = $request->input('jsonpayload');

        // Check if payload exists to avoid errors
        if (!$payload) {
            return response()->json(['message' => 'Invalid Payload'], 400);
        }

        $status = $payload['status'] ?? null; // e.g., 'Successfull' or 'failed' [cite: 92]
        $requestId = $payload['requesttransactionid'] ?? null; // This is your internal Order ID [cite: 89]

        /**
         * Status for success is explicitly "Successfull" in the documentation.
         */
        if ($status === 'Successfull') {
            DB::transaction(function () use ($requestId) {
                // Find the order item and lock it to prevent double-processing vendor payments
                $orderItem = OrderItem::where('order_id', $requestId)
                    ->lockForUpdate()
                    ->first();

                if ($orderItem && $orderItem->status !== 'completed') {
                    /** * Update triggers your Observer for vendor payout.
                     * Ensure your logic uses updateQuietly() inside the observer 
                     * if needed to prevent loops.
                     */
                    $orderItem->status = 'completed';
                    $orderItem->save(); 
                }
            });

            /**
             * The App must respond with HTTP 200 OK and a specific JSON structure[cite: 102, 105, 106, 107].
             */
            return response()->json([
                'message' => 'success',
                'success' => true,
                'request_id' => $requestId
            ], 200);
        }

        Log::warning("InTouch Payment not successful for Order: {$requestId}. Status: {$status}");
        
        return response()->json([
            'message' => 'Payment failed or pending',
            'success' => false,
            'request_id' => $requestId
        ], 200); // Always return 200 to acknowledge the gateway's ping
    }
}