<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Part;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class PartCard extends Component
{
    public Part $part;
    public $quantity = 1;

    public function mount(Part $part)
    {
        // Eager load shop and brand. Make sure 'location' is a column in your shops table.
        $this->part = $part->load(['shop', 'partBrand']);
    }

    public function addToCart()
    {
        if ($this->quantity > $this->part->stock_quantity) {
            $this->dispatch('notify', message: 'Not enough stock available!');
            return;
        }

        Cart::instance('default')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => $this->quantity,
            'price'   => $this->part->price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_id'       => $this->part->shop_id,
                'shop_location' => $this->part->shop?->address, // Added Shop Location
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('default');
        }

        $this->dispatch('cartUpdated');
        $this->dispatch('notify', message: 'Item added to cart!');
    }

    public function addToWishlist()
    {
        $exists = Cart::instance('wishlist')
            ->search(fn($cartItem) => $cartItem->id == $this->part->id)
            ->isNotEmpty();

        if ($exists) {
            $this->dispatch('notify', message: 'Already in wishlist!');
            return;
        }

        Cart::instance('wishlist')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => 1,
            'price'   => $this->part->price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'part_number'   => $this->part->part_number,
                'image'         => $this->part->image,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_location' => $this->part->shop?->address, // Added Shop Location
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('wishlist');
        }

        $this->dispatch('wishlistUpdated');
        $this->dispatch('notify', message: 'Added to wishlist!');
    }

    protected function syncCartWithDatabase($instance)
    {
        try {
            Cart::instance($instance)->store(auth()->id());
        } catch (\Gloudemans\Shoppingcart\Exceptions\CartAlreadyStoredException $e) {
            Cart::instance($instance)->erase(auth()->id());
            Cart::instance($instance)->store(auth()->id());
        }
    }

    public function render()
    {
        return view('livewire.part-card');
    }
}