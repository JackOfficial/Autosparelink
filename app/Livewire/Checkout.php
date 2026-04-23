<?php

namespace App\Livewire;

use App\Mail\{OrderCallbackAdmin, OrderCallbackClient};
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\{Order, OrderItem, Address, Commission, Part};
use Illuminate\Support\Facades\{Auth, DB, Mail, Log, Cookie};
use App\Services\InTouchPaymentService; // Added for InTouchPay
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $addresses, $address_id, $guest_email;         // Selected existing address
    public $new_address = [];    // Array for new address
    public $use_new_address = false; // Toggle for using new address

    public function mount()
    {
        $this->addresses = Auth::check() ? Auth::user()->addresses()->get() : collect();

        $saved_email   = Cookie::get('guest_email');
        $saved_name    = Cookie::get('guest_name');
        $saved_phone   = Cookie::get('guest_phone');
        $saved_street  = Cookie::get('guest_address');
        $saved_city    = Cookie::get('guest_city');
        $saved_zip     = Cookie::get('guest_postal_code');

        if (!Auth::check() && $saved_email) {
            $this->use_new_address = false;
        } else {
            $this->use_new_address = !Auth::check() || $this->addresses->isEmpty();
        }

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

    private function saveGuestCookies()
    {
        if (!Auth::check()) {
            $duration = 60 * 24 * 30; // 30 days
            Cookie::queue('guest_email', $this->guest_email, $duration);
            Cookie::queue('guest_name', $this->new_address['full_name'], $duration);
            Cookie::queue('guest_phone', $this->new_address['phone'], $duration);
            Cookie::queue('guest_address', $this->new_address['street_address'], $duration);
            Cookie::queue('guest_city', $this->new_address['city'], $duration);
            Cookie::queue('guest_postal_code', $this->new_address['postal_code'] ?? '', $duration);
        }
    }

    /**
     * Updated to use InTouchPaymentService"
     */
    public function placeOrder(InTouchPaymentService $inTouch)
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
            
            // Unique external_id for InTouchPay
            $transactionId = 'AST-' . strtoupper(Str::random(10));

            $order = Order::create([
                'user_id'                => Auth::id(),
                'address_id'             => $final_address_id,
                'total_amount'           => $subtotal,
                'status'                 => 'pending',
                'order_id'               => $transactionId, // Store the unique ID for callback matching
                'is_guest'               => !Auth::check(),
                'guest_name'             => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email'            => $this->guest_email,
                'guest_phone'            => $this->new_address['phone'],
                'guest_shipping_address' => !Auth::check() 
                    ? ($this->new_address['street_address'] . ', ' . $this->new_address['city'] . ', ' . $this->new_address['country']) 
                    : null,
            ]);

            foreach ($cartItems as $item) {
                $part = Part::find($item->id);
                if ($part) {
                    $rate = Commission::getRate();
                    $commissionAmount = (($item->price * $item->qty) * $rate) / 100;

                    OrderItem::create([
                        'order_id'          => $order->id,
                        'part_id'           => $item->id,
                        'shop_id'           => $part->shop_id,
                        'part_name'         => $item->name,
                        'quantity'          => $item->qty,
                        'unit_price'        => $item->price,
                        'commission_amount' => $commissionAmount,
                        'status'            => 'pending',
                    ]);
                }
            }

            // 1. Initiate the InTouchPay push request
            $paymentPhone = $this->use_new_address || !Auth::check() 
                ? $this->new_address['phone'] 
                : Auth::user()->addresses->find($this->address_id)->phone;

            $response = $inTouch->requestPayment(
                $paymentPhone,
                $subtotal,
                $transactionId
            );

            // 2. Process Response
            if (isset($response['success']) && $response['success'] == true) {
                DB::commit();
                $this->saveGuestCookies();
                Cart::instance('default')->destroy();

                if (Auth::check()) {
                    DB::table('shoppingcart')->where('identifier', Auth::id())->where('instance', 'default')->delete();
                }

                // Commented out Flutterwave logic as requested:
                // return redirect()->route('payment.process', ['order' => $order->id]);

                session()->flash('message', 'Payment request sent to ' . $paymentPhone . '. Please check your phone.');
                return redirect()->route('order.success', ['order' => $order->id]);
            } else {
                throw new \Exception($response['message'] ?? 'Could not initiate InTouchPay payment.');
            }

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
            $this->dispatch('notify', message: 'Please select an address.');
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
                'user_id'                => Auth::id(),
                'address_id'             => $final_address_id,
                'total_amount'           => $subtotal,
                'status'                 => 'callback_requested',
                'is_guest'               => !Auth::check(),
                'guest_name'             => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email'            => $this->guest_email,
                'guest_phone'            => $this->new_address['phone'],
                'guest_shipping_address' => !Auth::check() 
                    ? ($this->new_address['street_address'] . ', ' . $this->new_address['city']) 
                    : null,
            ]);

            foreach ($cartItems as $item) {
                $part = Part::find($item->id);
                if ($part) {
                    $rate = Commission::getRate();
                    $commissionAmount = (($item->price * $item->qty) * $rate) / 100;

                    OrderItem::create([
                        'order_id'          => $order->id,
                        'part_id'           => $item->id,
                        'shop_id'           => $part->shop_id,
                        'part_name'         => $item->name,
                        'quantity'          => $item->qty,
                        'unit_price'        => $item->price,
                        'commission_amount' => $commissionAmount,
                        'status'            => 'pending',
                    ]);
                }
            }

            DB::commit();
            $this->saveGuestCookies();
            Cart::instance('default')->destroy();

            if (Auth::check()) {
                DB::table('shoppingcart')->where('identifier', Auth::id())->delete();
            }

            // Emails
            try {
                Mail::to('musengimanajacques@gmail.com')->send(new OrderCallbackAdmin($order));
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
            $this->dispatch('notify', message: 'Something went wrong.');
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