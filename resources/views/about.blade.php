@extends('layouts.app')

@section('style')
<style>
    /* Premium Branding & Layout */
    .hero-wrapper {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
        padding: 80px 0 120px;
        clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        color: #fff;
    }

    /* Alpine.js Cloak to prevent flicker */
    [x-cloak] { display: none !important; }

    .card-modern {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        background: #fff;
    }

    .value-box {
        padding: 30px;
        border: 2px solid transparent;
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .value-box:hover {
        background: #f8fafc;
        border-color: #007bff;
        transform: translateY(-8px);
    }

    .icon-wrapper {
        width: 65px;
        height: 65px;
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 26px;
    }

    /* Team Styling */
    .team-avatar {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 20px;
        margin-bottom: 15px;
        transition: transform 0.5s ease;
    }

    .team-item:hover .team-avatar {
        transform: scale(1.03);
    }

    .stat-badge {
        background: #ffc107;
        padding: 15px 25px;
        border-radius: 12px;
        position: absolute;
        bottom: -25px;
        left: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
</style>
@endsection

@section('content')

<div x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 50)">

    <header class="hero-wrapper text-center">
        <div class="container" x-show="loaded" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 transform -translate-y-4">
            <h1 class="display-4 font-weight-bold mb-3">Our Identity & Mission</h1>
            <p class="lead opacity-75 mx-auto" style="max-width: 700px;">
                Providing Rwanda's automotive industry with precision-engineered parts and world-class service.
            </p>
        </div>
    </header>

    <div class="container" style="margin-top: -60px;">
        <div class="row align-items-center mb-5 bg-white shadow-lg rounded-xl p-4 p-lg-5 mx-0">
            <div class="col-lg-6 mb-4 mb-lg-0 position-relative">
                <img src="{{ asset('frontend/img/part.png') }}" class="img-fluid rounded-lg shadow" alt="Auto Parts">
                <div class="stat-badge d-none d-md-block">
                    <h4 class="font-weight-bold mb-0 text-dark">100%</h4>
                    <small class="text-uppercase font-weight-bold text-dark">Genuine Parts</small>
                </div>
            </div>
            <div class="col-lg-6 pl-lg-5">
                <h6 class="text-primary font-weight-bold text-uppercase tracking-wider mb-3">Trusted by Mechanics</h6>
                <h2 class="font-weight-bold mb-4" style="color: #1a202c;">Your Partner for Every Mile</h2>
                <p class="text-muted mb-4">
                    At <strong>AutoSpareLink</strong>, we bridge the gap between global manufacturers and local vehicle owners. Our goal is to ensure your vehicle stays on the road longer with certified components that meet rigorous safety standards.
                </p>
                <div class="row no-gutters">
                    <div class="col-6 mb-2"><i class="fa fa-check text-success mr-2"></i> Fast Shipping</div>
                    <div class="col-6 mb-2"><i class="fa fa-check text-success mr-2"></i> Expert Help</div>
                    <div class="col-6 mb-2"><i class="fa fa-check text-success mr-2"></i> Secure Payments</div>
                    <div class="col-6 mb-2"><i class="fa fa-check text-success mr-2"></i> Easy Returns</div>
                </div>
            </div>
        </div>

        <div class="py-5" x-data="{ tab: 'mission' }">
            <div class="text-center mb-5">
                <div class="btn-group p-1 bg-light rounded-pill shadow-sm">
                    <button class="btn rounded-pill px-4 py-2" :class="tab === 'mission' ? 'btn-primary shadow-sm' : 'btn-light'" @click="tab = 'mission'">Our Mission</button>
                    <button class="btn rounded-pill px-4 py-2" :class="tab === 'vision' ? 'btn-primary shadow-sm' : 'btn-light'" @click="tab = 'vision'">Our Vision</button>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" x-show="tab === 'mission'" x-transition>
                    <h3 class="font-weight-bold mb-3">Empowering Mobility</h3>
                    <p class="text-muted lead">To provide a seamless, transparent online shopping experience for auto spare parts, ensuring quality, affordability, and expert support for every maintenance need.</p>
                </div>
                <div class="col-lg-8 text-center" x-show="tab === 'vision'" x-transition x-cloak>
                    <h3 class="font-weight-bold mb-3">The Future of Spare Parts</h3>
                    <p class="text-muted lead">To become Africa’s most trusted and innovative auto parts marketplace, recognized for service excellence and technological reliability.</p>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h2 class="font-weight-bold text-center mb-5">Our Core Values</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="value-box text-center shadow-sm bg-white">
                        <div class="icon-wrapper"><i class="fa fa-award"></i></div>
                        <h5 class="font-weight-bold">Unyielding Quality</h5>
                        <p class="small text-muted mb-0">We only supply parts that pass our multi-point inspection protocol for safety and longevity.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="value-box text-center shadow-sm bg-white border-primary">
                        <div class="icon-wrapper bg-primary text-white"><i class="fa fa-shipping-fast"></i></div>
                        <h5 class="font-weight-bold">Precision Delivery</h5>
                        <p class="small text-muted mb-0">Our logistics network is optimized for speed, ensuring your parts arrive exactly when promised.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="value-box text-center shadow-sm bg-white">
                        <div class="icon-wrapper"><i class="fa fa-headset"></i></div>
                        <h5 class="font-weight-bold">Dedicated Care</h5>
                        <p class="small text-muted mb-0">Our automotive experts are just a call away to help you identify the correct part for your VIN.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <h2 class="font-weight-bold text-center mb-5">Meet the Leadership</h2>
            <div class="row">
                <div class="col-md-4 col-lg-3 mb-4 team-item">
                    <div class="position-relative overflow-hidden rounded-xl shadow-sm">
                        <img src="{{ asset('frontend/img/parts.jpg') }}" class="team-avatar" alt="CEO">
                    </div>
                    <div class="mt-3 text-center">
                        <h6 class="font-weight-bold mb-0">Alain Dushimimana</h6>
                        <small class="text-primary text-uppercase font-weight-bold">Founder & CEO</small>
                    </div>
                </div>
                </div>
        </div>

        <div class="bg-dark rounded-xl p-4 p-md-5 text-white mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="font-weight-bold text-white">Visit Our Headquarters</h3>
                    <p class="text-white-50 mb-4">Come talk to our specialists or pick up your orders directly from our Kigali hub.</p>
                    <div class="d-flex mb-3"><i class="fa fa-map-marker-alt text-warning mt-1 mr-3"></i> Gisozi, 33P7+5HW, Kigali</div>
                    <div class="d-flex mb-3"><i class="fa fa-envelope text-warning mt-1 mr-3"></i> info@autosparelink.com</div>
                    <div class="d-flex"><i class="fa fa-phone-alt text-warning mt-1 mr-3"></i> +250 788 430 122</div>
                </div>
                <div class="col-lg-6">
                    <div class="rounded-lg overflow-hidden shadow" style="filter: grayscale(100%) invert(90%);">
                        <iframe style="width:100%; height:300px; border:0;" src="http://googleusercontent.com/maps.google.com/2" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection