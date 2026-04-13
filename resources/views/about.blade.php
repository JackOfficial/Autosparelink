@extends('layouts.app')

@push('style')
<style>
    :root {
        --primary-dark: #1a202c;
        --secondary-dark: #2d3748;
        --accent-blue: #007bff;
    }

    .hero-wrapper {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        padding: 100px 0 140px;
        clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%);
        color: #fff;
    }

    [x-cloak] { display: none !important; }

    .value-box {
        padding: 30px;
        border: 2px solid transparent;
        border-radius: 20px;
        background: #fff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .value-box:hover {
        border-color: var(--accent-blue);
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }

    .icon-wrapper {
        width: 60px; height: 60px;
        background: rgba(0, 123, 255, 0.1);
        color: var(--accent-blue);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
        font-size: 24px;
    }

    .mt-overlap { margin-top: -80px; }
    
    .step-number {
        width: 40px; height: 40px;
        background: var(--accent-blue);
        color: white;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 15px;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .hero-wrapper { padding: 70px 0 100px; }
        .mt-overlap { margin-top: -40px; }
    }
</style>
@endpush

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">

    <header class="hero-wrapper text-center">
        <div class="container" x-show="loaded" x-transition.duration.800ms>
            <h1 class="display-4 font-weight-bold mb-3">Rwanda's Trusted Auto Marketplace</h1>
            <p class="lead opacity-75 mx-auto px-3" style="max-width: 750px;">
                The bridge between certified spare part vendors and vehicle owners. Quality parts, verified shops, and nationwide delivery.
            </p>
        </div>
    </header>

    <div class="container mt-overlap">
        <div class="row align-items-center mb-5 bg-white shadow-lg rounded p-4 p-md-5 mx-0 no-gutters">
            <div class="col-lg-6 mb-4 mb-lg-0 pr-lg-4 position-relative">
                <img src="{{ asset('frontend/img/part.png') }}" class="img-fluid rounded shadow-sm" alt="Auto Spare Marketplace">
            </div>
            <div class="col-lg-6 pl-lg-4">
                <span class="badge badge-primary px-3 py-2 text-uppercase mb-3">One Platform, Endless Options</span>
                <h2 class="font-weight-bold mb-4" style="color: var(--primary-dark);">The Smartest Way to Source Parts</h2>
                <p class="text-muted mb-4">
                    Why spend hours visiting different shops? <strong>AutoSpareLink</strong> brings together Rwanda's most reputable auto shops under one roof. Whether you are a car owner needing a specific engine part or a shop owner wanting to expand your reach, we provide the infrastructure to make it happen safely.
                </p>
                <div class="row">
                    <div class="col-6 mb-2"><i class="fa fa-store text-primary mr-2"></i> Verified Vendors</div>
                    <div class="col-6 mb-2"><i class="fa fa-truck text-primary mr-2"></i> Nationwide Delivery</div>
                    <div class="col-6 mb-2"><i class="fa fa-check-double text-primary mr-2"></i> Quality Checks</div>
                    <div class="col-6 mb-2"><i class="fa fa-shield-alt text-primary mr-2"></i> Buyer Protection</div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h2 class="font-weight-bold text-center mb-5">Buying Made Simple</h2>
            <div class="row">
                <div class="col-md-4 mb-4 text-center px-4">
                    <div class="step-number mx-auto">1</div>
                    <h5 class="font-weight-bold">Search & Compare</h5>
                    <p class="small text-muted">Browse thousands of parts from different shops. Filter by car model, year, and price.</p>
                </div>
                <div class="col-md-4 mb-4 text-center px-4">
                    <div class="step-number mx-auto">2</div>
                    <h5 class="font-weight-bold">Secure Order</h5>
                    <p class="small text-muted">Pay securely through local gateways. We hold your funds until you receive the correct part.</p>
                </div>
                <div class="col-md-4 mb-4 text-center px-4">
                    <div class="step-number mx-auto">3</div>
                    <h5 class="font-weight-bold">Fast Delivery</h5>
                    <p class="small text-muted">Our logistics team picks up the part from the vendor and delivers it to you, anywhere in Rwanda.</p>
                </div>
            </div>
        </div>

        <hr class="my-5">

        <div class="py-5 bg-light rounded-xl p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h2 class="font-weight-bold mb-4">Grow Your Shop with Us</h2>
                    <p class="text-muted mb-4">Are you a spare parts vendor? Join the digital revolution. Reach customers in provinces you've never reached before and let us handle the logistics while you focus on your inventory.</p>
                    <div class="row mb-4">
                        <div class="col-sm-6 mb-3">
                            <h6 class="font-weight-bold"><i class="fa fa-chart-line text-success mr-2"></i> Increased Sales</h6>
                            <p class="small text-muted">Get exposure to thousands of daily visitors across the country.</p>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <h6 class="font-weight-bold"><i class="fa fa-box text-success mr-2"></i> Easy Inventory</h6>
                            <p class="small text-muted">Simple dashboard to upload and manage your parts list.</p>
                        </div>
                    </div>
                    <div class="mt-4">
    @guest
        {{-- Show this to visitors who are not logged in --}}
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
            Register Today!
        </a>
    @else
    @if(auth()->user()->hasActiveShop() && auth()->user()->shop)
           <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
            <i class="fas fa-tachometer-alt mr-1"></i> Go into {{ auth()->user()->shop->shop_name }}
        </a>
    @else
      <a href="{{ url('/vendor/register') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
            <i class="fa fa-plus-circle mr-2"></i> Launch Your Shop
        </a>
    @endif
    @endguest
</div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <div class="p-4 bg-white shadow rounded-lg">
                        <i class="fa fa-store-alt fa-5x text-primary mb-3"></i>
                        <h4 class="font-weight-bold">Become a Vendor</h4>
                        <p class="small text-muted">Join the network of trusted Rwandan auto shops.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h2 class="font-weight-bold text-center mb-5">Why People Trust Us</h2>
            <div class="row">
                @php 
                    $values = [
                        ['icon' => 'certificate', 'title' => 'Vetted Excellence', 'desc' => 'We physically visit and verify every shop before they list on our platform.'],
                        ['icon' => 'hand-holding-usd', 'title' => 'Fair Pricing', 'desc' => 'Competitive market prices with no hidden fees for the buyer.'],
                        ['icon' => 'headset', 'title' => '24/7 Assistance', 'desc' => 'Our experts help you match the right part to your VIN number.']
                    ];
                @endphp
                @foreach($values as $v)
                <div class="col-md-4 mb-4">
                    <div class="value-box text-center shadow-sm h-100">
                        <div class="icon-wrapper"><i class="fa fa-{{ $v['icon'] }}"></i></div>
                        <h5 class="font-weight-bold">{{ $v['title'] }}</h5>
                        <p class="small text-muted mb-0">{{ $v['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection