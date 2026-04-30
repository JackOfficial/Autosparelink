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
                                    <th class="py-3 px-4">Product</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3 text-center">Quantity</th>
                                    <th class="py-3">Total</th>
                                    <th class="py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartContent as $item)
                                <tr class="border-bottom" wire:key="{{ $item->rowId }}">
                                    <td class="py-4 px-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->options->image ? Storage::url($item->options->image) : asset('frontend/img/placeholder.png') }}" 
                                                 class="rounded mr-3" 
                                                 style="width: 70px; height: 70px; object-fit: cover;"
                                                 alt="{{ $item->name }}">
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">{{ $item->name }}</h6>
                                                <div class="d-flex flex-column">
                                                    <small class="text-muted">{{ $item->options->brand ?? '' }}</small>
                                                    {{-- Displaying State/Condition if available --}}
                                                    @if(isset($item->options->state))
                                                        <span class="badge badge-pill badge-light text-dark align-self-start" style="font-size: 10px;">
                                                            {{ $item->options->state }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">{{ number_format($item->price, 0) }} RWF</td>
                                    <td class="py-4">
                                        <div class="d-flex justify-content-center">
                                            <div class="input-group input-group-sm" style="width: 110px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-secondary border-right-0" 
                                                            wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty - 1 }})"
                                                            wire:loading.attr="disabled">-</button>
                                                </div>
                                                <input type="text" class="form-control text-center border-left-0 border-right-0 bg-white" 
                                                       value="{{ $item->qty }}" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary border-left-0" 
                                                            wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty + 1 }})"
                                                            wire:loading.attr="disabled">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 font-weight-bold text-primary">
                                        {{ number_format($item->subtotal, 0) }} RWF
                                    </td>
                                    <td class="py-4 text-right px-4">
                                        <div class="btn-group">
                                            <button class="btn btn-link text-primary p-0 mr-3" 
                                                    title="Move to Wishlist"
                                                    wire:click="moveToWishlist('{{ $item->rowId }}')"
                                                    wire:loading.attr="disabled">
                                                <i class="far fa-heart" wire:loading.remove wire:target="moveToWishlist('{{ $item->rowId }}')"></i>
                                                <span class="spinner-border spinner-border-sm" role="status" wire:loading wire:target="moveToWishlist('{{ $item->rowId }}')"></span>
                                            </button>

                                            <button class="btn btn-link text-danger p-0" title="Remove Item"
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
                </div>

                <div class="mt-4">
                    <a href="{{ url('/') }}" class="btn btn-link text-decoration-none p-0 text-primary">
                        <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                    </a>
                </div>

            @else
                <div class="text-center py-5 card shadow-sm border-0">
                    <div class="card-body">
                        <i class="fas fa-shopping-cart fa-4x text-light mb-3"></i>
                        <h4>Your cart is empty</h4>
                        <p class="text-muted">Explore our spare parts and automotive services.</p>
                        <a href="{{ url('/spare-parts') }}" class="btn btn-primary px-5 shadow-sm mt-3">Browse Products</a>
                    </div>
                </div>
            @endif
        </div>

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