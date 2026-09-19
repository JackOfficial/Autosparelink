<?php

namespace App\Livewire;

use App\Mail\{OrderCallbackAdmin, OrderCallbackClient};
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\{Order, OrderItem, Address, Commission, Part, Shipping};
use Illuminate\Support\Facades\{Auth, DB, Mail, Log, Cookie};
use App\Services\InTouchPaymentService;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public $addresses, $address_id, $guest_email;
    public $new_address = [];
    public $use_new_address = false;
    public $payment_method = 'momo'; // Options: 'momo' or 'cod'
    public $total;

    public function mount()
    {
        Log::info('[Checkout Mounted] User ID: ' . (Auth::id() ?? 'Guest'));

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

        // Auto-select the default address
        if (Auth::check() && !$this->use_new_address && $this->addresses->isNotEmpty()) {
            $this->address_id = $this->addresses->first()->id;
        }

        // Fix: Explicitly compute the initial total upon initial page load
        $city = $this->getCurrentCity();
        $shippingFee = $this->calculateAverageShippingPrice($city);
        $subtotal = (float) Cart::instance('default')->subtotal(2, '.', '');
        $this->total = $subtotal + $shippingFee;

        Log::info('[Checkout Initialized]', [
            'use_new_address' => $this->use_new_address,
            'address_id' => $this->address_id,
            'city' => $city,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $this->total
        ]);
    }

    public function updated($propertyName)
    {
        Log::info("[Checkout Updated] Property changed: {$propertyName}");

        if (in_array($propertyName, ['address_id', 'use_new_address', 'new_address.city'])) {
            $city = $this->getCurrentCity();
            $shippingFee = $this->calculateAverageShippingPrice($city);
            $subtotal = (float) Cart::instance('default')->subtotal(2, '.', '');
            $this->total = $subtotal + $shippingFee;

            Log::info('[Checkout Re-calculated]', [
                'city' => $city,
                'shipping_fee' => $shippingFee,
                'total' => $this->total
            ]);
        }
    }

    private function getCurrentCity()
    {
        $city = '';
        if ($this->use_new_address || !Auth::check()) {
            $city = $this->new_address['city'] ?? '';
        } elseif ($this->address_id) {
            $selectedAddress = Address::find($this->address_id);
            $city = $selectedAddress ? $selectedAddress->city : '';
        }
        return $city;
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

            Log::info('[Guest Cookies Saved]', [
                'guest_email' => $this->guest_email,
                'guest_name' => $this->new_address['full_name'],
                'guest_city' => $this->new_address['city']
            ]);
        }
    }

    private function calculateAverageShippingPrice($city)
    {
        $cartItems = Cart::instance('default')->content();
        if ($cartItems->isEmpty()) {
            Log::warning('[Shipping Calculation] Cart is empty during shipping calculation.');
            return 0;
        }

        $cityClean = strtolower(trim($city));
        $fallbackFee = ($cityClean === 'kigali') ? 3000 : 5000;

        $itemIds = $cartItems->pluck('id')->toArray();
        $parts = Part::with('category')->whereIn('id', $itemIds)->get()->keyBy('id');

        $fees = [];

        foreach ($cartItems as $item) {
            $part = $parts->get($item->id);

            if ($part) {
                if ($part->category && $part->category->shipping_price > 0) {
                    $fees[] = (float) $part->category->shipping_price;
                } else {
                    $fees[] = (float) $fallbackFee;
                }
            } else {
                Log::warning("[Shipping Calculation] Part ID {$item->id} not found in database.");
                $fees[] = (float) $fallbackFee;
            }
        }

        if (empty($fees)) {
            return $fallbackFee;
        }

        // 1. Highest fee wins
        $highestFee = max($fees);

        // 2. Count extra items (total items minus the first one)
        $extraItemsCount = count($fees) - 1;

        if ($extraItemsCount <= 0) {
            return round($highestFee);
        }

        // 3. Add flat addition for each extra item (500 RWF)
        $flatAddition = 500 * $extraItemsCount;
        $calculatedShipping = round($highestFee + $flatAddition);

        Log::info('[Shipping Calculated]', [
            'city' => $city,
            'city_clean' => $cityClean,
            'item_count' => count($fees),
            'highest_fee' => $highestFee,
            'flat_addition' => $flatAddition,
            'final_shipping' => $calculatedShipping
        ]);

        return $calculatedShipping;
    }

    private function createOrder($finalAddressId, $totalOrderAmount, $shippingFee, $orderStatus, $localTransactionId, $city)
    {
        Log::info('[Create Order Initiated]', [
            'user_id' => Auth::id(),
            'final_address_id' => $finalAddressId,
            'total_amount' => $totalOrderAmount,
            'shipping_fee' => $shippingFee,
            'order_status' => $orderStatus,
            'order_number' => $localTransactionId,
            'city' => $city
        ]);

        $cartItems = Cart::instance('default')->content();

        // 1. Create the base Order record
        $order = Order::create([
            'user_id'                 => Auth::id(),
            'address_id'              => $finalAddressId,
            'total_amount'            => $totalOrderAmount,
            'net_total_amount'        => 0, 
            'delivery_price'          => $shippingFee, 
            'status'                  => $orderStatus,
            'order_number'            => $localTransactionId, 
            'is_guest'                => !Auth::check(),
            'guest_name'              => !Auth::check() ? $this->new_address['full_name'] : null,
            'guest_email'             => $this->guest_email,
            'guest_phone'             => $this->new_address['phone'],
            'guest_shipping_address' => !Auth::check() 
                ? ($this->new_address['street_address'] . ', ' . $city . ', ' . $this->new_address['country']) 
                : null,
        ]);

        Log::info("[Order Base Record Created] Order ID: {$order->id}");

        $itemIds = $cartItems->pluck('id')->toArray();
        $parts = Part::whereIn('id', $itemIds)->get()->keyBy('id');

        $totalNetShopPayout = 0;

        // 2. Generate individual Order Items
        foreach ($cartItems as $item) {
            $part = $parts->get($item->id);
            if ($part) {
                $unitPublicPrice = (float) $item->price; 
                $unitShopPayout  = (float) $part->price; 
                
                $itemTotalCustomerPaid = $unitPublicPrice * $item->qty;
                $itemTotalShopPayout   = $unitShopPayout * $item->qty;
                $itemCommissionAmount  = $itemTotalCustomerPaid - $itemTotalShopPayout;

                $totalNetShopPayout += $itemTotalShopPayout;

                $orderItem = OrderItem::create([
                    'order_id'          => $order->id,
                    'part_id'           => $item->id,
                    'shop_id'           => $part->shop_id,
                    'part_name'         => $item->name,
                    'quantity'          => $item->qty,
                    'unit_price'        => $unitPublicPrice,    
                    'shop_payout'       => $unitShopPayout,      
                    'commission_amount' => $itemCommissionAmount, 
                    'status'            => 'pending',
                ]);

                Log::info("[OrderItem Created] Item ID: {$orderItem->id}", [
                    'part_id' => $item->id,
                    'shop_id' => $part->shop_id,
                    'quantity' => $item->qty,
                    'unit_price' => $unitPublicPrice,
                    'shop_payout' => $unitShopPayout,
                    'commission' => $itemCommissionAmount
                ]);
            } else {
                Log::error("[OrderItem Skipped] Part ID {$item->id} missing from database when creating order items.");
            }
        }

        // 3. Update parent order payouts snapshot
        $order->update([
            'net_total_amount' => $totalNetShopPayout
        ]);

        Log::info("[Order Payout Snapshot Updated] Order ID: {$order->id}, Net Payout: {$totalNetShopPayout}");

        // 4. Generate metadata for the single shipping package snapshot
        $compiledAddressText = '';
        $recipientName = '';
        $recipientPhone = '';

        if (!Auth::check()) {
            $compiledAddressText = $this->new_address['street_address'] . ', ' . $city . ', ' . $this->new_address['country'];
            $recipientName = $this->new_address['full_name'];
            $recipientPhone = $this->new_address['phone'];
        } else {
            $recipientName = Auth::user()->name;
            if ($finalAddressId) {
                $dbAddress = Address::find($finalAddressId);
                if ($dbAddress) {
                    $compiledAddressText = $dbAddress->street_address . ', ' . $dbAddress->city . ', ' . $dbAddress->country;
                    $recipientPhone = $dbAddress->phone ?? Auth::user()->phone ?? '';
                }
            } else {
                $recipientPhone = Auth::user()->phone ?? '';
            }
        }

        // Fix: Removed vendor loop. Create exactly one package managed by the platform hub.
        $shipping = Shipping::create([
            'order_id'        => $order->id,
            'shop_id'         => null, // System package
            'address_id'      => Auth::check() ? $finalAddressId : null,
            'address_text'    => $compiledAddressText,
            'carrier'         => null,
            'shipping_method' => 'Standard',
            'shipping_cost'   => $shippingFee, // Entire fee applied to the single package
            'tracking_number' => null,
            'status'          => 'pending',
            'recipient_name'  => $recipientName,
            'recipient_phone' => $recipientPhone,
        ]);

        Log::info("[Shipping Package Created] Shipping ID: {$shipping->id}", [
            'order_id' => $order->id,
            'recipient' => $recipientName,
            'phone' => $recipientPhone,
            'cost' => $shippingFee
        ]);

        return $order;
    }

    public function placeOrder(InTouchPaymentService $inTouch)
    {
        Log::info('[Place Order Attempt Initiated]', [
            'payment_method' => $this->payment_method,
            'is_authenticated' => Auth::check(),
            'guest_email' => $this->guest_email
        ]);

        $cartItems = Cart::instance('default')->content();

        if ($cartItems->isEmpty()) {
            Log::warning('[Place Order Aborted] Cart is empty.');
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
            Log::warning('[Place Order Aborted] No delivery address selected.');
            $this->dispatch('notify', message: 'Please select a delivery address.');
            return;
        }

        $this->validate($rules);
        Log::info('[Place Order Validation Passed]');

        DB::beginTransaction();
        try {
            $final_address_id = null;
            $paymentPhone = '';
            $city = '';

            if (Auth::check() && !$this->use_new_address) {
                $selectedAddress = Address::find($this->address_id);
                if (!$selectedAddress) {
                    throw new \Exception("The selected address is invalid.");
                }
                $final_address_id = $selectedAddress->id;
                $paymentPhone = $selectedAddress->phone;
                $city = $selectedAddress->city;
            } else {
                $paymentPhone = $this->new_address['phone'];
                $city = $this->new_address['city'];
                if (Auth::check()) {
                    $address = Address::create(array_merge($this->new_address, ['user_id' => Auth::id()]));
                    $final_address_id = $address->id;
                    $this->addresses = Auth::user()->addresses()->get();
                    Log::info("[New Address Created] Address ID: {$address->id}");
                }
            }

            $shippingFee = $this->calculateAverageShippingPrice($city);
            $subtotal = (float) Cart::instance('default')->subtotal(2, '.', '');
            $totalOrderAmount = $subtotal + $shippingFee;

            $payableNow = ($this->payment_method === 'cod') ? $shippingFee : $totalOrderAmount;
            $orderStatus = 'pending';

            $localTransactionId = 'AST-' . strtoupper(Str::random(10));
            
            $order = $this->createOrder($final_address_id, $totalOrderAmount, $shippingFee, $orderStatus, $localTransactionId, $city);
        
            Log::info('[InTouch Payment Requesting]', [
                'phone' => $paymentPhone,
                'amount' => $payableNow,
                'transaction_id' => $localTransactionId
            ]);

            $response = $inTouch->requestPayment($paymentPhone, $payableNow, $localTransactionId);
            
            Log::info('[InTouch Payment Response Received]', ['response' => $response]);

            if ($response && isset($response['success']) && $response['success'] == true) {
                $order->update(['transaction_id' => $response['transactionid'] ?? null]);

                DB::commit();
                Log::info("[Order Successful & Transaction Committed] Order ID: {$order->id}");

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
            Log::error('Checkout API Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            $this->dispatch('notify', message: 'Payment Error: ' . $e->getMessage());
        }
    }

    public function requestCallback() 
    {
        Log::info('[Request Callback Initiated]', [
            'is_authenticated' => Auth::check(),
            'guest_email' => $this->guest_email
        ]);

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
            Log::warning('[Request Callback Aborted] No address selected.');
            $this->dispatch('notify', message: 'Please select an address.');
            return;
        }
        $this->validate($rules);

        $cartItems = Cart::instance('default')->content();
        if ($cartItems->isEmpty()) {
            Log::warning('[Request Callback Aborted] Cart is empty.');
            $this->dispatch('notify', message: 'Your cart is empty!');
            return;
        }

        DB::beginTransaction();
        try {
            $final_address_id = null;
            $city = $this->getCurrentCity();

            if (Auth::check()) {
                if ($this->use_new_address) {
                    $address = Address::create(array_merge($this->new_address, [
                        'user_id' => Auth::id(),
                    ]));
                    $final_address_id = $address->id;
                    $this->addresses = Auth::user()->addresses()->get();
                    Log::info("[Callback New Address Created] Address ID: {$address->id}");
                } else {
                    $final_address_id = $this->address_id;
                }
            }

            $shippingFee = $this->calculateAverageShippingPrice($city);
            $subtotal = (float) Cart::instance('default')->subtotal(2, '.', '');
            $totalOrderAmount = $subtotal + $shippingFee;
            $localTransactionId = 'AST-' . strtoupper(Str::random(10));

            $order = $this->createOrder($final_address_id, $totalOrderAmount, $shippingFee, 'callback_requested', $localTransactionId, $city);

            DB::commit();
            Log::info("[Callback Order Committed] Order ID: {$order->id}");

            $this->saveGuestCookies();
            Cart::instance('default')->destroy();

            if (Auth::check()) {
                DB::table('shoppingcart')->where('identifier', Auth::id())->where('instance', 'default')->delete();
            }

            try {
                Mail::to('musengimanajacques@gmail.com')->send(new OrderCallbackAdmin($order));
                Log::info("[Admin Callback Email Sent] Order ID: {$order->id}");

                $targetEmail = Auth::check() ? Auth::user()->email : $this->guest_email;
                if ($targetEmail) {
                    Mail::to($targetEmail)->send(new OrderCallbackClient($order));
                    Log::info("[Client Callback Email Sent] Recipient: {$targetEmail}");
                }
            } catch (\Exception $e) {
                Log::error('Callback Email Failed: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
            }

            return redirect()->route('order.success', ['order' => $order->id])
                             ->with('message', 'Murakoze! We will call you shortly.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Callback Failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify', message: 'Something went wrong.');
        }
    }

    public function render()
    {
        $city = $this->getCurrentCity();
        $shippingFee = $this->calculateAverageShippingPrice($city);
        $subtotal = (float) Cart::instance('default')->subtotal(2, '.', '');
        $this->total = $subtotal + $shippingFee;

        return view('livewire.checkout', [
            'cartContent'       => Cart::instance('default')->content(),
            'subtotal'          => $subtotal,
            'shippingFee'       => $shippingFee,
            'totalWithShipping' => $this->total,
            'total'             => $this->total,
            'addresses'         => $this->addresses
        ]);
    }
}