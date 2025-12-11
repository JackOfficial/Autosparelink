@extends('layouts.app')
@section('content')

<!-- =================== PREMIUM HERO BANNER =================== -->
<style>
    .hero-banner {
        height: 520px;
        background: url('{{ asset('frontend/img/part.png') }}') center/cover no-repeat;
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }

    .hero-overlay {
        background: linear-gradient(to right, rgba(0,0,0,0.75), rgba(0,0,0,0.25));
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
    }

    .hero-content {
        position: relative;
        z-index: 3;
        max-width: 700px;
    }

    .search-wrapper {
        background: #fff;
        border-radius: 50px;
        padding: 7px;
        display: flex;
        gap: 10px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }

    .search-wrapper input {
        border: none;
        border-radius: 50px;
        padding-left: 22px;
        height: 55px;
        font-size: 16px;
        width: 100%;
    }

    .search-wrapper input:focus {
        box-shadow: none;
        outline: none;
    }

    .search-wrapper button {
        border-radius: 50px;
        padding: 0 30px;
        font-size: 16px;
        font-weight: 600;
    }
</style>

<div class="container-fluid px-xl-5 mb-5">
    <div class="hero-banner d-flex align-items-center">
        <div class="hero-overlay"></div>

        <div class="hero-content text-white px-4">
            <h1 class="display-4 fw-bold text-white lead mb-3 animate__animated animate__fadeInDown">
                Find Genuine Spare Parts Instantly
            </h1>

            <p class="lead mb-4 animate__animated animate__fadeInUp">
                Enter your VIN or Frame Number to access the exact parts catalog for your vehicle.
            </p>

            <!-- VIN Search Box -->
            <form action="" method="GET" class="animate__animated animate__fadeInUp">
                <div class="search-wrapper">
                    <input type="text" name="vin" placeholder="Search by VIN / Frame Number...">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>

            <small class="d-block mt-3 text-light opacity-75">
                Example: JTDBE32K220123456 / KL1NF487J4K123456
            </small>
        </div>
    </div>
</div>
<!-- =================== END PREMIUM HERO BANNER =================== -->



<!-- =================== FEATURED START =================== -->
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
                    <small class="text-muted">OEM & premium spare parts</small>
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
                    <h6 class="fw-bold mb-1">Fast Shipping</h6>
                    <small class="text-muted">Worldwide delivery</small>
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
                    <h6 class="fw-bold mb-1">{{ number_format($partsCounter) }} Available</h6>
                    <small class="text-muted">Explore our full catalog</small>
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
                    <h6 class="fw-bold mb-1">24/7 Support</h6>
                    <small class="text-muted">Expert assistance anytime</small>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- =================== FEATURED END =================== -->



<!-- =================== CATEGORIES START =================== -->
<div class="container-fluid pt-5">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Genuine Parts Online Catalogs</span>
    </h2>

    <div class="row px-xl-5 pb-3">
        @forelse ($brands as $brand)
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <a class="text-decoration-none" href="models/{{ $brand->id }}">
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
            <div class="col-md-12 text-center py-2">No brand available.</div>
        @endforelse
    </div>
</div>
<!-- =================== CATEGORIES END =================== -->



<!-- =================== FEATURED PRODUCTS =================== -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Featured Spare Parts</span>
    </h2>

    <div class="row px-xl-5">
        @forelse ($parts as $part)
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{ asset('storage/'.$part->photo) }}" alt="{{ $part->part_name }}">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square"><i class="fa fa-shopping-cart"></i></a>
                            <a class="btn btn-outline-dark btn-square"><i class="far fa-heart"></i></a>
                            <a class="btn btn-outline-dark btn-square"><i class="fa fa-sync-alt"></i></a>
                            <a class="btn btn-outline-dark btn-square"><i class="fa fa-search"></i></a>
                        </div>
                    </div>
                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate">{{ $part->part_name }}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>{{ $part->price }}</h5>
                            <h6 class="text-muted ml-2">
                                <del>{{ $part->price + ($part->price * 25)/100 }}</del>
                            </h6>
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
            <div class="col-md-12 text-center py-2">No spare part available.</div>
        @endforelse
    </div>
</div>
<!-- =================== FEATURED PRODUCTS END =================== -->



<!-- =================== OFFERS START =================== -->
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
<!-- =================== OFFERS END =================== -->



<!-- =================== RECENT PRODUCTS =================== -->
<div class="container-fluid pt-5 pb-3">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">Recent Spare Parts</span>
    </h2>

    <div class="row px-xl-5">
        @forelse ($recent_parts as $recent_part)
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{{ asset('storage/'.$recent_part->photo) }}" alt="">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square"><i class="fa fa-shopping-cart"></i></a>
                            <a class="btn btn-outline-dark btn-square"><i class="far fa-heart"></i></a>
                            <a class="btn btn-outline-dark btn-square"><i class="fa fa-sync-alt"></i></a>
                            <a class="btn btn-outline-dark btn-square"><i class="fa fa-search"></i></a>
                        </div>
                    </div>

                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate">{{ $recent_part->part_name }}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>{{ $recent_part->price }}</h5>
                            <h6 class="text-muted ml-2">
                                <del>{{ $recent_part->price + ($recent_part->price * 25)/100 }}</del>
                            </h6>
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
            <div class="col-md-12 text-center py-2">No spare part available.</div>
        @endforelse
    </div>
</div>
<!-- =================== RECENT PRODUCTS END =================== -->



<!-- =================== VENDORS =================== -->
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
<!-- =================== VENDORS END =================== -->

@endsection
