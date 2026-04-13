<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "updated" event.
     * We monitor the status change to trigger vendor payments.
     */
    public function updated(OrderItem $orderItem): void
    {
        // 1. Check if the status has flipped to 'completed'
        if ($orderItem->isDirty('status') && $orderItem->status === 'completed') {
            $this->processVendorPayment($orderItem);
        }
    }

    /**
     * Logic to calculate split and credit the vendor wallet
     */
    private function processVendorPayment(OrderItem $orderItem): void
    {
        $shop = $orderItem->shop;
        
        // Ensure shop and wallet exist before proceeding
        if (!$shop || !$shop->wallet) {
            Log::error("Payment failed for OrderItem #{$orderItem->id}: Shop or Wallet missing.");
            return;
        }

        $wallet = $shop->wallet;

        // 2. Prevent duplicate crediting for this specific OrderItem ID
        $alreadyPaid = WalletTransaction::where('reference_type', OrderItem::class)
            ->where('reference_id', $orderItem->id)
            ->exists();

        if ($alreadyPaid) {
            return;
        }

        // 3. Financial Calculations
        // Note: In a production environment, '10.00' should be a dynamic setting 
        // from a database or a $shop->commission_rate column.
        $feePercentage = 10.00; 
        $itemSubtotal = $orderItem->price * $orderItem->quantity;
        $adminServiceFee = ($itemSubtotal * $feePercentage) / 100;
        $vendorNetEarnings = $itemSubtotal - $adminServiceFee;

        // 4. Atomic Transaction to ensure database consistency
        DB::transaction(function () use ($wallet, $vendorNetEarnings, $adminServiceFee, $feePercentage, $orderItem) {
            
            // Increment the balance in the wallet table
            $wallet->increment('balance', $vendorNetEarnings);

            // Create the audit trail in the transactions table
            $wallet->transactions()->create([
                'type' => 'credit',
                'amount' => $vendorNetEarnings,
                'service_fee' => $adminServiceFee,
                'fee_percentage' => $feePercentage,
                'reference_type' => OrderItem::class,
                'reference_id' => $orderItem->id,
                'description' => "Earnings for item: {$orderItem->product_name} (Order #{$orderItem->order->order_number})",
                'status' => 'completed',
            ]);

            // Update the last activity timestamp
            $wallet->update(['last_transaction_at' => now()]);
        });
    }

    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        // Optional: Logic for when an item is first added to an order
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        // Safety check: If an item is deleted after being paid, 
        // you might want to log it for admin review.
    }

    /**
     * Handle the OrderItem "restored" event.
     */
    public function restored(OrderItem $orderItem): void
    {
        //
    }

    /**
     * Handle the OrderItem "force deleted" event.
     */
    public function forceDeleted(OrderItem $orderItem): void
    {
        //
    }
}