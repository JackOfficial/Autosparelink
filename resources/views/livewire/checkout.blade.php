<div class="container py-5">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold text-dark mb-1">Finalize Your Order</h2>
            <p class="text-muted mb-0">Please review your items and provide delivery details to proceed.</p>
        </div>
    </div>

    {{-- Error Notifications --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-3 fa-lg"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($cartContent->isEmpty())
        {{-- Empty Cart State --}}
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 16px;">
            <div class="card-body py-5">
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 100px; height: 100px;">
                        <i class="fa fa-shopping-cart fa-3x text-muted" style="opacity: 0.4;"></i>
                    </span>
                </div>
                <h4 class="text-dark font-weight-bold mb-2">Your cart is empty!</h4>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="/spare-parts" class="btn btn-primary rounded-pill px-5 shadow-sm font-weight-bold">
                    Browse Parts
                </a>
            </div>
        </div>
    @else
        <div class="row">
            {{-- Main Form Content --}}
            <div class="col-lg-7 mb-4 mb-lg-0">
                
                {{-- Delivery Information Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="btn btn-sm btn-light rounded-circle mr-3 pointer-event-none" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-truck text-primary"></i>
                            </span>
                            <h5 class="font-weight-bold mb-0 text-dark">Delivery Information</h5>
                        </div>

                        @if(Auth::check())
                            {{-- Authenticated User View --}}
                            @if($addresses->isNotEmpty())
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-uppercase text-muted mb-2" style="letter-spacing: 0.5px;">Saved Addresses</label>
                                    <select class="form-control custom-select border-0 bg-light rounded-lg @error('address_id') is-invalid @enderror" wire:model.live="address_id" style="height: 52px; border-radius: 10px;">
                                        <option value="">-- Choose a delivery location --</option>
                                        @foreach($addresses as $address)
                                            <option value="{{ $address->id }}">
                                                {{ $address->full_name }} — {{ $address->street_address }}, {{ $address->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('address_id') 
                                        <span class="text-danger small mt-1 d-block"><i class="fa fa-info-circle mr-1"></i>{{ $message }}</span> 
                                    @enderror
                                </div>
                            @endif

                            <div class="custom-control custom-checkbox mb-4 p-3 bg-light rounded-lg d-flex align-items-center" style="border-radius: 12px; min-height: 54px;">
                                <input type="checkbox" class="custom-control-input" wire:model.live="use_new_address" id="useNewAddress">
                                <label class="custom-control-label font-weight-bold text-dark ml-2 mt-1" for="useNewAddress" style="cursor: pointer;">
                                    Deliver to a different address
                                </label>
                            </div>
                        @else
                            {{-- Guest Status Indicator --}}
                            <div class="alert alert-secondary border-0 mb-4 px-3 py-3" style="border-radius: 12px; background-color: #f8f9fa;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="fa fa-user-circle text-primary mr-3 fa-lg"></i>
                                        <span class="text-dark small">
                                            @if(Cookie::get('guest_email')) 
                                                Welcome back! Checking out as <strong>Guest</strong>.
                                            @else
                                                Checking out as a <strong>Guest</strong>.
                                            @endif
                                            <a href="{{ route('login') }}" class="text-primary font-weight-bold ml-1">Login here</a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if(Cookie::get('guest_email') && !$use_new_address)
                                {{-- Guest Saved Details Overview --}}
                                <div class="p-4 border rounded-lg mb-4 bg-white shadow-sm animate__animated animate__fadeIn" style="border-left: 5px solid #007bff !important; border-radius: 12px;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h6 class="font-weight-bold text-primary mb-1">
                                                <i class="fa fa-check-circle mr-2"></i>Using Saved Details
                                            </h6>
                                            <p class="small text-muted mb-0">Retrieved from your last purchase.</p>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm font-weight-bold" wire:click="$set('use_new_address', true)">
                                            Change Address
                                        </button>
                                    </div>
                                    <hr class="my-3" style="opacity: 0.6;">
                                    <div class="row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <label class="small text-muted text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">Recipient</label>
                                            <span class="font-weight-bold text-dark d-block">{{ $new_address['full_name'] ?? 'N/A' }}</span>
                                            <span class="small text-muted d-block mt-1">{{ $new_address['phone'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="small text-muted text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">Delivery Spot</label>
                                            <span class="font-weight-bold text-dark d-block">
                                                {{ $new_address['street_address'] ?? '' }}
                                            </span>
                                            <span class="small text-muted d-block mt-1">
                                                {{ $new_address['city'] ?? '' }} {{ $new_address['postal_code'] ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        {{-- New/Guest Address Fields --}}
                        @if($use_new_address || (!Auth::check() && !Cookie::get('guest_email')))
                            <div class="row animate__animated animate__fadeIn">
                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Email Address</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text border-0 bg-light px-3" style="border-radius: 10px 0 0 10px;">
                                                <i class="fa fa-envelope text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="email" class="form-control border-0 bg-light @error('guest_email') is-invalid @enderror" placeholder="email@example.com" wire:model="guest_email" style="height: 50px; border-radius: 0 10px 10px 0;">
                                    </div>
                                    @error('guest_email') 
                                        <span class="text-danger small mt-1 d-block"><i class="fa fa-info-circle mr-1"></i>{{ $message }}</span> 
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Recipient Name</label>
                                    <input type="text" class="form-control border-0 bg-light @error('new_address.full_name') is-invalid @enderror" placeholder="Full Name" wire:model="new_address.full_name" style="height: 50px; border-radius: 10px;">
                                    @error('new_address.full_name') 
                                        <span class="text-danger small mt-1 d-block"><i class="fa fa-info-circle mr-1"></i>{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Phone Number</label>
                                    <input type="text" class="form-control border-0 bg-light @error('new_address.phone') is-invalid @enderror" placeholder="e.g. 078XXXXXXX" wire:model="new_address.phone" style="height: 50px; border-radius: 10px;">
                                    @error('new_address.phone') 
                                        <span class="text-danger small mt-1 d-block"><i class="fa fa-info-circle mr-1"></i>{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Street Address</label>
                                    <input type="text" class="form-control border-0 bg-light @error('new_address.street_address') is-invalid @enderror" placeholder="Street / Landmark / House No." wire:model="new_address.street_address" style="height: 50px; border-radius: 10px;">
                                    @error('new_address.street_address') 
                                        <span class="text-danger small mt-1 d-block"><i class="fa fa-info-circle mr-1"></i>{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">City</label>
                                    <input type="text" class="form-control border-0 bg-light @error('new_address.city') is-invalid @enderror" placeholder="e.g. Kigali" wire:model.live="new_address.city" style="height: 50px; border-radius: 10px;">
                                    @error('new_address.city') 
                                        <span class="text-danger small mt-1 d-block"><i class="fa fa-info-circle mr-1"></i>{{ $message }}</span> 
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted text-uppercase mb-2" style="letter-spacing: 0.5px;">Postal Code</label>
                                    <input type="text" class="form-control border-0 bg-light" placeholder="Optional" wire:model="new_address.postal_code" style="height: 50px; border-radius: 10px;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Method Selection Card --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center mb-4">
                            <span class="btn btn-sm btn-light rounded-circle mr-3 pointer-event-none" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-credit-card text-primary"></i>
                            </span>
                            <h5 class="font-weight-bold mb-0 text-dark">Payment Method</h5>
                        </div>
                        
                        <div class="row mb-4">
                            {{-- Option 1: Mobile Money --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="p-3 border rounded-lg h-100 d-flex flex-column justify-content-between transition-all shadow-sm {{ $payment_method === 'momo' ? 'border-primary bg-light' : 'bg-white' }}" 
                                     wire:click="$set('payment_method', 'momo')"
                                     style="border-left: 5px solid #007bff !important; cursor: pointer; border-radius: 12px; min-height: 85px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/momo.jpg') }}" style="width: 42px; height: 42px; object-fit: contain;" class="rounded mr-3 bg-white p-1" alt="MoMo">
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.85rem;">Pay in Full</h6>
                                                <p class="text-muted mb-0 small">via Mobile Money</p>
                                            </div>
                                        </div>
                                        <i class="fa fa-check-circle fa-lg {{ $payment_method === 'momo' ? 'text-primary' : 'text-light' }}" style="opacity: {{ $payment_method === 'momo' ? '1' : '0.4' }};"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- Option 2: Pay on Delivery --}}
                            <div class="col-md-6">
                                <div class="p-3 border rounded-lg h-100 d-flex flex-column justify-content-between transition-all shadow-sm {{ $payment_method === 'cod' ? 'border-success bg-light' : 'bg-white' }}"
                                     wire:click="$set('payment_method', 'cod')"
                                     style="border-left: 5px solid #28a745 !important; cursor: pointer; border-radius: 12px; min-height: 85px;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="bg-success rounded d-flex align-items-center justify-content-center text-white mr-3" style="width: 42px; height: 42px;">
                                                <i class="fa fa-hand-holding-usd"></i>
                                            </span>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.85rem;">Pay on Delivery</h6>
                                                <p class="text-muted mb-0 small">Commitment Fee Required</p>
                                            </div>
                                        </div>
                                        <i class="fa fa-check-circle fa-lg {{ $payment_method === 'cod' ? 'text-success' : 'text-light' }}" style="opacity: {{ $payment_method === 'cod' ? '1' : '0.4' }};"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Delivery Fee Notice for COD --}}
                        @if($payment_method === 'cod')
                            <div class="alert alert-warning border-0 mb-4 px-3 py-3 animate__animated animate__fadeIn" style="border-radius: 12px; background-color: #fff8e1; border-left: 4px solid #ffc107 !important;">
                                <div class="d-flex">
                                    <i class="fa fa-info-circle mr-3 mt-1 text-warning fa-lg"></i>
                                    <div>
                                        <h6 class="font-weight-bold mb-1 text-dark" style="font-size: 0.9rem;">Commitment Fee Required</h6>
                                        <p class="small mb-0 text-secondary" style="line-height: 1.5;">
                                            To confirm Pay on Delivery, please pay a commitment fee: <br>
                                            <strong>3,000 RWF</strong> (Within Kigali) / <strong>5,000 RWF</strong> (Upcountry & Provinces).
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <p class="small text-muted mb-0">
                                By placing this order, you agree to our 
                                <a href="/terms" target="_blank" class="text-primary font-weight-bold">Terms of Use</a>.
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <button wire:click="placeOrder" wire:loading.attr="disabled" class="btn btn-primary btn-lg btn-block rounded-pill shadow font-weight-bold py-3" style="font-size: 0.95rem;">
                                    <span wire:loading.remove wire:target="placeOrder">
                                        <i class="fa fa-lock mr-2"></i>
                                        @if($payment_method === 'cod')
                                            Pay Fee: {{ number_format(strtolower($new_address['city'] ?? '') == 'kigali' ? 3000 : 5000) }} RWF
                                        @else
                                            Pay {{ $total }} RWF
                                        @endif
                                    </span>
                                    <span wire:loading wire:target="placeOrder">
                                        <i class="fa fa-circle-notch fa-spin mr-2"></i>Processing...
                                    </span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-outline-dark btn-lg btn-block rounded-pill font-weight-bold py-3" 
                                        wire:click="requestCallback" wire:loading.attr="disabled" style="font-size: 0.95rem; border-width: 1.5px;">
                                    <span wire:loading.remove wire:target="requestCallback">
                                        <i class="fa fa-phone-alt mr-2"></i>Request Callback
                                    </span>
                                    <span wire:loading wire:target="requestCallback">
                                        <i class="fa fa-circle-notch fa-spin mr-2"></i>Please wait...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 24px;">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-dark text-white p-3 border-0">
                            <h6 class="mb-0 font-weight-bold text-center py-1" style="letter-spacing: 0.5px; font-size: 0.9rem;">ORDER SUMMARY</h6>
                        </div>
                        <div class="card-body p-0">
                            <div style="max-height: 380px; overflow-y: auto;">
                                <ul class="list-group list-group-flush">
                                    @foreach($cartContent as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 bg-white border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    @if($item->options->image)
                                                        <img src="{{ Storage::url($item->options->image) }}" class="rounded shadow-sm border" style="width: 58px; height: 58px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                                            <i class="fa fa-tools text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 small font-weight-bold text-dark text-truncate" style="max-width: 180px;">{{ $item->name }}</h6>
                                                    <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.75rem;">Qty: {{ $item->qty }}</span>
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
                                <span class="text-muted small">Items Subtotal</span>
                                <span class="text-dark font-weight-bold">{{ $total }} RWF</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted small">Shipping & Delivery</span>
                                <span class="text-success font-weight-bold small">Calculated at dispatch</span>
                            </div>
                            <hr class="my-3" style="border-style: dashed; opacity: 0.5;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.1rem;">Total Due</h5>
                                <h4 class="font-weight-bold text-primary mb-0" style="font-size: 1.35rem;">{{ $total }} RWF</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>