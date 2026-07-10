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
            
            // 1. Lock the wallet row in the DB to prevent race conditions
            $lockedWallet = DB::table('wallets')
                ->where('id', $wallet->id)
                ->lockForUpdate()
                ->first();

            // 2. Idempotency Check: Ensure this item hasn't already been credited
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

            // 4. Update the order item silently to prevent loops
            // $orderItem->updateQuietly([
            //     'commission_amount' => $adminMarkupRevenue
            // ]);

            // 5. UPDATE WALLET BALANCE (The missing step)
            DB::table('wallets')
                ->where('id', $wallet->id)
                ->increment('balance', $vendorNetEarnings);

            // 6. Log the audit transaction history record
            $wallet->transactions()->create([
                'type'           => 'credit',
                'amount'         => $vendorNetEarnings,
                'service_fee'    => $adminMarkupRevenue,
                'fee_percentage' => $orderItem->commission_amount ?? 0, // Fallback safety
                'reference_type' => OrderItem::class,
                'reference_id'   => $orderItem->id,
                'description'    => "Earnings for: " . ($orderItem->part_name ?? $orderItem->part?->part_name ?? 'Spare Part'),
                'status'         => 'completed',
            ]);
        });
    }
}