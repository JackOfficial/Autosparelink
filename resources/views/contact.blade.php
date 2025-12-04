@extends('layouts.app')
@section('content')
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('storage/headers/team.jpg') }});
        background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Contact Us</h1>
                <p class="fs-5 text-white mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active text-white">Contact</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

        <!-- Contact Start -->
        <div class="container-fluid bg-light py-5">
            <div class="container py-5">
                <div class="contact p-5">
                    <div class="row g-4">
                        <div class="col-xl-5">
                            <h1 class="mb-4">Get in touch</h1>
                            <p class="mb-4">
                                For any inquiry, don't hesitate to reach out to us via below contact address.
                            </p>
                            <livewire:contact-component />
                        </div>
                        <div class="col-xl-7">
                            <div>
                                <div class="row g-4">
                                    <div class="col-lg-4">
                                        <div class="bg-white p-4">
                                            <i class="fas fa-map-marker-alt fa-2x text-primary mb-2"></i>
                                            <h4>Address</h4>
                                            <p class="mb-0">1 KN 78 St, Kigali - Nyarugenge, Norrsken</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="bg-white p-4">
                                            <i class="fas fa-envelope fa-2x text-primary mb-2"></i>
                                            <h4>Mail Us</h4>
                                            <p class="mb-0">info@hfro.org</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="bg-white p-4">
                                            <i class="fa fa-phone-alt fa-2x text-primary mb-2"></i>
                                            <h4>Telephone</h4>
                                            <p class="mb-0">(+250) 788708314</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d63800.78981807256!2d30.035565795219192!3d-1.932389900791949!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d-1.9170551!2d30.088831799999998!4m5!1s0x19dca5a86d814c61%3A0x7d3b83e12b1c11a9!2snorrsken%20kigali!3m2!1d-1.9511728!2d30.0599867!5e0!3m2!1sen!2srw!4v1699281438213!5m2!1sen!2srw" class="w-100" style="height: 412px; margin-bottom: -6px;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->

@endsection