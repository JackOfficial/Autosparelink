<?php

namespace App\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
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

        // Load user's addresses - ensure the relationship exists on User model
        $this->addresses = Auth::user()->addresses()->get() ?? collect();

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

        // 1. Validation Logic
        if ($this->use_new_address) {
            $this->validate([
                'new_address.full_name' => 'required|string|max:255',
                'new_address.phone' => 'required|string|max:20',
                'new_address.street_address' => 'required|string|max:255',
                'new_address.city' => 'required|string|max:100',
                'new_address.country' => 'required|string|max:100',
            ]);
        } else {
            if (!$this->address_id) {
                $this->dispatch('notify', message: 'Please select a delivery address.');
                return;
            }
        }

        DB::beginTransaction();

        try {
            // 2. Process Address
            $final_address_id = $this->address_id;
            if ($this->use_new_address) {
                $address = Address::create(array_merge($this->new_address, [
                    'user_id' => Auth::id(),
                ]));
                $final_address_id = $address->id;
            }

            // 3. Create the Order
            // Remove commas from subtotal string to ensure it's a valid float
            $subtotal = str_replace(',', '', Cart::instance('default')->subtotal());
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $final_address_id,
                'total_amount' => (float) $subtotal,
                'status' => 'pending',
            ]);

            // 4. Create Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'part_id' => $item->id,
                    'quantity' => $item->qty,
                    'unit_price' => $item->price,
                ]);
            }

            DB::commit();

            // 5. Clear the Cart
            Cart::instance('default')->destroy();

            /**
             * FIX: Redirect to a GET route.
             * Instead of posting directly to initialize, we redirect to a 'processing' 
             * page that will then submit the POST form to Flutterwave/Payment Gateway.
             */
            return redirect()->route('payment.process', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', message: 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function requestCallback()
    {
        // Simple callback logic for the button in your blade
        $this->dispatch('notify', message: 'We have received your request. A representative will call you shortly.');
    }

    public function render()
    {
        $cartItems = Cart::instance('default')->content()->map(function ($item) {
            return [
                'rowId' => $item->rowId,
                'id' => $item->id,
                'name' => $item->name,
                'qty' => $item->qty,
                'price' => $item->price,
                'options' => $item->options,
            ];
        })->toArray();

        $total = Cart::instance('default')->subtotal();
        
        // Refresh addresses list
        $this->addresses = Auth::check() ? Auth::user()->addresses()->get() : collect();

        return view('livewire.checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'addresses' => $this->addresses
        ]);
    }
}