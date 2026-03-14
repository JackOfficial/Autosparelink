@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
<style>
    :root { 
        --primary-blue: #0061f2; 
        --dark-steel: #212529; 
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
    }
    
    /* 1. Enhanced Layout & Spacing */
    .container-custom { max-width: 1320px; margin: 0 auto; }
    .sticky-sidebar { position: sticky; top: 2rem; }

    /* 2. Gallery Refinement */
    .gallery-container { 
        background: #fff; 
        border-radius: 16px; 
        border: 1px solid var(--border-color); 
        overflow: hidden; 
        transition: box-shadow 0.3s ease;
    }
    .gallery-container:hover { box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .main-image-viewport { 
        height: 520px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        background: #fff; 
        padding: 1.5rem; 
        position: relative; 
    }
    .main-image-viewport img { 
        max-height: 100%; 
        width: auto; 
        object-fit: contain; 
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
    }
    
    /* 3. Modern Tech Tables */
    .tech-card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .tech-table thead th { 
        background: var(--soft-bg); 
        color: #64748b; 
        font-weight: 700; 
        text-transform: uppercase; 
        font-size: 0.7rem; 
        letter-spacing: 0.05em;
        padding: 1rem 1.2rem;
    }
    .tech-table tbody td { padding: 1rem 1.2rem; border-bottom: 1px solid var(--soft-bg) !important; }

    /* 4. Functional Badges */
    .badge-pill-custom {
        padding: 0.35rem 0.8rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-in { background: #ecfdf5; color: #059669; border: 1px solid #bbf7d0; }
    .badge-out { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    @media (max-width: 991px) {
        .main-image-viewport { height: 350px; }
        .sticky-sidebar { position: relative; top: 0; }
    }

    /* Modern Quantity Selector */
.qty-container {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border-radius: 12px;
    padding: 4px;
    width: fit-content;
    border: 1px solid var(--border-color);
}

.qty-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: none;
    background: #fff;
    color: var(--dark-steel);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.qty-btn:hover {
    background: var(--primary-blue);
    color: #fff;
}

.qty-input {
    width: 50px;
    text-align: center;
    border: none;
    background: transparent;
    font-weight: 800;
    color: var(--dark-steel);
    font-size: 1.1rem;
    outline: none;
}

/* Chrome, Safari, Edge, Opera - Remove Arrows */
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Image Wrapper - The fix for 'bitter' looks */
.sub-image-wrapper {
    width: 64px;
    height: 64px;
    background: #f8fafc; /* Soft background so the image pops */
    border: 1px solid #edf2f7;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    transition: all 0.3s ease;
}

.sub-image-wrapper img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain; /* Prevents stretching */
    mix-blend-mode: multiply; /* Makes white backgrounds of photos transparent */
}

/* Row Hover Effect */
.tech-table tbody tr { transition: background 0.2s; }
.tech-table tbody tr:hover { background-color: #fbfcfe; }
.tech-table tbody tr:hover .sub-image-wrapper { transform: scale(1.05); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

/* Typography & Elements */
.part-link { color: #1a202c; font-weight: 800; text-decoration: none !important; font-size: 0.95rem; }
.part-link:hover { color: var(--primary-blue); }

.manufacturer-tag { 
    background: #f1f5f9; 
    color: #475569; 
    padding: 4px 12px; 
    border-radius: 6px; 
    font-size: 0.8rem; 
    font-weight: 700; 
    display: inline-block; 
}

.price-text { font-size: 1.1rem; font-weight: 800; color: #000; }

/* Stock Indicator */
.stock-indicator { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; }
.stock-indicator .dot { width: 8px; height: 8px; border-radius: 50%; }
.stock-indicator.is-in { color: #059669; }
.stock-indicator.is-in .dot { background: #10b981; box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); }
.stock-indicator.is-out { color: #dc2626; }
.stock-indicator.is-out .dot { background: #ef4444; }

/* Circular View Button */
.btn-view-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.btn-view-circle:hover {
    background: var(--primary-blue);
    color: #fff;
    border-color: var(--primary-blue);
    transform: translateX(3px);
}

/* Background for headers */
.bg-soft-blue { background-color: #f0f7ff; }

/* Brand Badge */
.brand-badge {
    background: #0061f2;
    color: #fff;
    padding: 5px 12px;
    border-radius: 6px;
    font-weight: 800;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Model & Trim Typography */
.model-text { font-weight: 700; color: #1e293b; font-size: 0.95rem; }
.trim-box { 
    font-size: 0.85rem; 
    color: #64748b; 
    background: #f8fafc; 
    padding: 4px 10px; 
    border-radius: 6px;
    display: inline-block;
}

/* Year Range Visuals */
.year-range {
    display: inline-flex;
    align-items: center;
    background: #fff;
    border: 1px solid #e2e8f0;
    padding: 2px;
    border-radius: 8px;
}
.year-tag {
    padding: 2px 8px;
    font-weight: 700;
    font-size: 0.8rem;
    color: #334155;
}
.year-divider {
    width: 8px;
    height: 2px;
    background: #cbd5e1;
    margin: 0 2px;
}

/* Hover Effects */
.hover-row { transition: all 0.2s; }
.hover-row:hover { background-color: #f8fbff !important; }

/* Icon Box */
.icon-box-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
}
.bg-primary-soft { background: rgba(0, 97, 242, 0.1); }

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
                    <img :src="images[index]" alt="{{ $part->part_name }}" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="gallery-nav" x-show="images.length > 1">
                        <button class="nav-btn" @click="index = (index - 1 + images.length) % images.length">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        <button class="nav-btn" @click="index = (index + 1) % images.length">
                            <i class="fa fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="thumb-strip" x-show="images.length > 1">
                    <template x-for="(img, i) in images" :key="i">
                        <div class="thumb-item" :class="{ 'active-thumb': index === i }" @click="index = i">
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
            <h4 class="section-title mb-0">Alternative Replacements</h4>
            <span class="badge badge-soft-primary px-3 py-2 rounded-pill small font-weight-bold">
                {{ $substitutions->count() }} Options Available
            </span>
        </div>
        
        <div class="card tech-card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table tech-table mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 pl-4">Product Details</th>
                            <th class="py-3">Brand</th>
                            <th class="py-3">Availability</th>
                            <th class="py-3 text-right">Price (RWF)</th>
                            <th class="py-3 text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($substitutions as $sub)
                        <tr>
                            {{-- Photo & Identification --}}
                            <td class="pl-4 py-3" style="min-width: 320px;">
                                <div class="d-flex align-items-center">
                                    <div class="sub-image-wrapper">
                                        <img src="{{ $sub->photos->first() ? asset('storage/' . $sub->photos->first()->file_path) : asset('frontend/img/placeholder.jpg') }}" 
                                             alt="{{ $sub->part_name }}">
                                    </div>
                                    <div class="ml-3">
                                        <a href="{{ route('spare-parts.show', $sub->sku) }}" class="part-link">
                                            {{ $sub->part_number }}
                                        </a>
                                        <div class="text-muted small font-weight-medium">{{ Str::limit($sub->part_name, 35) }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Manufacturer --}}
                            <td>
                                <div class="manufacturer-tag">
                                    {{ $sub->partBrand->name ?? 'Generic' }}
                                </div>
                            </td>

                            {{-- Stock --}}
                            <td>
                                <div class="stock-indicator {{ $sub->stock_quantity > 0 ? 'is-in' : 'is-out' }}">
                                    <span class="dot"></span>
                                    {{ $sub->stock_quantity > 0 ? 'In Stock' : 'On Order' }}
                                </div>
                            </td>

                            {{-- Price --}}
                            <td class="text-right pr-4">
                                <span class="price-text">{{ number_format($sub->price, 0) }}</span>
                            </td>

                            {{-- Action Link --}}
                            <td class="text-center pr-4">
                                <a href="{{ route('spare-parts.show', $sub->sku) }}" class="btn-view-circle">
                                    <i class="fas fa-arrow-right"></i>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compatibilities as $fitment)
                        @php
                            $spec = $fitment->specification;
                            $model = $spec?->vehicleModel; 
                            $variant = $spec?->variant;
                        @endphp
                        <tr class="hover-row"> 
                            {{-- 1. Brand Name with Badge style --}}
                            <td class="pl-4">
                                <span class="brand-badge">
                                    {{ $model?->brand?->brand_name ?? '—' }}
                                </span>
                            </td>

                            {{-- 2. Model & Series --}}
                            <td>
                                <div class="model-text">
                                    {{ $model?->model_name ?? 'Universal Fit' }}
                                    @if($model?->series)
                                        <small class="text-muted d-block">{{ $model->series }}</small>
                                    @endif
                                </div>
                            </td>

                            {{-- 3. Engine / Trim --}}
                            <td>
                                <div class="trim-box">
                                    <i class="fas fa-microchip mr-2 text-muted small"></i>
                                    {{ $variant?->name ?? 'Standard' }}
                                </div>
                            </td>

                            {{-- 4. Production Years --}}
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <p class="mt-3 text-muted small px-2">
            <i class="fas fa-info-circle mr-1"></i> Please verify your part number matches the identification above to ensure 100% compatibility.
        </p>
    </div>
</div>
@endif
</div>
@endsection