<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart; // Ensure this is the correct namespace

class PartCard extends Component
{
    public Part $part;
    public $quantity = 1;

    // In Livewire 3, mount is still fine, but usually, we type-hint the property
    public function mount(Part $part)
    {
        $this->part = $part;
    }

    public function addToCart()
{
    if ($this->quantity > $this->part->stock_quantity) {
        $this->dispatch('notify', message: 'Not enough stock available!');
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

      if (auth()->check()) {
        try {
            Cart::instance('default')->store(auth()->id());
        } catch (\Gloudemans\Shoppingcart\Exceptions\CartAlreadyStoredException $e) {
            // erase existing stored cart and store fresh
            Cart::instance('default')->erase(auth()->id());
            Cart::instance('default')->store(auth()->id());
        }
    }

    $this->dispatch('cartUpdated');
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
        'price'   => $this->part->price,
        'weight'  => 0,
        'options' => [
            'brand'       => $this->part->partBrand?->name,
            'part_number' => $this->part->part_number,
        ]
    ]);

     if (auth()->check()) {
        try {
            Cart::instance('wishlist')->store(auth()->id());
        } catch (\Gloudemans\Shoppingcart\Exceptions\CartAlreadyStoredException $e) {
            // erase previous stored wishlist and store fresh
            Cart::instance('wishlist')->erase(auth()->id());
            Cart::instance('wishlist')->store(auth()->id());
        }
    }

    $this->dispatch('wishlistUpdated');
    $this->dispatch('notify', message: 'Added to wishlist!');
}

    public function render()
    {
        return view('livewire.part-card');
    }
}