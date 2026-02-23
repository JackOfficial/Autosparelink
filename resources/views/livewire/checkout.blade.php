<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="font-weight-bold text-dark">Finalize Your Order</h2>
            <p class="text-muted">Please review your items and provide delivery details.</p>
        </div>
    </div>

    @if(empty($cartItems))
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
            <div class="card-body">
                <i class="fa fa-shopping-cart fa-4x text-light mb-3"></i>
                <h4 class="text-muted">Your cart is empty!</h4>
                <a href="{{ route('parts.index') }}" class="btn btn-primary rounded-pill px-4 mt-3">Browse Parts</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-7">
                {{-- Shipping Section --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-4"><i class="fa fa-truck text-primary mr-2"></i>Delivery Information</h5>

                        @if(Auth::check())
                            @if(!empty($addresses) && count($addresses) > 0)
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted">Saved Addresses</label>
                                    <select class="form-control custom-select border-light bg-light rounded-lg" wire:model.live="address_id">
                                        <option value="">-- Choose a delivery location --</option>
                                        @foreach($addresses as $address)
                                            <option value="{{ $address->id }}">
                                                {{ $address->full_name }} - {{ $address->street_address }}, {{ $address->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="custom-control custom-checkbox mb-4">
                                <input type="checkbox" class="custom-control-input" wire:model.live="use_new_address" id="useNewAddress">
                                <label class="custom-control-label font-weight-bold" for="useNewAddress">Deliver to a different address</label>
                            </div>

                            @if($use_new_address || empty($addresses))
                                <div class="row animate__animated animate__fadeIn">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Recipient Name" wire:model="new_address.full_name">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Phone Number" wire:model="new_address.phone">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Street Address / House No." wire:model="new_address.street_address">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="City" wire:model="new_address.city">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Postal Code (Optional)" wire:model="new_address.postal_code">
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info border-0 shadow-sm" style="border-radius: 10px;">
                                <i class="fa fa-info-circle mr-2"></i> Please <a href="{{ route('login') }}" class="font-weight-bold text-primary">Log in</a> or <a href="{{ route('register') }}" class="font-weight-bold text-primary">Register</a> to continue with your order.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Section (MoMo Focus) --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; border-left: 5px solid #ffcc00 !important;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3"><i class="fa fa-wallet text-warning mr-2"></i>Payment Method</h5>
                        <div class="d-flex align-items-center p-3 bg-light rounded-lg border">
                            <img src="{{ asset('images/momo.jpg')}}" style="width: 40px;" class="mr-3">
                            <div>
                                <h6 class="mb-0 font-weight-bold">MTN MoMo Pay</h6>
                                <p class="small text-muted mb-0">Pay manually using the code below after placing order.</p>
                            </div>
                        </div>
                        
                        <div class="mt-3 p-3 bg-white border border-warning rounded text-center">
                            <span class="text-muted small d-block">Merchant Code</span>
                            <h3 class="font-weight-bold text-dark mb-0 tracking-widest">000 000</h3> {{-- Replace with your real code --}}
                            <small class="text-muted">Account Name: <strong>AutoSpareLink</strong></small>
                        </div>
                    </div>
                </div>

                {{-- Call to Action Buttons --}}
                <div class="d-flex flex-column flex-md-row gap-3">
                    @if(Auth::check())
                        <button class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm font-weight-bold mb-2 mb-md-0" wire:click="placeOrder">
                            <i class="fa fa-check-circle mr-2"></i>Confirm & Place Order
                        </button>
                    @endif
                    
                    <button class="btn btn-outline-dark btn-lg rounded-pill px-4 shadow-sm" wire:click="requestCallback">
                        <i class="fa fa-phone-alt mr-2"></i>Request a Callback
                    </button>
                </div>
                <p class="small text-muted mt-3"><i class="fa fa-shield-alt mr-1"></i> Your data is secure. A representative will contact you to confirm delivery.</p>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header bg-dark text-white p-3 border-0">
                            <h6 class="mb-0 font-weight-bold">Order Summary</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($cartItems as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 mr-3" style="width: 50px; text-align: center;">
                                                <i class="fa fa-tools text-muted"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 small font-weight-bold text-dark text-truncate" style="max-width: 150px;">{{ $item['name'] }}</h6>
                                                <small class="text-muted">Qty: {{ $item['qty'] }}</small>
                                            </div>
                                        </div>
                                        <span class="font-weight-bold text-dark small">{{ number_format((float)$item['price'] * $item['qty'], 0) }} RWF</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-light border-0 p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="text-dark">{{ number_format((float)$total, 0) }} RWF</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Delivery</span>
                                <span class="text-success font-weight-bold small">Calculated at call</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <h5 class="font-weight-bold text-dark">Total</h5>
                                <h5 class="font-weight-bold text-primary">{{ $total }} RWF</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>