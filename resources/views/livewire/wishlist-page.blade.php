<div class="py-3">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-weight-bold">My Wishlist</h2>
                <span class="badge badge-primary px-3 py-2 rounded-pill">
                    {{ $wishlistContent->count() }} Saved Items
                </span>
            </div>

            @if($wishlistContent->count() > 0)
                <div class="card shadow-sm border-0 rounded-lg">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 px-4">Product</th>
                                    <th class="border-0 py-3">Unit Price</th>
                                    <th class="border-0 py-3">Stock Status</th>
                                    <th class="border-0 py-3 text-right px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wishlistContent as $item)
                                    <tr>
                                        <td class="py-4 px-4">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset($item->options->image) }}" 
                                                     class="rounded shadow-sm mr-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0 font-weight-bold">{{ $item->name }}</h6>
                                                    <small class="text-muted">{{ $item->options->brand ?? 'Generic' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 font-weight-bold text-primary">
                                            {{ number_format($item->price, 0) }} RWF
                                        </td>
                                        <td class="py-4">
                                            <span class="badge badge-success-light text-success">
                                                <i class="fas fa-check-circle mr-1"></i> In Stock
                                            </span>
                                        </td>
                                        <td class="py-4 text-right px-4">
                                            <button wire:click="moveToCart('{{ $item->rowId }}')" 
                                                    class="btn btn-primary btn-sm rounded-pill px-3 mr-2">
                                                <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                                            </button>
                                            <button wire:click="removeItem('{{ $item->rowId }}')" 
                                                    class="btn btn-outline-danger btn-sm rounded-circle" 
                                                    title="Remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-5 card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-4">
                            <i class="far fa-heart fa-4x text-muted opacity-50"></i>
                        </div>
                        <h4>Your wishlist is empty</h4>
                        <p class="text-muted">Save items you like to buy them later.</p>
                        <a href="{{ url('/spare-parts') }}" class="btn btn-primary px-5 mt-2 shadow-sm">Start Shopping</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>