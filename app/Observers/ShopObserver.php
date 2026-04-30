<?php

namespace App\Observers;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopObserver
{
    /**
     * Handle the Shop "updated" event.
     */
    public function updated(Shop $shop): void
    {
        // 1. Check if 'is_verified' flipped to true.
        if ($shop->isDirty('is_verified') && $shop->is_verified === true) {
            
            // 2. Initialize the Wallet if it doesn't exist.
            if (!$shop->wallet()->exists()) {
                $this->createShopWallet($shop);
            }

            // 3. Ensure a commission rate is set for the markup model.
            // Using updateQuietly prevents re-triggering this observer.
            if (is_null($shop->commission_rate)) {
                $shop->updateQuietly([
                    'commission_rate' => config('app.default_commission_rate', 10)
                ]);
            }
        }
    }

    /**
     * Helper to initialize the wallet with default RWF values.
     */
    private function createShopWallet(Shop $shop): void
    {
        DB::transaction(function () use ($shop) {
            // Safety check inside the transaction
            if ($shop->wallet()->exists()) {
                return;
            }

            $shop->wallet()->create([
                'currency'            => 'RWF',
                'balance'             => 0,
                'pending_balance'     => 0, // Explicitly set for dashboard stats accuracy
                'total_earnings'      => 0,
                'last_transaction_at' => now(),
            ]);
            
            Log::info("Wallet initialized for Shop ID: #{$shop->id} ({$shop->shop_name})");
        });
    }

    /**
     * Handle immediate verification upon creation (e.g., admin-created shops).
     */
    public function created(Shop $shop): void
    {
        if ($shop->is_verified) {
            $this->createShopWallet($shop);
        }
    }

    /**
     * Handle the Shop "deleted" event.
     */
    public function deleted(Shop $shop): void
    {
        Log::warning("Shop ID #{$shop->id} was deleted. Wallet remains for financial audit trails.");
    }
}