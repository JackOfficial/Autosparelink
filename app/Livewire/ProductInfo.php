<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;

class ProductInfo extends Component
{
    public $part;
    public $quantity = 1;
    public $shareUrl;
    public $shareText;

    public function mount($part)
    {
        // Eager load shop and category for the UI
        $part->load([
            'partBrand',
            'shop',
            'category', 
            'fitments.vehicleModel.brand',
        ]);

        $this->part = $part;
        $this->shareUrl  = urlencode(request()->fullUrl());
        
        // Use unit_price (Markup Price) for the share text
        $this->shareText = urlencode($this->part->part_name . ' - Only ' . number_format($this->part->unit_price, 0) . ' RWF');
    }

    public function incrementQty()
    {
        if ($this->quantity < $this->part->stock_quantity) {
            $this->quantity++;
        } else {
            $this->dispatch('swal', [
                'icon'  => 'warning',
                'title' => 'Stock Limit',
                'text'  => "We only have {$this->part->stock_quantity} available in stock.",
                'timer' => 2500
            ]);
        }
    }

    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        if ($this->quantity > $this->part->stock_quantity) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Out of Stock',
                'text'  => 'Not enough stock available to fulfill your request.',
                'timer' => 3000
            ]);
            return;
        }

        $mainPhoto = $this->part->photos->first()?->file_path ?? $this->part->image ?? 'frontend/img/placeholder.png';

        Cart::instance('default')->add([
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
                'shop_location' => $this->part->shop?->address,
                'base_price'    => (float) $this->part->price,
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('default');
        }

        // Trigger JS Success Toast
        $this->dispatch('swal', [
            'icon'     => 'success',
            'title'    => 'Done!',
            'text'     => 'Item added to your cart!',
            'timer'    => 2000,
            'toast'    => true,
            'position' => 'top-end'
        ]);

        $this->dispatch('cartUpdated');
    }

    public function addToWishlist()
    {
        $exists = Cart::instance('wishlist')
            ->search(fn($item) => $item->id == $this->part->id)
            ->isNotEmpty();

        if ($exists) {
            $this->dispatch('swal', [
                'icon'     => 'info',
                'text'     => 'Item is already in your wishlist.',
                'toast'    => true,
                'position' => 'top-end',
                'timer'    => 2500
            ]);
            return;
        }

        Cart::instance('wishlist')->add([
            'id'      => $this->part->id,
            'name'    => $this->part->part_name,
            'qty'     => 1,
            'price'   => (float) $this->part->unit_price,
            'weight'  => 0,
            'options' => [
                'brand'         => $this->part->partBrand?->name,
                'part_number'   => $this->part->part_number,
                'shop_name'     => $this->part->shop?->shop_name,
                'shop_location' => $this->part->shop?->address,
            ]
        ]);

        if (auth()->check()) {
            $this->syncCartWithDatabase('wishlist');
        }

        $this->dispatch('swal', [
            'icon'     => 'success',
            'title'    => 'Saved!',
            'text'     => 'Added to your wishlist.',
            'timer'    => 2000,
            'toast'    => true,
            'position' => 'top-end'
        ]);

        $this->dispatch('wishlistUpdated');
    }

    protected function syncCartWithDatabase($instance)
    {
        // Using a more robust deletion before storage to avoid duplicates
        $identifier = auth()->id() . ($instance === 'wishlist' ? '_wishlist' : '');
        
        DB::table('shoppingcart')
            ->where('identifier', $identifier)
            ->where('instance', $instance)
            ->delete();
            
        Cart::instance($instance)->store($identifier);
    }

    public function render()
    {
        return view('livewire.product-info');
    }
}