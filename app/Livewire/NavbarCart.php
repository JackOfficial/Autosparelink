<?php

namespace App\Livewire;

use Livewire\Component;
//use Cart;
use Darryldecode\Cart\Cart as DarryldecodeCart;
use Illuminate\Support\Facades\Auth;

class NavbarCart extends Component
{
    public $cartCount = 0;
    public $wishlistCount = 0;

    public function mount()
    {
        $this->updateCounts();
    }

    public function updateCounts()
    {
        $sessionId = Auth::check() ? Auth::id() : session()->getId();

        $this->cartCount = DarryldecodeCart::session($sessionId)->getContent()->count();
        $this->wishlistCount = DarryldecodeCart::session($sessionId)->instance('wishlist')->getContent()->count();
    }

    protected $listeners = [
        'cartUpdated' => 'updateCounts',
        'wishlistUpdated' => 'updateCounts',
    ];

    public function render()
    {
        return view('livewire.navbar-cart');
    }
}
