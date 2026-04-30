@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
<style>
    :root { 
        --primary-blue: #0061f2; 
        --dark-steel: #212529; 
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
        --orange-main: #ff8a00;
    }
    
    .container-custom { max-width: 1320px; margin: 0 auto; }
    .sticky-sidebar { position: sticky; top: 2rem; }

    /* --- FIXED GALLERY STYLES --- */
    .gallery-container { 
        background: #fff; border-radius: 16px; border: 1px solid var(--border-color); 
        overflow: hidden; transition: box-shadow 0.3s ease; position: relative;
    }
    .main-image-viewport { 
        height: 520px; display: flex; align-items: center; justify-content: center; 
        background: #fff; padding: 1.5rem; position: relative; overflow: hidden;
    }
    .main-image-viewport img { 
        max-height: 100%; width: auto; object-fit: contain; 
        transition: all 0.4s ease;
    }

    /* Navigation Arrows */
    .gallery-nav {
        position: absolute; top: 50%; width: 100%; display: flex;
        justify-content: space-between; padding: 0 20px; transform: translateY(-50%);
        pointer-events: none; z-index: 10;
    }
    .nav-btn {
        width: 45px; height: 45px; border-radius: 50%; background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-color); display: flex; align-items: center;
        justify-content: center; color: var(--dark-steel); cursor: pointer;
        pointer-events: auto; transition: 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .nav-btn:hover { background: var(--primary-blue); color: #fff; }

    /* Thumbnail Strip */
    .thumb-strip {
        display: flex; gap: 10px; padding: 15px; background: var(--soft-bg);
        border-top: 1px solid var(--border-color); overflow-x: auto;
    }
    .thumb-item {
        width: 70px; height: 70px; border-radius: 8px; border: 2px solid transparent;
        background: #fff; padding: 5px; cursor: pointer; transition: 0.2s; flex-shrink: 0;
    }
    .thumb-item img { width: 100%; height: 100%; object-fit: contain; }
    .thumb-item.active-thumb { border-color: var(--primary-blue); box-shadow: 0 0 0 2px rgba(0,97,242,0.1); }
    /* --- END GALLERY FIXES --- */

    /* Rest of your existing styles preserved */
    .tech-card { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .tech-table thead th { 
        background: var(--soft-bg); color: #64748b; font-weight: 700; 
        text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; padding: 1rem 1.2rem;
    }
    .tech-table tbody td { padding: 1rem 1.2rem; border-bottom: 1px solid var(--soft-bg) !important; }
    .brand-badge { background: var(--primary-blue); color: #fff; padding: 5px 12px; border-radius: 6px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; }
    .price-text { font-size: 1.1rem; font-weight: 800; color: #000; }
    .sub-image-wrapper { width: 64px; height: 64px; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: flex; align-items: center; justify-content: center; padding: 6px; }
    .sub-image-wrapper img { max-width: 100%; max-height: 100%; object-fit: contain; }

        /* New Shop Styling */
    .shop-avatar {
        width: 32px;
        height: 32px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .x-small { padding: 2px 6px; border-radius: 4px; }
    
    .part-link { color: #1e293b; text-decoration: none; transition: 0.2s; }
    .part-link:hover { color: var(--primary-blue); }

    /* Re-stating necessary button styles for clarity */
    .btn-outline-primary {
        border-width: 2px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
    }

    @media (max-width: 991px) {
        .main-image-viewport { height: 350px; }
        .sticky-sidebar { position: relative; top: 0; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid container-custom mt-3">

    {{-- 1. Modern Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="/" class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="/shop" class="text-muted text-decoration-none">Catalog</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">{{ $part->part_name }}</li>
        </ol>
    </nav>

    
        <div class="row gx-lg-5">
        {{-- 2. IMAGE GALLERY --}}
        <div class="col-lg-7 mb-4">
            <div class="gallery-container shadow-sm" 
                 x-data="{ 
                    index: 0, 
                    images: {{ Js::from($photos->isNotEmpty() ? $photos->pluck('file_path')->map(fn($p) => asset('storage/'.$p)) : [asset('frontend/img/placeholder.jpg')]) }} 
                 }">
                
                <div class="main-image-viewport">
                    {{-- Main Dynamic Image --}}
                    <template x-for="(img, i) in images" :key="i">
                        <img x-show="index === i" 
                             :src="img" 
                             alt="{{ $part->part_name }}"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             style="position: absolute;">
                    </template>
                    
                    {{-- Navigation Arrows --}}
                    <div class="gallery-nav" x-show="images.length > 1">
                        <button class="nav-btn" @click="index = (index - 1 + images.length) % images.length">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button class="nav-btn" @click="index = (index + 1) % images.length">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                {{-- Thumbnail Strip --}}
                <div class="thumb-strip" x-show="images.length > 1">
                    <template x-for="(img, i) in images" :key="i">
                        <div class="thumb-item" 
                             :class="{ 'active-thumb': index === i }" 
                             @click="index = i">
                            <img :src="img" loading="lazy">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- 3. PRODUCT INFO (Sticky Sidebar) --}}
        <div class="col-lg-5">
            <div class="sticky-sidebar">
                {{-- Livewire Component handles Price, Stock, and Add-to-Cart logic --}}
                @livewire('product-info', ['part' => $part])

                <div class="mt-4 p-3 rounded-xl bg-light border">
                    <div class="d-flex align-items-center text-muted small">
                        <i class="fas fa-shipping-fast mr-2"></i> Fast Delivery available across Rwanda
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- 4. SUBSTITUTIONS --}}
@if($substitutions->isNotEmpty())
<div class="row mt-5">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="icon-box-sm bg-orange-soft mr-3">
                    <i class="fas fa-exchange-alt text-orange"></i>
                </div>
                <h4 class="section-title mb-0">Alternative Replacements</h4>
            </div>
            <span class="badge badge-soft-primary px-3 py-2 rounded-pill small font-weight-bold">
                {{ $substitutions->count() }} Options Available
            </span>
        </div>
        
        <div class="card tech-card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table tech-table mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 pl-4">Product & Specification</th>
                            <th class="py-3">Brand</th>
                            <th class="py-3">Vendor / Shop</th>
                            <th class="py-3">Stock Status</th>
                            <th class="py-3 text-right">Price (RWF)</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($substitutions as $sub)
                        <tr>
                            {{-- Product Details with Quality Badge --}}
                            <td class="pl-4 py-3" style="min-width: 300px;">
                                <div class="d-flex align-items-center">
                                    <div class="sub-image-wrapper">
                                        <img src="{{ $sub->photos->first() ? asset('storage/' . $sub->photos->first()->file_path) : asset('frontend/img/placeholder.jpg') }}" 
                                             alt="{{ $sub->part_name }}">
                                    </div>
                                    <div class="ml-3">
                                        <a href="{{ route('spare-parts.show', $sub->sku) }}" class="part-link font-weight-bold">
                                            {{ $sub->part_number }}
                                        </a>
                                        <div class="text-muted small mb-1">{{ Str::limit($sub->part_name, 30) }}</div>
                                        {{-- Example Condition Badge --}}
                                        <span class="badge {{ $sub->is_genuine ? 'badge-success' : 'badge-light' }} x-small" style="font-size: 0.65rem; letter-spacing: 0.02em;">
                                            {{ $sub->is_genuine ? 'GENUINE O.E.M' : 'AFTERMARKET' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Brand --}}
                            <td>
                                <div class="manufacturer-tag small font-weight-bold text-uppercase">
                                    {{ $sub->partBrand->name ?? 'Generic' }}
                                </div>
                            </td>

                            {{-- Shop / Vendor Information --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="shop-avatar mr-2">
                                        <i class="fas fa-store text-muted"></i>
                                    </div>
                                    <div>
                                        <a href="#" class="text-dark small font-weight-bold d-block mb-0">
                                            {{ $sub->shop->name ?? 'AutoLink Official' }}
                                        </a>
                                        <div class="text-warning small" style="font-size: 0.7rem;">
                                            <i class="fas fa-star"></i> 4.8 (120+ sales)
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Stock --}}
                            <td>
                                <div class="stock-indicator {{ $sub->stock_quantity > 0 ? 'is-in' : 'is-out' }}">
                                    <span class="dot"></span>
                                    {{ $sub->stock_quantity > 0 ? $sub->stock_quantity . ' in stock' : 'Out of Stock' }}
                                </div>
                            </td>

                            {{-- Price --}}
                            <td class="text-right pr-4">
                                @if($sub->old_unit_price && $sub->old_unit_price > $sub->unit_price)
                                    <del class="text-muted small d-block" style="font-size: 0.7rem;">{{ number_format($sub->old_unit_price, 0) }}</del>
                                @endif
                                <span class="price-text text-primary">{{ number_format($sub->unit_price ?? $sub->price, 0) }}</span>
                            </td>

                            {{-- View Button --}}
                            <td class="text-center pr-4">
                                <a href="{{ route('spare-parts.show', $sub->sku) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- 5. COMPATIBILITY --}}
@if($compatibilities->isNotEmpty())
<div class="row mt-5 mb-5">
    <div class="col-12">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-box-sm bg-primary-soft mr-3">
                <i class="fas fa-car-side text-primary"></i>
            </div>
            <h4 class="section-title mb-0">Exact Fitment Guide</h4>
        </div>
        
        <div class="card tech-card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table tech-table mb-0">
                    <thead class="bg-soft-blue">
                        <tr>
                            <th class="py-3 pl-4">Vehicle Make</th>
                            <th class="py-3">Model & Series</th>
                            <th class="py-3">Engine / Trim</th>
                            <th class="py-3 text-center">Production Years</th>
                            <th class="py-3 text-center">Shop Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compatibilities as $fitment)
                      @php
    $spec = $fitment->specification;
    $model = $spec->vehicleModel;
    $variant = $spec->variant;
    
    // 1. Use keys that match your Route placeholders exactly to avoid Query Strings (?)
    $params = [
        'brand'   => $model?->brand?->slug ?? 'all',
        'model'   => $model?->slug ?? 'all',
        'variant' => $variant?->slug ?? 'all'
    ];

    // 2. Only add search if it's actually set
    if (request('search')) {
        $params['search'] = request('search');
    }

    // This will now produce: /parts-catalog/kia/k5/kia-k5-hybrid...
    // $catalogUrl = route('parts.catalog', $params);
@endphp
                        <tr class="hover-row"> 
                            {{-- 1. Brand --}}
                            <td class="pl-4">
                                <span class="brand-badge">{{ $model?->brand?->brand_name ?? '—' }}</span>
                            </td>

                            {{-- 2. Model --}}
                            <td>
                                <div class="model-text">
                                    {{ $model?->model_name ?? 'Universal Fit' }}
                                    @if($model?->series)
                                        <small class="text-muted d-block">{{ $model->series }}</small>
                                    @endif
                                </div>
                            </td>

                            {{-- 3. Engine / Variant --}}
                            <td>
                                <div class="trim-box">
                                    <i class="fas fa-microchip mr-2 text-muted small"></i>
                                    {{ $variant?->name ?? 'Standard' }}
                                </div>
                            </td>

                            {{-- 4. Years --}}
                            <td class="text-center">
                                @if($model?->production_start_year)
                                    <div class="year-range">
                                        <span class="year-tag">{{ $model->production_start_year }}</span>
                                        <span class="year-divider"></span>
                                        <span class="year-tag">{{ $model->production_end_year ?? 'Now' }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small">Universal</span>
                                @endif
                            </td>

                            {{-- 5. Updated Interactive Button --}}
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm font-weight-bold">
                                    <i class="fas fa-search-plus mr-1"></i> See All Parts
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <p class="mt-3 text-muted small px-2">
            <i class="fas fa-info-circle mr-1"></i> Click "See All Parts" to view all components compatible with this specific vehicle configuration.
        </p>
    </div>
</div>
@endif
</div>
@endsection