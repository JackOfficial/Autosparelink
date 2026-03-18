<?php

namespace App\Livewire;

use App\Mail\OrderCallbackAdmin;
use App\Mail\OrderCallbackClient;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Checkout extends Component
{
    public $addresses;           // User addresses
    public $address_id;          // Selected existing address
    public $new_address = [];    // Array for new address
    public $use_new_address = false; // Toggle for using new address
    public $guest_email;         // Added for guest email specifically

    public function mount()
    {
        // Load user's addresses if logged in
        $this->addresses = Auth::check() ? Auth::user()->addresses()->get() : collect();

        // Auto toggle new address if guest or no saved addresses exist
        $this->use_new_address = !Auth::check() || $this->addresses->isEmpty();

        // Default new address fields
        $this->new_address = [
            'full_name' => Auth::check() ? Auth::user()->name : '',
            'phone' => Auth::check() ? (Auth::user()->phone ?? '') : '',
            'street_address' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => 'Rwanda',
        ];

        if (Auth::check()) {
            $this->guest_email = Auth::user()->email;
        }
    }

    public function placeOrder()
    {
        $cartItems = Cart::instance('default')->content();

        if ($cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Your cart is empty!');
            return;
        }

        // 1. Validation Logic (Handles both Guests and Auth users)
        $rules = [
            'guest_email' => Auth::check() ? 'nullable|email' : 'required|email',
        ];

        if ($this->use_new_address || !Auth::check()) {
            $rules = array_merge($rules, [
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
        $this->validate($rules);

        DB::beginTransaction();

        try {
            // 2. Process Address
            $final_address_id = null;
            
            // Only create an Address record if the user is logged in
            if (Auth::check()) {
                if ($this->use_new_address) {
                    $address = Address::create(array_merge($this->new_address, [
                        'user_id' => Auth::id(),
                    ]));
                    $final_address_id = $address->id;
                } else {
                    $final_address_id = $this->address_id;
                }
            }

            // 3. Create the Order
            $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
            
            $order = Order::create([
                'user_id' => Auth::id(), // returns null for guests
                'address_id' => $final_address_id, // returns null for guests
                'total_amount' => $subtotal,
                'status' => 'pending',
                // New Guest Fields (from migration)
                'guest_name' => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email' => $this->guest_email,
                'guest_phone' => $this->new_address['phone'],
                'guest_shipping_address' => !Auth::check() ? ($this->new_address['street_address'] . ', ' . $this->new_address['city']) : null,
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

            if (Auth::check()) {
                DB::table('shoppingcart')
                    ->where('identifier', Auth::id())
                    ->where('instance', 'default')
                    ->delete();
            }

            return redirect()->route('payment.process', ['order' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function requestCallback() 
    {
        // Removed Auth::check() requirement
        
        // 1. Validation
        $rules = [
            'guest_email' => Auth::check() ? 'nullable|email' : 'required|email',
        ];

        if ($this->use_new_address || !Auth::check()) {
            $rules = array_merge($rules, [
                'new_address.full_name' => 'required|string|max:255',
                'new_address.phone' => 'required|string|max:20',
                'new_address.street_address' => 'required|string',
                'new_address.city' => 'required|string',
            ]);
        } elseif (!$this->address_id) {
            $this->dispatch('notify', message: 'Please select an address so we know your location.');
            return;
        }
        $this->validate($rules);

        $cartItems = Cart::instance('default')->content();
        if ($cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Your cart is empty!');
            return;
        }

        DB::beginTransaction();

        try {
            // 2. Process Address (Auth only)
            $final_address_id = null;
            if (Auth::check()) {
                if ($this->use_new_address) {
                    $address = Address::create(array_merge($this->new_address, [
                        'user_id' => Auth::id(),
                    ]));
                    $final_address_id = $address->id;
                } else {
                    $final_address_id = $this->address_id;
                }
            }

            // 3. Create the Order
            $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $final_address_id,
                'total_amount' => $subtotal,
                'status' => 'callback_requested',
                'guest_name' => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email' => $this->guest_email,
                'guest_phone' => $this->new_address['phone'],
            ]);

            // 4. Save Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'part_id' => $item->id,
                    'quantity' => $item->qty,
                    'unit_price' => $item->price,
                ]);
            }

            Cart::instance('default')->destroy();
            if (Auth::check()) {
                DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
            }

            DB::commit();

            // 6. Send Emails
            try {
                Mail::to('admin@happyfamilyrwanda.org')->send(new OrderCallbackAdmin($order));
                
                $targetEmail = Auth::check() ? Auth::user()->email : $this->guest_email;
                if ($targetEmail) {
                    Mail::to($targetEmail)->send(new OrderCallbackClient($order));
                }
            } catch (\Exception $e) {
                Log::error('Callback Email Failed: ' . $e->getMessage());
            }

            return redirect()->route('order.success', ['order' => $order->id])
                             ->with('message', 'Murakoze! We will call you shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Callback Failed: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Something went wrong. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.checkout', [
            'cartContent' => Cart::instance('default')->content(),
            'total' => Cart::instance('default')->subtotal(),
            'addresses' => Auth::check() ? Auth::user()->addresses : collect()
        ]);
    }
}