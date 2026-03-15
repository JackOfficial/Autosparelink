<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Shipping;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    public $addresses;           // User addresses
    public $address_id;          // Selected existing address
    public $new_address = [];    // Array for new address
    public $use_new_address = false; // Toggle for using new address

    public function mount()
    {
        if (!Auth::check()) return;

        // Load user's addresses
        $this->addresses = Auth::user()->addresses ?? collect();

        // Auto toggle new address if none exist
        $this->use_new_address = $this->addresses->isEmpty();

        // Default new address fields
        $this->new_address = [
            'full_name' => Auth::user()->name,
            'phone' => Auth::user()->phone ?? '',
            'street_address' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => 'Rwanda',
        ];
    }

    public function placeOrder()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', message: 'Please log in to place an order.');
            return;
        }

        $cartItems = Cart::instance('default')->content();

        if ($cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Your cart is empty!');
            return;
        }

        // Validate new address if selected
        if ($this->use_new_address) {
            $this->validate([
                'new_address.full_name' => 'required|string|max:255',
                'new_address.phone' => 'required|string|max:20',
                'new_address.street_address' => 'required|string|max:255',
                'new_address.city' => 'required|string|max:100',
                'new_address.state' => 'nullable|string|max:100',
                'new_address.postal_code' => 'nullable|string|max:20',
                'new_address.country' => 'required|string|max:100',
            ]);
        } else {
            if (!$this->address_id) {
                $this->dispatch('notify', message: 'Please select an address.');
                return;
            }
        }

        DB::beginTransaction();

        try {
        // 1. Save address
        $address_id = $this->address_id;
        if ($this->use_new_address) {
            $address = Address::create(array_merge($this->new_address, [
                'user_id' => Auth::id(),
            ]));
            $address_id = $address->id;
        }

        // 2. Create the Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $address_id,
            'total_amount' => (float) str_replace(',', '', Cart::instance('default')->subtotal()),
            'status' => 'pending',
        ]);

        // 3. Create Order Items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'part_id' => $item->id,
                'quantity' => $item->qty,
                'unit_price' => $item->price,
            ]);
        }

        DB::commit();

        // 4. Instead of showing the order, redirect to our Payment Initialization
        // We pass the order ID so the controller knows what we are paying for
        return redirect()->route('payment.initialize', [
            'order_id' => $order->id,
            'amount' => $order->total_amount
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('notify', message: 'Error: ' . $e->getMessage());
    }
    
    }

    public function render()
    {
        if (!Auth::check()) {
            return view('livewire.checkout', [
                'cartItems' => [],
                'total' => 0,
                'addresses' => collect(),
            ]);
        }

        // Convert cart items to plain array for Livewire
        $cartItems = Cart::instance('default')->content()->map(function ($item) {
            return [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'weight' => $item->weight,
                'options' => $item->options,
            ];
        })->toArray();

        $total = Cart::instance('default')->subtotal();
        $addresses = Auth::user()->addresses ?? collect();

        return view('livewire.checkout', compact('cartItems', 'total', 'addresses'));
    }
}