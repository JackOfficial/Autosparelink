@extends('layouts.app')

@section('title', 'How to Choose the Right Spare Parts for Your Car | AutoSpareLink')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <a class="breadcrumb-item text-dark" href="/blogs">Blog</a>
                <span class="breadcrumb-item active">How to Choose the Right Spare Parts</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Blog Post Start -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- Sidebar Start -->
        <div class="col-lg-3 col-md-4">

            <!-- Search -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Search</span>
                </h5>
                <form>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search blog...">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Categories -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Categories</span>
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a class="text-dark" href="#">Car Maintenance</a></li>
                    <li class="mb-2"><a class="text-dark" href="#">Spare Parts</a></li>
                    <li class="mb-2"><a class="text-dark" href="#">Auto Tips</a></li>
                    <li><a class="text-dark" href="#">Industry News</a></li>
                </ul>
            </div>

            <!-- Recent Posts -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Recent Posts</span>
                </h5>

                @for ($i = 1; $i <= 4; $i++)
                <div class="media mb-3">
                    <img src="{{ asset('frontend/img/parts.jpg') }}" class="mr-3" style="width: 80px; height: 60px; object-fit: cover;">
                    <div class="media-body">
                        <a class="text-dark" href="#">
                            <h6 class="mt-0 text-truncate">Sample Blog Title {{ $i }}</h6>
                        </a>
                        <small class="text-muted">
                            <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                        </small>
                    </div>
                </div>
                @endfor
            </div>

        </div>
        <!-- Sidebar End -->

        <!-- Blog Content Start -->
        <div class="col-lg-9 col-md-8">

            <div class="bg-light p-4 mb-30">

                <!-- Featured Image -->
                <img src="{{ asset('frontend/img/part.png') }}"
                     alt="How to choose spare parts"
                     class="img-fluid w-100 mb-4"
                     style="max-height: 420px; object-fit: cover;">

                <!-- Title -->
                <h1 class="mb-3 font-weight-bold">
                    How to Choose the Right Spare Parts for Your Car
                </h1>

                <!-- Meta -->
                <div class="d-flex flex-wrap text-muted mb-4" style="font-size: 14px;">
                    <span class="mr-3">
                        <i class="fa fa-user text-primary mr-1"></i> AutoSpareLink Team
                    </span>
                    <span class="mr-3">
                        <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                    </span>
                    <span>
                        <i class="fa fa-folder text-primary mr-1"></i> Spare Parts
                    </span>
                </div>

                <!-- Content -->
                <div class="blog-content text-dark" style="line-height: 1.8; font-size: 15px;">

                    <p>
                        Choosing the right spare parts for your vehicle is essential for performance,
                        safety, and long-term reliability. At <strong>AutoSpareLink</strong>, we help
                        drivers find genuine and high-quality aftermarket parts with confidence.
                    </p>

                    <h4 class="mt-4">1. Understand OEM vs Aftermarket Parts</h4>
                    <p>
                        OEM (Original Equipment Manufacturer) parts are made by the same manufacturer
                        that produced the original parts in your car. Aftermarket parts are produced
                        by third-party companies and often offer more affordable alternatives.
                    </p>

                    <h4 class="mt-4">2. Verify Compatibility</h4>
                    <p>
                        Always ensure the part number matches your vehicle’s make, model, and year.
                        Incorrect parts can cause serious mechanical issues and unnecessary costs.
                    </p>

                    <h4 class="mt-4">3. Buy from Trusted Suppliers</h4>
                    <p>
                        Purchasing spare parts from a trusted supplier like AutoSpareLink ensures
                        authenticity, warranty support, and professional guidance.
                    </p>

                    <blockquote class="blockquote bg-white p-3 border-left border-primary my-4">
                        <p class="mb-0 font-italic">
                            “The right spare part saves you money, time, and stress in the long run.”
                        </p>
                    </blockquote>

                    <p>
                        Whether you are a professional mechanic or a car owner, making informed
                        decisions when buying spare parts will keep your vehicle running smoothly.
                    </p>

                </div>

                <!-- Tags -->
                <div class="mt-4">
                    <strong>Tags:</strong>
                    <a href="#" class="badge badge-secondary ml-1">Spare Parts</a>
                    <a href="#" class="badge badge-secondary ml-1">OEM</a>
                    <a href="#" class="badge badge-secondary ml-1">Car Maintenance</a>
                </div>

            </div>

            <!-- Related Posts -->
            <div class="bg-light p-4">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">Related Posts</span>
                </h4>

                <div class="row">
                    @for ($i = 1; $i <= 3; $i++)
                    <div class="col-md-4">
                        <div class="bg-white shadow-sm mb-4 hover-shadow">
                            <img src="{{ asset('frontend/img/part.png') }}"
                                 class="img-fluid w-100"
                                 style="height: 180px; object-fit: cover;">
                            <div class="p-3">
                                <a href="#" class="h6 d-block text-dark text-decoration-none">
                                    Related Blog Title {{ $i }}
                                </a>
                                <small class="text-muted">
                                    <i class="fa fa-calendar text-primary mr-1"></i> 10 Dec, 2025
                                </small>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

        </div>
        <!-- Blog Content End -->

    </div>
</div>
<!-- Blog Post End -->

<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
</style>

@endsection
