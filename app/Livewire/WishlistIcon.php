<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Cart;

class WishlistIcon extends Component
{
    public $count = 0;

    public function mount()
    {
        $this->updateCount();
    }

    #[On('wishlistUpdated')] 
    public function updateCount()
    {
        // We use the 'wishlist' instance specifically
        $this->count = Cart::instance('wishlist')->count();
    }

    public function render()
    {
        return view('livewire.wishlist-icon');
    }
}