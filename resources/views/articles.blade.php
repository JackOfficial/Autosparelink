@extends('layouts.app')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active">Articles</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Articles Start -->
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
                        <input type="text" class="form-control" placeholder="Search articles...">
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
                        <a class="text-dark" href="#">Maintenance Tips</a>
                        <span class="badge border font-weight-normal">14</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-dark" href="#">Product Reviews</a>
                        <span class="badge border font-weight-normal">9</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-dark" href="#">Guides & Tutorials</a>
                        <span class="badge border font-weight-normal">7</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <a class="text-dark" href="#">Industry Insights</a>
                        <span class="badge border font-weight-normal">5</span>
                    </li>
                </ul>
            </div>
            <!-- Categories End -->

            <!-- Recent Articles Start -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Recent Articles</span>
                </h5>

                @for ($i = 1; $i <= 5; $i++)
                <div class="media mb-3">
                    <img src="{{ asset('frontend/img/parts.jpg') }}" class="mr-3" style="width: 80px; height: 60px; object-fit: cover;">
                    <div class="media-body">
                        <a class="text-dark" href="/articles/1">
                            <h6 class="mt-0 text-truncate">Sample Article Title {{ $i }}</h6>
                        </a>
                        <small><i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025</small>
                    </div>
                </div>
                @endfor

            </div>
            <!-- Recent Articles End -->

        </div>
        <!-- Sidebar End -->

        <!-- Articles List Start -->
        <div class="col-lg-9 col-md-8">
            <div class="row pb-3">

                @for ($i = 1; $i <= 6; $i++)
                <!-- Article Item -->
                <div class="col-lg-4 col-md-6 col-sm-6 pb-4">
                    <div class="bg-light mb-4 shadow-sm hover-shadow">
                        <!-- Top Image -->
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{{ asset('frontend/img/part.png') }}" alt="" style="height: 250px; object-fit: cover;">
                        </div>
                        <!-- Card Content -->
                        <div class="p-4">
                            <a class="h6 text-decoration-none d-block article-card-title mb-2" href="#">
                                Sample Article Title {{ $i }} That Might Be Very Long
                            </a>
                            <p class="text-muted mb-3" style="font-size: 14px; line-height:1.5;">
                                This is a short description for article {{ $i }}. Replace it with your actual content.
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">
                                    <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                                </small>
                                <a href="/articles/1" class="btn btn-sm btn-primary">Read More <i class="fa fa-angle-right"></i></a>
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
        <!-- Articles List End -->

    </div>
</div>
<!-- Articles End -->

<style>
    /* Truncate long titles */
    .article-card-title {
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
