@php
    $mainPhoto = $part->photos->first()?->file_path ?? 'frontend/img/placeholder.png';
    
    $discount = !empty($part->old_price) && $part->old_price > $part->price
        ? round((($part->old_price - $part->price) / $part->old_price) * 100)
        : null;
    
    $firstSpec = $part->specifications->first();
    
    $descriptiveName = 'Multiple vehicles';
    if ($firstSpec && $firstSpec->variant) {
        $descriptiveName = $firstSpec->variant->variant_name ?? $firstSpec->variant->name ?? 'Multiple vehicles';
    }
@endphp

<div class="product-item bg-white h-100 shadow-sm border-0 d-flex flex-column" style="border-radius: 12px; transition: 0.3s; position: relative;">
    <div class="product-img position-relative overflow-hidden" style="border-radius: 12px 12px 0 0;">
        
        {{-- Compatibility Badge --}}
        @if(isset($isCompatible) && $isCompatible)
            <div class="position-absolute w-100 text-center" style="top: 10px; z-index: 10;">
                <span class="badge badge-success shadow-sm px-3 py-2 rounded-pill" style="font-size: 0.7rem; opacity: 0.95;">
                    <i class="fa fa-check-circle mr-1"></i> Guaranteed Fit
                </span>
            </div>
        @endif

        {{-- Brand/Type Badge --}}
        <div class="badge-custom {{ ($part->partBrand->type ?? '') == 'OEM' ? 'badge-new' : 'badge-aftermarket' }}" style="z-index: 5; position: absolute; top: 10px; left: 10px;">
            {{ $part->partBrand->type ?? 'Parts' }}
        </div>

        @if($discount)
            <div class="badge-custom badge-discount" style="top:40px; left: 10px; z-index: 5; position: absolute;">-{{ $discount }}%</div>
        @endif

        <a href="{{ route('spare-parts.show', $part->sku) }}" class="d-block">
            <img loading="lazy" src="{{ Storage::url($mainPhoto) }}" alt="{{ $part->part_name }}" style="width: 100%; height: 200px; object-fit: cover;">
        </a>

        <div class="product-action">
            <button wire:click="addToCart" class="btn btn-light btn-square shadow-sm mx-1"><i class="fa fa-shopping-cart"></i></button>
            <button wire:click="addToWishlist" class="btn btn-light btn-square shadow-sm mx-1"><i class="far fa-heart"></i></button>
            <a href="{{ route('spare-parts.show', $part->sku) }}" class="btn btn-light btn-square shadow-sm mx-1"><i class="fa fa-search"></i></a>
        </div>
    </div>

    <div class="text-center py-3 px-2 d-flex flex-column flex-grow-1">
        <a class="h6 text-truncate d-block mb-1 text-dark font-weight-bold px-2" href="{{ route('spare-parts.show', $part->sku) }}" title="{{ $part->part_name }}">
            {{ Str::limit($part->part_name, 35) }}
        </a>

        <div class="mt-auto"> 
            {{-- Vehicle & Part Number --}}
           <small class="text-muted d-block mb-1 text-truncate px-2">
        <i class="fa fa-car mr-1"></i> {{ $descriptiveName }}
    </small>

    <small class="text-muted d-block mb-1">
        <i class="fa fa-cog mr-1"></i> {{ $part->part_number }}
    </small>

            {{-- SHOP INFO SECTION --}}
         <small class="text-muted d-block mb-2 text-truncate px-2">
        <i class="fa fa-store mr-1 text-primary"></i> 
        <span class="font-weight-bold text-dark">{{ $part->shop->shop_name ?? 'Shop' }}</span> 
        @if($part->shop?->address)
            <span class="mx-1">|</span>
            <i class="fa fa-map-marker-alt mr-1 text-danger"></i> {{ $part->shop->address }}
        @endif
    </small>

            @if($part->stock_quantity > 0)
                <small class="badge {{ $part->stock_quantity < 5 ? 'badge-warning' : 'badge-light text-success' }} mb-2">
                    {{ $part->stock_quantity < 5 ? 'Low Stock' : 'In Stock' }}
                </small>
            @else
                <small class="badge badge-light text-danger mb-2">Out of Stock</small>
            @endif

            <div class="d-flex align-items-center justify-content-center mb-3">
                <h5 class="mb-0 text-primary font-weight-bold">{{ number_format($part->price, 0) }} {{ $currencySymbol ?? 'RWF' }}</h5>
                @if(!empty($part->old_price))
                    <small class="text-muted ml-2"><del>{{ number_format($part->old_price, 0) }}</del></small>
                @endif
            </div>

            <div class="px-2" x-data="{ success: false }" @cart-updated.window="if($event.detail.part_id == {{ $part->id }}) { success = true; setTimeout(() => success = false, 2000) }">
                @if($part->stock_quantity > 0)
                    <button wire:click="addToCart" 
                            class="btn btn-primary btn-block btn-sm rounded-pill py-2 transition-all shadow-sm" 
                            :class="success ? 'btn-success' : 'btn-primary'"
                            @click="confetti ? confetti() : null"
                            wire:loading.attr="disabled">
                        <i x-show="success" class="fa fa-check mr-1"></i>
                        <span wire:loading wire:target="addToCart" class="spinner-border spinner-border-sm mr-1"></span>
                        <i x-show="!success" wire:loading.remove wire:target="addToCart" class="fa fa-shopping-cart mr-1"></i>
                        <span x-text="success ? 'Added!' : 'Add to Cart'"></span>
                    </button>
                @else
                    <button class="btn btn-secondary btn-block btn-sm rounded-pill py-2" disabled>
                        <i class="fa fa-clock mr-1"></i> Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>