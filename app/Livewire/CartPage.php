<?php

namespace App\Livewire;

use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartPage extends Component
{
    // Removing $cartItems from public property to avoid serialization issues with objects
    // We will fetch them directly in the render method for better reliability.

    public function updateQuantity($rowId, $qty)
    {
        if ($qty < 1) return;

        // Gloudemans syntax is simple: update(rowId, qty)
        Cart::instance('default')->update($rowId, $qty);

        $this->refreshCart();
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
        Cart::instance('default')->clear();
        $this->refreshCart();
        $this->dispatch('cartUpdated');
    }

    private function refreshCart()
    {
        if (Auth::check()) {
            // Restore-Store cycle to keep DB in sync
            // Note: restore() handles the deletion of the old row automatically
            Cart::instance('default')->restore(Auth::id());
            Cart::instance('default')->store(Auth::id());
        }
        
        // No need to call loadCart(), Livewire will re-render with fresh data
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