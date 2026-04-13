@extends('layouts.app')

@push('style')
<style>
    :root {
        --primary-dark: #1a202c;
        --secondary-dark: #2d3748;
        --accent-blue: #007bff;
    }

    /* Hero Section with modern curve */
    .hero-wrapper {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--secondary-dark) 100%);
        padding: 100px 0 140px;
        clip-path: polygon(0 0, 100% 0, 100% 88%, 0 100%);
        color: #fff;
    }

    [x-cloak] { display: none !important; }

    /* Value Box Hover Effects */
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

    /* Team & Avatar Improvements */
    .team-avatar {
        width: 100%; height: 350px;
        object-fit: cover; border-radius: 20px;
        transition: transform 0.6s ease;
    }

    .team-item:hover .team-avatar { transform: scale(1.05); }

    .stat-badge {
        background: #ffc107;
        padding: 12px 20px;
        border-radius: 12px;
        position: absolute; bottom: -20px; left: 25px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    /* Bootstrap 4 Negative Margin Hack for Overlap */
    .mt-overlap { margin-top: -80px; }

    @media (max-width: 768px) {
        .hero-wrapper { padding: 70px 0 100px; clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%); }
        .mt-overlap { margin-top: -40px; }
        .display-4 { font-size: 2.5rem; }
    }
</style>
@endpush

@section('content')
<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">

    <header class="hero-wrapper text-center">
        <div class="container" x-show="loaded" x-transition.duration.800ms>
            <h1 class="display-4 font-weight-bold mb-3">Our Identity & Mission</h1>
            <p class="lead opacity-75 mx-auto px-3" style="max-width: 750px;">
                Providing Rwanda's automotive industry with precision-engineered parts and world-class service.
            </p>
        </div>
    </header>

    <div class="container mt-overlap">
        <div class="row align-items-center mb-5 bg-white shadow-lg rounded p-4 p-md-5 mx-0 no-gutters">
            <div class="col-lg-6 mb-4 mb-lg-0 pr-lg-4 position-relative">
                <img src="{{ asset('frontend/img/part.png') }}" class="img-fluid rounded shadow-sm" alt="Genuine Auto Parts">
                <div class="stat-badge d-none d-lg-block">
                    <h4 class="font-weight-bold mb-0 text-dark">100%</h4>
                    <p class="small text-uppercase font-weight-bold mb-0 text-dark">Genuine</p>
                </div>
            </div>
            <div class="col-lg-6 pl-lg-4">
                <span class="badge badge-primary px-3 py-2 text-uppercase mb-3">Established in Kigali</span>
                <h2 class="font-weight-bold mb-4" style="color: var(--primary-dark);">Your Partner for Every Mile</h2>
                <p class="text-muted mb-4">
                    At <strong>AutoSpareLink</strong>, we bridge the gap between global manufacturers and local vehicle owners. Our goal is to ensure your vehicle stays on the road longer.
                </p>
                <div class="row">
                    @php $features = ['Fast Shipping', 'Expert Help', 'Secure Payments', 'Easy Returns']; @endphp
                    @foreach($features as $feature)
                        <div class="col-6 mb-2">
                            <i class="fa fa-check-circle text-success mr-2"></i> {{ $feature }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="py-5" x-data="{ tab: 'mission' }">
            <div class="text-center mb-5">
                <div class="btn-group p-1 bg-light rounded-pill border shadow-sm">
                    <button type="button" class="btn rounded-pill px-5 py-2 transition" :class="tab === 'mission' ? 'btn-primary shadow' : 'btn-light'" @click="tab = 'mission'">Mission</button>
                    <button type="button" class="btn rounded-pill px-5 py-2 transition" :class="tab === 'vision' ? 'btn-primary shadow' : 'btn-light'" @click="tab = 'vision'">Vision</button>
                </div>
            </div>
            
            <div class="row justify-content-center min-vh-20">
                <div class="col-lg-8 text-center" x-show="tab === 'mission'" x-transition.fade>
                    <h3 class="font-weight-bold">Empowering Mobility</h3>
                    <p class="text-muted lead">To provide a seamless, transparent online shopping experience for auto spare parts in Rwanda.</p>
                </div>
                <div class="col-lg-8 text-center" x-show="tab === 'vision'" x-transition.fade x-cloak>
                    <h3 class="font-weight-bold">The Future of Spare Parts</h3>
                    <p class="text-muted lead">To become Africa’s most trusted and innovative auto parts marketplace.</p>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h2 class="font-weight-bold text-center mb-5">Our Core Values</h2>
            <div class="row">
                @php 
                    $values = [
                        ['icon' => 'award', 'title' => 'Unyielding Quality', 'desc' => 'Multi-point inspection protocol for safety.', 'active' => false],
                        ['icon' => 'shipping-fast', 'title' => 'Precision Delivery', 'desc' => 'Logistics network optimized for speed.', 'active' => true],
                        ['icon' => 'headset', 'title' => 'Dedicated Care', 'desc' => 'Experts ready to assist with VIN decoding.', 'active' => false]
                    ];
                @endphp
                @foreach($values as $v)
                <div class="col-md-4 mb-4">
                    <div class="value-box text-center shadow-sm h-100 {{ $v['active'] ? 'border-primary' : '' }}">
                        <div class="icon-wrapper {{ $v['active'] ? 'bg-primary text-white' : '' }}"><i class="fa fa-{{ $v['icon'] }}"></i></div>
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