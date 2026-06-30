<?php

namespace App\Observers;

use App\Models\Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShopObserver
{
    /**
     * Handle the Shop "created" event.
     */
    public function created(Shop $shop): void
    {
        // If created already verified (e.g., via Admin Panel), run full initialization
        if ($shop->is_verified) {
            $this->initializeVerifiedShop($shop);
        }
    }

    /**
     * Handle the Shop "updated" event.
     */
    public function updated(Shop $shop): void
    {
        // Run full initialization if the verification flag just flipped to true
        if ($shop->isDirty('is_verified') && $shop->is_verified === true) {
            $this->initializeVerifiedShop($shop);
        }
    }

    /**
     * Handle the Shop "deleted" event.
     */
    public function deleted(Shop $shop): void
    {
        Log::warning("Shop ID #{$shop->id} was deleted. Wallet remains intact for financial audit trails.");
    }

    /**
     * Centralized supervisor for onboarding verified shops cleanly
     */
    private function initializeVerifiedShop(Shop $shop): void
    {
        // 1. Initialize the Wallet securely if it doesn't exist
        if (!$shop->wallet()->exists()) {
            $this->createShopWallet($shop);
        }

        // 2. Ensure a commission rate is established for the markup pipeline
        if (is_null($shop->commission_rate)) {
            $shop->updateQuietly([
                'commission_rate' => config('app.default_commission_rate', 10)
            ]);
        }
    }

    /**
     * Helper to initialize the wallet with default RWF values.
     */
    private function createShopWallet(Shop $shop): void
    {
        DB::transaction(function () use ($shop) {
            // Re-verify existence inside the isolated database transaction context
            if ($shop->wallet()->exists()) {
                return;
            }

            // FIX: Removed balance configurations and virtual accessors. 
            // This relies cleanly on your database migration structural defaults (0.00) 
            // and safely bypasses mass-assignment blocks.
            $shop->wallet()->create([
                'currency'            => 'RWF',
                'last_transaction_at' => now(),
            ]);
            
            Log::info("Wallet initialized for Shop ID: #{$shop->id} ({$shop->shop_name})");
        });
    }
}