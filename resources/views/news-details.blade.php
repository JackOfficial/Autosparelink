@extends('layouts.app')

@section('content')

<!-- Reading Progress Bar -->
<div id="reading-progress"></div>

<!-- Breadcrumb -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <a class="breadcrumb-item text-dark" href="/blog">Blog</a>
                <span class="breadcrumb-item active">Blog Details</span>
            </nav>

            <a href="/blog" class="btn btn-sm btn-outline-primary mb-30">
                ← Back to Blog
            </a>
        </div>
    </div>
</div>

<!-- Blog Details -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- Sidebar -->
        <div class="col-lg-3 col-md-4">
            @include('blog.partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-lg-9 col-md-8">

            <article class="bg-light p-4 mb-30 shadow-sm">

                <!-- Title -->
                <div class="mb-3">
                    <span class="badge badge-danger text-uppercase mb-2">🔥 Trending News</span>

                    <h1 class="mt-2 font-weight-bold">
                        How to Choose the Right Spare Parts for Your Vehicle
                    </h1>

                    <div class="d-flex flex-wrap text-muted small mt-2">
                        <span class="mr-3">
                            <i class="fa fa-calendar text-primary mr-1"></i> 12 Dec, 2025
                        </span>
                        <span class="mr-3">
                            <i class="fa fa-user text-primary mr-1"></i> AutoSpareLink Team
                        </span>
                        <span>
                            <i class="fa fa-clock text-primary mr-1"></i> 6 min read
                        </span>
                    </div>
                </div>

                <!-- Featured Image -->
                <img src="{{ asset('frontend/img/part.png') }}"
                     class="img-fluid w-100 mb-4"
                     style="max-height: 450px; object-fit: cover;"
                     alt="Choosing spare parts">

                <!-- Share Buttons -->
                <div class="d-flex align-items-center mb-4">
                    <strong class="mr-3">Share:</strong>

                    <a href="#" class="btn btn-sm btn-success mr-2">
                        <i class="fab fa-whatsapp"></i>
                    </a>

                    <a href="#" class="btn btn-sm btn-dark mr-2">
                        <i class="fab fa-x-twitter"></i>
                    </a>

                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>

                <!-- Blog Content -->
                <div class="blog-content text-dark" style="line-height:1.8;">
                    <p>
                        Choosing the right spare parts for your vehicle is essential for
                        performance, safety, and durability. Many car owners make the mistake
                        of buying cheap parts without considering compatibility.
                    </p>

                    <h4 class="mt-4">1. Understand OEM vs Aftermarket</h4>
                    <p>
                        OEM parts are produced by the vehicle manufacturer, while aftermarket
                        parts are produced by third parties. Each has its advantages.
                    </p>

                    <h4 class="mt-4">2. Check Brand Reputation</h4>
                    <p>
                        Always buy parts from trusted brands such as Bosch, Denso, Aisin,
                        and other certified manufacturers.
                    </p>

                    <blockquote class="blockquote bg-white p-3 border-left border-primary mt-4">
                        <p class="mb-0">
                            “Quality spare parts are an investment, not an expense.”
                        </p>
                    </blockquote>

                    <p class="mt-4">
                        At AutoSpareLink, we help you find verified spare parts suppliers
                        across Rwanda with confidence.
                    </p>
                </div>

            </article>

            <!-- Related Posts -->
            <div class="bg-light p-4 mb-30">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">Related Posts</span>
                </h4>

                <div class="related-scroll d-flex">

                    @for ($i = 1; $i <= 6; $i++)
                    <div class="card mr-3" style="min-width: 260px;">
                        <img src="{{ asset('frontend/img/parts.jpg') }}"
                             class="card-img-top"
                             style="height: 160px; object-fit: cover;">

                        <div class="card-body">
                            <h6 class="card-title text-truncate">
                                Best Brake Pads for Toyota Vehicles
                            </h6>
                            <a href="#" class="btn btn-sm btn-primary">Read</a>
                        </div>
                    </div>
                    @endfor

                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-light p-4 mb-30">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">Comments</span>
                </h4>

                <!-- Comment -->
                <div class="media mb-4">
                    <img src="{{ asset('frontend/img/user.png') }}"
                         class="mr-3 rounded-circle"
                         style="width:50px;">
                    <div class="media-body">
                        <h6 class="mt-0 mb-1">Jean Claude</h6>
                        <small class="text-muted">2 days ago</small>
                        <p class="mt-2">
                            This article helped me understand OEM parts better. Thanks!
                        </p>
                    </div>
                </div>

                <!-- Comment Form -->
                <form>
                    <div class="form-group">
                        <label>Your Comment *</label>
                        <textarea class="form-control" rows="4" required></textarea>
                    </div>
                    <button class="btn btn-primary">Post Comment</button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Styles -->
<style>
#reading-progress {
    position: fixed;
    top: 0;
    left: 0;
    height: 4px;
    background: #007bff;
    width: 0%;
    z-index: 9999;
}

.related-scroll {
    overflow-x: auto;
    padding-bottom: 10px;
}

.related-scroll::-webkit-scrollbar {
    height: 6px;
}
.related-scroll::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
</style>

<!-- Reading Progress Script -->
<script>
window.addEventListener('scroll', () => {
    const scrollTop = document.documentElement.scrollTop;
    const height =
        document.documentElement.scrollHeight -
        document.documentElement.clientHeight;

    const progress = (scrollTop / height) * 100;
    document.getElementById('reading-progress').style.width = progress + '%';
});
</script>

@endsection
