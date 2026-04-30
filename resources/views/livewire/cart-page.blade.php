<div class="py-4">
    <div class="container">
        <div class="row">
            {{-- Main Cart Items Column --}}
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="font-weight-bold mb-1">Shopping Cart</h2>
                        <p class="text-muted mb-0">You have <span class="text-primary font-weight-bold">{{ $cartContent->count() }} items</span> in your cart</p>
                    </div>
                    
                    @if($cartContent->count() > 0)
                        <button class="btn btn-sm btn-light text-danger rounded-pill px-3 shadow-sm" 
                                wire:click="clearCart" 
                                wire:confirm="Are you sure you want to empty your entire cart?"
                                wire:loading.attr="disabled">
                            <i class="fas fa-trash-alt mr-1"></i> 
                            <span wire:loading.remove wire:target="clearCart">Empty Cart</span>
                            <span wire:loading wire:target="clearCart">Processing...</span>
                        </button>
                    @endif
                </div>

                @if($cartContent->count() > 0)
                    <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="bg-light text-muted small font-weight-bold">
                                    <tr>
                                        <th class="py-3 px-4">Item Details</th>
                                        <th class="py-3">Price</th>
                                        <th class="py-3 text-center">Quantity</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3 text-right px-4"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartContent as $item)
                                    <tr class="border-bottom transition-all hover-bg-light" wire:key="{{ $item->rowId }}">
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-start">
                                                <div class="position-relative mr-3">
                                                    <img src="{{ $item->options->image ? Storage::url($item->options->image) : asset('frontend/img/placeholder.png') }}" 
                                                         class="rounded shadow-sm border bg-white" 
                                                         style="width: 90px; height: 90px; object-fit: cover;"
                                                         alt="{{ $item->name }}">
                                                    @if($item->options->discount)
                                                        <span class="badge badge-pill badge-danger position-absolute" style="top: -8px; left: -8px; font-size: 10px;">
                                                            -{{ $item->options->discount }}%
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 font-weight-bold text-dark">{{ $item->name }}</h6>
                                                    <div class="d-flex flex-column" style="gap: 4px;">
                                                        <span class="small text-muted">
                                                            <i class="fas fa-tag mr-1 opacity-5"></i> 
                                                            {{ $item->options->brand ?? 'Generic' }}
                                                        </span>
                                                        @if($item->options->sku)
                                                            <span class="small text-muted">
                                                                <i class="fas fa-barcode mr-1 opacity-5"></i> 
                                                                {{ $item->options->sku }}
                                                            </span>
                                                        @endif
                                                        @if(isset($item->options->state))
                                                            <span class="badge badge-soft-{{ $item->options->state == 'New' ? 'success' : 'warning' }} align-self-start px-2 py-1 mt-1" style="font-size: 9px; letter-spacing: 0.5px;">
                                                                {{ strtoupper($item->options->state) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 vertical-align-middle">
                                            <span class="text-dark font-weight-bold">{{ number_format($item->price, 0) }}</span>
                                            <small class="text-muted d-block" style="font-size: 10px;">RWF</small>
                                        </td>
                                        <td class="py-4 vertical-align-middle">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="input-group input-group-sm quantity-selector shadow-sm" style="width: 100px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-white border border-right-0 rounded-left-pill px-3" 
                                                                wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty - 1 }})"
                                                                wire:loading.attr="disabled"
                                                                {{ $item->qty <= 1 ? 'disabled' : '' }}>-</button>
                                                    </div>
                                                    <input type="text" class="form-control text-center border-top border-bottom bg-white font-weight-bold" 
                                                           value="{{ $item->qty }}" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-white border border-left-0 rounded-right-pill px-3" 
                                                                wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty + 1 }})"
                                                                wire:loading.attr="disabled">+</button>
                                                    </div>
                                                </div>
                                                <small class="text-success mt-2 font-weight-bold" style="font-size: 9px;">
                                                    <i class="fas fa-check-circle mr-1"></i> Ready for dispatch
                                                </small>
                                            </div>
                                        </td>
                                        <td class="py-4 vertical-align-middle">
                                            <div class="text-dark font-weight-bold" style="font-size: 1rem;">
                                                {{ number_format($item->subtotal, 0) }} <span class="small text-muted">RWF</span>
                                            </div>
                                        </td>
                                        <td class="py-4 text-right px-4 vertical-align-middle">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <button class="btn btn-icon btn-outline-primary rounded-circle mr-2 hover-shadow" 
                                                        style="width: 38px; height: 38px; transition: 0.3s;"
                                                        title="Save for Later"
                                                        wire:click="moveToWishlist('{{ $item->rowId }}')"
                                                        wire:loading.attr="disabled">
                                                    <i class="far fa-heart" wire:loading.remove wire:target="moveToWishlist('{{ $item->rowId }}')"></i>
                                                    <span class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="moveToWishlist('{{ $item->rowId }}')"></span>
                                                </button>

                                                <button class="btn btn-icon btn-outline-danger rounded-circle hover-shadow" 
                                                        style="width: 38px; height: 38px; transition: 0.3s;"
                                                        title="Remove Item"
                                                        wire:click="removeItem('{{ $item->rowId }}')" 
                                                        wire:loading.attr="disabled">
                                                    <i class="fas fa-trash-alt" wire:loading.remove wire:target="removeItem('{{ $item->rowId }}')"></i>
                                                    <span class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="removeItem('{{ $item->rowId }}')"></span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-light p-3 border-top">
                            <small class="text-muted"><i class="fas fa-shield-alt mr-2"></i> All orders are processed using secure encrypted transactions.</small>
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-between align-items-center">
                        <a href="{{ url('/') }}" class="btn btn-link text-decoration-none p-0 text-primary font-weight-bold">
                            <i class="fas fa-chevron-left mr-2 small"></i> Keep Shopping
                        </a>
                        <small class="text-muted">Subtotal is inclusive of all taxes</small>
                    </div>

                @else
                    {{-- Enhanced Empty State --}}
                    <div class="text-center py-5 card shadow-sm border-0 rounded-xl">
                        <div class="card-body py-5">
                            <div class="mb-4">
                                <div class="bg-light d-inline-flex align-items-center justify-content-center rounded-circle shadow-inner" style="width: 120px; height: 120px;">
                                    <i class="fas fa-shopping-basket fa-4x text-muted opacity-3"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bold">Your cart is feeling lonely</h4>
                            <p class="text-muted mx-auto mb-4" style="max-width: 400px;">
                                It looks like you haven't added any products yet. Explore our store and find high-quality parts for your vehicle!
                            </p>
                            <a href="{{ url('/products') }}" class="btn btn-primary btn-lg px-5 shadow-sm rounded-pill font-weight-bold">
                                Start Shopping <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Summary Sidebar --}}
            <div class="col-lg-4 mt-lg-0 mt-5">
                <div class="card border-0 shadow-lg rounded-xl sticky-top sticky-offset" style="top: 100px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <h4 class="font-weight-bold mb-0">Summary</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Bag Subtotal</span>
                                <span class="font-weight-bold">{{ number_format((float)$subTotal, 0) }} RWF</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Processing Fee</span>
                                <span class="text-success small font-weight-bold">FREE</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Logistics & Shipping</span>
                                <span class="small text-muted italic">Calculated next</span>
                            </div>
                        </div>

                        <div class="coupon-box mb-4">
                            <label class="small font-weight-bold text-uppercase text-muted">Promo Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 rounded-left" placeholder="Enter code">
                                <div class="input-group-append">
                                    <button class="btn btn-dark px-3 rounded-right">Apply</button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-dashed">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="font-weight-bold mb-0">Total Amount</h5>
                            <div class="text-right">
                                <h4 class="font-weight-bold text-primary mb-0">{{ number_format((float)$total, 0) }} RWF</h4>
                                <small class="text-muted">VAT Included</small>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" 
                           class="btn btn-primary btn-lg btn-block shadow-lg py-3 font-weight-bold rounded-pill mb-3 transform-hover {{ $cartContent->count() == 0 ? 'disabled' : '' }}">
                            CHECKOUT NOW <i class="fas fa-credit-card ml-2"></i>
                        </a>

                        <div class="text-center p-3 bg-light rounded-lg">
                            <p class="small text-muted mb-2 font-weight-bold">We Accept</p>
                            <div class="d-flex justify-content-center align-items-center" style="gap: 20px; filter: grayscale(1);">
                                 <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="15" alt="Visa">
                                 <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20" alt="Mastercard">
                                 <i class="fas fa-mobile-alt fa-lg text-dark" title="Mobile Money"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="card border-0 shadow-sm rounded-xl mt-4 bg-primary text-white overflow-hidden position-relative">
                    <div class="card-body p-4 position-relative" style="z-index: 1;">
                        <h6 class="font-weight-bold mb-2">Need Assistance?</h6>
                        <p class="small opacity-8 mb-0">Our support team is available 24/7 to help with your vehicle parts inquiry.</p>
                        <a href="#" class="btn btn-sm btn-white text-primary rounded-pill px-3 mt-3 font-weight-bold">Contact Support</a>
                    </div>
                    <i class="fas fa-headset position-absolute" style="bottom: -10px; right: -10px; font-size: 5rem; opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </div>
    <style>
    .rounded-xl { border-radius: 1rem !important; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0,0,0,0.06) !important; }
    .vertical-align-middle { vertical-align: middle !important; }
    .transition-all { transition: all 0.3s ease; }
    .hover-bg-light:hover { background-color: #fbfbfb; }
    .transform-hover:hover { transform: translateY(-2px); }
    .border-dashed { border-style: dashed !important; }
    .btn-white { background-color: #fff; color: #000; }
    .badge-soft-success { background-color: #e6fcf5; color: #0ca678; }
    .badge-soft-warning { background-color: #fff9db; color: #f08c00; }
    .hover-shadow:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
</style>
</div>

