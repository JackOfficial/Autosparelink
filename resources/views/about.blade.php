@extends('layouts.app')
@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active">About Us</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- About Us Start -->
<div class="container-fluid">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">About Us</span>
    </h2>

    <!-- Company Overview -->
    <div class="row px-xl-5 mb-5">
        <div class="col-lg-6">
            <img class="img-fluid rounded" src="{{ asset('frontend/img/about-us.jpg') }}" alt="About AutoSpareLink">
        </div>
        <div class="col-lg-6">
            <h3 class="mb-3">Your Trusted Partner for Quality Auto Spare Parts</h3>
            <p>At <strong>AutoSpareLink</strong>, we are dedicated to delivering the highest quality spare parts for all types of vehicles. From engine components to brakes, lights, and accessories, we provide a wide range of genuine and aftermarket products at competitive prices. Our mission is to make auto maintenance and repairs easy, affordable, and reliable for every customer.</p>
            <p>With years of experience in the automotive industry, our team is passionate about vehicles and committed to offering exceptional customer service, fast delivery, and professional advice to help you find the right parts for your car.</p>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="row px-xl-5 mb-5">
        <div class="col-lg-6">
            <h4 class="mb-3">Our Mission</h4>
            <p>We aim to provide customers with a seamless online shopping experience for auto spare parts, offering fast delivery, high-quality products, and expert guidance for all vehicle maintenance needs.</p>
        </div>
        <div class="col-lg-6">
            <h4 class="mb-3">Our Vision</h4>
            <p>To be the leading online auto parts marketplace in the region, recognized for reliability, quality, and innovation, helping drivers keep their vehicles running safely and efficiently.</p>
        </div>
    </div>

    <!-- Core Values -->
    <div class="row px-xl-5 mb-5">
        <div class="col-12">
            <h4 class="mb-4">Core Values</h4>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-light p-4 h-100 text-center">
                <i class="fa fa-cogs fa-2x text-primary mb-2"></i>
                <h5>Quality</h5>
                <p>We ensure all our products meet rigorous quality standards for safety and performance.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-light p-4 h-100 text-center">
                <i class="fa fa-truck fa-2x text-primary mb-2"></i>
                <h5>Fast Delivery</h5>
                <p>Our efficient logistics ensure you get the parts you need, when you need them.</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-light p-4 h-100 text-center">
                <i class="fa fa-headset fa-2x text-primary mb-2"></i>
                <h5>Customer Support</h5>
                <p>Expert assistance to guide you in choosing the right part for your vehicle.</p>
            </div>
        </div>
    </div>

    <!-- Team Highlight -->
    <div class="row px-xl-5 mb-5">
        <div class="col-12">
            <h4 class="mb-4">Meet Our Team</h4>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <img src="{{ asset('frontend/img/team-1.jpg') }}" class="img-fluid rounded-circle mb-2" alt="Team Member">
            <h6>Rahamatali Alain Dushimimana</h6>
            <p class="text-muted">Founder & CEO</p>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <img src="{{ asset('frontend/img/team-2.jpg') }}" class="img-fluid rounded-circle mb-2" alt="Team Member">
            <h6>Jane Smith</h6>
            <p class="text-muted">Operations Manager</p>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <img src="{{ asset('frontend/img/team-3.jpg') }}" class="img-fluid rounded-circle mb-2" alt="Team Member">
            <h6>Mike Johnson</h6>
            <p class="text-muted">Logistics & Support</p>
        </div>
        <div class="col-md-3 mb-3 text-center">
            <img src="{{ asset('frontend/img/team-4.jpg') }}" class="img-fluid rounded-circle mb-2" alt="Team Member">
            <h6>Sarah Lee</h6>
            <p class="text-muted">Customer Relations</p>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="row px-xl-5">
        <div class="col-lg-6 mb-5">
            <div class="bg-light p-30">
                <h5>Contact Information</h5>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Gisozi, 33P7+5HW, Kigali</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@autospareparts.com</p>
                <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>+250 788 430 122</p>
            </div>
        </div>
        <div class="col-lg-6 mb-5">
            <iframe style="width: 100%; height: 250px;"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d498.4488985926045!2d30.063701159764786!3d-1.914494903295142!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca3e2fc8f48c5%3A0x38b1db6f529abe0e!2sGisozi%20Sector%20Office!5e0!3m2!1sen!2srw!4v1765296751852!5m2!1sen!2srw"
            frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </div>
</div>
<!-- About Us End -->

@endsection
