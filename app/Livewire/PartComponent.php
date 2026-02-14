<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Gloudemans\Shoppingcart\Facades\Cart; // The correct Facade for the package
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PartComponent extends Component
{
    public Part $part; // Type-hinting the Model enables Route Model Binding/Auto-serialization
    public $quantity = 1;
    public $currencySymbol;

    public function mount(Part $part, $currencySymbol = 'RWF')
    {
        $this->part = $part;
        $this->currencySymbol = $currencySymbol;
    }

   public function addToCart()
{
    $identifier = Auth::id();
    $instance = 'default';

    // 1. Sync with DB if logged in
    if (Auth::check()) {
        Cart::instance($instance)->restore($identifier);
    }

    // 2. Search for the item in the current cart
    $cartItem = Cart::instance($instance)->search(function ($cartItem, $rowId) {
        return $cartItem->id === $this->part->id;
    })->first();

    if ($cartItem) {
        // Item exists: Update the quantity of the existing rowId
        Cart::instance($instance)->update($cartItem->rowId, [
            'qty' => $cartItem->qty + $this->quantity
        ]);
        $message = 'Cart updated!';
    } else {
        // Item does not exist: Add it fresh
        Cart::instance($instance)->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            'price'   => $this->part->price,
            'weight'  => 0,
            'options' => [
                'brand' => $this->part->partBrand?->name,
                'image' => $this->part->image_path,
            ]
        ]);
        $message = 'Added to cart!';
    }

    // 3. Persist back to DB
    if (Auth::check()) {
        Cart::instance($instance)->store($identifier);
    }

    // 4. Notify UI
    $this->dispatch('cartUpdated'); 
    $this->dispatch('notify', message: $message);
}

    public function addToWishlist()
{
    $instance = 'wishlist';
    
    // 1. Sync with DB if logged in
    if (Auth::check()) {
        Cart::instance($instance)->restore(Auth::id() . '_wishlist');
    }

    // 2. Check if the item is already in the wishlist
    $exists = Cart::instance($instance)->search(function ($cartItem, $rowId) {
        return $cartItem->id === $this->part->id;
    });

    if ($exists->isNotEmpty()) {
        // If it exists, save back to DB (because restore deleted it) and exit
        if (Auth::check()) {
            Cart::instance($instance)->store(Auth::id() . '_wishlist');
        }
        
        $this->dispatch('notify', message: 'Item is already in your wishlist!');
        return;
    }

    // 3. If it doesn't exist, add it
    Cart::instance($instance)->add([
        'id'    => $this->part->id,
        'name'  => $this->part->part_name,
        'qty'   => 1,
        'price' => $this->part->price,
        'weight'=> 0,
    ]);

    // 4. Store back to DB
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