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
        // Eager load shop and category for the UI
        $part->load([
            'partBrand',
            'shop',
            'category', 
            'fitments.vehicleModel.brand',
        ]);

        $this->part = $part;
        $this->shareUrl  = urlencode(request()->fullUrl());
        
        // Update: Use unit_price (Markup Price) for the share text
        $this->shareText = urlencode($this->part->part_name . ' - Only ' . number_format($this->part->unit_price, 0) . ' RWF');
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
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            // Update: Pass the unit_price (Markup Price) to the cart
            'price'   => $this->part->unit_price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'image'         => $mainPhoto,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_id'       => $this->part->shop_id,
                'shop_location' => $this->part->shop?->address,
                'base_price'    => $this->part->price, // Optional: keep record of original cost
            ]
        ]);

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
            // Update: Use unit_price for wishlist accuracy
            'price'   => $this->part->unit_price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'part_number'   => $this->part->part_number,
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