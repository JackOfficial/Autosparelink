<?php

namespace App\Livewire;

use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartPage extends Component
{
    public function updateQuantity($rowId, $qty)
    {
        if ($qty < 1) return;

        Cart::instance('default')->update($rowId, $qty);
        $this->refreshCart();
        $this->dispatch('cartUpdated'); 
    }

    public function removeItem($rowId)
    {
        Cart::instance('default')->remove($rowId);
        
        $this->refreshCart();
        $this->dispatch('cartUpdated'); 
        $this->dispatch('notify', message: 'Item removed from cart.');
    }

    public function clearCart()
    {
        // Use destroy() for Gloudemans to wipe the current session instance
        Cart::instance('default')->destroy();

        if (Auth::check()) {
            // Manually wipe the DB record so it doesn't come back on next login
            DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notify', message: 'Cart cleared!');
    }

    private function refreshCart()
    {
        if (Auth::check()) {
            $identifier = Auth::id();
            // We use the Delete-then-Store pattern here. 
            // restore() isn't needed during a refresh because the session is already active.
            DB::table('shoppingcart')->where('identifier', $identifier)->delete();
            Cart::instance('default')->store($identifier);
        }
    }

    public function render()
    {
        $cartInstance = Cart::instance('default');

        return view('livewire.cart-page', [
            'cartContent' => $cartInstance->content(),
            'subTotal'    => $cartInstance->subtotal(),
            'total'       => $cartInstance->total(),
        ]);
    }
}