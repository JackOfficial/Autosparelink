<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="font-weight-bold text-dark">Finalize Your Order</h2>
            <p class="text-muted">Please review your items and provide delivery details.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($cartContent->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px;">
            <div class="card-body">
                <div class="mb-4">
                    <i class="fa fa-shopping-cart fa-4x text-light" style="opacity: 0.5;"></i>
                </div>
                <h4 class="text-muted">Your cart is empty!</h4>
                <a href="/spare-parts" class="btn btn-primary rounded-pill px-5 mt-3 shadow-sm">Browse Parts</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-lg-7">
                {{-- Delivery Section --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="font-weight-bold mb-0">
                                <i class="fa fa-truck text-primary mr-2"></i>Delivery Information
                            </h5>
                        </div>

                        {{-- Logged-in User Address Selection --}}
                        @if(Auth::check())
                            @if($addresses->isNotEmpty())
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted">Saved Addresses</label>
                                    <select class="form-control custom-select border-0 bg-light rounded-lg @error('address_id') is-invalid @enderror" wire:model.live="address_id" style="height: 50px;">
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

                            <div class="custom-control custom-checkbox mb-4 p-3 bg-light rounded-lg">
                                <input type="checkbox" class="custom-control-input" wire:model.live="use_new_address" id="useNewAddress">
                                <label class="custom-control-label font-weight-bold" for="useNewAddress">Deliver to a different address</label>
                            </div>
                        @else
                            {{-- GUEST UX --}}
                            <div class="alert alert-secondary border-0 mb-4 d-flex align-items-center justify-content-between" style="border-radius: 12px; background-color: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-user-circle text-primary mr-3 fa-lg"></i>
                                    <small class="text-dark">
                                        @if(Cookie::get('guest_email')) 
                                            Welcome back! Checking out as <strong>Guest</strong>.
                                        @else
                                            Checking out as a <strong>Guest</strong>.
                                        @endif
                                        <a href="{{ route('login') }}" class="text-primary font-weight-bold text-decoration-none ml-1">Login here</a>
                                    </small>
                                </div>
                            </div>

                            {{-- DEFAULT VIEW: Show saved info if cookies exist and user hasn't clicked "Change" --}}
                            @if(Cookie::get('guest_email') && !$use_new_address)
                                <div class="p-4 border rounded-lg mb-4 bg-white shadow-sm border-primary animate__animated animate__fadeIn" style="border-left: 5px solid #007bff !important;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="font-weight-bold text-primary mb-1"><i class="fa fa-check-circle mr-2"></i>Using Saved Details</h6>
                                            <p class="small text-muted mb-0">We've loaded your information from your last visit.</p>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" wire:click="$set('use_new_address', true)">
                                            Change Address
                                        </button>
                                    </div>
                                    <hr class="my-3">
                                    <div class="row">
                                        <div class="col-sm-6 mb-2">
                                            <label class="small text-muted text-uppercase d-block mb-0">Recipient</label>
                                            <span class="font-weight-bold">{{ $new_address['full_name'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label class="small text-muted text-uppercase d-block mb-0">Contact</label>
                                            <span class="font-weight-bold">{{ $new_address['phone'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-12">
                                            <label class="small text-muted text-uppercase d-block mb-0">Delivery Spot</label>
                                            <span class="font-weight-bold">{{ $new_address['street_address'] ?? '' }}, {{ $new_address['city'] ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- FORM VIEW: Show if New Address checkbox is checked OR guest has no cookies --}}
                        @if($use_new_address || (!Auth::check() && !Cookie::get('guest_email')))
                            <div class="row animate__animated animate__fadeIn">
                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase">Email Address</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0 bg-light"><i class="fa fa-envelope text-muted"></i></span>
                                        </div>
                                        <input type="email" class="form-control border-0 bg-light" placeholder="email@example.com" wire:model="guest_email" style="height: 45px;">
                                    </div>
                                    @error('guest_email') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase">Recipient Name</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="Full Name" wire:model="new_address.full_name" style="height: 45px;">
                                    @error('new_address.full_name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase">Phone Number</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="e.g. 078XXXXXXX" wire:model="new_address.phone" style="height: 45px;">
                                    @error('new_address.phone') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase">Street Address</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="Street / Landmark / House No." wire:model="new_address.street_address" style="height: 45px;">
                                    @error('new_address.street_address') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase">City</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="e.g. Kigali" wire:model="new_address.city" style="height: 45px;">
                                    @error('new_address.city') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase">Postal Code</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="Optional" wire:model="new_address.postal_code" style="height: 45px;">
                                </div>
                                
                                @if(!Auth::check() && Cookie::get('guest_email'))
                                    <div class="col-12 text-right">
                                        <button type="button" class="btn btn-link btn-sm text-primary font-weight-bold" wire:click="$set('use_new_address', false)">
                                            <i class="fa fa-arrow-left mr-1"></i> Use my saved information instead
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Section --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-4"><i class="fa fa-credit-card text-primary mr-2"></i>Payment Method</h5>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="p-3 border rounded-lg bg-light d-flex align-items-center shadow-sm" style="border-left: 5px solid #ffcc00 !important;">
                                    <img src="{{ asset('images/momo.jpg')}}" style="width: 45px; height: 45px; object-fit: contain;" class="rounded mr-3" alt="MoMo">
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">Mobile Money & Cards</h6>
                                        <p class="small text-muted mb-0">Secure payment via Flutterwave.</p>
                                    </div>
                                    <i class="fa fa-check-circle text-warning ml-auto fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <button wire:click="placeOrder" wire:loading.attr="disabled" class="btn btn-primary btn-lg btn-block rounded-pill shadow font-weight-bold py-3">
                                    <span wire:loading.remove wire:target="placeOrder">
                                        <i class="fa fa-lock mr-2"></i>Pay {{ $total }} RWF
                                    </span>
                                    <span wire:loading wire:target="placeOrder">
                                        <i class="fa fa-spinner fa-spin mr-2"></i>Processing...
                                    </span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-dark btn-lg btn-block rounded-pill font-weight-bold py-3" 
                                        wire:click="requestCallback" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="requestCallback">
                                        <i class="fa fa-phone-alt mr-2"></i>Request Callback
                                    </span>
                                    <span wire:loading wire:target="requestCallback">
                                        <i class="fa fa-spinner fa-spin mr-2"></i>Please wait...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header bg-dark text-white p-3 border-0">
                            <h6 class="mb-0 font-weight-bold text-center">Order Summary</h6>
                        </div>
                        <div class="card-body p-0">
                            <div style="max-height: 400px; overflow-y: auto;">
                                <ul class="list-group list-group-flush">
                                    @foreach($cartContent as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-white">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    @if($item->options->image)
                                                        <img src="{{ Storage::url($item->options->image) }}" class="rounded shadow-sm" style="width: 55px; height: 55px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded p-2 text-center" style="width: 55px; height: 55px;">
                                                            <i class="fa fa-tools text-muted mt-2"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 small font-weight-bold text-dark text-truncate" style="max-width: 180px;">{{ $item->name }}</h6>
                                                    <span class="badge badge-light border text-muted">Qty: {{ $item->qty }}</span>
                                                </div>
                                            </div>
                                            <span class="font-weight-bold text-dark small">{{ number_format($item->price * $item->qty, 0) }} RWF</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Subtotal</span>
                                <span class="text-dark font-weight-bold">{{ $total }} RWF</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted small">Delivery</span>
                                <span class="text-success font-weight-bold small">Calculated at call</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold text-dark mb-0">Total</h5>
                                <h4 class="font-weight-bold text-primary mb-0">{{ $total }} RWF</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>