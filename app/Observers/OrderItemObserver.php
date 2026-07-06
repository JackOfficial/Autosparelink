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
        // FIX: If the status didn't change, or it didn't change to 'completed', 
        // EXIT IMMEDIATELY. Do not let the execution proceed or evaluate relations.
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
            
            $lockedWallet = DB::table('wallets')
                ->where('id', $wallet->id)
                ->lockForUpdate()
                ->first();

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
                'description'    => "Earnings for: " . ($orderItem->part?->part_name ?? 'Spare Part'), // Safe navigation
                'status'         => 'completed',
            ]);
        });
    }
}