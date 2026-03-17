<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="font-weight-bold text-dark">Finalize Your Order</h2>
            <p class="text-muted">Please review your items and provide delivery details.</p>
        </div>
    </div>

    {{-- Check if collection is empty --}}
    @if($cartContent->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
            <div class="card-body">
                <i class="fa fa-shopping-cart fa-4x text-light mb-3"></i>
                <h4 class="text-muted">Your cart is empty!</h4>
                <a href="/spare-parts" class="btn btn-primary rounded-pill px-4 mt-3">Browse Parts</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-7">
                {{-- Delivery Section --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-4"><i class="fa fa-truck text-primary mr-2"></i>Delivery Information</h5>

                        @if(Auth::check())
                            @if($addresses->isNotEmpty())
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted">Saved Addresses</label>
                                    <select class="form-control custom-select border-light bg-light rounded-lg @error('address_id') is-invalid @enderror" wire:model.live="address_id">
                                        <option value="">-- Choose a delivery location --</option>
                                        @foreach($addresses as $address)
                                            <option value="{{ $address->id }}">
                                                {{ $address->full_name }} - {{ $address->street_address }}, {{ $address->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('address_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="custom-control custom-checkbox mb-4">
                                <input type="checkbox" class="custom-control-input" wire:model.live="use_new_address" id="useNewAddress">
                                <label class="custom-control-label font-weight-bold" for="useNewAddress">Deliver to a different address</label>
                            </div>

                            @if($use_new_address || $addresses->isEmpty())
                                <div class="row animate__animated animate__fadeIn">
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Recipient Name" wire:model="new_address.full_name">
                                        @error('new_address.full_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Phone Number" wire:model="new_address.phone">
                                        @error('new_address.phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Street Address / House No." wire:model="new_address.street_address">
                                        @error('new_address.street_address') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="City" wire:model="new_address.city">
                                        @error('new_address.city') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <input type="text" class="form-control border-light bg-light" placeholder="Postal Code (Optional)" wire:model="new_address.postal_code">
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info border-0 shadow-sm" style="border-radius: 10px;">
                                <i class="fa fa-info-circle mr-2"></i> Please <a href="{{ route('login') }}" class="font-weight-bold text-primary">Log in</a> to continue.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; border-left: 5px solid #ffcc00 !important;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3"><i class="fa fa-credit-card text-warning mr-2"></i>Payment Method</h5>
                        <div class="p-3 bg-light rounded-lg border mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('images/momo.jpg')}}" style="width: 35px;" class="mr-2" alt="MoMo">
                                <h6 class="mb-0 font-weight-bold">Mobile Money & Cards</h6>
                            </div>
                            <p class="small text-muted mb-0">Securely pay via Flutterwave.</p>
                        </div>

                        <button wire:click="placeOrder" wire:loading.attr="disabled" class="btn btn-primary btn-lg btn-block rounded-pill shadow-sm font-weight-bold py-3">
                            <span wire:loading.remove>
                                <i class="fa fa-lock mr-2"></i>Pay {{ $total }} RWF Now
                            </span>
                            <span wire:loading>
                                <i class="fa fa-spinner fa-spin mr-2"></i>Processing Order...
                            </span>
                        </button>
                    </div>
                </div>

               <button class="btn btn-outline-dark btn-lg rounded-pill px-4 shadow-sm" 
        wire:click="requestCallback" 
        wire:loading.attr="disabled">
    
    {{-- Text shown normally --}}
    <span wire:loading.remove wire:target="requestCallback">
        <i class="fa fa-phone-alt mr-2"></i>Request a Callback
    </span>

    {{-- Text/Spinner shown while processing --}}
    <span wire:loading wire:target="requestCallback">
        <i class="fa fa-spinner fa-spin mr-2"></i>Processing...
    </span>
</button>
            </div>

            {{-- Sidebar Summary --}}
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header bg-dark text-white p-3 border-0">
                            <h6 class="mb-0 font-weight-bold">Order Summary</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                {{-- Loop through objects using $item->property --}}
                                @foreach($cartContent as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                @if($item->options->image)
                                                    <img src="{{ Storage::url($item->options->image) }}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded p-2 text-center" style="width: 50px; height: 50px;">
                                                        <i class="fa fa-tools text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 small font-weight-bold text-dark text-truncate" style="max-width: 150px;">{{ $item->name }}</h6>
                                                <small class="text-muted">Qty: {{ $item->qty }}</small>
                                            </div>
                                        </div>
                                        <span class="font-weight-bold text-dark small">{{ number_format($item->price * $item->qty, 0) }} RWF</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-light border-0 p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="text-dark">{{ $total }} RWF</span>
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