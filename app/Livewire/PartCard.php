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
       Cart::instance('default')->add([
        'id'    => $this->part->id,
        'name'  => $this->part->part_name,
        'qty'   => $this->quantity,
        'price' => $this->part->price,
        'weight'=> 0,
    ]);

    // If the user IS logged in, save to DB immediately
    if (auth()->check()) {
        Cart::instance('default')->store(auth()->id());
    }

    $this->dispatch('cartUpdated');
    }

    public function addToWishlist()
    {
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

        $this->dispatch('notify', message: 'Added to wishlist!');
        $this->dispatch('wishlistUpdated');
    }

    public function render()
    {
        return view('livewire.part-card');
    }
}