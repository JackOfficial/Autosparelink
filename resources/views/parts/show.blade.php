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
                <div class="mb-2">
                    <span class="text-primary font-weight-bold small text-uppercase tracking-wider">
                        {{ $part->partBrand->name ?? 'Premium Quality' }}
                    </span>
                </div>
                <h1 class="h2 font-weight-bold text-dark mb-3">{{ $part->part_name }}</h1>
                
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
            <h4 class="section-title">Alternative Replacements</h4>
            <div class="card tech-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table tech-table mb-0">
                        <thead>
                            <tr>
                                <th>Manufacturer</th>
                                <th>Identification</th>
                                <th>Stock</th>
                                <th class="text-right">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($substitutions as $sub)
                            <tr class="align-middle">
                                <td><span class="font-weight-bold text-dark">{{ $sub->partBrand->name ?? 'Generic' }}</span></td>
                                <td>
                                    <a href="{{ route('spare-parts.show', $sub->sku) }}" class="text-primary font-weight-bold text-decoration-none d-block">
                                        {{ $sub->part_number }}
                                    </a>
                                    <small class="text-muted">{{ $sub->part_name }}</small>
                                </td>
                                <td>
                                    <span class="badge-pill-custom {{ $sub->stock_quantity > 0 ? 'badge-in' : 'badge-out' }}">
                                        <span class="small">●</span> {{ $sub->stock_quantity > 0 ? 'In Stock' : 'Call for Stock' }}
                                    </span>
                                </td>
                                <td class="text-right"><span class="h6 mb-0 font-weight-bold">{{ number_format($sub->price, 0) }} RWF</span></td>
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
            <h4 class="section-title">Exact Fitment Guide</h4>
            
            <div class="card tech-card overflow-hidden">
                <div class="table-responsive">
                    <table class="table tech-table mb-0">
                        <thead>
                            <tr>
                                <th>Make</th>
                                <th>Model & Series</th>
                                <th>Engine / Trim</th>
                                <th>Years</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compatibilities as $fitment)
                            <tr> 
                                <td class="font-weight-bold text-primary">{{ $fitment->vehicleModel->brand->brand_name ?? '—' }}</td>
                                <td>{{ $fitment->vehicleModel->model_name ?? ''}}</td>
                                <td><span class="text-muted">{{ $fitment->variant->name ?? 'Standard' }}</span></td>
                                <td>
                                    <span class="badge badge-light border font-weight-bold">
                                        {{ $fitment->vehicleModel->production_start_year }} - {{ $fitment->vehicleModel->production_end_year ?? 'Now' }}
                                    </span>
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
</div>
@endsection