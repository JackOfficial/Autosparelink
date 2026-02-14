<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;

class PartComponent extends Component
{
    public $part;
    public $currencySymbol;

    public function mount($part, $currencySymbol = 'RWF')
    {
        $this->part = $part;
        $this->currencySymbol = $currencySymbol;
    }

    public function addToCart()
    {
        // Replace with your cart logic
      Cart::add($this->part->id, $this->part->part_name, 1, $this->part->price);

        $this->dispatchBrowserEvent('notify', ['message' => 'Added to cart!']);
    }

    public function addToWishlist()
    {
        auth()->user()?->wishlist()->syncWithoutDetaching([$this->part->id]);

        $this->dispatchBrowserEvent('notify', ['message' => 'Added to wishlist!']);
    }

    public function render()
    {
        return view('livewire.part-component');
    }
}
