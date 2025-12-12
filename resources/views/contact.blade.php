@extends('layouts.app')
@section('content')

<!-- =======================
       BREADCRUMB
=========================== -->
<div class="container-fluid">
    <div class="row px-xl-5 mt-3">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <span class="breadcrumb-item active">Contact</span>
            </nav>
        </div>
    </div>
</div>

<!-- =======================
       PAGE TITLE
=========================== -->
<div class="container-fluid">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Contact Us</span>
    </h2>

    <div class="row px-xl-5">

        <!-- ========== LEFT SIDE FORM ========== -->
        <div class="col-lg-7 mb-5">
            <div class="bg-white p-4 shadow-sm rounded">
                <h4 class="text-dark mb-3">Get In Touch</h4>
                <p class="text-muted mb-4">
                    Have a question about spare parts? Need help with an order?
                    Our support team is available and ready to assist you.
                </p>

                <livewire:contact-component />

                <p class="text-muted mt-3">
                    *Our team usually responds within <strong>1–3 hours</strong>.
                </p>
            </div>
        </div>

        <!-- ========== RIGHT SIDE INFO ========== -->
        <div class="col-lg-5 mb-5">

            <!-- MAP -->
            <div class="bg-light shadow-sm rounded overflow-hidden mb-4">
                <iframe
                    class="w-100"
                    height="260"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d498.4488985926045!2d30.063701159764786!3d-1.914494903295142!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca3e2fc8f48c5%3A0x38b1db6f529abe0e!2sGisozi%20Sector%20Office!5e0!3m2!1sen!2srw!4v1765296751852!5m2!1sen!2srw"
                    frameborder="0"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    aria-hidden="false"
                    tabindex="0">
                </iframe>
            </div>

            <!-- CONTACT INFO CARDS -->
            <div class="bg-white shadow-sm p-4 rounded mb-4">
                <h5 class="mb-3"><i class="fa fa-info-circle text-primary mr-2"></i>Contact Details</h5>

                <div class="d-flex align-items-start mb-3">
                    <i class="fa fa-map-marker-alt text-primary fs-4 mr-3"></i>
                    <p class="mb-0 text-muted">Gisozi, 33P7+5HW, Kigali Rwanda</p>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <i class="fa fa-envelope text-primary fs-4 mr-3"></i>
                    <p class="mb-0 text-muted">info@autosparelink.com</p>
                </div>

                <div class="d-flex align-items-start">
                    <i class="fa fa-phone-alt text-primary fs-4 mr-3"></i>
                    <p class="mb-0 text-muted">+250 788 430 122</p>
                </div>
            </div>

            <!-- QUICK LINKS / FAQ -->
            <div class="bg-white shadow-sm p-4 rounded">
                <h5 class="mb-3"><i class="fa fa-question-circle text-primary mr-2"></i>Quick Help</h5>

                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-dark">
                            <i class="fa fa-angle-right text-primary mr-2"></i>FAQ (Frequently Asked Questions)
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-dark">
                            <i class="fa fa-angle-right text-primary mr-2"></i>Order Tracking
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-dark">
                            <i class="fa fa-angle-right text-primary mr-2"></i>Shipping & Delivery Info
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-dark">
                            <i class="fa fa-angle-right text-primary mr-2"></i>Return & Refund Policy
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <!-- =======================
        WHY CONTACT US SECTION
    =========================== -->
    <div class="row px-xl-5 mb-5">
        <div class="col-12">
            <div class="bg-white shadow-sm rounded p-4">
                <h4 class="mb-3 text-dark text-center">Why Contact Us?</h4>

                <div class="row text-center">
                    <div class="col-md-4 mb-4">
                        <i class="fa fa-cogs text-primary fs-1 mb-3"></i>
                        <h6 class="text-dark">Expert Spare Parts Support</h6>
                        <p class="text-muted small">Our team helps you find the exact part compatible with your vehicle.</p>
                    </div>

                    <div class="col-md-4 mb-4">
                        <i class="fa fa-shipping-fast text-primary fs-1 mb-3"></i>
                        <h6 class="text-dark">Fast Response Time</h6>
                        <p class="text-muted small">We aim to respond within a few hours—no long waiting!</p>
                    </div>

                    <div class="col-md-4 mb-4">
                        <i class="fa fa-shield-alt text-primary fs-1 mb-3"></i>
                        <h6 class="text-dark">Trusted & Transparent</h6>
                        <p class="text-muted small">We value honesty and provide clear information for every request.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
