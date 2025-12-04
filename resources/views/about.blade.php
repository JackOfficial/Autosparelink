@extends('layouts.app')
@section('content')
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('images/about.jpg') }});
background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">Welcome to Happy Family Rwanda Organization (HFRO)</h1>
        <p class="fs-5 text-white mb-4">Together, we are building a healthy, educated, and self-reliant Rwanda.</p>
    </div>
    
</div>
<!-- Header End -->

 <div class="container-fluid py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                <h5 class="text-uppercase text-primary">Our Mission</h5>
                <h1 class="mb-0 d-none">Our Mission</h1>
            </div>
            <div class="row g-4">
               <div class="col-md-6 col-lg-6 col-xl-6">
                  <img scr="{{ asset('images/about.jpg') }}" alt="About Us" class="w-100">
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                    <p>At Happy Family Rwanda Organization (HFRO), we believe that strong families build strong nations.</p>
                    <p>Our mission is to promote health, education, social well-being, and economic empowerment for all Rwandans.</p>
                    <p>
                        We work hand in hand with communities to fight poverty, improve access to education and healthcare, and promote gender equality.
                    </p>
                    <p>
                        Through compassion, innovation, and collaboration, we aim to create lasting change and transform lives — one family at a time.
                    </p>
                </div>   
           
            </div>
        </div>
    </div>
    
     <div class="container-fluid py-2 bg-light">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                <h5 class="text-uppercase text-primary">Our Vision</h5>
                <h1 class="mb-0 d-none">Our Mission</h1>
            </div>
            <div class="row g-4">
               <div class="col-md-6 col-lg-6 col-xl-6">
                      <p>Happy Family vision is to build a community where youth, women and communities are empowered to innovate and achieve socio-economic transformation.</p>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <img scr="{{ asset('images/about.jpg') }}" alt="About Us" class="w-100">
                </div>   
           
            </div>
        </div>
    </div>
    <div class="container-fluid py-2 bg-light">
       <div class="text-center">
           Together, we can make Rwanda a nation of healthy, empowered, and happy families.
       </div> 
        
    </div>
@endsection