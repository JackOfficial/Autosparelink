<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\{Auth, DB};

class PartComponent extends Component
{
    public Part $part;
    public $quantity = 1;
    public $currencySymbol;
    public $isCompatible;

    public function mount(Part $part, $currencySymbol = 'RWF', $isCompatible = false)
    {
        $this->part = $part->load(['shop', 'partBrand', 'photos']);
        $this->currencySymbol = $currencySymbol;
        $this->isCompatible = $isCompatible;
    }

    /**
     * Immediate checkout flow for the current item.
     */
    public function buyNow()
    {
        // 1. Run the strict stock checks and add item to cart instance safely
        $this->addToCart();

        // 2. Fetch cart instance to confirm the item was successfully injected
        $instance = 'default';
        if (Auth::check()) {
            Cart::instance($instance)->restore(Auth::id());
        }

        $hasItem = Cart::instance($instance)->search(function ($cartItem) {
            return $cartItem->id === $this->part->id;
        })->isNotEmpty();

        // 3. If validation failed in addToCart(), don't redirect
        if (!$hasItem) {
            return;
        }

        // 4. Everything checked out perfectly, send them straight to payment checkout
        return redirect()->route('checkout.index');
    }

    public function addToCart()
    {
        // 1. Validation: Check Stock
        if ($this->part->stock_quantity < $this->quantity) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Out of Stock',
                'text'  => "Only {$this->part->stock_quantity} left in stock!",
            ]);
            return;
        }

        $identifier = Auth::id();
        $instance = 'default';

        if (Auth::check()) {
            Cart::instance($instance)->restore($identifier);
        }

        $cartItem = Cart::instance($instance)->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->part->id;
        })->first();

        $currentQtyInCart = $cartItem ? $cartItem->qty : 0;
        $newTotalQty = $currentQtyInCart + $this->quantity;

        // 2. Validation: Prevent over-ordering based on stock
        if ($newTotalQty > $this->part->stock_quantity) {
            $this->dispatch('swal', [
                'icon'  => 'warning',
                'title' => 'Cart Limit',
                'text'  => "Cannot add more. You already have {$currentQtyInCart} in cart.",
            ]);
            
            if (Auth::check()) { 
                $this->saveCartToDb($identifier, $instance);
            }
            return;
        }

        if ($cartItem) {
            Cart::instance($instance)->update($cartItem->rowId, $newTotalQty);
            $message = 'Cart updated successfully!';
        } else {
            $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';
            
            Cart::instance($instance)->add([
                'id'      => $this->part->id,
                'name'    => $this->part->part_name,
                'qty'     => $this->quantity,
                'price'   => (float) $this->part->unit_price,
                'weight'  => 0,
                'options' => [
                    'brand'         => $this->part->partBrand?->name,
                    'image'         => $mainPhoto,
                    'part_number'   => $this->part->part_number,
                    'shop_name'     => $this->part->shop?->shop_name,
                    'shop_id'       => $this->part->shop_id,
                    'shop_payout'   => (float) $this->part->price,
                    'applied_rate'  => (float) $this->part->applied_rate,
                ]
            ]);
            $message = 'Added to cart!';
        }

        if (Auth::check()) {
            $this->saveCartToDb($identifier, $instance);
        }

        // Trigger Success Toast via JS Dispatch
        $this->dispatch('swal', [
            'icon'     => 'success',
            'title'    => 'Done!',
            'text'     => $message,
            'toast'    => true,
            'position' => 'top-end',
            'timer'    => 2000
        ]);

        $this->dispatch('cartUpdated', part_id: $this->part->id);
    }

    public function addToWishlist()
    {
        $instance = 'wishlist';
        $identifier = Auth::id() . '_wishlist';

        if (Auth::check()) {
            Cart::instance($instance)->restore($identifier);
        }

        $exists = Cart::instance($instance)->search(fn($cartItem) => $cartItem->id === $this->part->id);

        if ($exists->isNotEmpty()) {
            $this->dispatch('swal', [
                'icon'     => 'info',
                'text'     => 'Item is already in your wishlist!',
                'toast'    => true,
                'position' => 'top-end',
                'timer'    => 3000
            ]);
            
            if (Auth::check()) { $this->saveCartToDb($identifier, $instance); }
            return;
        }

        $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';

        Cart::instance($instance)->add([
            'id'    => $this->part->id,
            'name'  => $this->part->part_name,
            'qty'   => 1,
            'price' => (float) $this->part->unit_price,
            'weight'=> 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'image'         => $mainPhoto,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_id'       => $this->part->shop_id,
                'shop_payout'   => (float) $this->part->price,
            ]
        ]);

        if (Auth::check()) {
            $this->saveCartToDb($identifier, $instance);
        }

        $this->dispatch('swal', [
            'icon'     => 'success',
            'title'    => 'Saved',
            'text'     => 'Added to wishlist!',
            'toast'    => true,
            'position' => 'top-end',
            'timer'    => 2000
        ]);

        $this->dispatch('wishlistUpdated');
    }

    private function saveCartToDb($identifier, $instance)
    {
        DB::table('shoppingcart')
            ->where('identifier', $identifier)
            ->where('instance', $instance)
            ->delete();
            
        Cart::instance($instance)->store($identifier);
    }

    public function render()
    {
        return view('livewire.part-component');
    }
}