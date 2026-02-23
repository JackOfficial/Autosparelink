@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
<style>
    /* Wishlist & Quantity Hover Effects */
    .product-card:hover .wishlist-btn,
    .quantity-wrapper:hover .btn-minus,
    .quantity-wrapper:hover .btn-plus { opacity: 1; }
    .wishlist-btn, .btn-minus, .btn-plus { opacity: 0; transition: opacity 0.3s ease; }

    /* Table & Gallery */
    .table-hover tbody tr:hover { background-color: #f8f9fa; cursor: pointer; }
    .main-image { background: #fff; height: 450px; display: flex; align-items: center; justify-content: center; }
    .main-image img { max-height: 100%; object-fit: contain; transition: transform .3s ease; }
    .main-image:hover img { transform: scale(1.05); }
    
    .gallery-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(0,0,0,.5); color: #fff; border: none;
        opacity: 0; transition: all 0.3s; z-index: 10;
    }
    .main-image:hover .gallery-btn { opacity: 1; }
    .prev-btn { left: 15px; } .next-btn { right: 15px; }

    .thumbnail-wrapper { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; }
    .thumbnail-img {
        width: 70px; height: 70px; object-fit: cover;
        border-radius: 6px; border: 2px solid transparent; cursor: pointer;
    }
    .thumbnail-img.active-thumb { border-color: #007bff; }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">
    {{-- Breadcrumb --}}
    <div class="row px-xl-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="/shop">Shop</a></li>
                    <li class="breadcrumb-item active">{{ $part->part_name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row px-xl-5">
        {{-- IMAGE GALLERY --}}
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="bg-light p-3 rounded shadow-sm"
                 x-data="{
                    index: 0,
                    images: {{ Js::from($photos->isNotEmpty() 
                                ? $photos->map(fn($p) => asset('storage/'.$p->file_path)) 
                                : [asset('frontend/img/parts.jpg')]) }}
                 }">
                
                {{-- Main Image Area --}}
                <div class="main-image position-relative overflow-hidden rounded border bg-white">
                    <img :src="images[index]" class="img-fluid" alt="{{ $part->part_name }}">

                    <template x-if="images.length > 1">
                        <button class="gallery-btn prev-btn" @click="index = (index - 1 + images.length) % images.length">&lsaquo;</button>
                    </template>
                    <template x-if="images.length > 1">
                        <button class="gallery-btn next-btn" @click="index = (index + 1) % images.length">&rsaquo;</button>
                    </template>
                </div>

                {{-- Thumbnails --}}
                <div x-show="images.length > 1" class="thumbnail-wrapper mt-3">
                    <template x-for="(img, i) in images" :key="i">
                        <img :src="img" 
                             class="thumbnail-img shadow-sm"
                             :class="{ 'active-thumb': index === i }"
                             @click="index = i">
                    </template>
                </div>
            </div>
        </div>

        {{-- PRODUCT INFO (LIVEWIRE) --}}
        <div class="col-lg-7 col-md-6">
            @livewire('product-info', ['part' => $part])
        </div>
    </div>

    {{-- SUBSTITUTIONS --}}
    @if($substitutions->isNotEmpty())
    <div class="row px-xl-5 mt-5">
        <div class="col-12">
            <h4 class="font-weight-bold mb-3">Equivalent Substitutions</h4>
            <div class="table-responsive bg-white rounded shadow-sm border">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Brand</th>
                            <th>Part Number</th>
                            <th>Description</th>
                            <th>Availability</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($substitutions as $sub)
                        <tr>
                            <td class="align-middle">{{ $sub->partBrand->name ?? 'Generic' }}</td>
                            <td class="align-middle">
                                <a href="{{ route('spare-parts.show', $sub->sku) }}" class="font-weight-bold">{{ $sub->part_number }}</a>
                            </td>
                            <td class="align-middle">{{ $sub->part_name }}</td>
                            <td class="align-middle">
                                <span class="badge {{ $sub->stock_quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ $sub->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </td>
                            <td class="align-middle text-right font-weight-bold">{{ number_format($sub->price, 0) }} RWF</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- COMPATIBILITY --}}
    @if($compatibilities->isNotEmpty())
    <div class="row px-xl-5 mt-5 mb-5">
        <div class="col-12">
            <h4 class="font-weight-bold mb-3">Vehicle Compatibility</h4>
            <div class="table-responsive bg-white rounded shadow-sm border">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Make</th>
                            <th>Model Range</th>
                            <th>Trim / Variant</th>
                            <th>Year Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compatibilities as $fitment)
                        <tr> 
                            <td class="align-middle">
                                {{ $fitment->vehicleModel->brand->brand_name ?? '—' }}
                            </td>
                            <td class="align-middle">
                                @php
                                    $routeParam = $fitment->variant_id ? ['type' => 'variant', 'id' => $fitment->variant_id] : ['type' => 'model', 'id' => $fitment->vehicle_model_id];
                                @endphp
                                <a href="{{ route('specifications.show', $routeParam) }}" class="text-primary font-weight-bold">
                                    {{ $fitment->vehicleModel->model_name }}
                                </a>
                            </td>
                            <td class="align-middle text-muted small">
                                {{ $fitment->variant->name ?? 'Base Model' }}
                            </td>
                            <td class="align-middle">
                                {{ $fitment->vehicleModel->production_start_year ?? '—' }} - {{ $fitment->vehicleModel->production_end_year ?? 'Present' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection