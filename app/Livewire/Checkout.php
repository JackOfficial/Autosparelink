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
use Illuminate\Support\Facades\Cookie;

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

        // 1. Retrieve Guest details from Cookies if they exist
        $saved_email   = Cookie::get('guest_email');
        $saved_name    = Cookie::get('guest_name');
        $saved_phone   = Cookie::get('guest_phone');
        $saved_street  = Cookie::get('guest_address');
        $saved_city    = Cookie::get('guest_city');
        $saved_zip     = Cookie::get('guest_postal_code');

        // 2. Determine Initial Toggle State
        if (!Auth::check() && $saved_email) {
            // If they are a returning guest with data, show the "Saved" card first
            $this->use_new_address = false;
        } else {
            // Otherwise, open the form if guest or no saved addresses exist
            $this->use_new_address = !Auth::check() || $this->addresses->isEmpty();
        }

        // 3. Pre-fill address fields
        $this->new_address = [
            'full_name'      => Auth::check() ? Auth::user()->name : ($saved_name ?? ''),
            'phone'          => Auth::check() ? (Auth::user()->phone ?? '') : ($saved_phone ?? ''),
            'street_address' => $saved_street ?? '',
            'city'           => $saved_city ?? '',
            'state'          => '',
            'postal_code'    => $saved_zip ?? '',
            'country'        => 'Rwanda',
        ];

        $this->guest_email = Auth::check() ? Auth::user()->email : ($saved_email ?? '');
    }

    /**
     * Helper to remember guest info in cookies for 30 days
     */
    private function saveGuestCookies()
    {
        if (!Auth::check()) {
            Cookie::queue('guest_email', $this->guest_email, 60 * 24 * 30);
            Cookie::queue('guest_name', $this->new_address['full_name'], 60 * 24 * 30);
            Cookie::queue('guest_phone', $this->new_address['phone'], 60 * 24 * 30);
            Cookie::queue('guest_address', $this->new_address['street_address'], 60 * 24 * 30);
            Cookie::queue('guest_city', $this->new_address['city'], 60 * 24 * 30);
            Cookie::queue('guest_postal_code', $this->new_address['postal_code'] ?? '', 60 * 24 * 30);
        }
    }

    public function placeOrder()
    {
        $cartItems = Cart::instance('default')->content();

        if ($cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Your cart is empty!');
            return;
        }

        $rules = [
            'guest_email' => Auth::check() ? 'nullable|email' : 'required|email',
        ];

        if ($this->use_new_address || !Auth::check()) {
            $rules = array_merge($rules, [
                'new_address.full_name'      => 'required|string|max:255',
                'new_address.phone'          => 'required|string|max:20',
                'new_address.street_address' => 'required|string|max:255',
                'new_address.city'           => 'required|string|max:100',
                'new_address.country'        => 'required|string|max:100',
            ]);
        } elseif (!$this->address_id) {
            $this->dispatch('notify', message: 'Please select a delivery address.');
            return;
        }

        $this->validate($rules);

        DB::beginTransaction();

        try {
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

            $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
            
            $order = Order::create([
                'user_id'                => Auth::id(),
                'address_id'             => $final_address_id,
                'total_amount'           => $subtotal,
                'status'                 => 'pending',
                'guest_name'             => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email'            => $this->guest_email,
                'guest_phone'            => $this->new_address['phone'],
                'guest_shipping_address' => !Auth::check() 
                    ? ($this->new_address['street_address'] . ', ' . $this->new_address['city'] . ', ' . $this->new_address['country']) 
                    : null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'part_id'    => $item->id,
                    'quantity'   => $item->qty,
                    'unit_price' => $item->price,
                ]);
            }

            DB::commit();

            $this->saveGuestCookies(); // Save info for next time

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
        $rules = [
            'guest_email' => Auth::check() ? 'nullable|email' : 'required|email',
        ];

        if ($this->use_new_address || !Auth::check()) {
            $rules = array_merge($rules, [
                'new_address.full_name'      => 'required|string|max:255',
                'new_address.phone'          => 'required|string|max:20',
                'new_address.street_address' => 'required|string',
                'new_address.city'           => 'required|string',
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

            $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
            
            $order = Order::create([
                'user_id'      => Auth::id(),
                'address_id'   => $final_address_id,
                'total_amount' => $subtotal,
                'status'       => 'callback_requested',
                'guest_name'   => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email'  => $this->guest_email,
                'guest_phone'  => $this->new_address['phone'],
                'guest_shipping_address' => !Auth::check() 
                    ? ($this->new_address['street_address'] . ', ' . $this->new_address['city']) 
                    : null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'part_id'    => $item->id,
                    'quantity'   => $item->qty,
                    'unit_price' => $item->price,
                ]);
            }

            DB::commit();

            $this->saveGuestCookies(); // Save info for next time

            Cart::instance('default')->destroy();
            if (Auth::check()) {
                DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
            }

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
            'total'       => Cart::instance('default')->subtotal(),
            'addresses'   => Auth::check() ? Auth::user()->addresses : collect()
        ]);
    }
}