@extends('layouts.app')

@section('meta_tags')
    <title>{{ $post->title }} | Insights</title>
    <meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
    {{-- Keep other OG tags here... --}}
@endsection

<style>
    #reading-progress {
    position: fixed; top: 0; left: 0; width: 0%; height: 4px;
    background: #007bff; /* Use your primary theme color */
    z-index: 9999; transition: width 0.1s ease;
}

.vote-btn {
    transition: transform 0.2s ease, opacity 0.2s ease;
    outline: none !important;
}

.vote-btn:hover {
    transform: scale(1.15);
    opacity: 0.8;
}

.vote-btn:active {
    transform: scale(0.95);
}

.share-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Animation for the Submit Button */
.hover-grow {
    transition: all 0.3s ease;
}
.hover-grow:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Styling for the Empty State */
.border-dashed {
    border-style: dashed !important;
    border-width: 2px !important;
}

/* Smooth transition for new comments appearing */
.comment-bubble {
    transition: all 0.4s ease-in-out;
    border-left: 3px solid transparent;
}
.comment-bubble:hover {
    border-left: 3px solid #007bff; /* Highlight comment on hover */
}

.share-link {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff !important;
    transition: transform 0.2s;
}
.share-link:hover { transform: scale(1.1); }
.whatsapp { background-color: #25D366; }
.x-twitter { background-color: #000; }
.facebook { background-color: #1877F2; }

/* Force high contrast for comment text */
.comment-bubble p {
    color: #1a1a1a !important; /* Deep charcoal black */
    font-size: 1.05rem;
    line-height: 1.6;
}

/* Ensure names stand out */
.comment-bubble h6 {
    color: #000000 !important;
    font-weight: 700 !important;
}

/* Add a subtle "paper" effect to make the bubble distinct from the page */
.comment-bubble {
    background-color: #ffffff !important;
    border: 1px solid #dee2e6 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.sticky-sidebar {
    position: -webkit-sticky;
    position: sticky;
    top: 20px;
    z-index: 100;
    height: fit-content;
    max-height: calc(100vh - 40px);
    
    /* Set overflow to auto so it can scroll, but hide the bar initially */
    overflow-y: auto;
    
    /* Smooth transition for the hover effect */
    transition: all 0.3s ease;
}

/* 1. The Scrollbar Track (invisible by default) */
.sticky-sidebar::-webkit-scrollbar {
    width: 6px;
}

/* 2. The Scrollbar Thumb (transparent by default) */
.sticky-sidebar::-webkit-scrollbar-thumb {
    background-color: transparent;
    border-radius: 10px;
    transition: background-color 0.3s ease;
}

/* 3. The Hover Effect: Show the scrollbar when mouse enters the area */
.sticky-sidebar:hover::-webkit-scrollbar-thumb {
    background-color: #ddd; /* Use your primary Blue or #ddd as before */
}

/* Optional: Add a subtle track background on hover for better visibility */
.sticky-sidebar:hover::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
}
</style>

@section('content')
{{-- Reading Progress Bar --}}
<div id="reading-progress"></div>

<div class="container-fluid mt-4 mb-5">
    <div class="row px-xl-5">
        
        {{-- Sidebar: Navigation & Discovery --}}
     <aside class="col-lg-3 col-md-4">
            <div class="sticky-sidebar">
                {{-- Search --}}
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

                {{-- Categories --}}
               {{-- Categories --}}
<div class="bg-white p-4 mb-4 shadow-sm rounded border-top border-primary">
    <h6 class="text-uppercase font-weight-bold mb-3">Blog Categories</h6>
    @foreach($categories as $cat)
        @continue($cat->type !== 'blog')
        <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}" 
           class="d-flex justify-content-between align-items-center mb-2 text-dark text-decoration-none py-1 border-bottom">
            <span>{{ $cat->name }}</span>
            <span class="badge badge-pill badge-light border text-primary">{{ $cat->blogs_count ?? 0 }}</span>
        </a>
    @endforeach
</div>

{{-- Recent Posts Section --}}
<div class="bg-white p-4 mb-4 shadow-sm rounded border-top border-primary">
    <h6 class="text-uppercase font-weight-bold mb-4">Recent Insights</h6>
    
    @forelse($recentPosts as $recent)
        <div class="media mb-4 align-items-center pb-3 border-bottom last-child-no-border">
            @if($recent->blogPhoto)
                <img src="{{ asset('storage/' . $recent->blogPhoto->file_path) }}" 
                     class="mr-3 rounded shadow-sm" 
                     style="width: 65px; height: 65px; object-fit: cover;" 
                     alt="{{ $recent->title }}">
            @else
                <div class="mr-3 rounded bg-light d-flex align-items-center justify-content-center border" 
                     style="width: 65px; height: 65px;">
                    <i class="fa fa-image text-muted small"></i>
                </div>
            @endif
            
            <div class="media-body">
                <small class="text-primary font-weight-bold text-uppercase d-block mb-1" style="font-size: 10px;">
                    {{ $recent->category->name ?? 'General' }}
                </small>
                <h6 class="mb-0" style="line-height: 1.4;">
                    <a href="{{ route('blogs.show', $recent->slug) }}" class="text-dark font-weight-bold text-decoration-none small">
                        {{ Str::limit($recent->title, 45) }}
                    </a>
                </h6>
                <small class="text-muted" style="font-size: 11px;">
                    <i class="far fa-calendar-alt mr-1"></i> {{ $recent->created_at->format('M d') }}
                </small>
            </div>
        </div>
    @empty
        <p class="text-muted small italic">No other posts yet.</p>
    @endforelse
</div>
            </div>
        </aside>

        {{-- Main Article --}}
        <main class="col-lg-9 col-md-8">
            <article class="bg-white p-4 p-lg-5 mb-4 shadow-sm rounded">
                <header class="mb-4">
                    <span class="badge badge-primary px-3 py-2 text-uppercase mb-3">{{ $post->category->name ?? 'General' }}</span>
                    <h1 class="display-5 font-weight-bold text-dark">{{ $post->title }}</h1>
                    
                    <div class="d-flex flex-wrap text-muted mt-3 py-3 border-top border-bottom">
                        <div class="mr-4 mb-2"><i class="fa fa-calendar-alt text-primary mr-2"></i> {{ $post->created_at->format('M d, Y') }}</div>
                        <div class="mr-4 mb-2"><i class="fa fa-user text-primary mr-2"></i> By {{ $post->user->name ?? 'Admin' }}</div>
                        <div class="mr-4 mb-2"><i class="fa fa-clock text-primary mr-2"></i> {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</div>
                    </div>
                </header>

                @if($post->blogPhoto)
                    <div class="mb-5">
                        <img src="{{ asset('storage/' . $post->blogPhoto->file_path) }}" class="img-fluid w-100 rounded shadow" alt="{{ $post->title }}">
                    </div>
                @endif

                <div class="blog-content">
                    {!! $post->content !!}
                </div>
                
                {{-- Engagement Footer (Likes & Share) --}}
                <livewire:blog-engagement :post="$post" />
            </article>

            {{-- Comments Section --}}
            <livewire:blog-comments :post="$post" />
        </main>
    </div>
</div>

<script>
    window.onscroll = function() {
    let winScroll = document.body.scrollTop || document.documentElement.scrollTop;
    let height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let scrolled = (winScroll / height) * 100;
    document.getElementById("reading-progress").style.width = scrolled + "%";
};
</script>
@endsection