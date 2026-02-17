<?php

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
        $part->load([
            'partBrand',
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
        // Prevent adding more than available stock
        if ($this->quantity > $this->part->stock_quantity) {
            $this->dispatch('notify', message: 'Not enough stock available.');
            return;
        }

        Cart::instance('default')->add([
            'id'    => $this->part->id,
            'name'  => $this->part->part_name,
            'qty'   => $this->quantity,
            'price' => $this->part->price,
            'weight'=> 0,
            'options' => [
                'brand'       => $this->part->partBrand?->name,
                'part_number' => $this->part->part_number,
            ]
        ]);

        // Store cart in database if logged in
        if (auth()->check()) {
            Cart::instance('default')->store(auth()->id());
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notify', message: 'Item added to cart!');
    }

    public function addToWishlist()
    {
        // Prevent duplicate wishlist items
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
                'brand'       => $this->part->partBrand?->name,
                'part_number' => $this->part->part_number,
            ]
        ]);

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    public function render()
    {
        return view('livewire.product-info');
    }
}