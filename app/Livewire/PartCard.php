<?php

namespace App\Livewire;

use Livewire\Component;
use Cart; // Darryldecode\Cart
use App\Models\Part;
use Illuminate\Support\Facades\Auth;

class PartCard extends Component
{
    public Part $part;
    public $quantity = 1;

    // Livewire will call this automatically when using <livewire:part-card :part="$part" />
    public function mount(Part $part)
    {
        $this->part = $part;
    }

   public function addToCart()
{
    $sessionId = Auth::check() ? Auth::id() : session()->getId();

    Cart::session($sessionId)->add([
        'id' => $this->part->id,
        'name' => $this->part->part_name,
        'price' => $this->part->price,
        'quantity' => $this->quantity,
        'attributes' => [
            'brand' => optional($this->part->partBrand)->name,
            'part_number' => $this->part->part_number,
        ]
    ]);

    $this->dispatchBrowserEvent('notify', [
        'message' => 'Added to cart!'
    ]);

    $this->emit('cartUpdated'); // <-- notify navbar
}

public function addToWishlist()
{
    $sessionId = Auth::check() ? Auth::id() : session()->getId();

    Cart::session($sessionId)->instance('wishlist')->add([
        'id' => $this->part->id,
        'name' => $this->part->part_name,
        'price' => $this->part->price,
        'quantity' => 1,
        'attributes' => [
            'brand' => optional($this->part->partBrand)->name,
            'part_number' => $this->part->part_number,
        ]
    ]);

    $this->dispatchBrowserEvent('notify', [
        'message' => 'Added to wishlist!'
    ]);

    $this->emit('wishlistUpdated'); // <-- notify navbar
}


    public function render()
    {
        return view('livewire.part-card');
    }
}
