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

        // Wrapped in a transaction with lockForUpdate to prevent double payments
        DB::transaction(function () use ($wallet, $orderItem) {
            
            // 1. Re-check for existing transaction to ensure integrity
            $alreadyPaid = WalletTransaction::where('reference_type', OrderItem::class)
                ->where('reference_id', $orderItem->id)
                ->lockForUpdate()
                ->exists();

            if ($alreadyPaid) {
                return;
            }

            // 2. Calculations (Markup Model Logic)
            // Vendor gets 100% of their set shop_payout
            $vendorNetEarnings = $orderItem->shop_payout * $orderItem->quantity;
            
            // Platform Revenue (The Markup) = Total Customer Paid - Vendor Base Price
            $totalCustomerPaid = $orderItem->unit_price * $orderItem->quantity;
            $adminMarkupRevenue = $totalCustomerPaid - $vendorNetEarnings;

            // 3. Persist local record using updateQuietly to avoid infinite loops
            $orderItem->updateQuietly([
                'commission_amount' => $adminMarkupRevenue
            ]);

            // 4. Trigger the wallet flow
            $wallet->transactions()->create([
                'type'           => 'credit',
                'amount'         => $vendorNetEarnings, // The base price the shop set
                'service_fee'    => $adminMarkupRevenue, // The profit the platform made via markup
                'fee_percentage' => $orderItem->applied_rate, // The rate applied at time of part creation
                'reference_type' => OrderItem::class,
                'reference_id'   => $orderItem->id,
                'description'    => "Earnings for: " . ($orderItem->part->part_name ?? 'Spare Part'),
                'status'         => 'completed',
            ]);
        });
    }
}