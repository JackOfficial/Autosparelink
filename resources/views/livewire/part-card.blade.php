<div class="bg-light p-4 rounded shadow-sm product-card">
    <h2 class="mb-3">{{ $part->part_name }}</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <p class="mb-1"><strong>Make:</strong> {{ $part->partBrand?->name ?? '—' }}</p>
            <p class="mb-1"><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
            <p class="mb-1 {{ $part->weight ? '' : 'd-none' }}"><strong>Weight:</strong> {{ $part->weight }} kg</p>
        </div>
        
        {{-- Shop Info Section --}}
        <div class="col-md-6 border-left">
            <p class="mb-1 text-muted small uppercase font-weight-bold">Sold By</p>
            <p class="mb-1">
                <i class="fas fa-store mr-2 text-primary"></i>
                <span class="font-weight-bold">{{ $part->shop->shop_name ?? 'Official Store' }}</span>
            </p>
            @if($part->shop?->address)
            <p class="mb-0 small text-muted">
                <i class="fas fa-map-marker-alt mr-2 text-danger"></i>
                {{ $part->shop->address }}
            </p>
            @endif
        </div>
    </div>

    {{-- Pricing Section with Markup Support --}}
    <div class="mb-3">
        <h3 class="text-primary mb-0">
            {{ number_format($part->unit_price, 0) }} RWF
        </h3>
        @if($part->old_unit_price && $part->old_unit_price > $part->unit_price)
            <small class="text-muted">
                <del>{{ number_format($part->old_unit_price, 0) }} RWF</del>
                <span class="text-success ml-2">
                    -{{ round((($part->old_unit_price - $part->unit_price) / $part->old_unit_price) * 100) }}%
                </span>
            </small>
        @endif
    </div>

    <p>
        <strong>Availability:</strong>
        <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
            {{ $part->stock_quantity > 0 ? $part->stock_quantity . ' in stock' : 'Out of Stock' }}
        </span>
    </p>

    <div class="quantity-wrapper mb-3">
        <label class="mb-2">Quantity:</label>
        <div class="input-group w-50">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" 
                        wire:click="{{ $quantity > 1 ? '$set(\'quantity\', ' . ($quantity - 1) . ')' : '' }}"
                        @if($part->stock_quantity <= 0 || $quantity <= 1) disabled @endif>
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            
            <input type="number" class="form-control text-center" wire:model.live="quantity" min="1">
            
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" 
                        wire:click="$set('quantity', {{ $quantity + 1 }})"
                        @if($quantity >= $part->stock_quantity) disabled @endif>
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="mb-3 d-flex align-items-center">
        <button class="btn btn-primary btn-lg mr-2 shadow-sm" 
                wire:click="addToCart" 
                wire:loading.attr="disabled"
                @if($part->stock_quantity <= 0) disabled @endif>
            <i class="fa fa-shopping-cart mr-1" wire:loading.remove wire:target="addToCart"></i>
            <span class="spinner-border spinner-border-sm mr-1" wire:loading wire:target="addToCart"></span>
            {{ $part->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
        </button>

        <button class="btn btn-outline-secondary btn-lg wishlist-btn shadow-sm" wire:click="addToWishlist" wire:loading.attr="disabled">
            <i class="fa fa-heart mr-1"></i> Add to Wishlist
        </button>
    </div>

    <div class="mb-4">
        <strong class="mr-2">Share:</strong>
        @php $shareUrl = urlencode(url()->current()); @endphp
        <a href="https://wa.me/?text={{ $shareUrl }}" target="_blank" class="btn btn-sm btn-success mr-1 shadow-sm"><i class="fab fa-whatsapp"></i></a>
        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}" target="_blank" class="btn btn-sm btn-dark mr-1 shadow-sm"><i class="fab fa-twitter"></i></a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="btn btn-sm btn-primary shadow-sm"><i class="fab fa-facebook-f"></i></a>
    </div>

    <div class="border-top pt-3">
        <h5 class="font-weight-bold">Product Description</h5>
        <p class="text-muted">{{ $part->description ?? 'No description available for this part.' }}</p>
    </div>
</div>