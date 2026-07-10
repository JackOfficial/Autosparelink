@php
    $mainPhoto = $part->photos->first()?->file_path ?? $part->image ?? 'frontend/img/placeholder.png';
    
    /**
     * CRITICAL MARKUP UPDATE:
     * Use unit_price (Base + Markup) for display and discount math.
     */
    $displayPrice = $part->price; 
    $oldDisplayPrice = $part->old_price;
    
    $discount = !empty($oldDisplayPrice) && $oldDisplayPrice > $displayPrice
        ? round((($oldDisplayPrice - $displayPrice) / $oldDisplayPrice) * 100)
        : null;
    
    $firstSpec = $part->specifications->first();
    
    $descriptiveName = 'Multiple vehicles';
    if ($firstSpec && $firstSpec->variant) {
        $descriptiveName = $firstSpec->variant->variant_name ?? $firstSpec->variant->name ?? 'Multiple vehicles';
    }

    // Safely aggregate active scores for display formatting
    $approvedReviews = $part->reviews->where('status', 'approved');
    $reviewCount = $approvedReviews->count();
    $averageRating = $reviewCount > 0 ? round($approvedReviews->avg('rating'), 1) : 0;
@endphp

<div class="product-item bg-white h-100 shadow-sm border-0 d-flex flex-column" 
     wire:key="part-card-{{ $part->id }}" 
     style="border-radius: 12px; transition: 0.3s; position: relative;">
    
    <div class="product-img position-relative overflow-hidden" style="border-radius: 12px 12px 0 0;">
        
        {{-- Compatibility Badge --}}
        @if(isset($isCompatible) && $isCompatible)
            <div class="position-absolute w-100 text-center" style="top: 10px; z-index: 10;">
                <span class="badge badge-success shadow-sm px-3 py-2 rounded-pill" style="font-size: 0.7rem; opacity: 0.95;">
                    <i class="fa fa-check-circle mr-1"></i> Guaranteed Fit
                </span>
            </div>
        @endif

        @php
            $stateName = strtolower($part->state->name ?? '');
            $badgeClass = match($stateName) {
                'new'         => 'badge-new',
                'refurbished' => 'badge-refurbished',
                'used'        => 'badge-used',
                default       => 'badge-new',
            };
        @endphp

        {{-- Part State Badge --}}
        <div class="badge-custom {{ $badgeClass }}" style="z-index: 5; position: absolute; top: 10px; left: 10px;">
            {{ $part->state->name ?? '' }}
        </div>

        @if($discount)
            <div class="badge-custom badge-discount" style="top:40px; left: 10px; z-index: 5; position: absolute;">-{{ $discount }}%</div>
        @endif

        <a href="{{ route('spare-parts.show', $part->sku) }}" class="d-block">
            <img loading="lazy" src="{{ Storage::url($mainPhoto) }}" alt="{{ $part->part_name }}" style="width: 100%; height: 200px; object-fit: cover;">
        </a>

        <div class="product-action">
            <button wire:click="buyNow" class="btn btn-light btn-square shadow-sm mx-1" title="Buy Now"><i class="fa fa-bolt text-warning"></i></button>
            <button wire:click="addToCart" class="btn btn-light btn-square shadow-sm mx-1" title="Add to Cart"><i class="fa fa-shopping-cart"></i></button>
            <button wire:click="addToWishlist" class="btn btn-light btn-square shadow-sm mx-1" title="Add to Wishlist"><i class="far fa-heart"></i></button>
            
            <button wire:click="openReviewModal" class="btn btn-light btn-square shadow-sm mx-1" title="Write a Review" wire:loading.attr="disabled">
                <i class="fa fa-star text-warning"></i>
            </button>
            
            <a href="{{ route('spare-parts.show', $part->sku) }}" class="btn btn-light btn-square shadow-sm mx-1" title="View Details"><i class="fa fa-search"></i></a>
        </div>
    </div>

    <div class="text-center py-3 px-2 d-flex flex-column flex-grow-1">
        <a class="h6 text-truncate d-block mb-1 text-dark font-weight-bold px-2" href="{{ route('spare-parts.show', $part->sku) }}" title="{{ $part->part_name }}">
            {{ Str::limit($part->part_name, 35) }}
        </a>

        {{-- Dynamic Live aggregate stars score block under the title header --}}
        <div class="d-flex align-items-center justify-content-center mb-2" style="font-size: 0.82rem;">
            <div class="text-warning mr-1">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= round($averageRating))
                        <i class="fa fa-star"></i>
                    @else
                        <i class="far fa-star text-muted" style="opacity: 0.35;"></i>
                    @endif
                @endfor
            </div>
            <small class="text-muted font-weight-medium">
                @if($reviewCount > 0)
                    <strong>{{ $averageRating }}</strong> ({{ $reviewCount }})
                @else
                    <span class="text-muted" style="font-size: 0.72rem; opacity: 0.8;">No reviews yet</span>
                @endif
            </small>
        </div>

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
                <span class="font-weight-bold text-dark">
                    <a href="{{ route('shops.show', $part->shop_id) }}" class="font-weight-bold text-dark hover-underline">
                        {{ $part->shop->shop_name ?? 'Shop' }}
                    </a>
                </span> 
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
                {{-- DISPLAY UNIT_PRICE (Base + Markup) --}}
                <h5 class="mb-0 text-primary font-weight-bold">{{ number_format($displayPrice, 0) }} {{ $currencySymbol ?? 'RWF' }}</h5>
                @if(!empty($oldDisplayPrice))
                    <small class="text-muted ml-2"><del>{{ number_format($oldDisplayPrice, 0) }}</del></small>
                @endif
            </div>

            <div class="px-1" x-data="{ success: false }" @cart-updated.window="if($event.detail.part_id == '{{ $part->id }}') { success = true; setTimeout(() => success = false, 2000) }">
                @if($part->stock_quantity > 0)
                    <div class="row no-gutters mx-n1">
                        {{-- Add to Cart Button --}}
                        <div class="col-6 px-1">
                            <button wire:click="addToCart" 
                                    class="btn btn-primary btn-block btn-sm rounded-pill py-2 transition-all shadow-sm font-weight-semi-bold" 
                                    :class="success ? 'btn-success' : 'btn-primary'"
                                    wire:loading.attr="disabled">
                                <i x-show="success" class="fa fa-check mr-1"></i>
                                <span wire:loading wire:target="addToCart" class="spinner-border spinner-border-sm"></span>
                                <i x-show="!success" wire:loading.remove wire:target="addToCart" class="fa fa-shopping-cart mr-1"></i>
                                <span x-text="success ? 'Added!' : 'Cart'"></span>
                            </button>
                        </div>
                        
                        {{-- Buy Now Button --}}
                        <div class="col-6 px-1">
                            <button wire:click="buyNow" 
                                    class="btn btn-outline-primary btn-block btn-sm rounded-pill py-2 transition-all shadow-sm font-weight-semi-bold"
                                    wire:loading.attr="disabled">
                                <span wire:loading wire:target="buyNow" class="spinner-border spinner-border-sm"></span>
                                <i wire:loading.remove wire:target="buyNow" class="fa fa-bolt mr-1"></i> Buy Now!
                            </button>
                        </div>
                    </div>
                @else
                    <button class="btn btn-secondary btn-block btn-sm rounded-pill py-2" disabled>
                        <i class="fa fa-clock mr-1"></i> Out of Stock
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>