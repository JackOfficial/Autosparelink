<div class="py-3">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-weight-bold">Shopping Cart</h2>
                <div class="d-flex align-items-center">
                    <span class="text-muted mr-3">{{ $cartContent->count() }} Items</span>
                    
                    {{-- Clear Cart Button --}}
                    @if($cartContent->count() > 0)
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3" 
                                wire:click="clearCart" 
                                wire:confirm="Are you sure you want to empty your entire cart?">
                            <i class="fas fa-trash-sweep mr-1"></i> Clear Cart
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
                                    <th class="py-3">Quantity</th>
                                    <th class="py-3">Total</th>
                                    <th class="py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartContent as $item)
                                <tr class="border-bottom">
                                    <td class="py-4 px-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($item->options->image) }}" 
                                                 class="rounded mr-3" style="width: 70px; height: 70px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 font-weight-bold">{{ $item->name }}</h6>
                                                <small class="text-muted">{{ $item->options->brand ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4">{{ number_format($item->price, 0) }} RWF</td>
                                    <td class="py-4">
                                        <div class="input-group input-group-sm" style="width: 110px;">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-outline-secondary border-right-0" 
                                                        wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty - 1 }})">-</button>
                                            </div>
                                            <input type="text" class="form-control text-center border-left-0 border-right-0 bg-white" 
                                                   value="{{ $item->qty }}" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary border-left-0" 
                                                        wire:click="updateQuantity('{{ $item->rowId }}', {{ $item->qty + 1 }})">+</button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 font-weight-bold">
                                        {{ number_format($item->subtotal, 0) }} RWF
                                    </td>
                                    <td class="py-4 text-right px-4">
                                        <button class="btn btn-link text-danger p-0" title="Remove Item"
                                                wire:click="removeItem('{{ $item->rowId }}')" 
                                                wire:loading.attr="disabled">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
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
                        <p class="text-muted">Looks like you haven't added anything yet.</p>
                        <a href="{{ url('/spare-parts') }}" class="btn btn-primary px-5 shadow-sm mt-3">Browse Products</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4 mt-lg-0 mt-5">
            <div class="card border-0 shadow-sm rounded-lg sticky-top" style="top: 40px;">
                <div class="card-body p-4">
                    <h4 class="font-weight-bold mb-4">Order Summary</h4>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ $subTotal }} RWF</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span class="text-success">Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <h5 class="font-weight-bold">Total</h5>
                        <h5 class="font-weight-bold text-primary">{{ $total }} RWF</h5>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg btn-block shadow-sm py-3 font-weight-bold rounded-pill" 
                            @if($cartContent->count() == 0) disabled @endif>
                        PROCEED TO CHECKOUT
                    </a>

                    <div class="text-center mt-4">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" height="20" class="mr-2 op-5">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="15" class="mr-2 op-5">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20" class="op-5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>