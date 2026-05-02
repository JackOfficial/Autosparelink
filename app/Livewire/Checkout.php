<?php

namespace App\Livewire;

use App\Mail\{OrderCallbackAdmin, OrderCallbackClient};
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\{Order, OrderItem, Address, Commission, Part};
use Illuminate\Support\Facades\{Auth, DB, Mail, Log, Cookie};
use App\Services\InTouchPaymentService;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $addresses, $address_id, $guest_email;
    public $new_address = [];
    public $use_new_address = false;
    public $payment_method = 'momo'; // Options: 'momo' or 'cod'

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
     * Calculates the average shipping price for items in the cart.
     * Falls back to regional fees if no specific custom category shipping price is set.
     */
    private function calculateAverageShippingPrice($city)
    {
        $cartItems = Cart::instance('default')->content();
        if ($cartItems->isEmpty()) {
            return 0;
        }

        $totalShipping = 0;
        $validItemCount = 0;

        // Regional base fallback fee
        $fallbackFee = (strtolower(trim($city)) === 'kigali') ? 3000 : 5000;

        foreach ($cartItems as $item) {
            $part = Part::with('category')->find($item->id);

            if ($part) {
                // If the direct category has a custom shipping price, use it
                if ($part->category && $part->category->shipping_price > 0) {
                    $totalShipping += $part->category->shipping_price;
                } else {
                    $totalShipping += $fallbackFee;
                }
                $validItemCount++;
            }
        }

        return $validItemCount > 0 ? ($totalShipping / $validItemCount) : $fallbackFee;
    }

    public function placeOrder(InTouchPaymentService $inTouch)
    {
        $cartItems = Cart::instance('default')->content();

        if ($cartItems->isEmpty()) {
            $this->dispatch('notify', message: 'Your cart is empty!');
            return;
        }

        $rules = [
            'guest_email' => Auth::check() ? 'nullable|email' : 'required|email',
            'payment_method' => 'required|in:momo,cod',
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
            $paymentPhone = '';
            $city = '';

            // 1. Resolve Address and City
            if (Auth::check() && !$this->use_new_address) {
                $selectedAddress = Address::find($this->address_id);
                $final_address_id = $selectedAddress->id;
                $paymentPhone = $selectedAddress->phone;
                $city = $selectedAddress->city;
            } else {
                $paymentPhone = $this->new_address['phone'];
                $city = $this->new_address['city'];
                if (Auth::check()) {
                    $address = Address::create(array_merge($this->new_address, ['user_id' => Auth::id()]));
                    $final_address_id = $address->id;
                }
            }

            // Calculate exact shipping fee for the cart
            $shippingFee = $this->calculateAverageShippingPrice($city);
            $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
            
            // Total price includes the subtotal and the calculated shipping fee
            $totalOrderAmount = $subtotal + $shippingFee;

            // 2. Logic flow for Payment Method vs Payable Now amount
            if ($this->payment_method === 'cod') {
                $payableNow = $shippingFee; // Charge only shipping upfront for CoD
                $orderStatus = 'awaiting_commitment_fee';
            } else {
                $payableNow = $totalOrderAmount; // Pay everything now for MoMo
                $orderStatus = 'pending';
            }

            $localTransactionId = 'AST-' . strtoupper(Str::random(10));

            $order = Order::create([
                'user_id'                => Auth::id(),
                'address_id'             => $final_address_id,
                'total_amount'           => $totalOrderAmount,
                'shipping_amount'        => $shippingFee, // Added for record keeping
                'paid_amount'            => 0,            // Will be updated via Webhook
                'status'                 => $orderStatus,
                'order_number'           => $localTransactionId, 
                'payment_method'         => $this->payment_method,
                'is_guest'               => !Auth::check(),
                'guest_name'             => !Auth::check() ? $this->new_address['full_name'] : null,
                'guest_email'            => $this->guest_email,
                'guest_phone'            => $this->new_address['phone'],
                'guest_shipping_address' => !Auth::check() 
                    ? ($this->new_address['street_address'] . ', ' . $city . ', ' . $this->new_address['country']) 
                    : null,
            ]);

            foreach ($cartItems as $item) {
                $part = Part::find($item->id);
                if ($part) {
                    $rate = Commission::getRate();
                    OrderItem::create([
                        'order_id'          => $order->id,
                        'part_id'           => $item->id,
                        'shop_id'           => $part->shop_id,
                        'part_name'         => $item->name,
                        'quantity'          => $item->qty,
                        'unit_price'        => $item->price,
                        'commission_amount' => (($item->price * $item->qty) * $rate) / 100,
                        'status'            => 'pending',
                    ]);
                }
            }

            // 3. Initiate InTouchPay Request
            $response = $inTouch->requestPayment($paymentPhone, $payableNow, $localTransactionId);

            if ($response && isset($response['success']) && $response['success'] == true) {
                $order->update(['transaction_id' => $response['transactionid'] ?? null]);

                DB::commit();
                $this->saveGuestCookies();
                Cart::instance('default')->destroy();

                if (Auth::check()) {
                    DB::table('shoppingcart')->where('identifier', Auth::id())->where('instance', 'default')->delete();
                }

                $message = ($this->payment_method === 'cod') 
                    ? "Please pay the delivery fee of " . number_format($payableNow) . " RWF on your phone to confirm delivery."
                    : "Payment request sent. Please check your phone.";

                session()->flash('message', $message);
                return redirect()->route('order.success', ['order' => $order->id]);
            } else {
                throw new \Exception($response['message'] ?? "Gateway Connection Failed");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout API Error: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Payment Error: ' . $e->getMessage());
        }
    }

    public function requestCallback() 
    {
        // Existing Callback logic updated with shipping price
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
            $city = $this->use_new_address ? $this->new_address['city'] : '';

            if (Auth::check()) {
                if ($this->use_new_address) {
                    $address = Address::create(array_merge($this->new_address, [
                        'user_id' => Auth::id(),
                    ]));
                    $final_address_id = $address->id;
                } else {
                    $final_address_id = $this->address_id;
                    $selectedAddress = Address::find($this->address_id);
                    $city = $selectedAddress ? $selectedAddress->city : '';
                }
            }

            $shippingFee = $this->calculateAverageShippingPrice($city);
            $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
            $totalOrderAmount = $subtotal + $shippingFee;
            
            $order = Order::create([
                'user_id'                => Auth::id(),
                'address_id'             => $final_address_id,
                'total_amount'           => $totalOrderAmount,
                'shipping_amount'        => $shippingFee,
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
        $city = '';
        if ($this->use_new_address || !Auth::check()) {
            $city = $this->new_address['city'] ?? '';
        } elseif ($this->address_id) {
            $selectedAddress = Address::find($this->address_id);
            $city = $selectedAddress ? $selectedAddress->city : '';
        }

        $shippingFee = $this->calculateAverageShippingPrice($city);
        $subtotal = (float) str_replace(',', '', Cart::instance('default')->subtotal());
        $totalWithShipping = $subtotal + $shippingFee;

        return view('livewire.checkout', [
            'cartContent'       => Cart::instance('default')->content(),
            'subtotal'          => $subtotal,
            'shippingFee'       => $shippingFee,
            'totalWithShipping' => $totalWithShipping,
            'addresses'         => Auth::check() ? Auth::user()->addresses : collect()
        ]);
    }
}