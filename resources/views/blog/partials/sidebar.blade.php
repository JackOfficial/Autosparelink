{{-- resources/views/blog/partials/sidebar.blade.php --}}

<div class="col-lg-3 col-md-4">

    <!-- Search -->
    <div class="bg-light p-4 mb-30">
        <h5 class="section-title position-relative text-uppercase mb-3">
            <span class="bg-secondary pr-3">Search</span>
        </h5>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search...">
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
            <img src="{{ asset('frontend/img/parts.jpg') }}"
                 class="mr-3"
                 style="width:80px;height:60px;object-fit:cover;">
            <div class="media-body">
                <a href="/blog/1" class="text-dark">
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
