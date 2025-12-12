@extends('layouts.app')

@section('title', 'Brands | AutoSpareLink')

@section('style')
<style>
    .page-header {
        background: url('{{ asset('frontend/img/banners/brands-banner.jpg') }}') center/cover no-repeat;
        padding: 80px 0;
        text-align: center;
        color: #fff;
        position: relative;
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
        height: 150px;
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
        height: 50px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .brand-card span {
        font-weight: 600;
        color: #333;
    }

    .brands-section {
        padding: 50px 0;
    }

    .brands-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
    }
</style>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <h1>Trusted Brands We Work With</h1>
    <p>Your reliable source for Genuine & Aftermarket Auto Spare Parts</p>
</div>

<!-- Vehicle Brands -->
<div class="container brands-section">
    <h2 class="section-title">Vehicle Brands (Car Manufacturers)</h2>
    <p class="mb-4 text-muted">We supply genuine parts for a wide range of vehicle manufacturers.</p>

    <div class="brands-grid">
        @foreach($vehicle_brands as $brand)
            <div class="brand-card">
                <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="{{ $brand->brand_name }}">
                <span>{{ strtoupper($brand->brand_name) }}</span>
            </div>
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
                <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="{{ $brand->brand_name }}">
                <span>{{ strtoupper($brand->name) }}</span>
            </div>
        @endforeach
    </div>
</div>

@endsection
