<div>
    {{-- 1. Brand & Title Section --}}
    <div class="mb-4">
        <span class="text-primary font-weight-bold text-uppercase small tracking-widest">
            {{ optional($part->partBrand)->name ?? 'Genuine Part' }}
        </span>
        <h1 class="display-6 font-weight-bold text-dark mt-1 mb-2">{{ $part->part_name }}</h1>
        <div class="d-flex align-items-center flex-wrap">
            <span class="text-muted small mr-3">SKU: <strong class="text-dark">{{ $part->part_number ?? 'N/A' }}</strong></span>
            <span class="text-muted small">Category: <strong class="text-dark">{{ $part->category->name ?? 'Spare Parts' }}</strong></span>
        </div>
    </div>

    {{-- 2. Pricing & Stock Status --}}
    <div class="d-flex align-items-baseline justify-content-between mb-2">
        <div>
            <h2 class="text-dark font-weight-bold mb-0">
                {{ number_format($part->price, 0) }} <span class="h5 text-muted">RWF</span>
            </h2>
            @if($part->weight)
                <p class="text-muted small mb-0"><i class="fa fa-weight-hanging mr-1"></i> {{ $part->weight }} kg</p>
            @endif
        </div>
        <div class="text-right">
            <span class="badge-stock-status {{ $part->stock_quantity > 0 ? 'in-stock' : 'low-stock' }}">
                <i class="fa fa-circle mr-1 small"></i>
                {{ $part->stock_quantity > 0 ? $part->stock_quantity . ' Units Available' : 'Low Stock' }}
            </span>
        </div>
    </div>

    {{-- SHOP INFO (Minimalist One Line) --}}
    <div class="shop-detail-row mb-4">
        <small class="text-muted">
            <i class="fa fa-store mr-1 text-primary"></i> 
            <span class="font-weight-bold text-dark">{{ $part->shop->shop_name ?? 'Official Store' }}</span>
            @if($part->shop?->address)
                <span class="mx-2 text-light-gray">|</span>
                <i class="fa fa-map-marker-alt mr-1 text-danger"></i> {{ $part->shop->address }}
            @endif
        </small>
    </div>

    <hr class="my-4 border-soft">

    {{-- 3. STRUCTURED ACTION BAR --}}
    <div class="action-bar-grid">
        {{-- Custom Quantity Selector --}}
        <div class="qty-group">
            <label class="small font-weight-bold text-muted text-uppercase d-block mb-2">Quantity</label>
            <div class="qty-controls">
                <button type="button" wire:click="decrementQty" class="qty-btn-minus">
                    <i class="fa fa-minus"></i>
                </button>
                <input type="number" wire:model="quantity" readonly class="qty-val">
                <button type="button" wire:click="incrementQty" class="qty-btn-plus">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>

        {{-- Add to Cart & Wishlist --}}
        <div class="btn-group-actions">
            <button class="btn btn-primary btn-action shadow-sm" 
                    wire:click="addToCart" 
                    {{ $part->stock_quantity <= 0 ? 'disabled' : '' }}>
                <i class="fa fa-shopping-cart mr-2"></i> 
                {{ $part->stock_quantity > 0 ? 'Add to Cart' : 'Out of Stock' }}
            </button>
            <button class="btn btn-wishlist" wire:click="addToWishlist">
                <i class="fa fa-heart"></i>
            </button>
        </div>
    </div>

    {{-- 4. Shipping Note & Sharing --}}
    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap">
        <div class="text-muted small">
            <i class="fas fa-truck-moving text-primary mr-2"></i> Delivery within 24-48h in Kigali
        </div>
        <div class="share-area">
            <a href="https://wa.me/?text={{ urlencode($shareText . ' ' . $shareUrl) }}" target="_blank" class="share-icon wa"><i class="fab fa-whatsapp"></i></a>
            <a href="#" class="share-icon tw"><i class="fab fa-twitter"></i></a>
        </div>
    </div>

    {{-- 5. Product Specs --}}
    <div class="mt-5 border-top pt-4">
        <h6 class="text-uppercase font-weight-bold text-dark small mb-3">Product Specifications</h6>
        <p class="text-muted" style="line-height: 1.8;">
            {{ $part->description ?? 'Premium quality replacement part specifically engineered for your vehicle model.' }}
        </p>
    </div>

    {{-- REFINED STYLES --}}
    <style>
        .border-soft { border-color: #f1f5f9; }
        .text-light-gray { color: #cbd5e1; }
        
        .shop-detail-row {
            padding-left: 2px;
            letter-spacing: 0.2px;
        }

        /* The Grid for Actions */
        .action-bar-grid {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 15px;
            align-items: end;
        }

        /* ... existing styles preserved ... */
        .qty-controls {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            height: 54px;
            overflow: hidden;
        }
        .qty-btn-minus, .qty-btn-plus {
            flex: 1;
            border: none;
            background: transparent;
            color: #64748b;
            transition: 0.2s;
        }
        .qty-btn-minus:hover, .qty-btn-plus:hover { background: #e2e8f0; color: #000; }
        .qty-val {
            width: 45px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .btn-group-actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            height: 54px;
            border-radius: 12px;
            flex-grow: 1;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-wishlist {
            height: 54px;
            width: 54px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
        }
        .btn-wishlist:hover { border-color: #f43f5e; color: #f43f5e; }

        .badge-stock-status {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
        }
        .in-stock { background: #dcfce7; color: #166534; }
        .low-stock { background: #fee2e2; color: #991b1b; }

        .share-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-left: 8px; font-size: 0.9rem; transition: 0.3s; color: #94a3b8; border: 1px solid #e2e8f0; }
        .share-icon:hover { color: #fff; transform: translateY(-3px); }
        .share-icon.wa:hover { background: #25D366; border-color: #25D366; }

        @media (max-width: 576px) {
            .action-bar-grid { grid-template-columns: 1fr; }
        }
    </style>
</div>