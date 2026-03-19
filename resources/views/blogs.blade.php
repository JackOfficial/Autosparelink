@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 shadow-sm">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <span class="breadcrumb-item active">Our Insights</span>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row px-xl-5">

        {{-- Sidebar --}}
        <div class="col-lg-3 col-md-4">
            {{-- Search Section --}}
            <div class="bg-light p-4 mb-30 shadow-sm border-top border-primary">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Search</span>
                </h5>
                <form action="{{ route('blogs.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search articles..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="input-group-text bg-transparent text-primary border-left-0">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Categories Section --}}
            <div class="bg-light p-4 mb-30 shadow-sm">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Categories</span>
                </h5>
                <ul class="list-unstyled mb-0">
                    @foreach($categories as $cat)
                    <li class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <a class="text-dark hover-text-primary" href="{{ route('blogs.index', ['category' => $cat->slug]) }}">
                            {{ $cat->name }}
                        </a>
                        <span class="badge badge-pill badge-secondary font-weight-normal">{{ $cat->blogs_count + $cat->news_count }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Latest News Sidebar (Using the $recentNews variable from your controller) --}}
            <div class="bg-light p-4 mb-30 shadow-sm">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Latest News</span>
                </h5>
                @foreach($latestNews as $news)
                <div class="media mb-3 align-items-center">
                    @php
                        $newsImg = $news->newsPhoto ? asset('storage/' . $news->newsPhoto->file_path) : asset('defaults/no-image.jpg');
                    @endphp
                    <img src="{{ $newsImg }}" 
                         class="mr-3 rounded shadow-sm" 
                         style="width: 70px; height: 50px; object-fit: cover;">
                    <div class="media-body">
                        <a class="text-dark small font-weight-bold d-block text-truncate" href="{{ route('news.show', $news->slug) }}" style="max-width: 150px;">
                            {{ $news->title }}
                        </a>
                        <small class="text-muted" style="font-size: 11px;">
                            <i class="fa fa-clock mr-1"></i> {{ $news->created_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Main Content - Unified Blog & News Feed --}}
        <div class="col-lg-9 col-md-8">
            <div class="row pb-3">

                @forelse ($blogs as $post)
                    <div class="col-lg-4 col-md-6 col-sm-6 pb-4">
                        <div class="bg-light mb-4 shadow-sm hover-shadow border-0 card-h-100">
                            <div class="position-relative overflow-hidden">
                                @php
                                    // Logic to handle images for both Blog and News models if merged in the same feed
                                    $imagePath = 'defaults/no-image.jpg';
                                    if(isset($post->blogPhoto)) $imagePath = 'storage/' . $post->blogPhoto->file_path;
                                    elseif(isset($post->newsPhoto)) $imagePath = 'storage/' . $post->newsPhoto->file_path;
                                @endphp
                                <img class="img-fluid w-100" 
                                     src="{{ asset($imagePath) }}" 
                                     alt="{{ $post->title }}" 
                                     style="height: 200px; object-fit: cover;">
                                
                                {{-- Badge for Type --}}
                                <div class="position-absolute px-3 py-1 text-white" 
                                     style="top: 10px; right: 10px; border-radius: 20px; font-size: 12px; background: {{ isset($post->newsPhoto) || (isset($post->category) && $post->category->type == 'news') ? '#28a745' : '#FFD333' }}; color: #3d464d !important;">
                                    {{ isset($post->newsPhoto) ? 'News' : 'Blog' }}
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <small class="text-primary font-weight-bold mb-2 d-block text-uppercase">
                                    {{ $post->blogCategory->name ?? $post->category->name }}
                                </small>
                                <a class="h6 text-decoration-none d-block blog-card-title mb-3 text-dark" href="{{ route('blogs.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                                <p class="text-muted mb-3 small">
                                    {{ Str::limit(strip_tags($post->content), 85) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <small class="text-muted">
                                        <i class="fa fa-calendar-alt text-primary mr-1"></i> {{ $post->created_at->format('d M, Y') }}
                                    </small>
                                    <a href="{{ route('blogs.show', $post->slug) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
                                        Read <i class="fa fa-angle-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-newspaper fa-4x text-light mb-3"></i>
                        <h4 class="text-muted">No articles found in this section yet.</h4>
                        <a href="{{ route('blogs.index') }}" class="btn btn-primary mt-3 text-dark">Back to All Posts</a>
                    </div>
                @endforelse

                <div class="col-12 pt-4">
                    {{ $blogs->links('vendor.pagination.bootstrap-4') }}
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    /* UI Polish */
    .blog-card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 38px;
        line-height: 1.2;
        font-weight: 700;
    }

    .hover-shadow {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }

    .hover-text-primary:hover {
        color: #FFD333 !important; 
        text-decoration: none;
        padding-left: 5px;
        transition: 0.2s;
    }

    .card-h-100 {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
</style>

@endsection