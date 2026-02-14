<?php

namespace App\Livewire;

use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartPage extends Component
{
    public $cartItems = [];
    public $subTotal = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $instance = Cart::instance('default');
        
        // If logged in, sync with DB
        if (Auth::check()) {
            $instance->restore(Auth::id());
            // We store it back immediately to maintain the "restore-store" cycle
            DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
            $instance->store(Auth::id());
        }

        $this->cartItems = $instance->getContent()->toArray();
        $this->subTotal = $instance->getSubTotal();
    }

    public function updateQuantity($rowId, $qty)
    {
        if ($qty < 1) return;

        Cart::instance('default')->update($rowId, [
            'quantity' => [
                'relative' => false,
                'value' => $qty
            ]
        ]);

        $this->refreshCart();
    }

    public function removeItem($rowId)
    {
        Cart::instance('default')->remove($rowId);
        $this->refreshCart();
        $this->dispatch('cartUpdated'); // Update the Navbar count
        $this->dispatch('notify', message: 'Item removed from cart.');
    }

    public function clearCart()
    {
        Cart::instance('default')->clear();
        $this->refreshCart();
        $this->dispatch('cartUpdated');
    }

    private function refreshCart()
    {
        if (Auth::check()) {
            DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
            Cart::instance('default')->store(Auth::id());
        }
        $this->loadCart();
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}