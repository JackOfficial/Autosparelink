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

    // 1. If logged in, restore existing DB cart first to avoid overwriting data
    if (Auth::check()) {
        // 'restore' pulls DB items into the current session and deletes the DB row
        Cart::instance($instance)->restore($identifier);
    }

    // 2. Add the new item (to the now merged cart)
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

    // 3. Persist back to DB
    if (Auth::check()) {
        // Since 'restore' deleted the old row, 'store' will now succeed without errors
        Cart::instance($instance)->store($identifier);
    }

    // 4. Notify UI
    $this->dispatch('cartUpdated'); 
    $this->dispatch('notify', message: 'Added to cart!');
}

    public function addToWishlist()
    {
       Cart::instance('wishlist')->add([
        'id'    => $this->part->id,
        'name'  => $this->part->part_name,
        'qty'   => 1,
        'price' => $this->part->price,
        'weight'=> 0,
    ]);

    if (Auth::check()) {
        $identifier = Auth::id() . '_wishlist';
        
        // 1. Remove the old entry to avoid the "Already Stored" exception
        DB::table('shoppingcart')->where('identifier', $identifier)->delete();
        
        // 2. Store the fresh state
        Cart::instance('wishlist')->store($identifier);
    }

    $this->dispatch('wishlistUpdated');
    $this->dispatch('notify', message: 'Added to wishlist!');
    }

    public function render()
    {
        return view('livewire.part-component');
    }
}