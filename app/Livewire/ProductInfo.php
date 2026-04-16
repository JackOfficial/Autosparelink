<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class ProductInfo extends Component
{
    public $part;
    public $quantity = 1;
    public $shareUrl;
    public $shareText;

    public function mount($part)
    {
        // Eager load shop along with other relationships
        $part->load([
            'partBrand',
            'shop', // Added shop here
            'fitments.vehicleModel.brand',
        ]);

        $this->part = $part;
        $this->shareUrl  = urlencode(request()->fullUrl());
        $this->shareText = urlencode($this->part->part_name . ' - Only ' . number_format($this->part->price, 2) . ' RWF');
    }

    public function incrementQty()
    {
        if ($this->quantity < $this->part->stock_quantity) {
            $this->quantity++;
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if ($this->quantity > $this->part->stock_quantity) {
            $this->dispatch('notify', message: 'Not enough stock available!');
            return;
        }

        $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';

        Cart::instance('default')->add([
            'id'    => $this->part->id,
            'name'  => $this->part->part_name,
            'qty'   => $this->quantity,
            'price' => $this->part->price,
            'weight'=> 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'image'         => $mainPhoto,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name, // Added Shop Name
                'shop_id'       => $this->part->shop_id,
                'shop_location' => $this->part->shop?->address,   // Direct column access
            ]
        ]);

        //

        //

        if (auth()->check()) {
            $this->syncCartWithDatabase('default');
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notify', message: 'Item added to cart!');
    }

    public function addToWishlist()
    {
        $exists = Cart::instance('wishlist')
            ->search(fn($item) => $item->id == $this->part->id)
            ->isNotEmpty();

        if ($exists) {
            $this->dispatch('notify', message: 'Already in wishlist.');
            return;
        }

        Cart::instance('wishlist')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => 1,
            'price'   => $this->part->price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name, // Added Shop Name
                'shop_location' => $this->part->shop?->address,   // Direct column access
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('wishlist');
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    /**
     * Refactored helper to handle database storage logic
     */
    protected function syncCartWithDatabase($instance)
    {
        try {
            Cart::instance($instance)->store(auth()->id());
        } catch (\Gloudemans\Shoppingcart\Exceptions\CartAlreadyStoredException $e) {
            Cart::instance($instance)->erase(auth()->id());
            Cart::instance($instance)->store(auth()->id());
        }
    }

    public function render()
    {
        return view('livewire.product-info');
    }
}