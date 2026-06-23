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
        // 1. Trigger vendor payment only when status explicitly flips to 'completed'
        // This follows your logic for processing vendor payments
        if ($orderItem->isDirty('status') && $orderItem->status == 'completed') {
            $this->processVendorPayment($orderItem);
        }
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
        
        // CRITICAL FIX: Lock the primary wallet record row immediately!
        // This stops any parallel request from executing code on this wallet.
        $lockedWallet = DB::table('wallets')
            ->where('id', $wallet->id)
            ->lockForUpdate()
            ->first();

        // Re-check for existing transaction safely now that the room is locked
        $alreadyPaid = WalletTransaction::where('reference_type', OrderItem::class)
            ->where('reference_id', $orderItem->id)
            ->exists();

        if ($alreadyPaid) {
            return;
        }

        $vendorNetEarnings = $orderItem->shop_payout * $orderItem->quantity;
        $totalCustomerPaid = $orderItem->unit_price * $orderItem->quantity;
        $adminMarkupRevenue = $totalCustomerPaid - $vendorNetEarnings;

        $orderItem->updateQuietly([
            'commission_amount' => $adminMarkupRevenue
        ]);

        $wallet->transactions()->create([
            'type'           => 'credit',
            'amount'         => $vendorNetEarnings,
            'service_fee'    => $adminMarkupRevenue,
            'fee_percentage' => $orderItem->applied_rate,
            'reference_type' => OrderItem::class,
            'reference_id'   => $orderItem->id,
            'description'    => "Earnings for: " . ($orderItem->part->part_name ?? 'Spare Part'),
            'status'         => 'completed',
        ]);
    });
}
}