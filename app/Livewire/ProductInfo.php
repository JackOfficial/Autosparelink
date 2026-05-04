<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use SweetAlert2\Laravel\Swal; // Import the SweetAlert2 Facade

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
        
        // Use unit_price (Markup Price) for the share text
        $this->shareText = urlencode($this->part->part_name . ' - Only ' . number_format($this->part->unit_price, 0) . ' RWF');
    }

    public function incrementQty()
    {
        if ($this->quantity < $this->part->stock_quantity) {
            $this->quantity++;
        } else {
            Swal::warning([
                'title' => 'Stock Limit',
                'text' => "We only have {$this->part->stock_quantity} available in stock.",
                'timer' => 2500
            ]);
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
            Swal::error([
                'title' => 'Out of Stock',
                'text' => 'Not enough stock available to fulfill your request.',
                'timer' => 3000
            ]);
            return;
        }

        $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';

        Cart::instance('default')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            'price'   => (float) $this->part->unit_price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'image'         => $mainPhoto,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_id'       => $this->part->shop_id,
                'shop_location' => $this->part->shop?->address,
                'base_price'    => (float) $this->part->price,
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('default');
        }

        // Beautiful Toast confirmation
        Swal::success([
            'title' => 'Done!',
            'text' => 'Item added to your cart!',
            'timer' => 2000,
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false
        ]);

        $this->dispatch('cartUpdated');
    }

    public function addToWishlist()
    {
        $exists = Cart::instance('wishlist')
            ->search(fn($item) => $item->id == $this->part->id)
            ->isNotEmpty();

        if ($exists) {
            Swal::info([
                'text' => 'Item is already in your wishlist.',
                'toast' => true,
                'position' => 'top-end',
                'timer' => 2500
            ]);
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
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_location' => $this->part->shop?->address,
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('wishlist');
        }

        Swal::success([
            'title' => 'Saved!',
            'text' => 'Added to your wishlist.',
            'timer' => 2000,
            'toast' => true,
            'position' => 'top-end',
            'showConfirmButton' => false
        ]);

        $this->dispatch('wishlistUpdated');
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