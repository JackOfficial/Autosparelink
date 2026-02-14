<?php

namespace App\Livewire;

namespace App\Livewire;

use Livewire\Component;
use Cart;
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
        $item = Cart::instance('wishlist')->get($rowId);

        // Add to Default Cart
        Cart::instance('default')->add([
            'id' => $item->id,
            'name' => $item->name,
            'qty' => 1,
            'price' => $item->price,
            'weight' => $item->weight,
            'options' => $item->options->toArray(),
        ]);

        // Remove from Wishlist
        Cart::instance('wishlist')->remove($rowId);

        $this->refreshWishlist();
        
        // Sync the Cart DB as well
        if (Auth::check()) {
            DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
            Cart::instance('default')->store(Auth::id());
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
