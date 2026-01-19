<?php

namespace App\Livewire;

use Livewire\Component;
use Cart;
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

        $this->cartCount = Cart::session($sessionId)->getContent()->count();
        $this->wishlistCount = Cart::session($sessionId)->instance('wishlist')->getContent()->count();
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
