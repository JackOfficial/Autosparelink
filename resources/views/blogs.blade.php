@extends('layouts.app')

@section('title', 'How to Choose the Right Spare Parts for Your Car | AutoSpareLink')

@section('content')

<!-- Breadcrumb -->
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

<!-- Blog Page -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">

            <!-- Search -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Search</span>
                </h5>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search blog...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-transparent text-primary">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </div>
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
                    <img src="{{ asset('frontend/img/parts.jpg') }}" class="mr-3"
                         style="width:80px;height:60px;object-fit:cover;">
                    <div class="media-body">
                        <a href="#" class="text-dark">
                            <h6 class="mt-0 text-truncate">Recent Blog {{ $i }}</h6>
                        </a>
                        <small class="text-muted">
                            <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                        </small>
                    </div>
                </div>
                @endfor
            </div>

        </div>

        <!-- Blog Content -->
        <div class="col-lg-9 col-md-8">

            <div class="bg-light p-4 mb-30">

                <!-- Back Button -->
                <a href="/blogs" class="btn btn-outline-primary btn-sm mb-3">
                    <i class="fa fa-arrow-left mr-1"></i> Back to Blog
                </a>

                <!-- Featured Image -->
                <img src="{{ asset('frontend/img/part.png') }}"
                     class="img-fluid w-100 mb-4"
                     style="max-height:420px;object-fit:cover;">

                <!-- Title -->
                <h1 class="font-weight-bold mb-2">
                    How to Choose the Right Spare Parts for Your Car
                </h1>

                <!-- Meta -->
                <div class="d-flex flex-wrap text-muted mb-4" style="font-size:14px;">
                    <span class="mr-3">
                        <i class="fa fa-user text-primary mr-1"></i> AutoSpareLink
                    </span>
                    <span class="mr-3">
                        <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                    </span>
                    <span class="mr-3">
                        <i class="fa fa-clock text-primary mr-1"></i> 5 min read
                    </span>
                    <span>
                        <i class="fa fa-folder text-primary mr-1"></i> Spare Parts
                    </span>
                </div>

                <!-- Content -->
                <div class="blog-content" style="line-height:1.8;font-size:15px;">
                    <p>
                        Choosing the correct spare parts is critical for performance,
                        safety, and long-term vehicle reliability. At <strong>AutoSpareLink</strong>,
                        we make sourcing trusted OEM and aftermarket parts simple.
                    </p>

                    <h4 class="mt-4">OEM vs Aftermarket</h4>
                    <p>
                        OEM parts are produced by original manufacturers, while aftermarket
                        parts offer cost-effective alternatives with wide availability.
                    </p>

                    <blockquote class="blockquote bg-white p-3 border-left border-primary my-4">
                        <p class="mb-0 font-italic">
                            “Quality spare parts protect your vehicle investment.”
                        </p>
                    </blockquote>

                    <p>
                        Always confirm compatibility and buy from trusted suppliers to avoid
                        unnecessary breakdowns.
                    </p>
                </div>

            </div>

            <!-- Related Posts (Horizontal Scroll) -->
            <div class="bg-light p-4 mb-30">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">Related Posts</span>
                </h4>

                <div class="related-scroll d-flex overflow-auto pb-2">
                    @for ($i = 1; $i <= 6; $i++)
                    <div class="related-card bg-white shadow-sm mr-3">
                        <img src="{{ asset('frontend/img/part.png') }}"
                             style="height:160px;object-fit:cover;width:100%;">
                        <div class="p-3">
                            <a href="#" class="h6 text-dark text-decoration-none">
                                Related Blog {{ $i }}
                            </a>
                            <small class="text-muted d-block mt-1">
                                <i class="fa fa-calendar text-primary mr-1"></i> 10 Dec, 2025
                            </small>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- Comments -->
            <div class="bg-light p-4">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">Comments (3)</span>
                </h4>

                <!-- Comment -->
                <div class="media mb-4">
                    <i class="fa fa-user-circle fa-2x text-primary mr-3"></i>
                    <div class="media-body">
                        <h6 class="mt-0 mb-1">John Doe</h6>
                        <small class="text-muted">2 days ago</small>
                        <p class="mt-2 mb-0">
                            Very informative article. Helped me choose the right parts!
                        </p>
                    </div>
                </div>

                <!-- Add Comment -->
                <form>
                    <div class="form-group">
                        <label>Your Comment</label>
                        <textarea class="form-control" rows="4" placeholder="Write your comment..."></textarea>
                    </div>
                    <button class="btn btn-primary">
                        <i class="fa fa-paper-plane mr-1"></i> Post Comment
                    </button>
                </form>
            </div>

        </div>

    </div>
</div>

<style>
.related-card {
    min-width: 260px;
    border-radius: 6px;
    overflow: hidden;
}

.related-scroll::-webkit-scrollbar {
    height: 6px;
}

.related-scroll::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
</style>

@endsection
