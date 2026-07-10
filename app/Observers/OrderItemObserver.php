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
     */
    public function updated(OrderItem $orderItem): void
    {
        // If the status didn't change, or it didn't change to 'completed', EXIT IMMEDIATELY.
        if (!$orderItem->isDirty('status') || $orderItem->status !== 'completed') {
            return;
        }

        $this->processVendorPayment($orderItem);
    }

    /**
     * Credit the vendor wallet based on the markup model
     */
    private function processVendorPayment(OrderItem $orderItem): void
    {
        $shop = $orderItem->shop;
        if (!$shop || !$shop->wallet) {
            Log::error("Payment failed: Shop or Wallet missing for OrderItem #{$orderItem->id}");
            return;
        }

        $wallet = $shop->wallet;

        DB::transaction(function () use ($wallet, $orderItem) {
            
            // 1. Lock the order item row to securely serialize concurrent processing attempts
            $lockedItem = OrderItem::where('id', $orderItem->id)->lockForUpdate()->first();

            if (!$lockedItem || $lockedItem->status !== 'completed') {
                return;
            }

            // 2. Idempotency Check: Now completely safe under the row lock
            $alreadyPaid = WalletTransaction::where('reference_type', OrderItem::class)
                ->where('reference_id', $orderItem->id)
                ->exists();

            if ($alreadyPaid) {
                Log::info("OrderItem #{$orderItem->id} payment skipped: already processed.");
                return;
            }

            // 3. Financial calculations
            $vendorNetEarnings = $orderItem->shop_payout * $orderItem->quantity;
            $totalCustomerPaid = $orderItem->unit_price * $orderItem->quantity;
            $adminMarkupRevenue = $totalCustomerPaid - $vendorNetEarnings;

            // 4. Update the order item silently if needed
            // $orderItem->updateQuietly([
            //     'commission_amount' => $adminMarkupRevenue
            // ]);

            // [STEP 5 REMOVED] -> DO NOT manually increment balance here. 
            // The creation of the WalletTransaction below will auto-trigger the model event to increment it safely.

            // 5. Log the audit transaction history record (Triggers model boot event balance update)
            $wallet->transactions()->create([
                'type'           => 'credit',
                'amount'         => $vendorNetEarnings,
                'service_fee'    => $adminMarkupRevenue,
                'fee_percentage' => $orderItem->commission_amount ?? 0, 
                'reference_type' => OrderItem::class,
                'reference_id'   => $orderItem->id,
                'description'    => "Earnings for: " . ($orderItem->part_name ?? $orderItem->part?->part_name ?? 'Spare Part'),
                'status'         => 'completed', // 'completed' matches the static::created criterion to update the wallet balance
            ]);
        });
    }
}