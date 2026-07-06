<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class CartPage extends Component
{
    // Define constants for instances to avoid magic strings
    const DEFAULT_CART = 'default';
    const WISHLIST = 'wishlist';

    public $shippingFee = 0;
    public $totalWithShipping = 0;

    public function updateQuantity($rowId, $qty)
    {
        if ($qty < 1) return;

        // 1. Fetch current item context to validate real-time stock
        $item = Cart::instance(self::DEFAULT_CART)->get($rowId);
        if ($item) {
            $part = Part::find($item->id);
            if ($part && $qty > $part->stock_quantity) {
                $this->dispatch('swal', [
                    'icon'  => 'warning',
                    'title' => 'Insufficient Stock',
                    'text'  => "Only {$part->stock_quantity} items are available in stock.",
                ]);
                return;
            }
        }

        Cart::instance(self::DEFAULT_CART)->update($rowId, $qty);
        
        // Silent database sync & broad broadcast
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
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Oops!',
                'text'  => 'Item not found!',
            ]);
            return;
        }

        // Add to wishlist
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
        
        $this->dispatch('swal', [
            'icon'     => 'success',
            'text'     => 'Item moved to wishlist!',
            'toast'    => true,
            'position' => 'top-end',
            'timer'    => 2500
        ]);
    }

    public function clearCart()
    {
        $instance = self::DEFAULT_CART;

        // 1. Clear the current session instance immediately
        Cart::instance($instance)->destroy();

        // 2. Clear from database if the user is authenticated
        if (Auth::check()) {
            DB::transaction(function () use ($instance) {
                DB::table('shoppingcart')
                    ->where('identifier', Auth::id())
                    ->where('instance', $instance)
                    ->delete();
            });
        }

        // 3. Trigger global updates and dispatch single clean SweetAlert notification
        $this->dispatch('cartUpdated');
        $this->dispatch('swal', [
            'icon'     => 'success',
            'title'    => 'Cart Cleared',
            'text'     => 'All items have been removed from your cart.',
            'toast'    => true,
            'position' => 'top-end',
            'timer'    => 2000
        ]);
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
            $this->dispatch('swal', [
                'icon'     => 'success',
                'text'     => $message,
                'toast'    => true,
                'position' => 'top-end',
                'timer'    => 2000
            ]);
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

    /**
     * Dynamic shipping core calculator synced directly with Checkout logic
     */
    private function calculateAverageShippingPrice($city)
    {
        $cartItems = Cart::instance(self::DEFAULT_CART)->content();
        if ($cartItems->isEmpty()) {
            return 0;
        }

        $totalShipping = 0;
        $validItemCount = 0;
        $fallbackFee = (strtolower(trim($city)) === 'kigali') ? 3000 : 5000;

        $itemIds = $cartItems->pluck('id')->toArray();
        $parts = Part::with('category')->whereIn('id', $itemIds)->get()->keyBy('id');

        foreach ($cartItems as $item) {
            $part = $parts->get($item->id);

            if ($part) {
                if ($part->category && $part->category->shipping_price > 0) {
                    $totalShipping += $part->category->shipping_price;
                } else {
                    $totalShipping += $fallbackFee;
                }
                $validItemCount++;
            }
        }

        return $validItemCount > 0 ? ($totalShipping / $validItemCount) : $fallbackFee;
    }

    public function render()
    {
        $cart = Cart::instance(self::DEFAULT_CART);
        
        // Match user's address/cookie state or fallback safely to regional baseline
        $city = Cookie::get('guest_city') ?? 'Kigali';
        
        $subtotal = (float) $cart->subtotal(2, '.', '');
        $this->shippingFee = $this->calculateAverageShippingPrice($city);
        $this->totalWithShipping = $subtotal + $this->shippingFee;

        return view('livewire.cart-page', [
            'cartContent'       => $cart->content(),
            'subTotal'          => $subtotal, 
            'shippingFee'       => $this->shippingFee,
            'totalWithShipping' => $this->totalWithShipping,
            'total'             => $this->totalWithShipping,
        ]);
    }
}