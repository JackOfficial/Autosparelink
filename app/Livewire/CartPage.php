<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
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

    public function moveToWishlist($rowId)
{
    $cartInstance = 'default';
    $wishlistInstance = 'wishlist';
    $userId = Auth::id();

    // 1. Restore sessions to ensure we have the latest data
    if (Auth::check()) {
        Cart::instance($cartInstance)->restore($userId);
        Cart::instance($wishlistInstance)->restore($userId . '_wishlist');
    }

    // 2. Get the item from the cart
    $item = Cart::instance($cartInstance)->get($rowId);

    if (!$item) {
        $this->dispatch('notify', message: 'Item not found in cart!');
        return;
    }

    // 3. Add to Wishlist
    Cart::instance($wishlistInstance)->add([
        'id'      => $item->id,
        'name'    => $item->name,
        'qty'     => 1,
        'price'   => $item->price,
        'weight'  => $item->weight,
        'options' => $item->options->all(),
    ]);

    // 4. Remove from Cart
    Cart::instance($cartInstance)->remove($rowId);

    // 5. Sync to DB
    if (Auth::check()) {
        $this->syncToDb($userId, $cartInstance);
        $this->syncToDb($userId . '_wishlist', $wishlistInstance);
    }

    $this->dispatch('cartUpdated');
    $this->dispatch('wishlistUpdated');
    $this->dispatch('notify', message: 'Item moved to wishlist!');
}

/**
 * Helper to prevent "Duplicate Entry" and ensure DB matches Session
 */
private function syncToDb($identifier, $instance)
{
    \Illuminate\Support\Facades\DB::table('shoppingcart')
        ->where('identifier', $identifier)
        ->where('instance', $instance)
        ->delete();
        
    Cart::instance($instance)->store($identifier);
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
            'total'       => $cartInstance->subtotal(), //(float) $cartInstance->total(),
        ]);
    }
}