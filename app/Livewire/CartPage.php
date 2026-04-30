<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartPage extends Component
{
    // Define constants for instances to avoid magic strings
    const DEFAULT_CART = 'default';
    const WISHLIST = 'wishlist';

    public function updateQuantity($rowId, $qty)
    {
        if ($qty < 1) return;

        Cart::instance(self::DEFAULT_CART)->update($rowId, $qty);
        $this->syncAndNotify('cartUpdated');
    }

    public function removeItem($rowId)
    {
        Cart::instance(self::DEFAULT_CART)->remove($rowId);
        $this->syncAndNotify('cartUpdated', 'Item removed from cart.');
    }

    public function moveToWishlist($rowId)
    {
        $item = Cart::instance(self::DEFAULT_CART)->get($rowId);

        if (!$item) {
            $this->dispatch('notify', message: 'Item not found!');
            return;
        }

        // Add to wishlist (copying all options including part_state_id)
        Cart::instance(self::WISHLIST)->add([
            'id'      => $item->id,
            'name'    => $item->name,
            'qty'     => 1,
            'price'   => $item->price,
            'weight'  => $item->weight,
            'options' => $item->options->all(),
        ]);

        Cart::instance(self::DEFAULT_CART)->remove($rowId);

        if (Auth::check()) {
            $userId = Auth::id();
            $this->syncToDb($userId, self::DEFAULT_CART);
            $this->syncToDb($userId . '_wishlist', self::WISHLIST);
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Item moved to wishlist!');
    }

    public function clearCart()
    {
        Cart::instance(self::DEFAULT_CART)->destroy();

        if (Auth::check()) {
            DB::table('shoppingcart')
                ->where('identifier', Auth::id())
                ->where('instance', self::DEFAULT_CART)
                ->delete();
        }

        $this->syncAndNotify('cartUpdated', 'Cart cleared!');
    }

    /**
     * Unified helper for syncing, dispatching, and notifying
     */
    private function syncAndNotify($event, $message = null)
    {
        if (Auth::check()) {
            $this->syncToDb(Auth::id(), self::DEFAULT_CART);
        }

        $this->dispatch($event);
        
        if ($message) {
            $this->dispatch('notify', message: $message);
        }
    }

    /**
     * Optimized "Atomic" Sync to prevent race conditions or duplicates
     */
    private function syncToDb($identifier, $instance)
    {
        DB::transaction(function () use ($identifier, $instance) {
            DB::table('shoppingcart')
                ->where('identifier', $identifier)
                ->where('instance', $instance)
                ->delete();
                
            Cart::instance($instance)->store($identifier);
        });
    }

    public function render()
    {
        $cart = Cart::instance(self::DEFAULT_CART);

        return view('livewire.cart-page', [
            'cartContent' => $cart->content(),
            // Ensure numeric values for RWF formatting in Blade
            'subTotal'    => $cart->subtotal(0, '', ''), 
            'total'       => $cart->subtotal(0, '', ''),
        ]);
    }
}