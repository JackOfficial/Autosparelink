@extends('layouts.app')

@section('style')
<style>
    /* -------------------------------------------
       GLOBAL STYLES
    -------------------------------------------- */
    .section-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 35px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        margin-bottom: 40px;
    }

    .section-title-custom {
        font-weight: 700;
        font-size: 26px;
        position: relative;
        margin-bottom: 30px;
    }

    .section-title-custom::after {
        content: "";
        width: 60px;
        height: 3px;
        background: #007bff;
        position: absolute;
        left: 0;
        bottom: -8px;
        border-radius: 5px;
    }

    .highlight-text {
        font-weight: 600;
        color: #0056b3;
    }

    .about-img {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    /* -------------------------------------------
       VALUES CARDS
    -------------------------------------------- */
    .value-card {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 25px;
        transition: all .3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .value-card:hover {
        transform: translateY(-5px);
        background: #eef7ff;
    }
    .value-card i {
        margin-bottom: 8px;
    }

    /* -------------------------------------------
       TEAM
    -------------------------------------------- */
    .team-card img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    /* -------------------------------------------
       RELATED PAGES
    -------------------------------------------- */
    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid px-xl-5 mt-3">
    <div class="row">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-3 rounded">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active">About Us</span>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid px-xl-5">

    <!-- About Us Header -->
    <div class="section-card">
        <h2 class="section-title-custom">About Us</h2>

        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img class="img-fluid about-img" src="{{ asset('frontend/img/part.png') }}" alt="About AutoSpareLink">
            </div>

            <div class="col-lg-6">
                <h3 class="fw-bold mb-3">Your Trusted Partner for Quality Auto Spare Parts</h3>
                <p>
                    At <strong class="highlight-text">AutoSpareLink</strong>, our goal is simple —  
                    <span class="highlight-text">to deliver genuine, high-performance spare parts</span> that keep your vehicle running smoothly and safely.  
                    From engine components to lighting, braking systems, suspension, and accessories, we offer a wide range of trusted brands at competitive prices.
                </p>

                <p>
                    Backed by years of experience in the automotive sector, our team is dedicated to providing  
                    <strong>reliable service, fast delivery, and expert assistance</strong> for every customer.  
                    Whether you're a mechanic, car owner, or parts dealer, we are here to help you find exactly what you need.
                </p>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="section-card">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h4 class="section-title-custom">Our Mission</h4>
                <p>
                    To provide a seamless online shopping experience for auto spare parts, ensuring  
                    <strong>quality, affordability, fast delivery, and expert support</strong> for all vehicle maintenance needs.
                </p>
            </div>

            <div class="col-lg-6">
                <h4 class="section-title-custom">Our Vision</h4>
                <p>
                    To become the region’s most trusted and innovative online auto parts marketplace,  
                    known for <strong>top-tier reliability, service excellence, and customer satisfaction.</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Core Values -->
    <div class="section-card">
        <h4 class="section-title-custom">Our Core Values</h4>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="value-card text-center h-100">
                    <i class="fa fa-cogs fa-3x text-primary"></i>
                    <h5 class="mt-3 mb-2">Quality</h5>
                    <p>We deliver only certified and thoroughly inspected products that meet strict safety standards.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="value-card text-center h-100">
                    <i class="fa fa-truck fa-3x text-primary"></i>
                    <h5 class="mt-3 mb-2">Fast Delivery</h5>
                    <p>With a strong logistics network, we ensure timely and dependable delivery for every order.</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="value-card text-center h-100">
                    <i class="fa fa-headset fa-3x text-primary"></i>
                    <h5 class="mt-3 mb-2">Customer Care</h5>
                    <p>Our experts are available to guide you in picking the most suitable parts for your vehicle.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team -->
    <div class="section-card">
        <h4 class="section-title-custom">Meet Our Team</h4>

        <div class="row text-center">
            <div class="col-md-3 mb-4 team-card">
                <img src="{{ asset('frontend/img/parts.jpg') }}" alt="Team Member">
                <h6 class="mt-3">Rahamatali Alain Dushimimana</h6>
                <p class="text-muted">Founder & CEO</p>
            </div>

            <div class="col-md-3 mb-4 team-card">
                <img src="{{ asset('frontend/img/part.png') }}" alt="Team Member">
                <h6 class="mt-3">Jane Smith</h6>
                <p class="text-muted">Operations Manager</p>
            </div>

            <div class="col-md-3 mb-4 team-card">
                <img src="{{ asset('frontend/img/parts.jpg') }}" alt="Team Member">
                <h6 class="mt-3">Mike Johnson</h6>
                <p class="text-muted">Logistics & Support</p>
            </div>

            <div class="col-md-3 mb-4 team-card">
                <img src="{{ asset('frontend/img/part.png') }}" alt="Team Member">
                <h6 class="mt-3">Sarah Lee</h6>
                <p class="text-muted">Customer Relations</p>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="section-card">
        <h4 class="section-title-custom">Contact Us</h4>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <p class="mb-3"><i class="fa fa-map-marker-alt text-primary mr-2"></i> Gisozi, 33P7+5HW, Kigali</p>
                <p class="mb-3"><i class="fa fa-envelope text-primary mr-2"></i> info@autospareparts.com</p>
                <p class="mb-3"><i class="fa fa-phone-alt text-primary mr-2"></i> +250 788 430 122</p>
            </div>

            <div class="col-lg-6">
                <iframe style="width:100%; height:260px; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1);"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d498.4488985926045!2d30.063701159764786!3d-1.914494903295142!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca3e2fc8f48c5%3A0x38b1db6f529abe0e!2sGisozi%20Sector%20Office!5e0!3m2!1sen!2srw!4v1765296751852!5m2!1sen!2srw"
                    frameborder="0" allowfullscreen="" aria-hidden="false" tabindex="0">
                </iframe>
            </div>
        </div>
    </div>

    <!-- Related Pages Start -->
    <div class="section-card">
        <h5 class="mb-3 text-uppercase">Related Pages</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <a class="text-decoration-none">
                    <div class="card hover-shadow border-0 text-center p-3">
                        <i class="fa fa-info-circle fa-2x mb-2 text-primary"></i>
                        <h6 class="mb-0">About Us</h6>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="/terms-and-conditions" class="text-decoration-none">
                    <div class="card hover-shadow border-0 text-center p-3">
                        <i class="fa fa-file fa-2x mb-2 text-primary"></i>
                        <h6 class="mb-0">Terms & Conditions</h6>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="/policies" class="text-decoration-none">
                    <div class="card hover-shadow border-0 text-center p-3">
                        <i class="fa fa-shield-alt fa-2x mb-2 text-primary"></i>
                        <h6 class="mb-0">Policies</h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- Related Pages End -->

</div>
@endsection
