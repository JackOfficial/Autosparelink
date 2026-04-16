<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\WalletTransaction;
use App\Models\Commission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "updated" event.
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
        // Use the relationship to get the shop and wallet
        $shop = $orderItem->shop;
        
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

        // 3. Dynamic Financial Calculations
        $feePercentage = Commission::getRateForShop($shop->id); 
        
        // MATCHED TO MODEL: Changed 'price' to 'unit_price'
        $itemSubtotal = $orderItem->unit_price * $orderItem->quantity;
        $adminServiceFee = ($itemSubtotal * $feePercentage) / 100;
        $vendorNetEarnings = $itemSubtotal - $adminServiceFee;

        // 4. Atomic Transaction to ensure database consistency
        DB::transaction(function () use ($wallet, $vendorNetEarnings, $adminServiceFee, $feePercentage, $orderItem) {
            
            // NEW: Save the calculated commission to the OrderItem for record-keeping
            $orderItem->commission_amount = $adminServiceFee;
            $orderItem->save();

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
                // MATCHED TO MODEL: Changed 'product_name' to 'part_name'
                'description' => "Earnings for item: {$orderItem->part_name} (Order #{$orderItem->order->order_number})",
                'status' => 'completed',
            ]);

            // Update the last activity timestamp
            $wallet->update(['last_transaction_at' => now()]);
        });
    }

    public function created(OrderItem $orderItem): void
    {
        //
    }

    public function deleted(OrderItem $orderItem): void
    {
        //
    }

    public function restored(OrderItem $orderItem): void
    {
        //
    }

    public function forceDeleted(OrderItem $orderItem): void
    {
        //
    }
}