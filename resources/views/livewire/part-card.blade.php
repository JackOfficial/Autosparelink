<div class="bg-light p-4 rounded shadow-sm product-card">
    <h2 class="mb-3">{{ $part->part_name }}</h2>

    <p><strong>Make:</strong> {{ optional($part->partBrand)->name ?? '—' }}</p>
    <p><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
    <p class="{{ $part->weight ? '' : 'd-none' }}"><strong>Weight:</strong> {{ $part->weight ?? '—' }} kg</p>

    <h3 class="text-primary mb-3">{{ number_format($part->price, 2) }} RWF</h3>

    <p>
        <strong>Availability:</strong>
        <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
            {{ $part->stock_quantity }}
        </span>
    </p>

    <!-- Quantity Selector -->
    <div class="quantity-wrapper mb-3">
        <label class="mb-2">Quantity:</label>
        <div class="input-group w-50">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary" type="button" wire:click="$set('quantity', max(1, $quantity - 1))">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <input type="number" class="form-control text-center" wire:model="quantity" min="1">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" wire:click="$set('quantity', $quantity + 1)">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Add to Cart / Wishlist -->
    <div class="mb-3 d-flex align-items-center">
        <button class="btn btn-primary btn-lg mr-2" wire:click="addToCart">
            <i class="fa fa-shopping-cart mr-1"></i> Add to Cart
        </button>
        <button class="btn btn-outline-secondary btn-lg wishlist-btn" wire:click="addToWishlist">
            <i class="fa fa-heart mr-1"></i> Add to Wishlist
        </button>
    </div>

    <!-- Share Buttons -->
    <div class="mb-4">
        <strong class="mr-2">Share:</strong>
        <a href="#" class="btn btn-sm btn-success mr-1"><i class="fab fa-whatsapp"></i></a>
        <a href="#" class="btn btn-sm btn-dark mr-1"><i class="fab fa-twitter"></i></a>
        <a href="#" class="btn btn-sm btn-primary"><i class="fab fa-facebook-f"></i></a>
    </div>

    <div class="border-top pt-3">
        <h5>Product Description</h5>
        <p>{{ $part->description ?? 'No description available.' }}</p>
    </div>
</div>
