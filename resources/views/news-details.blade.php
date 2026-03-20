@extends('layouts.app')

@section('content')

<div id="reading-progress"></div>

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white shadow-sm rounded-pill px-4">
                    <li class="breadcrumb-item"><a class="text-primary font-weight-bold" href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="text-primary font-weight-bold" href="{{ route('news.index') }}">News</a></li>
                    <li class="breadcrumb-item active text-muted text-truncate" aria-current="page">{{ $news->title }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        
        <div class="col-lg-3 col-md-4">
            {{-- Search --}}
            <div class="bg-white p-4 mb-4 shadow-sm rounded border-top border-primary">
                <h6 class="text-uppercase font-weight-bold mb-3">Search News</h6>
                <form action="{{ route('news.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control border-secondary" placeholder="Keywords...">
                        <div class="input-group-append">
                            <button class="btn btn-primary text-dark"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Categories --}}
            <div class="bg-white p-4 mb-4 shadow-sm rounded">
                <h6 class="text-uppercase font-weight-bold mb-3">News Categories</h6>
                @foreach($categories as $cat)
                    <a href="{{ route('news.index', ['category' => $cat->slug]) }}" 
                       class="d-flex justify-content-between align-items-center mb-2 text-dark text-decoration-none py-1 border-bottom hover-link">
                        <span>{{ $cat->name }}</span>
                        <span class="badge badge-pill badge-light border text-primary">{{ $cat->news_count }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Recent News Sidebar (Using the aliased relationship) --}}
            <div class="bg-white p-4 mb-4 shadow-sm rounded">
                <h6 class="text-uppercase font-weight-bold mb-3">Latest Updates</h6>
                @foreach($recentPosts as $recent)
                    <div class="d-flex align-items-center mb-3">
                        @php
                            $sidePath = ($recent->blogPhoto && $recent->blogPhoto->file_path) 
                                ? asset('storage/' . $recent->blogPhoto->file_path) 
                                : asset('defaults/no-photo.jpg');
                        @endphp
                        <img src="{{ $sidePath }}" class="rounded shadow-sm mr-3" style="width:60px; height:60px; object-fit:cover;">
                        <div class="overflow-hidden">
                            <a href="{{ route('news.show', $recent->slug) }}" class="text-dark small font-weight-bold text-truncate d-block">
                                {{ $recent->title }}
                            </a>
                            <small class="text-muted"><i class="far fa-clock mr-1 text-primary"></i> {{ $recent->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <article class="bg-white p-4 p-md-5 mb-4 shadow-sm rounded">
                {{-- Category Badge --}}
                <span class="badge badge-primary px-3 py-2 text-uppercase mb-3" style="border-radius: 50px;">
                    {{ $news->category->name ?? 'General News' }}
                </span>

                <h1 class="display-4 font-weight-bold mb-4" style="font-size: 2.2rem; line-height: 1.2;">
                    {{ $news->title }}
                </h1>

                {{-- Meta Info --}}
                <div class="d-flex flex-wrap align-items-center text-muted mb-4 pb-4 border-bottom">
                    <div class="d-flex align-items-center mr-4 mb-2">
                        <i class="far fa-calendar-alt text-primary mr-2"></i>
                        <span>{{ $news->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center mr-4 mb-2">
                        <i class="far fa-user text-primary mr-2"></i>
                        <span>{{ $news->user->name ?? 'Admin' }}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="far fa-eye text-primary mr-2"></i>
                        <span>{{ number_format($news->views) }} Views</span>
                    </div>
                </div>

                {{-- Main Image --}}
                @php
                    $mainImagePath = ($news->newsPhoto && $news->newsPhoto->file_path) 
                        ? asset('storage/' . $news->newsPhoto->file_path) 
                        : asset('defaults/no-photo.jpg');
                @endphp
                <div class="mb-5">
                    <img src="{{ $mainImagePath }}" class="img-fluid w-100 rounded shadow-sm" 
                         style="max-height: 500px; object-fit: cover;" alt="{{ $news->title }}">
                </div>

                {{-- Content --}}
                <div class="news-content mb-5" style="font-size: 1.1rem; line-height: 1.8; color: #444;">
                    {!! $news->content !!}
                </div>

                {{-- Social Share --}}
                <div class="p-4 bg-light rounded d-flex align-items-center flex-wrap">
                    <h6 class="font-weight-bold mb-0 mr-4">Share this update:</h6>
                    <div class="share-buttons">
                        <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-success btn-sm rounded-circle mr-2"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ url()->current() }}" target="_blank" class="btn btn-dark btn-sm rounded-circle mr-2"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-primary btn-sm rounded-circle"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </article>

            {{-- Comments Section (Reusing your UI style) --}}
            <div class="bg-white p-4 p-md-5 mb-4 shadow-sm rounded">
                <h4 class="font-weight-bold mb-4">Reader Feedback</h4>
                
                {{-- Example Loop for Comments --}}
                @forelse($news->comments ?? [] as $comment)
                    <div class="media mb-4 p-3 bg-light rounded shadow-xs">
                        <div class="mr-3 shadow-sm" style="flex-shrink:0;">
                            @if($comment->user && $comment->user->avatar)
                                <img src="{{ $comment->user->avatar }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <strong>{{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="media-body">
                            <div class="d-flex justify-content-between mb-1">
                                <h6 class="font-weight-bold mb-0">{{ $comment->user->name ?? 'Guest User' }}</h6>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0 text-muted">{{ $comment->comment }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted italic mb-4">No comments yet. Be the first to share your thoughts!</p>
                @endforelse

                <hr class="my-5">

                <h5 class="font-weight-bold mb-3">Leave a Comment</h5>
                <form action="#" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea class="form-control border-secondary" name="comment" rows="4" placeholder="Write your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 py-2 text-dark font-weight-bold shadow-sm">Post Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #reading-progress { position: fixed; top: 0; left: 0; height: 5px; background: #FFD333; width: 0; z-index: 9999; transition: width 0.1s ease; }
    .news-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
    .news-content blockquote { border-left: 5px solid #FFD333; padding: 20px; background: #f9f9f9; font-style: italic; font-size: 1.2rem; }
    .hover-link:hover { padding-left: 5px; color: #FFD333 !important; transition: 0.3s; }
    .share-buttons a { width: 35px; height: 35px; line-height: 35px; text-align: center; padding: 0; }
</style>

<script>
    window.addEventListener('scroll', () => {
        const h = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        document.getElementById('reading-progress').style.width = (document.documentElement.scrollTop / h) * 100 + '%';
    });
</script>

@endsection