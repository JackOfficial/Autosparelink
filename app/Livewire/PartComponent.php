<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

class PartComponent extends Component
{
    public Part $part;
    public $quantity = 1;
    public $currencySymbol;
    public $isCompatible; // Added this

    // Add $isCompatible = false as the third parameter
    public function mount(Part $part, $currencySymbol = 'RWF', $isCompatible = false)
    {
        $this->part = $part;
        $this->currencySymbol = $currencySymbol;
        $this->isCompatible = $isCompatible;
    }

  public function addToCart()
{
    // 1. Basic check: Is the requested quantity even available?
    if ($this->part->stock_quantity < $this->quantity) {
        $this->dispatch('notify', message: "Only {$this->part->stock_quantity} left in stock!");
        return;
    }

    $identifier = Auth::id();
    $instance = 'default';

    if (Auth::check()) {
        Cart::instance($instance)->restore($identifier);
    }

    // 2. Find the item in the cart to check existing quantity
    $cartItem = Cart::instance($instance)->search(function ($cartItem, $rowId) {
        return $cartItem->id === $this->part->id;
    })->first();

    $currentQtyInCart = $cartItem ? $cartItem->qty : 0;
    $newTotalQty = $currentQtyInCart + $this->quantity;

    // 3. CRITICAL: Check if (Existing Cart + New Request) exceeds Stock
    if ($newTotalQty > $this->part->stock_quantity) {
        $this->dispatch('notify', message: "Cannot add more. You already have {$currentQtyInCart} in cart and total stock is {$this->part->stock_quantity}.");
        
        // If logged in, we must re-store because 'restore' removes the row from the DB
        if (Auth::check()) { Cart::instance($instance)->store($identifier); }
        return;
    }

    if ($cartItem) {
        Cart::instance($instance)->update($cartItem->rowId, ['qty' => $newTotalQty]);
        $message = 'Cart updated!';
    } else {
        $mainPhoto = $this->part->photos->first()?->file_path ?? 'frontend/img/placeholder.png';
        Cart::instance($instance)->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            'price'   => $this->part->price,
            'weight'  => 0,
            'options' => [
                'brand' => $this->part->partBrand?->name,
                'image' => $mainPhoto,
            ]
        ]);
        $message = 'Added to cart!';
    }

    if (Auth::check()) {
        Cart::instance($instance)->store($identifier);
    }

    $this->dispatch('cartUpdated', part_id: $this->part->id);
    $this->dispatch('notify', message: $message);
}

    public function addToWishlist()
    {
        $instance = 'wishlist';
        if (Auth::check()) {
            Cart::instance($instance)->restore(Auth::id() . '_wishlist');
        }

        $exists = Cart::instance($instance)->search(fn($cartItem) => $cartItem->id === $this->part->id);

        if ($exists->isNotEmpty()) {
            if (Auth::check()) { Cart::instance($instance)->store(Auth::id() . '_wishlist'); }
            $this->dispatch('notify', message: 'Item is already in your wishlist!');
            return;
        }

        Cart::instance($instance)->add([
            'id'    => $this->part->id,
            'name'  => $this->part->part_name,
            'qty'   => 1,
            'price' => $this->part->price,
            'weight'=> 0,
        ]);

        if (Auth::check()) {
            Cart::instance($instance)->store(Auth::id() . '_wishlist');
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    public function render()
    {
        return view('livewire.part-component');
    }
}