<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Cart;

class CartIcon extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('cartUpdated')]
    public function updateCount()
    {
        // Default instance for the main shopping cart
        $this->count = Cart::instance('default')->count();
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}