@php
    $mainPhoto = $part->photos->first()?->file_path ?? 'frontend/img/placeholder.png';
    $discount = !empty($part->old_price) && $part->old_price > $part->price
        ? round((($part->old_price - $part->price)/$part->old_price)*100)
        : null;
    
    /** * Get the descriptive name from the first specification's variant.
     * We use specifications->first() because parts are linked to specs, 
     * and each spec belongs to a descriptive Variant.
     */
    $firstSpec = $part->specifications->first();
    $descriptiveName = $firstSpec && $firstSpec->variant 
        ? $firstSpec->variant->name 
        : 'Multiple vehicles';
@endphp

<div class="product-item bg-white h-100 shadow-sm border-0" style="border-radius: 12px; transition: 0.3s;">
    <div class="product-img position-relative overflow-hidden">
        
        {{-- 1. Compatibility Badge --}}
        @if($isCompatible)
            <div class="position-absolute w-100 text-center" style="top: 10px; z-index: 10;">
                <span class="badge badge-success shadow-sm px-3 py-2 rounded-pill" style="font-size: 0.75rem; opacity: 0.95;">
                    <i class="fa fa-check-circle mr-1"></i> Guaranteed Fit
                </span>
            </div>
        @endif

        {{-- Badge: OEM / Aftermarket --}}
        <div class="badge-custom {{ ($part->partBrand->type ?? '') == 'OEM' ? 'badge-new' : 'badge-aftermarket' }}" style="z-index: 5;">
            {{ $part->partBrand->type ?? 'Parts' }}
        </div>

        @if($discount)
            <div class="badge-custom badge-discount" style="top:50px; z-index: 5;">-{{ $discount }}%</div>
        @endif

        <a href="{{ route('spare-parts.show', $part->sku) }}">
            <img loading="lazy" src="{{ asset('storage/'.$mainPhoto) }}" alt="{{ $part->part_name }}" style="width: 100%; height: 200px; object-fit: cover;">
        </a>

        <div class="product-action">
            <button wire:click="addToCart" class="btn btn-light btn-square shadow-sm"><i class="fa fa-shopping-cart"></i></button>
            <button wire:click="addToWishlist" class="btn btn-light btn-square shadow-sm"><i class="far fa-heart"></i></button>
            <a href="{{ route('spare-parts.show', $part->sku) }}" class="btn btn-light btn-square shadow-sm"><i class="fa fa-search"></i></a>
        </div>
    </div>

    <div class="text-center py-3 px-2">
        <a class="h6 text-truncate d-block mb-1 text-dark font-weight-bold" href="{{ route('spare-parts.show', $part->sku) }}">
            {{ Str::limit($part->part_name, 35) }}
        </a>

        {{-- UPDATED: Accessing descriptive name via Specification -> Variant --}}
        <small class="text-muted d-block mb-1 text-truncate">
            <i class="fa fa-car mr-1"></i> Fits: {{ $descriptiveName }}
        </small>

        <small class="text-muted d-block mb-1">
            <i class="fa fa-cog mr-1"></i> {{ $part->part_number }}
        </small>

        @if($part->stock_quantity > 0)
            <small class="badge {{ $part->stock_quantity < 5 ? 'badge-warning' : 'badge-light text-success' }} mb-2">
                {{ $part->stock_quantity < 5 ? 'Low Stock' : 'In Stock' }}
            </small>
        @else
            <small class="badge badge-light text-danger mb-2">Out of Stock</small>
        @endif

        <div class="d-flex align-items-center justify-content-center mb-2">
            <h5 class="mb-0 text-primary font-weight-bold">{{ number_format($part->price, 0) }} {{ $currencySymbol }}</h5>
            @if(!empty($part->old_price))
                <small class="text-muted ml-2"><del>{{ number_format($part->old_price, 0) }}</del></small>
            @endif
        </div>

        {{-- BOTTOM ACTION AREA --}}
        <div class="px-2" x-data="{ success: false }" @cart-updated.window="if($event.detail.part_id == {{ $part->id }}) { success = true; setTimeout(() => success = false, 2000) }">
            
            @if($part->stock_quantity > 0)
                <button wire:click="addToCart" 
                        class="btn btn-block btn-sm rounded-pill py-2 transition-all shadow-sm" 
                        :class="success ? 'btn-success' : 'btn-primary'"
                        wire:loading.attr="disabled">
                    
                    <i x-show="success" class="fa fa-check mr-1"></i>
                    <span wire:loading wire:target="addToCart" class="spinner-border spinner-border-sm mr-1" role="status"></span>
                    <i x-show="!success" wire:loading.remove wire:target="addToCart" class="fa fa-shopping-cart mr-1"></i>

                    <span x-text="success ? 'Added!' : 'Add to Cart'"></span>
                </button>
            @else
                <button class="btn btn-secondary btn-block btn-sm rounded-pill py-2" disabled title="Check back later for availability">
                    <i class="fa fa-clock mr-1"></i> Out of Stock
                </button>
            @endif

        </div>
    </div>
</div>