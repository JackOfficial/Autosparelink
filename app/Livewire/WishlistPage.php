<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistPage extends Component
{
    /**
     * Runs when the component is initialized.
     * Restores both wishlist and default cart instances for the authenticated user.
     */
    public function mount()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            // Restore ensures the session is populated with the database state
            Cart::instance('wishlist')->restore($userId . '_wishlist');
            Cart::instance('default')->restore($userId);
        }
    }

    /**
     * Removes an item specifically from the wishlist instance.
     */
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

    /**
     * Moves an item from Wishlist to the Shopping Cart.
     */
    public function moveToCart($rowId)
    {
        $wishlistInstance = 'wishlist';
        $cartInstance = 'default';
        $userId = Auth::id();

        // 1. Get item from the current wishlist session
        $item = Cart::instance($wishlistInstance)->get($rowId);

        if (!$item) {
            $this->dispatch('notify', message: 'Item not found in wishlist!');
            return;
        }

        // 2. Transfer to Cart instance
        Cart::instance($cartInstance)->add([
            'id'      => $item->id,
            'name'    => $item->name,
            'qty'     => 1,
            'price'   => $item->price,
            'weight'  => $item->weight,
            'options' => $item->options->all(),
        ]);

        // 3. Remove from Wishlist instance
        Cart::instance($wishlistInstance)->remove($rowId);

        // 4. Persistence Layer: Sync both instances to the DB if logged in
        if (Auth::check()) {
            $this->syncToDb($userId, $cartInstance);
            $this->syncToDb($userId . '_wishlist', $wishlistInstance);
        }

        // 5. Update UI via events
        $this->dispatch('wishlistUpdated');
        $this->dispatch('cartUpdated');
        $this->dispatch('notify', message: 'Item moved to cart!');
    }

    /**
     * Helper to prevent "Duplicate Entry" errors. 
     * It clears the existing DB record before saving the fresh session state.
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