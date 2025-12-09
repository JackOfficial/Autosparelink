@extends('layouts.app')
@section('content')
<!-- Carousel Start -->
<div class="container-fluid mb-3">
    <div class="row px-xl-5">
        <div class="col-lg-8">
            <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#header-carousel" data-slide-to="0" class="active"></li>
                    <li data-target="#header-carousel" data-slide-to="1"></li>
                    <li data-target="#header-carousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <!-- Engine Parts Slide -->
                    <div class="carousel-item position-relative active" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="{{ asset('frontend/img/Engine Parts.jpg') }}" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Top Engine Parts</h1>
                                <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                    Find quality engine parts for your car, from filters and belts to pistons and spark plugs. Reliable, fast, and affordable.
                                </p>
                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="#">Shop Now</a>
                            </div>
                        </div>
                    </div>

                    <!-- Brakes & Suspension Slide -->
                    <div class="carousel-item position-relative" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="{{ asset('frontend/img/Brakes & Suspension.jpg') }}" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Brakes & Suspension</h1>
                                <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                    Upgrade your car’s braking and suspension system with high-quality discs, pads, shocks, and struts from trusted brands.
                                </p>
                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="#">Shop Now</a>
                            </div>
                        </div>
                    </div>

                    <!-- Accessories & Car Care Slide -->
                    <div class="carousel-item position-relative" style="height: 430px;">
                        <img class="position-absolute w-100 h-100" src="{{ asset('frontend/img/Accessories & Car Care.jpg') }}" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">Accessories & Car Care</h1>
                                <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                    Keep your vehicle in top shape with premium car accessories, detailing kits, and maintenance tools designed for all car models.
                                </p>
                                <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotional Offers -->
        <div class="col-lg-4">
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ asset('frontend/img/Engine Parts sales.jpg') }}" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Save 15%</h6>
                    <h3 class="text-white mb-3">Engine Parts Sale</h3>
                    <a href="#" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
            <div class="product-offer mb-30" style="height: 200px;">
                <img class="img-fluid" src="{{ asset('frontend/img/Brake & Suspension Deals.jpg') }}" alt="">
                <div class="offer-text">
                    <h6 class="text-white text-uppercase">Up to 20% Off</h6>
                    <h3 class="text-white mb-3">Brake & Suspension Deals</h3>
                    <a href="" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Carousel End -->

    <!-- Featured Start -->
<div class="container-fluid py-5 bg-light">
    <div class="row px-xl-5 gy-4">

        <!-- Quality Product -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="feature-card d-flex align-items-center p-4 bg-white border rounded shadow-sm hover-shadow">
                <div class="feature-icon text-primary d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px;">
                    <i class="fa fa-check fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Genuine Quality</h6>
                    <small class="text-muted">OEM and top-grade spare parts only</small>
                </div>
            </div>
        </div>

        <!-- Fast Shipping -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="feature-card d-flex align-items-center p-4 bg-white border rounded shadow-sm hover-shadow">
                <div class="feature-icon text-primary d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px;">
                    <i class="fa fa-shipping-fast fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Fast Worldwide Shipping</h6>
                    <small class="text-muted">Delivering right to your doorstep</small>
                </div>
            </div>
        </div>

        <!-- Available Parts Count -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="feature-card d-flex align-items-center p-4 bg-white border rounded shadow-sm hover-shadow">
                <div class="feature-icon text-primary d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px;">
                    <i class="fas fa-cogs fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">{{ number_format($partsCounter) }} {{ $partsCounter > 1 ? 'Parts' : 'Part' }} Available</h6>
                    <small class="text-muted">Explore our full parts catalog</small>
                </div>
            </div>
        </div>

        <!-- Support -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="feature-card d-flex align-items-center p-4 bg-white border rounded shadow-sm hover-shadow">
                <div class="feature-icon text-primary d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px;">
                    <i class="fa fa-headset fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">24/7 Expert Support</h6>
                    <small class="text-muted">Get assistance anytime you need</small>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- Featured End -->

    <!-- Featured End -->


    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Genuine Parts Online Catalogs</span></h2>
        <div class="row px-xl-5 pb-3">
            @forelse ($brands as $brand)
                 <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <a class="text-decoration-none" href="">
                    <div class="cat-item img-zoom d-flex align-items-center mb-4">
                        <div class="overflow-hidden" style="width: 100px; height: 100px;">
                            <img class="img-fluid" src="{{ asset('storage/' . $brand->brand_logo) }}" alt="">
                        </div>
                        <div class="flex-fill pl-3">
                            <h6>{{ $brand->brand_name }}</h6>
                            <small class="text-body">100 Products</small>
                        </div>
                    </div>
                </a>
            </div>
            @empty
                  <div class="col-md-12">
                    <h6 class="text-center my-2">
                        No Brand available at the moment!
                    </h6>
                    </div>
            @endforelse
        </div>
    </div>
    <!-- Categories End -->


    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Featured Spare Parts</span></h2>
        <div class="row px-xl-5">
            @forelse ($parts as $part)
                 <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{ asset('storage/'.$part->photo) }}" alt="{{ $part->part_name }}">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate" href="">{{ $part->part_name }}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>{{ $part->price }}</h5><h6 class="text-muted ml-2"><del>{{ $part->price+($part->price*25)/100 }}</del></h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small>(99)</small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-md-12 py-2 text-center">No spare part available at the moment</div>
            @endforelse
        </div>
    </div>
    <!-- Products End -->


    <!-- Offer Start -->
    
    <div class="container-fluid pt-5 pb-3">
        <div class="row px-xl-5">
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="{{ asset('frontend/img/Engine Parts sales.jpg') }}" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Engine Parts Sale</h3>
                        <a href="" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-offer mb-30" style="height: 300px;">
                    <img class="img-fluid" src="{{ asset('frontend/img/Brake & Suspension Deals.jpg') }}" alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Brake & Suspension Deals</h3>
                        <a href="" class="btn btn-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offer End -->


    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Recent Spare Parts</span></h2>
        <div class="row px-xl-5">
            @forelse ($recent_parts as $recent_part)
                 <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{ asset('storage/'.$recent_part->photo) }}" alt="{{ $recent_part->part_name }}">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate" href="">{{ $recent_part->part_name }}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                           <h5>{{ $recent_part->price }}</h5><h6 class="text-muted ml-2"><del>{{ $recent_part->price+($recent_part->price*25)/100 }}</del></h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small>(99)</small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                 <div class="col-md-12 py-2 text-center">No spare part available at the moment</div>
            @endforelse
        </div>
    </div>
    <!-- Products End -->


    <!-- Vendor Start -->
    <div class="container-fluid py-5">
        <div class="row px-xl-5">
            <div class="col">
                <div class="owl-carousel vendor-carousel">
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-1.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-2.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-3.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-4.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-5.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-6.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-7.jpg') }}" alt="">
                    </div>
                    <div class="bg-light p-4">
                        <img src="{{ asset('frontend/img/vendor-8.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Vendor End -->
@endsection