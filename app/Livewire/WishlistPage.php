<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistPage extends Component
{
    // This runs when the page loads
    public function mount()
    {
        if (Auth::check()) {
            // Force load from DB into Session so the content is actually there
            Cart::instance('wishlist')->restore(Auth::id() . '_wishlist');
            Cart::instance('default')->restore(Auth::id());
        }
    }

    public function removeItem($rowId)
    {
        $instance = 'wishlist';
        $identifier = Auth::id() . '_wishlist';

        Cart::instance($instance)->remove($rowId);

        if (Auth::check()) {
            $this->syncToDb($identifier, $instance);
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Removed from wishlist.');
    }

    public function moveToCart($rowId)
    {
        $wishlistInstance = 'wishlist';
        $cartInstance = 'default';
        $userId = Auth::id();

        // 1. Ensure session is synced with DB before acting
        if (Auth::check()) {
            Cart::instance($wishlistInstance)->restore($userId . '_wishlist');
            Cart::instance($cartInstance)->restore($userId);
        }

        // 2. Get item
        $item = Cart::instance($wishlistInstance)->get($rowId);

        if (!$item) {
            $this->dispatch('notify', message: 'Item not found in wishlist!');
            return;
        }

        // 3. Add to Cart
        Cart::instance($cartInstance)->add([
            'id'      => $item->id,
            'name'    => $item->name,
            'qty'     => 1,
            'price'   => $item->price,
            'weight'  => $item->weight,
            'options' => $item->options->all(),
        ]);

        // 4. Remove from Wishlist
        Cart::instance($wishlistInstance)->remove($rowId);

        // 5. Sync both to DB
        if (Auth::check()) {
            $this->syncToDb($userId, $cartInstance);
            $this->syncToDb($userId . '_wishlist', $wishlistInstance);
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('cartUpdated');
        $this->dispatch('notify', message: 'Item moved to cart!');
    }

    /**
     * Helper to prevent "Duplicate Entry" and ensure DB matches Session
     */
    private function syncToDb($identifier, $instance)
    {
        DB::table('shoppingcart')
            ->where('identifier', $identifier)
            ->where('instance', $instance)
            ->delete();
            
        Cart::instance($instance)->store($identifier);
    }

    public function render()
    {
        return view('livewire.wishlist-page', [
            'wishlistContent' => Cart::instance('wishlist')->content()
        ]);
    }
}