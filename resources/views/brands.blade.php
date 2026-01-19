@extends('layouts.app')

@section('title', 'Brands | AutoSpareLink')

@push('styles')
<style>
    .page-header {
        background: url('{{ asset('frontend/img/banners/brands-banner.jpg') }}') center/cover no-repeat;
        padding: 80px 0;
        text-align: center;
        color: #fff;
    }

    .page-header h1 {
        font-size: 2.7rem;
        font-weight: 700;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .brand-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        border: 1px solid #eee;
        text-align: center;
        transition: all 0.3s ease-in-out;
        min-height: 170px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .brand-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .brand-card img {
        height: 55px;
        object-fit: contain;
        margin-bottom: 12px;
    }

    .brand-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 6px;
    }

    .brand-type-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-oem {
        background: #e6f0ff;
        color: #0056d6;
        border: 1px solid #c8dcff;
    }

    .badge-aftermarket {
        background: #e8f7ed;
        color: #1b8a4d;
        border: 1px solid #c9edd5;
    }

    .brand-type-badge i {
        margin-right: 5px;
        font-size: 0.8rem;
    }

    .brands-section {
        padding: 50px 0;
    }

    .brands-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 22px;
    }
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <h1>Trusted Brands We Work With</h1>
    <p class="text-dark">Your reliable source for Genuine & Aftermarket Auto Spare Parts</p>
</div>

<!-- Vehicle Brands -->
<div class="container brands-section">
    <h2 class="section-title">Vehicle Brands (Car Manufacturers)</h2>
    <p class="mb-4 text-muted">We supply genuine parts for a wide range of vehicle manufacturers.</p>

    <div class="brands-grid">
        @foreach($vehicle_brands as $brand)
        <a href="{{ url('models/'.$brand->id) }}">
             <div class="brand-card">
                <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="{{ $brand->brand_name }}">
                <div class="brand-name">{{ strtoupper($brand->brand_name) }}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Parts Brands -->
<div class="container brands-section">
    <h2 class="section-title">Parts Brands (OEM & Aftermarket Manufacturers)</h2>
    <p class="mb-4 text-muted">We work with the world’s best OEM and Aftermarket parts manufacturers.</p>

    <div class="brands-grid">
        @foreach($parts_brands as $brand)
            <div class="brand-card">
                <img src="{{ asset('storage/' . $brand->logo) }}" class="d-none" alt="{{ $brand->name }}">
                <div class="brand-name">{{ strtoupper($brand->name) }}</div>

                @if($brand->type === 'OEM')
                    <div class="brand-type-badge badge-oem">
                        <i class="fas fa-certificate"></i> OEM
                    </div>
                @else
                    <div class="brand-type-badge badge-aftermarket">
                        <i class="fas fa-tools"></i> Aftermarket
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection
