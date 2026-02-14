<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Gloudemans\Shoppingcart\Facades\Cart; // The correct Facade for the package
use Illuminate\Support\Facades\Auth;

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
        // 1. Add to the 'default' shopping cart instance
        Cart::instance('default')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            'price'   => $this->part->price,
            'weight'  => 0, // Package requires weight, even if 0
            'options' => [
                'brand' => $this->part->partBrand?->name,
                'image' => $this->part->image_path, // Useful for the Cart Page later
            ]
        ]);

        // 2. If logged in, persist the cart session to the database
        if (Auth::check()) {
            Cart::instance('default')->store(Auth::id());
        }

        // 3. Notify the system (Livewire 3 syntax)
        $this->dispatch('cartUpdated'); 
        $this->dispatch('notify', message: 'Added to cart!');
    }

    public function addToWishlist()
    {
        // Use the 'wishlist' instance to keep it separate from the checkout cart
        Cart::instance('wishlist')->add([
            'id'    => $this->part->id,
            'name'  => $this->part->part_name,
            'qty'   => 1,
            'price' => $this->part->price,
            'weight'=> 0,
        ]);

        if (Auth::check()) {
            Cart::instance('wishlist')->store(Auth::id() . '_wishlist');
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    public function render()
    {
        return view('livewire.part-component');
    }
}