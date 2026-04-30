<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class PartCard extends Component
{
    public Part $part;
    public $quantity = 1;

    public function mount(Part $part)
    {
        $this->part = $part->load(['shop', 'partBrand', 'state']);
    }

    public function addToCart()
    {
        // Use the isAvailable helper from your Model for consistency
        if (!$this->part->isAvailable($this->quantity)) {
            $this->dispatch('notify', message: 'Not enough stock or item unavailable!');
            return;
        }

        $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';
        
        /**
         * CRITICAL MARKUP UPDATE:
         * 1. 'price' must be the customer-facing unit_price.
         * 2. 'options' stores the shop's original payout for accounting.
         */
        Cart::instance('default')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            'price'   => (float) $this->part->unit_price, // Marked up price
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'image'         => $mainPhoto,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_id'       => $this->part->shop_id,
                'shop_location' => $this->part->shop?->address,
                'shop_payout'   => (float) $this->part->price,    // Original base price
                'applied_rate'  => (float) $this->part->applied_rate, // Snapshot of commission
                'sku'           => $this->part->sku,
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('default');
        }

        // Pass the part_id to trigger the Alpine.js success state in your blade
        $this->dispatch('cartUpdated', part_id: $this->part->id);
        $this->dispatch('notify', message: 'Item added to cart!');
    }

    public function addToWishlist()
    {
        $exists = Cart::instance('wishlist')
            ->search(fn($cartItem) => $cartItem->id == $this->part->id)
            ->isNotEmpty();

        if ($exists) {
            $this->dispatch('notify', message: 'Already in wishlist!');
            return;
        }

        Cart::instance('wishlist')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => 1,
            'price'   => (float) $this->part->unit_price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'part_number'   => $this->part->part_number,
                'image'         => $this->part->image,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_location' => $this->part->shop?->address,
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('wishlist');
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    protected function syncCartWithDatabase($instance)
    {
        try {
            // Updated logic based on your saved info: erase and re-store to avoid duplicates
            Cart::instance($instance)->erase(auth()->id());
            Cart::instance($instance)->store(auth()->id());
        } catch (\Exception $e) {
            // Fail silently or log error for shared hosting stability
        }
    }

    public function render()
    {
        return view('livewire.part-card');
    }
}