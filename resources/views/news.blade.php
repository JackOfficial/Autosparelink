@extends('layouts.app')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <span class="breadcrumb-item active">News</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- News Start -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- Sidebar Start -->
        <div class="col-lg-3 col-md-4">

            <!-- Search Start -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Search</span>
                </h5>
                <form action="#" method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search news...">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Search End -->

            <!-- Categories Start -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Categories</span>
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-dark" href="#">Industry News</a>
                        <span class="badge border font-weight-normal">15</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-dark" href="#">Product Launches</a>
                        <span class="badge border font-weight-normal">8</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-dark" href="#">Company Updates</a>
                        <span class="badge border font-weight-normal">5</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <a class="text-dark" href="#">Events</a>
                        <span class="badge border font-weight-normal">4</span>
                    </li>
                </ul>
            </div>
            <!-- Categories End -->

            <!-- Recent News Start -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Recent News</span>
                </h5>

                @for ($i = 1; $i <= 5; $i++)
                <div class="media mb-3">
                    <img src="{{ asset('frontend/img/parts.jpg') }}" class="mr-3" style="width: 80px; height: 60px; object-fit: cover;">
                    <div class="media-body">
                        <a class="text-dark" href="/news/1">
                            <h6 class="mt-0 text-truncate">Sample News Title {{ $i }}</h6>
                        </a>
                        <small><i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025</small>
                    </div>
                </div>
                @endfor

            </div>
            <!-- Recent News End -->

        </div>
        <!-- Sidebar End -->

        <!-- News List Start -->
        <div class="col-lg-9 col-md-8">
            <div class="row pb-3">

                @for ($i = 1; $i <= 6; $i++)
                <!-- News Item -->
                <div class="col-lg-4 col-md-6 col-sm-6 pb-4">
                    <div class="bg-light mb-4 shadow-sm hover-shadow">
                        <!-- Top Image -->
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{{ asset('frontend/img/part.png') }}" alt="" style="height: 250px; object-fit: cover;">
                        </div>
                        <!-- Card Content -->
                        <div class="p-4">
                            <a class="h6 text-decoration-none d-block news-card-title mb-2" href="#">
                                Sample News Title {{ $i }} That Might Be Very Long
                            </a>
                            <p class="text-muted mb-3" style="font-size: 14px; line-height:1.5;">
                                This is a short description for news post {{ $i }}. Replace it with your actual content.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                                </small>
                                <a href="/news/1" class="btn btn-sm btn-primary">Read More <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor

                <!-- Pagination -->
                <div class="col-12">
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled"><a class="page-link">Previous</a></li>
                            <li class="page-item active"><a class="page-link">1</a></li>
                            <li class="page-item"><a class="page-link">2</a></li>
                            <li class="page-item"><a class="page-link">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
        <!-- News List End -->

    </div>
</div>
<!-- News End -->

<style>
    /* Truncate long titles */
    .news-card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Hover shadow effect */
    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }
</style>

@endsection
