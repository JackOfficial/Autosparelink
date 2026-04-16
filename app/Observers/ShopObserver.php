<?php

namespace App\Observers;

use App\Models\Shop;
use Illuminate\Support\Facades\Log;

class ShopObserver
{
    /**
     * Handle the Shop "updated" event.
     * This triggers when the Admin approves the shop in the backend.
     */
    public function updated(Shop $shop): void
    {
        // 1. Check if 'is_verified' just flipped from false to true.
        // We use is_verified for the "Gate" and is_active for "Status/Banning".
        if ($shop->isDirty('is_verified') && $shop->is_verified === true) {
            
            // 2. Double-check that a wallet doesn't already exist (Safety first!)
            if (!$shop->wallet()->exists()) {
                $this->createShopWallet($shop);
            }
        }
    }

    /**
     * Helper to initialize the wallet with default Rwandan Francs (RWF) values.
     */
    private function createShopWallet(Shop $shop): void
    {
        try {
            $shop->wallet()->create([
                'balance' => 0.00,
                'pending_balance' => 0.00,
                'withdrawn_balance' => 0.00,
                'currency' => 'RWF',
                'last_transaction_at' => now(),
            ]);
            
            Log::info("Financial Infrastructure: Wallet initialized for Shop '{$shop->shop_name}' (ID: #{$shop->id}) after Admin verification.");
        } catch (\Exception $e) {
            Log::error("CRITICAL: Failed to create wallet for Shop ID #{$shop->id} during verification. Error: " . $e->getMessage());
        }
    }

    /**
     * Handle the Shop "deleted" event.
     */
    public function deleted(Shop $shop): void
    {
        // We typically do NOT delete the wallet here to maintain financial 
        // audit trails for tax/RRA purposes in Rwanda.
    }
}