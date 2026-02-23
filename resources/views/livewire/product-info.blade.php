<div>
<div class="col-12 mb-4">
    <div class="bg-white p-4 rounded shadow-sm border-0" style="border-radius: 20px;">
        
        {{-- 1. Brand & Title --}}
        <div class="mb-3">
            <span class="text-primary font-weight-bold text-uppercase small tracking-widest">
                {{ optional($part->partBrand)->name ?? 'Genuine Part' }}
            </span>
            <h1 class="h2 font-weight-bold text-dark mt-1 mb-2">{{ $part->part_name }}</h1>
            <div class="d-flex align-items-center">
                <span class="text-muted small mr-3">SKU: <strong class="text-dark">{{ $part->part_number ?? 'N/A' }}</strong></span>
                <span class="text-muted small">Category: <strong class="text-dark">{{ $part->category->name ?? 'Spare Parts' }}</strong></span>
            </div>
        </div>

        <hr class="my-4" style="border-top: 1px dashed #e2e8f0;">

        {{-- 2. Pricing & Availability --}}
        <div class="row align-items-center mb-4">
            <div class="col-sm-6">
                <h2 class="text-primary font-weight-bold mb-0">
                    {{ number_format($part->price, 0) }} <span class="small">RWF</span>
                </h2>
                @if($part->weight)
                    <p class="text-muted small mb-0 mt-1"><i class="fa fa-weight-hanging mr-1"></i> Shipping Weight: {{ $part->weight }} kg</p>
                @endif
            </div>
            <div class="col-sm-6 text-sm-right mt-3 mt-sm-0">
                <div class="d-inline-block p-2 px-3 rounded-pill {{ $part->stock_quantity > 0 ? 'bg-light-success text-success' : 'bg-light-warning text-warning' }}" style="font-size: 0.85rem; font-weight: 700;">
                    <i class="fa fa-circle mr-1" style="font-size: 8px; vertical-align: middle;"></i>
                    {{ $part->stock_quantity > 0 ? 'In Stock: ' . $part->stock_quantity . ' units' : 'Low Stock' }}
                </div>
            </div>
        </div>

        {{-- 3. Quantity & Actions --}}
        <div class="card bg-light border-0 p-3 mb-4" style="border-radius: 15px;">
            <div class="row align-items-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <label class="small font-weight-bold text-muted text-uppercase mb-2 d-block">Quantity</label>
                    <div class="input-group shadow-sm bg-white rounded-pill overflow-hidden border">
                        <div class="input-group-prepend">
                            <button class="btn btn-white border-0 px-3" type="button" wire:click="decrementQty">
                                <i class="fa fa-minus text-muted small"></i>
                            </button>
                        </div>
                        <input type="number" class="form-control border-0 text-center font-weight-bold bg-white" 
                               wire:model="quantity" readonly style="box-shadow: none;">
                        <div class="input-group-append">
                            <button class="btn btn-white border-0 px-3" type="button" wire:click="incrementQty">
                                <i class="fa fa-plus text-muted small"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 d-flex align-items-end">
                    <button class="btn btn-primary btn-lg rounded-pill px-4 flex-grow-1 mr-2 shadow-sm font-weight-bold" 
                            wire:click="addToCart" style="letter-spacing: 0.5px;">
                        <i class="fa fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-dark btn-lg rounded-pill shadow-sm" 
                            wire:click="addToWishlist" title="Save to Wishlist">
                        <i class="fa fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- 4. Social Sharing (Minimalist) --}}
        <div class="d-flex align-items-center mb-4">
            <span class="small text-muted font-weight-bold mr-3">SHARE:</span>
            <div class="share-links">
                <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" class="text-success mx-2"><i class="fab fa-whatsapp fa-lg"></i></a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($shareText) }}&url={{ urlencode($shareUrl) }}" target="_blank" class="text-dark mx-2"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" class="text-primary mx-2"><i class="fab fa-facebook-f fa-lg"></i></a>
            </div>
        </div>

        {{-- 5. Tabs-style Description --}}
        <div class="border-top pt-4">
            <h6 class="text-uppercase font-weight-bold text-dark small mb-3" style="letter-spacing: 1px;">Product Specifications</h6>
            <div class="text-muted" style="line-height: 1.8; font-size: 0.95rem;">
                {{ $part->description ?? 'Premium quality replacement part specifically engineered for your vehicle model. Please check the compatibility guide below before purchasing.' }}
            </div>
        </div>

    </div>
</div>

{{-- Add this to your push('styles') section for the custom stock badge colors --}}
<style>
    .bg-light-success { background-color: #dcfce7 !important; }
    .bg-light-warning { background-color: #fef3c7 !important; }
    .tracking-widest { letter-spacing: 2px; }
    .btn-white { background-color: white; color: #333; }
    .btn-white:hover { background-color: #f8fafc; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
</div>