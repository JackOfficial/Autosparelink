<div class="container py-5">
    <h2>Checkout</h2>

    @if($cartItems->isEmpty())
        <p>Your cart is empty!</p>
    @else
        <div class="row">
            <!-- Shipping / Address Section -->
            <div class="col-md-6">
                <h4>Shipping Information</h4>

                @if(Auth::check())
                    {{-- Existing addresses dropdown --}}
                    @if($addresses->isNotEmpty())
                        <div class="mb-3">
                            <label>Select Address</label>
                            <select class="form-control" wire:model="address_id">
                                <option value="">-- Select --</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->full_name }}, {{ $address->street_address }}, {{ $address->city }}, {{ $address->country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Use new address checkbox --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" wire:model="use_new_address" id="useNewAddress">
                        <label class="form-check-label" for="useNewAddress">Use new address</label>
                    </div>

                    {{-- New address form: always show if checkbox checked or no addresses --}}
                    @if($use_new_address || $addresses->isEmpty())
                        <div class="mb-3">
                            <input type="text" class="form-control mb-2" placeholder="Full Name" wire:model="new_address.full_name">
                            <input type="text" class="form-control mb-2" placeholder="Phone" wire:model="new_address.phone">
                            <input type="text" class="form-control mb-2" placeholder="Street Address" wire:model="new_address.street_address">
                            <input type="text" class="form-control mb-2" placeholder="City" wire:model="new_address.city">
                            <input type="text" class="form-control mb-2" placeholder="State" wire:model="new_address.state">
                            <input type="text" class="form-control mb-2" placeholder="Postal Code" wire:model="new_address.postal_code">
                            <input type="text" class="form-control mb-2" placeholder="Country" wire:model="new_address.country">
                        </div>
                    @endif
                @else
                    <p>Please <a href="{{ route('login') }}">log in</a> to checkout.</p>
                @endif

                {{-- Place order button --}}
                @if(Auth::check())
                    <button class="btn btn-primary" wire:click="placeOrder">Place Order</button>
                @endif
            </div>

            <!-- Cart Section -->
            <div class="col-md-6">
                <h4>Your Cart</h4>
                <ul class="list-group mb-3">
                    @foreach($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                {{ $item->name }} x {{ $item->qty }}
                            </div>
                            <span>{{ number_format($item->price * $item->qty, 2) }} RWF</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        Total
                        <span>{{ number_format((float)$total, 2) }} RWF</span>
                    </li>
                </ul>
            </div>
        </div>
    @endif
</div>