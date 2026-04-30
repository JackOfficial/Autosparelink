<div class="py-5 bg-light min-vh-100">
    <div class="container">
        <div class="row">
            <div class="col-12">
                {{-- Header Section --}}
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="font-weight-bold text-dark mb-1">My Wishlist</h2>
                        <p class="text-muted mb-0">Items you've saved for later</p>
                    </div>
                    <span class="badge badge-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-heart mr-2"></i> {{ $wishlistContent->count() }} {{ Str::plural('Item', $wishlistContent->count()) }}
                    </span>
                </div>

                @if($wishlistContent->count() > 0)
                    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-white border-bottom small text-uppercase letter-spacing-1 text-muted">
                                    <tr>
                                        <th class="py-4 px-4" style="width: 45%;">Product Details</th>
                                        <th class="py-4">Unit Price</th>
                                        <th class="py-4">Availability</th>
                                        <th class="py-4 text-right px-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($wishlistContent as $item)
                                        @php
                                            $imagePath = $item->options->image;
                                            $imageUrl = str_starts_with($imagePath, 'frontend/') 
                                                ? asset($imagePath) 
                                                : (str_starts_with($imagePath, 'http') ? $imagePath : Storage::url($imagePath));
                                        @endphp
                                        <tr class="bg-white">
                                            <td class="py-4 px-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $imagePath ? $imageUrl : asset('frontend/img/placeholder.png') }}" 
                                                         class="rounded border shadow-sm" 
                                                         style="width: 70px; height: 70px; object-fit: cover;"
                                                         alt="{{ $item->name }}"
                                                         onerror="this.src='{{ asset('frontend/img/placeholder.png') }}'">
                                                    <div class="ml-3">
                                                        <h6 class="mb-1 font-weight-bold text-dark">{{ $item->name }}</h6>
                                                        <div class="small text-muted">
                                                            <span class="badge badge-light border text-dark font-weight-normal px-2">
                                                                {{ $item->options->brand ?? 'Genuine Part' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <span class="h6 mb-0 font-weight-bold text-primary">
                                                    {{ number_format($item->price, 0) }}
                                                </span>
                                                <small class="text-muted font-weight-bold">RWF</small>
                                            </td>
                                            <td class="py-4">
                                                <span class="badge badge-pill px-3 py-2 bg-success-light text-success small">
                                                    <i class="fas fa-check-circle mr-1"></i> In Stock
                                                </span>
                                            </td>
                                            <td class="py-4 text-right px-4">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <button wire:click="moveToCart('{{ $item->rowId }}')" 
                                                            wire:loading.attr="disabled"
                                                            class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm mr-3">
                                                        <i class="fas fa-shopping-basket mr-2"></i> Move to Cart
                                                    </button>
                                                    
                                                    <button wire:click="removeItem('{{ $item->rowId }}')" 
                                                            class="btn btn-outline-light btn-sm rounded-circle border-0 text-danger shadow-sm" 
                                                            style="width: 35px; height: 35px;"
                                                            title="Remove Item">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="card border-0 shadow-sm rounded-lg text-center py-5">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="bg-light d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 100px; height: 100px;">
                                    <i class="far fa-heart fa-3x text-muted opacity-50"></i>
                                </div>
                            </div>
                            <h4 class="font-weight-bold text-dark">Your wishlist is currently empty</h4>
                            <p class="text-muted mx-auto mb-4" style="max-width: 400px;">
                                Browse our collection and save items you're interested in. We'll keep them safe here for you!
                            </p>
                            <a href="{{ url('/') }}" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                                Explore Products
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>