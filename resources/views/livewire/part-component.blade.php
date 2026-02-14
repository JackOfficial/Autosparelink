@php
    $mainPhoto = $part->photos->first()?->file_path ?? 'frontend/img/placeholder.png';
    $discount = !empty($part->old_price) && $part->old_price > $part->price
        ? round((($part->old_price - $part->price)/$part->old_price)*100)
        : null;
@endphp

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="product-item bg-white h-100">
        <div class="product-img position-relative">

            {{-- Badge: NEW / OEM / Aftermarket --}}
            @if(!empty($part->is_new))
                <div class="badge-custom badge-new">NEW</div>
            @elseif(!empty($part->is_oem))
                <div class="badge-custom badge-oem">OEM</div>
            @else
                <div class="badge-custom badge-aftermarket">Aftermarket</div>
            @endif

            {{-- Discount Badge --}}
            @if($discount)
                <div class="badge-custom badge-discount" style="top:50px;">-{{ $discount }}%</div>
            @endif

            <a href="{{ route('spare-parts.show', $part->sku) }}">
                <img loading="lazy" src="{{ asset('storage/'.$mainPhoto) }}" alt="{{ $part->part_name }}">
            </a>

            <div class="product-action">
                <button wire:click="addToCart" class="btn btn-light btn-square" title="Add to cart">
                    <i class="fa fa-shopping-cart"></i>
                </button>

                <button wire:click="addToWishlist" class="btn btn-light btn-square" title="Add to wishlist">
                    <i class="far fa-heart"></i>
                </button>

                <button class="btn btn-light btn-square" title="Compare">
                    <i class="fa fa-sync-alt"></i>
                </button>

                <a href="{{ route('spare-parts.show', $part->sku) }}" class="btn btn-light btn-square" title="View">
                    <i class="fa fa-search"></i>
                </a>
            </div>
        </div>

        <div class="text-center py-3 px-2">
            <a class="h6 text-truncate d-block mb-1 text-dark"
               href="{{ route('spare-parts.show', $part->sku) }}">
               {{ Str::limit($part->part_name, 30) }}
            </a>

            <small class="text-muted d-block">
                Fits: {{ $part->specification->full_name ?? 'Multiple vehicles' }}
            </small>

            @if($part->stock_quantity > 0)
                <small class="{{ $part->stock_quantity < 5 ? 'text-warning' : 'text-success' }}">
                    {{ $part->stock_quantity < 5 ? 'Low Stock' : 'In Stock' }}
                </small>
            @else
                <small class="text-danger">Out of Stock</small>
            @endif

            <div class="d-flex align-items-center justify-content-center mb-2">
                <h5 class="mb-0">{{ number_format($part->price, 2) }} {{ $currencySymbol }}</h5>
                @if(!empty($part->old_price))
                    <h6 class="price-old mb-0">{{ number_format($part->old_price, 2) }}</h6>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-center mb-2">
                <small class="text-primary me-2">
                    @for($i=1;$i<=5;$i++)
                        <i class="fa fa-star {{ $i <= ($part->rating ?? 0) ? 'text-warning' : '' }}"></i>
                    @endfor
                </small>
                <small class="text-muted">({{ $part->reviews_count ?? 0 }})</small>
            </div>

            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('spare-parts.show', $part->sku) }}" class="btn btn-outline-primary btn-sm">
                    View details
                </a>
                <button wire:click="addToCart" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                    Add to cart
                </button>
            </div>
        </div>
    </div>
</div>