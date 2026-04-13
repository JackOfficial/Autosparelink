@extends('layouts.app')

@section('style')
<style>
    /* Modern Branding & Hero */
    .about-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('frontend/img/parts-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        padding: 80px 0;
        color: #fff;
        margin-bottom: 50px;
        border-radius: 0 0 50px 50px;
    }

    .section-spacing { padding: 60px 0; }

    /* Clean Card Aesthetic */
    .glass-card {
        background: #ffffff;
        border: 1px solid #f0f0f0;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    /* Icon Styling */
    .icon-box {
        width: 80px;
        height: 80px;
        background: #eef7ff;
        color: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 30px;
    }

    /* Team Section Revamp */
    .team-item {
        transition: 0.5s;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
    }
    .team-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .team-img-container {
        position: relative;
        overflow: hidden;
    }
    .team-img-container img {
        transition: 0.5s;
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    /* Stats Section */
    .stat-number {
        font-size: 40px;
        font-weight: 800;
        color: #007bff;
        display: block;
    }

    /* Map Styling */
    .map-container {
        filter: grayscale(100%) invert(92%) contrast(83%);
        border-radius: 20px;
        overflow: hidden;
    }
</style>
@endsection

@section('content')

<div class="about-hero text-center">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Our Story</h1>
        <p class="lead">Connecting vehicle owners with quality engineering since 2024.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row align-items-center section-spacing">
        <div class="col-lg-6 pr-lg-5">
            <h6 class="text-primary text-uppercase font-weight-bold mb-3">Who We Are</h6>
            <h2 class="mb-4 font-weight-bold" style="color: #2d3748;">Your Trusted Partner for Quality Auto Spare Parts</h2>
            <p class="text-muted mb-4">At <strong>AutoSpareLink</strong>, we don't just sell parts; we provide reliability. We bridge the gap between global manufacturers and local needs, ensuring your vehicle never misses a mile.</p>
            
            <div class="row">
                <div class="col-sm-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle text-success mr-2"></i>
                        <span class="font-weight-bold">Genuine OEM Parts</span>
                    </div>
                </div>
                <div class="col-sm-6 mb-3">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle text-success mr-2"></i>
                        <span class="font-weight-bold">Expert Support</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <img class="img-fluid rounded-lg shadow-lg" src="{{ asset('frontend/img/part.png') }}" alt="AutoSpareLink Gallery">
                <div class="bg-primary p-4 rounded text-white position-absolute shadow" style="bottom: -20px; right: 20px; max-width: 200px;">
                    <span class="stat-number text-white">10k+</span>
                    <span class="small">Parts Available</span>
                </div>
            </div>
        </div>
    </div>

    <div class="section-spacing text-center bg-light rounded-xl px-4">
        <h2 class="font-weight-bold mb-5">Our Core Values</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="glass-card p-4 h-100 shadow-sm">
                    <div class="icon-box"><i class="fa fa-cogs"></i></div>
                    <h5 class="font-weight-bold">Quality First</h5>
                    <p class="text-muted small">Every component undergoes rigorous testing to meet international safety standards.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="glass-card p-4 h-100 shadow-sm">
                    <div class="icon-box"><i class="fa fa-shipping-fast"></i></div>
                    <h5 class="font-weight-bold">Global Reach</h5>
                    <p class="text-muted small">Fast, trackable, and secure delivery network spanning across the region.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="glass-card p-4 h-100 shadow-sm">
                    <div class="icon-box"><i class="fa fa-headset"></i></div>
                    <h5 class="font-weight-bold">Expert Care</h5>
                    <p class="text-muted small">Specialized technical team ready to assist with part compatibility and fitment.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="section-spacing text-center">
        <h2 class="font-weight-bold mb-5">The Leadership Team</h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="team-item shadow-sm border">
                    <div class="team-img-container">
                        <img src="{{ asset('frontend/img/parts.jpg') }}" alt="">
                    </div>
                    <div class="p-4">
                        <h6 class="font-weight-bold mb-1">R. Alain Dushimimana</h6>
                        <small class="text-primary text-uppercase">Founder & CEO</small>
                    </div>
                </div>
            </div>
            </div>
    </div>

    <div class="row section-spacing align-items-center">
        <div class="col-lg-5 mb-5 mb-lg-0">
            <div class="bg-dark p-5 rounded-lg text-white shadow-lg">
                <h3 class="font-weight-bold text-white mb-4">Get In Touch</h3>
                <div class="d-flex mb-3">
                    <i class="fa fa-map-marker-alt text-primary mt-1 mr-3"></i>
                    <p class="mb-0">Gisozi, 33P7+5HW, Kigali, Rwanda</p>
                </div>
                <div class="d-flex mb-3">
                    <i class="fa fa-envelope text-primary mt-1 mr-3"></i>
                    <p class="mb-0">info@autosparelink.com</p>
                </div>
                <div class="d-flex">
                    <i class="fa fa-phone-alt text-primary mt-1 mr-3"></i>
                    <p class="mb-0">+250 788 430 122</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="map-container shadow border">
                <iframe style="width:100%; height:350px; border:0;"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15950.116526135314!2d30.051911!3d-1.939223!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMcKwNTYnMzEuMiJTIDMwwrAwMycwNi45IkU!5e0!3m2!1sen!2srw!4v123456789" 
                    allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection