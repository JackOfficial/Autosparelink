<div class="py-3">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-weight-bold">Shopping Cart</h2>
                <div class="d-flex align-items-center">
                    <span class="text-muted mr-3">{{ $cartContent->count() }} Items</span>
                    
                    @if($cartContent->count() > 0)
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                wire:click="clearCart" 
                                wire:confirm="Are you sure you want to empty your entire cart?"
                                wire:loading.attr="disabled">
                            <i class="fas fa-trash-sweep mr-1"></i> 
                            <span wire:loading.remove wire:target="clearCart">Clear Cart</span>
                            <span wire:loading wire:target="clearCart">Clearing...</span>
                        </button>
                    @endif
                </div>
            </div>

        @if($cartContent->count() > 0)
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <thead class="bg-light text-uppercase small font-weight-bold">
                    <tr>
                        <th class="py-3 px-4" style="width: 45%;">Product Details</th>
                        <th class="py-3">Price</th>
                        <th class="py-3 text-center">Quantity</th>
                        <th class="py-3">Total</th>
                        <th class="py-3 text-right px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartContent as $item)
                    <tr class="border-bottom" wire:key="{{ $item->rowId }}">
                        <td class="py-4 px-4">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <img src="{{ $item->options->image ? Storage::url($item->options->image) : asset('frontend/img/placeholder.png') }}" 
                                         class="rounded shadow-sm border" 
                                         style="width: 85px; height: 85px; object-fit: cover;"
                                         alt="{{ $item->name }}">
                                    @if($item->options->discount)
                                        <span class="badge badge-danger position-absolute" style="top: -5px; left: -5px;">-{{ $item->options->discount }}%</span>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <h6 class="mb-1 font-weight-bold text-dark">{{ $item->name }}</h6>
                                    
                                    <div class="d-flex flex-wrap align-items-center" style="gap: 10px;">
                                        {{-- Part Number / SKU --}}
                                        @if($item->options->sku)
                                            <small class="text-muted"><i class="fas fa-barcode mr-1"></i> {{ $item->options->sku }}</small>
                                        @endif

                                        {{-- Brand --}}
                                        <small class="text-muted"><i class="fas fa-industry mr-1"></i> {{ $item->options->brand ?? 'Generic' }}</small>
                                        
                                        {{-- Condition Badge --}}
                                        @if(isset($item->options->state))
                                            <span class="badge badge-soft-{{ $item->options->state == 'New' ? 'success' : 'warning' }} px-2" style="font-size: 10px; background-color: #f0f0f0;">
                                                {{ strtoupper($item->options->state) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Shipping Estimate --}}
                                    <div class="mt-2">
                                        <small class="text-success">
                                            <i class="fas fa-truck-loading mr-1"></i> 
                                            Est. Delivery: {{ now()->addDays(2)->format('d M') }} - {{ now()->addDays(5)->format('d M') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4">
                            <span class="text-dark font-weight-bold">{{ number_format($item->price, 0) }}</span>
                            <small class="text-muted d-block">RWF / unit</small>
                        </td>
                        <td class="py-4">
                            <div class="d-flex flex-column align-items-center">
                                <div class="input-group input-group-sm shadow-sm" style="width: 110px;">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-white border border-right-0" 
                                                wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty - 1 }})"
                                                wire:loading.attr="disabled">-</button>
                                    </div>
                                    <input type="text" class="form-control text-center border-top border-bottom bg-white" 
                                           value="{{ $item->qty }}" readonly style="max-width: 40px;">
                                    <div class="input-group-append">
                                        <button class="btn btn-white border border-left-0" 
                                                wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty + 1 }})"
                                                wire:loading.attr="disabled">+</button>
                                    </div>
                                </div>
                                <small class="text-muted mt-1" style="font-size: 10px;">In Stock</small>
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-primary font-weight-bold" style="font-size: 1.1rem;">
                                {{ number_format($item->subtotal, 0) }} RWF
                            </div>
                        </td>
                        <td class="py-4 text-right px-4">
                            <div class="d-flex justify-content-end align-items-center">
                                {{-- Add to Wishlist --}}
                                <button class="btn btn-icon btn-light rounded-circle mr-2" 
                                        style="width: 35px; height: 35px;"
                                        title="Save for Later"
                                        wire:click="moveToWishlist('{{ $item->rowId }}')"
                                        wire:loading.attr="disabled">
                                    <i class="far fa-heart text-primary" wire:loading.remove wire:target="moveToWishlist('{{ $item->rowId }}')"></i>
                                    <span class="spinner-border spinner-border-sm text-primary" role="status" wire:loading wire:target="moveToWishlist('{{ $item->rowId }}')"></span>
                                </button>

                                {{-- Delete --}}
                                <button class="btn btn-icon btn-light rounded-circle text-danger" 
                                        style="width: 35px; height: 35px;"
                                        title="Remove from Cart"
                                        wire:click="removeItem('{{ $item->rowId }}')" 
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-times" wire:loading.remove wire:target="removeItem('{{ $item->rowId }}')"></i>
                                    <span class="spinner-border spinner-border-sm text-danger" role="status" wire:loading wire:target="removeItem('{{ $item->rowId }}')"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

        {{-- Order Summary Section --}}
        <div class="col-lg-4 mt-lg-0 mt-5">
            <div class="card border-0 shadow-sm rounded-lg sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h4 class="font-weight-bold mb-4">Order Summary</h4>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="font-weight-bold">{{ number_format((float)$subTotal, 0) }} RWF</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Estimated Shipping</span>
                        <span class="text-success font-weight-bold">Calculated at Checkout</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold text-primary">{{ number_format((float)$total, 0) }} RWF</h5>
                    </div>

                    <a href="{{ route('checkout.index') }}" 
                       class="btn btn-primary btn-lg btn-block shadow-sm py-3 font-weight-bold rounded-pill {{ $cartContent->count() == 0 ? 'disabled' : '' }}">
                        PROCEED TO CHECKOUT
                    </a>

                    <div class="text-center mt-4 op-5">
                        <small class="text-muted d-block mb-2">Secure Payments via</small>
                        <div class="d-flex justify-content-center align-items-center" style="gap: 15px;">
                             <i class="fab fa-cc-visa fa-2x"></i>
                             <i class="fab fa-cc-mastercard fa-2x"></i>
                             <i class="fas fa-mobile-alt fa-2x" title="Mobile Money"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>