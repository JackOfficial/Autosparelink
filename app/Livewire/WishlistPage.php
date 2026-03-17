<?php

namespace App\Livewire;

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WishlistPage extends Component
{
    public function removeItem($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        $this->refreshWishlist();
        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Removed from wishlist.');
    }

   public function moveToCart($rowId)
{
    // 1. Get item from wishlist
    $wishlist = Cart::instance('wishlist');
    $item = $wishlist->get($rowId);

    if (!$item) {
        $this->dispatch('notify', message: 'Item not found!');
        return;
    }

    // 2. Add to Default Cart
    Cart::instance('default')->add([
        'id'      => $item->id,
        'name'    => $item->name,
        'qty'     => 1,
        'price'   => $item->price,
        'weight'  => $item->weight,
        'options' => $item->options->all(), // Using .all() is often cleaner for these objects
    ]);

    // 3. Remove from Wishlist
    $wishlist->remove($rowId);

    // 4. Critical: Sync BOTH instances to the Database
    if (Auth::check()) {
        $userId = Auth::id();
        
        // Update Default Cart in DB
        DB::table('shoppingcart')->where('identifier', $userId)->where('instance', 'default')->delete();
        Cart::instance('default')->store($userId);
        
        // Update Wishlist in DB (So the item stays gone)
        DB::table('shoppingcart')->where('identifier', $userId . '_wishlist')->where('instance', 'wishlist')->delete();
        Cart::instance('wishlist')->store($userId . '_wishlist');
    }

    $this->dispatch('wishlistUpdated');
    $this->dispatch('cartUpdated');
    $this->dispatch('notify', message: 'Item moved to cart!');
}

    private function refreshWishlist()
    {
        if (Auth::check()) {
            $identifier = Auth::id() . '_wishlist';
            DB::table('shoppingcart')->where('identifier', $identifier)->delete();
            Cart::instance('wishlist')->store($identifier);
        }
    }

    public function render()
    {
        return view('livewire.wishlist-page', [
            'wishlistContent' => Cart::instance('wishlist')->content()
        ]);
    }
}
