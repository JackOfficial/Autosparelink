@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
<style>
    :root { --primary-blue: #0061f2; --dark-steel: #212529; }
    
    /* Elegant Breadcrumb */
    .breadcrumb { background: transparent !important; padding: 0.75rem 0; font-size: 0.9rem; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; color: #ccc; font-size: 1.2rem; line-height: 1; }

    /* Modern Gallery Container */
    .gallery-container { background: #fff; border-radius: 20px; border: 1px solid #edf2f7; overflow: hidden; }
    .main-image-viewport { height: 480px; display: flex; align-items: center; justify-content: center; background: #fff; padding: 2rem; position: relative; }
    .main-image-viewport img { max-height: 100%; width: auto; object-fit: contain; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    
    /* Floating Gallery Buttons */
    .gallery-nav {
        position: absolute; top: 50%; width: 100%; display: flex; justify-content: space-between; padding: 0 15px; transform: translateY(-50%);
        opacity: 0; transition: opacity 0.3s; pointer-events: none;
    }
    .main-image-viewport:hover .gallery-nav { opacity: 1; }
    .nav-btn {
        width: 45px; height: 45px; border-radius: 50%; background: #fff; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: flex; align-items: center; justify-content: center; pointer-events: auto; transition: transform 0.2s;
    }
    .nav-btn:hover { transform: scale(1.1); background: var(--primary-blue); color: #fff; }

    /* Thumbnails */
    .thumb-strip { display: flex; gap: 12px; padding: 15px; border-top: 1px solid #f1f4f8; overflow-x: auto; scrollbar-width: none; }
    .thumb-item {
        width: 75px; height: 75px; flex: 0 0 75px; border-radius: 10px; border: 2px solid transparent;
        cursor: pointer; overflow: hidden; transition: all 0.2s; background: #f8fafc;
    }
    .thumb-item img { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; }
    .thumb-item.active-thumb { border-color: var(--primary-blue); transform: translateY(-2px); }
    .thumb-item.active-thumb img { opacity: 1; }

    /* Section Headers */
    .section-title { font-size: 1.25rem; font-weight: 800; color: var(--dark-steel); position: relative; padding-bottom: 10px; margin-bottom: 20px; }
    .section-title::after { content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: var(--primary-blue); border-radius: 2px; }

    /* Technical Tables */
    .tech-table { border: none !important; }
    .tech-table thead th { background: #f8fafc; border: none !important; color: #64748b; font-size: 0.75rem; text-uppercase: uppercase; letter-spacing: 0.05em; padding: 1rem; }
    .tech-table tbody td { border-bottom: 1px solid #f1f5f9 !important; padding: 1.2rem 1rem; vertical-align: middle; color: #334155; font-size: 0.95rem; }
    .tech-table tbody tr:hover { background-color: #fcfdfe; }

    /* Badge Styling */
    .badge-stock { padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }
    .badge-in { background: #dcfce7; color: #15803d; }
    .badge-out { background: #fee2e2; color: #b91c1c; }
</style>
@endpush

@section('content')
<div class="container-fluid px-xl-5 mt-3">

    {{-- 1. Modern Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="/shop" class="text-muted text-decoration-none">Catalog</a></li>
            <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">{{ $part->part_name }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- 2. IMAGE GALLERY --}}
        <div class="col-lg-6 mb-4">
            <div class="gallery-container shadow-sm" 
                 x-data="{ 
                    index: 0, 
                    images: {{ Js::from($photos->isNotEmpty() ? $photos->map(fn($p) => asset('storage/'.$p->file_path)) : [asset('frontend/img/parts.jpg')]) }} 
                 }">
                
                <div class="main-image-viewport">
                    <img :src="images[index]" alt="{{ $part->part_name }}" x-transition:enter="fade">
                    
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
                            <img :src="img" alt="Thumbnail">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- 3. PRODUCT INFO (LIVEWIRE) --}}
        <div class="col-lg-6">
            <div class="pl-lg-4">
                @livewire('product-info', ['part' => $part])
            </div>
        </div>
    </div>

    {{-- 4. SUBSTITUTIONS --}}
    @if($substitutions->isNotEmpty())
    <div class="row mt-5">
        <div class="col-12">
            <h4 class="section-title">Alternative Replacements</h4>
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden">
                <div class="table-responsive">
                    <table class="table tech-table mb-0">
                        <thead>
                            <tr>
                                <th>Manufacturer</th>
                                <th>Part Identification</th>
                                <th>Stock Status</th>
                                <th class="text-right">Unit Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($substitutions as $sub)
                            <tr>
                                <td>
                                    <span class="font-weight-bold text-dark">{{ $sub->partBrand->name ?? 'Generic' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <a href="{{ route('spare-parts.show', $sub->sku) }}" class="text-primary font-weight-bold mb-0">
                                            {{ $sub->part_number }}
                                        </a>
                                        <small class="text-muted">{{ $sub->part_name }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-stock {{ $sub->stock_quantity > 0 ? 'badge-in' : 'badge-out' }}">
                                        {{ $sub->stock_quantity > 0 ? '● Available' : '○ Out of Stock' }}
                                    </span>
                                </td>
                                <td class="text-right align-middle">
                                    <span class="h6 mb-0 font-weight-bold">{{ number_format($sub->price, 0) }} RWF</span>
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
            <h4 class="section-title">Exact Fitment Guide</h4>
            <div class="card border-0 shadow-sm rounded-xl overflow-hidden">
                <div class="table-responsive">
                    <table class="table tech-table mb-0">
                        <thead>
                            <tr>
                                <th>Make</th>
                                <th>Model & Series</th>
                                <th>Engine / Trim</th>
                                <th>Production Years</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compatibilities as $fitment)
                            <tr> 
                                <td class="font-weight-bold">{{ $fitment->vehicleModel->brand->brand_name ?? '—' }}</td>
                                <td>
                                    @php
                                        $routeParam = $fitment->variant_id ? ['type' => 'variant', 'id' => $fitment->variant_id] : ['type' => 'model', 'id' => $fitment->vehicle_model_id];
                                    @endphp
                                    <a href="{{ route('specifications.show', $routeParam) }}" class="text-dark text-decoration-none">
                                        {{ $fitment->vehicleModel->model_name ?? ''}} <i class="fa fa-external-link-alt ml-1 small text-muted"></i>
                                    </a>
                                </td>
                                <td><span class="text-muted">{{ $fitment->variant->name ?? 'Standard' }}</span></td>
                                <td>
                                    <span class="badge badge-light border px-2 py-1">
                                        {{ $fitment->vehicleModel->production_start_year ?? '—' }} — {{ $fitment->vehicleModel->production_end_year ?? 'Present' }}
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