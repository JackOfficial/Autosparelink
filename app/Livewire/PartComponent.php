<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartComponent extends Component
{
    public Part $part;
    public $quantity = 1;
    public $currencySymbol;
    public $isCompatible;

    public function mount(Part $part, $currencySymbol = 'RWF', $isCompatible = false)
    {
        $this->part = $part;
        $this->currencySymbol = $currencySymbol;
        $this->isCompatible = $isCompatible;
    }

    public function addToCart()
    {
        if ($this->part->stock_quantity < $this->quantity) {
            $this->dispatch('notify', message: "Only {$this->part->stock_quantity} left in stock!");
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

        if ($newTotalQty > $this->part->stock_quantity) {
            $this->dispatch('notify', message: "Cannot add more. You already have {$currentQtyInCart} in cart.");
            if (Auth::check()) { 
                $this->saveCartToDb($identifier, $instance);
            }
            return;
        }

        if ($cartItem) {
            Cart::instance($instance)->update($cartItem->rowId, $newTotalQty);
            $message = 'Cart updated!';
        } else {
            // Priority: Check photos relationship, then direct image attribute, then placeholder
            $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';
            
            Cart::instance($instance)->add([
                'id'      => $this->part->id,
                'name'    => $this->part->part_name,
                'qty'     => $this->quantity,
                'price'   => $this->part->price,
                'weight'  => 0,
                'options' => [
                    'brand'       => $this->part->partBrand?->name,
                    'image'       => $mainPhoto,
                    'part_number' => $this->part->part_number,
                ]
            ]);
            $message = 'Added to cart!';
        }

        if (Auth::check()) {
            $this->saveCartToDb($identifier, $instance);
        }

        $this->dispatch('cartUpdated', part_id: $this->part->id);
        $this->dispatch('notify', message: $message);
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
            if (Auth::check()) { $this->saveCartToDb($identifier, $instance); }
            $this->dispatch('notify', message: 'Item is already in your wishlist!');
            return;
        }

        $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';

        Cart::instance($instance)->add([
            'id'    => $this->part->id,
            'name'  => $this->part->part_name,
            'qty'   => 1,
            'price' => $this->part->price,
            'weight'=> 0,
            'options' => [
                'brand' => $this->part->partBrand?->name,
                'image' => $mainPhoto,
            ]
        ]);

        if (Auth::check()) {
            $this->saveCartToDb($identifier, $instance);
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    /**
     * Helper to prevent "Duplicate entry" errors by cleaning DB before saving
     */
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