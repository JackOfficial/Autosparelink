@extends('layouts.app')

{{-- 1. SEO & SOCIAL MEDIA META TAGS --}}
@section('meta_tags')
    @php
        $title = $post->title . ' | Insights';
        $description = Str::limit(strip_tags($post->content), 160);
        $url = url()->current();
        $image = $post->blogPhoto ? asset('storage/' . $post->blogPhoto->file_path) : asset('defaults/no-image.jpg');
    @endphp
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $title }}">
    <meta property="twitter:image" content="{{ $image }}">
@endsection

@section('content')

{{-- Reading Progress Bar --}}
<div id="reading-progress"></div>

{{-- Back to Top Button --}}
<button id="backToTop" class="btn btn-primary shadow" title="Go to top">
    <i class="fa fa-chevron-up"></i>
</button>

<style>
    #reading-progress { position:fixed; top:0; left:0; height:4px; background:#FFD333; width:0; z-index:9999; transition: width 0.1s ease; }
    
    /* Back to top styles */
    #backToTop { position: fixed; bottom: 30px; right: 30px; display: none; z-index: 99; border-radius: 50%; width: 50px; height: 50px; transition: 0.3s; }
    #backToTop:hover { transform: scale(1.1); background: #333; color: #FFD333; }

    .sticky-sidebar { position: sticky; top: 25px; }
    .blog-content { line-height: 2; font-size: 1.1rem; color: #333; }
    .blog-content p { margin-bottom: 1.5rem; }
    .blog-content img { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin: 2rem 0; transition: transform 0.3s ease; }
    .blog-content img:hover { transform: scale(1.01); }
    
    .comment-bubble { transition: all 0.3s ease; border: 1px solid transparent; }
    .comment-bubble:hover { border-color: #FFD333; background: #fff !important; }
    
    .share-btn { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; color: white !important; }
    .share-btn:hover { transform: translateY(-3px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); opacity: 0.9; }
    
    .hover-grow { transition: transform 0.3s ease; }
    .hover-grow:hover { transform: translateY(-5px); }

    /* Breadcrumb update to match your "pill" UI */
    .breadcrumb-custom { background: #f8f9fa; border-radius: 50px; padding: 12px 25px; border: 1px solid #eee; }
</style>

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom shadow-sm">
                    <li class="breadcrumb-item"><a class="text-primary font-weight-bold" href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-primary font-weight-bold" href="{{ route('blogs.index') }}">Insights</a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        {{-- Sidebar --}}
        <div class="col-lg-3 col-md-4">
            <div class="sticky-sidebar">
                {{-- Search Widget --}}
                <div class="bg-white p-4 mb-4 shadow-sm border-top border-primary rounded">
                    <h6 class="text-uppercase font-weight-bold mb-3">Search Insights</h6>
                    <form action="{{ route('blogs.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control border-secondary" placeholder="Keywords...">
                            <div class="input-group-append">
                                <button class="btn btn-primary text-dark"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Categories Widget --}}
                <div class="bg-white p-4 mb-4 shadow-sm rounded">
                    <h6 class="text-uppercase font-weight-bold mb-3">Categories</h6>
                    @foreach($categories as $cat)
                        <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}" 
                           class="d-flex justify-content-between align-items-center mb-2 text-dark text-decoration-none py-1 border-bottom">
                            <span>{{ $cat->name }}</span>
                            <span class="badge badge-pill badge-light border">{{ $cat->blogs_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9 col-md-8">
            <article class="bg-white p-4 p-lg-5 mb-4 shadow-sm rounded">
                <header class="mb-4 text-center text-md-left">
                    <span class="badge badge-primary px-3 py-2 text-uppercase mb-3">{{ $post->category->name ?? 'General' }}</span>
                    <h1 class="display-5 font-weight-bold text-dark">{{ $post->title }}</h1>
                    
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start text-muted mt-3 py-3 border-top border-bottom">
                        <div class="mr-4 mb-2"><i class="fa fa-calendar-alt text-primary mr-2"></i> {{ $post->created_at->format('M d, Y') }}</div>
                        <div class="mr-4 mb-2"><i class="fa fa-user text-primary mr-2"></i> By {{ $post->user->name ?? 'Admin' }}</div>
                        
                        {{-- Read Time Estimator --}}
                        <div class="mr-4 mb-2">
                            <i class="fa fa-clock text-primary mr-2"></i>
                            @php
                                $words = str_word_count(strip_tags($post->content));
                                $readTime = ceil($words / 200); 
                            @endphp
                            {{ $readTime }} min read
                        </div>
                        
                        <div class="mb-2"><i class="fa fa-comments text-primary mr-2"></i> {{ $post->comments->count() }} Comments</div>
                    </div>
                </header>

                @if($post->blogPhoto)
                    <div class="mb-5">
                        <img src="{{ asset('storage/' . $post->blogPhoto->file_path) }}" 
                             class="img-fluid w-100 rounded shadow" alt="{{ $post->title }}">
                    </div>
                @endif

                <div class="blog-content">
                    {!! $post->content !!}
                </div>

                {{-- Enhanced Share Footer --}}
                <footer class="mt-5 pt-4 border-top">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="mb-3">
                            <span class="font-weight-bold mr-3 text-muted">SHARE THIS INSIGHT:</span>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="share-btn btn btn-success rounded-circle mr-2"><i class="fab fa-whatsapp"></i></a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}" target="_blank" class="share-btn btn btn-dark rounded-circle mr-2"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="share-btn btn btn-primary rounded-circle"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </footer>
            </article>

            {{-- Related Posts Section --}}
            <div class="bg-white p-4 mb-4 shadow-sm rounded">
                <h5 class="font-weight-bold text-uppercase mb-4 border-left border-primary pl-3">Related Insights</h5>
                <div class="row">
                    @foreach(\App\Models\Blog::where('blog_category_id', $post->blog_category_id)->where('id', '!=', $post->id)->limit(3)->get() as $rel)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-0 shadow-sm hover-grow">
                                <img src="{{ $rel->blogPhoto ? asset('storage/' . $rel->blogPhoto->file_path) : asset('defaults/no-image.jpg') }}" 
                                     class="card-img-top" style="height:150px; object-fit:cover;">
                                <div class="card-body p-3 text-center">
                                    <h6 class="mb-0"><a href="{{ route('blogs.show', $rel->slug) }}" class="text-dark stretched-link font-weight-bold">{{ Str::limit($rel->title, 40) }}</a></h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Comments Section (Forelse logic preserved) --}}
            <div class="bg-white p-4 p-lg-5 shadow-sm rounded border-top border-primary">
                <h4 class="font-weight-bold mb-4">Join the Conversation</h4>
                {{-- ... Your existing comments loop and form ... --}}
            </div>
        </div>
    </div>
</div>

<script>
    const btt = document.getElementById("backToTop");
    const progress = document.getElementById('reading-progress');

    window.onscroll = function() {
        // Handle Progress Bar
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        progress.style.width = (winScroll / height * 100) + "%";

        // Handle Back to Top visibility
        if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
            btt.style.display = "block";
        } else {
            btt.style.display = "none";
        }
    };

    // Scroll to top execution
    btt.onclick = function() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };
</script>

@endsection