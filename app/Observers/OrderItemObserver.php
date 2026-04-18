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
        Log::info("Observer triggered for OrderItem #{$orderItem->id}. Status: {$orderItem->status}");

        if ($orderItem->isDirty('status')) {
        Log::info("Status is dirty. New status: " . $orderItem->status);
        }

        // 1. Check if the status has flipped to 'completed'
        if ($orderItem->isDirty('status') && $orderItem->status == 'completed') {
            $this->processVendorPayment($orderItem);
        }
    }

    /**
     * Logic to calculate split and credit the vendor wallet
     */
    private function processVendorPayment(OrderItem $orderItem): void
    {
        $shop = $orderItem->shop;
        
        if (!$shop || !$shop->wallet) {
            Log::error("Payment failed: Shop or Wallet missing for OrderItem #{$orderItem->id}");
            return;
        }

        $wallet = $shop->wallet;

        // Perform the logic inside a transaction to ensure integrity
        DB::transaction(function () use ($wallet, $shop, $orderItem) {
            
            // 1. Re-check inside the transaction with a lock to be 100% safe
            $alreadyPaid = WalletTransaction::where('reference_type', OrderItem::class)
                ->where('reference_id', $orderItem->id)
                ->lockForUpdate()
                ->exists();

            if ($alreadyPaid) {
                return;
            }

            // 2. Calculations
            // UPDATED: Now using the simplified global rate method
            $feePercentage = Commission::getRate(); 
            
            $itemSubtotal = $orderItem->unit_price * $orderItem->quantity;
            
            // Ensure we use 2 decimal places for financial precision
            $adminServiceFee = round(($itemSubtotal * $feePercentage) / 100, 2);
            $vendorNetEarnings = $itemSubtotal - $adminServiceFee;

            // 3. Persist local record
            // Use updateQuietly to avoid triggering this observer again in an infinite loop
            $orderItem->updateQuietly([
                'commission_amount' => $adminServiceFee
            ]);

            // 4. Trigger the wallet flow
            $wallet->transactions()->create([
                'type'           => 'credit',
                'amount'         => $vendorNetEarnings,
                'service_fee'    => $adminServiceFee,
                'fee_percentage' => $feePercentage,
                'reference_type' => OrderItem::class,
                'reference_id'   => $orderItem->id,
                'description'    => "Earnings for item: " . ($orderItem->part->part_name ?? 'Spare Part') . " (Order #{$orderItem->order_id})",
                'status'         => 'completed',
            ]);
        });
    }
}