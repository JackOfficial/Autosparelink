<div class="py-5 bg-light min-vh-100">
    <div class="container">
        {{-- Header Section --}}
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-end">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-2">
                            <li class="breadcrumb-item small"><a href="/">Home</a></li>
                            <li class="breadcrumb-item small active" aria-current="page">Shopping Cart</li>
                        </ol>
                    </nav>
                    <h2 class="font-weight-bold text-dark">Your Cart</h2>
                    <p class="text-muted mb-0">Review your <span class="text-primary font-weight-bold">{{ $cartContent->count() }} items</span> before checkout</p>
                </div>
                
                @if($cartContent->count() > 0)
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-4" 
                            wire:click="clearCart" 
                            wire:confirm="Empty entire cart?"
                            wire:loading.attr="disabled">
                        <i class="fas fa-trash-alt mr-2"></i>
                        <span wire:loading.remove wire:target="clearCart">Clear Cart</span>
                        <span wire:loading wire:target="clearCart">Clearing...</span>
                    </button>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- Main Cart Items Column --}}
            <div class="col-lg-8">
                @if($cartContent->count() > 0)
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <thead class="bg-white border-bottom small text-uppercase letter-spacing-1 text-muted">
                                    <tr>
                                        <th class="py-4 px-4" style="width: 50%;">Product Details</th>
                                        <th class="py-4 text-center">Quantity</th>
                                        <th class="py-4 text-right px-4">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartContent as $item)
                                    <tr class="border-bottom" wire:key="{{ $item->rowId }}">
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ $item->options->image ? Storage::url($item->options->image) : asset('frontend/img/placeholder.png') }}" 
                                                         class="rounded border bg-white shadow-sm" 
                                                         style="width: 100px; height: 100px; object-fit: cover;"
                                                         alt="{{ $item->name }}">
                                                    @if($item->options->discount)
                                                        <span class="badge badge-danger position-absolute" style="top: -5px; left: -5px;">-{{ $item->options->discount }}%</span>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <h6 class="font-weight-bold mb-1 text-dark">{{ $item->name }}</h6>
                                                    <div class="small text-muted mb-2">
                                                        <span class="mr-3"><i class="fas fa-tag mr-1"></i> {{ $item->options->brand ?? 'Generic' }}</span>
                                                        @if($item->options->sku)
                                                            <span><i class="fas fa-barcode mr-1"></i> {{ $item->options->sku }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <button wire:click="moveToWishlist('{{ $item->rowId }}')" class="btn btn-link btn-sm p-0 text-muted mr-3">
                                                            <i class="far fa-heart mr-1"></i> Save
                                                        </button>
                                                        <button wire:click="removeItem('{{ $item->rowId }}')" class="btn btn-link btn-sm p-0 text-danger">
                                                            <i class="fas fa-times mr-1"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 text-center">
                                            <div class="d-inline-flex flex-column align-items-center">
                                                <div class="input-group input-group-sm quantity-wrapper border rounded-pill overflow-hidden bg-light" style="width: 110px;">
                                                    <div class="input-group-prepend">
                                                        <button class="btn btn-light border-0 px-3" wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty - 1 }})" {{ $item->qty <= 1 ? 'disabled' : '' }}>-</button>
                                                    </div>
                                                    <input type="text" class="form-control border-0 bg-transparent text-center font-weight-bold" value="{{ $item->qty }}" readonly>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-light border-0 px-3" wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty + 1 }})">+</button>
                                                    </div>
                                                </div>
                                                <small class="text-dark mt-2 font-weight-bold">{{ number_format($item->price, 0) }} RWF / unit</small>
                                            </div>
                                        </td>
                                        <td class="py-4 text-right px-4">
                                            <h5 class="font-weight-bold text-primary mb-0">{{ number_format($item->subtotal, 0) }}</h5>
                                            <small class="text-muted">RWF</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-link text-dark font-weight-bold p-0">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Shopping
                        </a>
                    </div>
                @else
                    <div class="card shadow-sm border-0 rounded-lg text-center py-5">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-4 opacity-25"></i>
                            <h4 class="font-weight-bold">Your cart is empty</h4>
                            <p class="text-muted mb-4">You haven't added any products to your cart yet.</p>
                            <a href="/" class="btn btn-primary px-5 rounded-pill">Discover Products</a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Summary Sidebar --}}
            <div class="col-lg-4 mt-lg-0 mt-5">
                <div class="card border-0 shadow-sm rounded-lg sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-4 border-bottom pb-3">Order Summary</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Items Total</span>
                            <span class="font-weight-bold">{{ number_format((float)$subTotal, 0) }} RWF</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success font-weight-bold">Free</span>
                        </div>
                        
                        <div class="my-4 bg-light p-3 rounded">
                            <label class="small font-weight-bold text-muted text-uppercase">Discount Code</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control border-right-0" placeholder="Code">
                                <div class="input-group-append">
                                    <button class="btn btn-dark px-3">Apply</button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="font-weight-bold mb-0">Total</h5>
                            <div class="text-right">
                                <h4 class="font-weight-bold text-primary mb-0">{{ number_format((float)$total, 0) }} RWF</h4>
                                <small class="text-muted">Tax included</small>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" 
                           class="btn btn-primary btn-block btn-lg shadow-sm py-3 rounded-pill font-weight-bold {{ $cartContent->count() == 0 ? 'disabled' : '' }}">
                            CHECKOUT NOW
                        </a>

                        <div class="text-center mt-4">
                            <div class="d-flex justify-content-center align-items-center" style="gap: 15px; opacity: 0.6;">
                                <i class="fab fa-cc-visa fa-2x"></i>
                                <i class="fab fa-cc-mastercard fa-2x"></i>
                                <i class="fas fa-wallet fa-2x" title="Mobile Money"></i>
                            </div>
                            <small class="text-muted mt-2 d-block"><i class="fas fa-lock mr-1"></i> Secure 256-bit SSL Payment</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>