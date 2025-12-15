@extends('layouts.app')

@section('content')

<!-- Reading Progress Bar -->
<div id="reading-progress"></div>

<div class="container-fluid bg-light border-bottom">
    <div class="d-flex justify-content-between align-items-center px-xl-5 py-3">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-0">
            <ol class="breadcrumb bg-transparent p-0 mb-0">
                <li class="breadcrumb-item">
                    <a href="/" class="text-dark">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/blog" class="text-dark">Blog</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Blog Details
                </li>
            </ol>
        </nav>

        <!-- Back Button -->
        <a href="/blogs" class="btn btn-sm btn-outline-primary">
            <i class="fa fa-arrow-left mr-1"></i> Back to Blog
        </a>

    </div>
</div>


<!-- Blog Details -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- INLINE SIDEBAR -->
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
                    <li class="mb-2"><a href="#" class="text-dark">Car Maintenance</a></li>
                    <li class="mb-2"><a href="#" class="text-dark">Spare Parts</a></li>
                    <li class="mb-2"><a href="#" class="text-dark">Auto Tips</a></li>
                    <li><a href="#" class="text-dark">Industry News</a></li>
                </ul>
            </div>

            <!-- Recent Posts -->
            <div class="bg-light p-4 mb-30">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Recent Posts</span>
                </h5>

                @for ($i = 1; $i <= 4; $i++)
                <div class="media mb-3">
                    <img src="{{ asset('frontend/img/parts.jpg') }}"
                         class="mr-3"
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

        <!-- MAIN CONTENT -->
        <div class="col-lg-9 col-md-8">

            <article class="bg-light p-4 mb-30 shadow-sm">

                <!-- Title -->
                <span class="badge badge-danger text-uppercase mb-2">🔥 Trending</span>

                <h1 class="mt-2 font-weight-bold">
                    How to Choose the Right Spare Parts for Your Vehicle
                </h1>

                <!-- Meta -->
                <div class="d-flex flex-wrap text-muted small mb-3">
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

                <!-- Image -->
                <img src="{{ asset('frontend/img/part.png') }}"
                     class="img-fluid w-100 mb-4"
                     style="max-height:450px;object-fit:cover;"
                     alt="Choosing spare parts">

                <!-- Share -->
                <div class="mb-4">
                    <strong class="mr-2">Share:</strong>
                    <a href="#" class="btn btn-sm btn-success mr-1">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-dark mr-1">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-primary">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>

                <!-- Content -->
                <div class="blog-content" style="line-height:1.8;">
                    <p>
                        Choosing the right spare parts for your vehicle is essential for
                        performance, safety, and durability.
                    </p>

                    <h4 class="mt-4">OEM vs Aftermarket</h4>
                    <p>
                        OEM parts match factory standards, while aftermarket options
                        provide affordable alternatives.
                    </p>

                    <blockquote class="blockquote bg-white p-3 border-left border-primary">
                        “Quality spare parts are an investment, not an expense.”
                    </blockquote>

                    <p>
                        AutoSpareLink connects you with trusted suppliers across Rwanda.
                    </p>
                </div>
            </article>

            <!-- RELATED POSTS -->
            <div class="bg-light p-4 mb-30">
                <h4 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Related Posts</span>
                </h4>

                <div class="d-flex related-scroll">
                    @for ($i = 1; $i <= 6; $i++)
                    <div class="card mr-3" style="min-width:260px;">
                        <img src="{{ asset('frontend/img/parts.jpg') }}"
                             class="card-img-top"
                             style="height:160px;object-fit:cover;">
                        <div class="card-body">
                            <h6 class="card-title text-truncate">
                                Best Brake Pads for Toyota
                            </h6>
                            <a href="#" class="btn btn-sm btn-primary">Read</a>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <!-- COMMENTS -->
            <div class="bg-light p-4 mb-30">
                <h4 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Comments</span>
                </h4>

                <div class="media mb-3">
                    <i class="fa fa-user-circle fa-2x text-primary mr-3"></i>
                    <div class="media-body">
                        <h6 class="mb-1">Jean Claude</h6>
                        <small class="text-muted">2 days ago</small>
                        <p class="mt-2 mb-0">
                            Very helpful explanation about OEM parts.
                        </p>
                    </div>
                </div>

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
#reading-progress{
    position:fixed;
    top:0;
    left:0;
    height:4px;
    background:#007bff;
    width:0;
    z-index:9999;
}
.related-scroll{
    overflow-x:auto;
    padding-bottom:8px;
}
.related-scroll::-webkit-scrollbar{
    height:6px;
}
.related-scroll::-webkit-scrollbar-thumb{
    background:#ccc;
    border-radius:10px;
}
</style>

<script>
window.addEventListener('scroll', () => {
    const h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    document.getElementById('reading-progress').style.width =
        (document.documentElement.scrollTop / h) * 100 + '%';
});
</script>

@endsection
