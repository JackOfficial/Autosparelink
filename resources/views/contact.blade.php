@extends('layouts.app')
@section('content')
           <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30">
                    <a class="breadcrumb-item text-dark" href="#">Home</a>
                    <span class="breadcrumb-item active">Contact</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Contact Start -->
    <div class="container-fluid">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Contact Us</span></h2>
        <div class="row px-xl-5">
            <div class="col-lg-7 mb-5">
               <livewire:contact-component />
            </div>
            <div class="col-lg-5 mb-5">
                <div class="bg-light p-30 mb-30">
                    <iframe style="width: 100%; height: 250px;"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d498.4488985926045!2d30.063701159764786!3d-1.914494903295142!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca3e2fc8f48c5%3A0x38b1db6f529abe0e!2sGisozi%20Sector%20Office!5e0!3m2!1sen!2srw!4v1765296751852!5m2!1sen!2srw"
                    frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
                <div class="bg-light p-30 mb-3">
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>Gisozi, 33P7+5HW, Kigali</p>
                    <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@autosparelink.com</p>
                    <p class="mb-2"><i class="fa fa-phone-alt text-primary mr-3"></i>+250 788 430 122</p>
                </div>
            </div>
        </div>
     </div>
    <!-- Contact End -->
@endsection