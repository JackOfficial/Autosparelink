@extends('layouts.app')

@section('content')

<div id="reading-progress"></div>

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 shadow-sm">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="{{ route('blogs.index') }}">Insights</a>
                <span class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}</span>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row px-xl-5">

        <div class="col-lg-3 col-md-4">
            <div class="bg-light p-4 mb-30 shadow-sm border-top border-primary">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Search</span>
                </h5>
                <form action="{{ route('blogs.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search insights...">
                        <div class="input-group-append">
                            <button class="input-group-text bg-transparent text-primary border-left-0">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

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
                        <span class="badge badge-pill badge-secondary font-weight-normal">{{ $cat->blogs_count }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-light p-4 mb-30 shadow-sm">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Latest Updates</span>
                </h5>
                @foreach($recentPosts as $recent)
                <div class="media mb-3 align-items-center">
                    @php
                        $recentImg = $recent->newsPhoto ? asset('storage/' . $recent->newsPhoto->file_path) : asset('defaults/no-image.jpg');
                    @endphp
                    <img src="{{ $recentImg }}" class="mr-3 rounded shadow-sm" style="width:70px;height:50px;object-fit:cover;">
                    <div class="media-body">
                        <a href="{{ route('news.show', $recent->slug) }}" class="text-dark small font-weight-bold d-block text-truncate" style="max-width: 150px;">
                            {{ $recent->title }}
                        </a>
                        <small class="text-muted" style="font-size: 11px;">
                            <i class="fa fa-calendar text-primary mr-1"></i> {{ $recent->created_at->format('d M, Y') }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <article class="bg-light p-4 mb-30 shadow-sm">
                <span class="badge badge-primary text-uppercase mb-2 px-3 py-2">
                    {{ $post->category->name ?? 'General' }}
                </span>

                <h1 class="mt-2 font-weight-bold h2 text-dark">{{ $post->title }}</h1>

                <div class="d-flex flex-wrap text-muted small mb-4 py-2 border-top border-bottom">
                    <span class="mr-4">
                        <i class="fa fa-calendar text-primary mr-1"></i> {{ $post->created_at->format('M d, Y') }}
                    </span>
                    <span class="mr-4">
                        <i class="fa fa-user text-primary mr-1"></i> {{ $post->user->name ?? 'Admin' }}
                    </span>
                    <span class="mr-4">
                        <i class="fa fa-eye text-primary mr-1"></i> {{ $post->views ?? 0 }} Views
                    </span>
                </div>

                @if($post->blogPhoto)
                <div class="position-relative mb-4">
                    <img src="{{ asset('storage/' . $post->blogPhoto->file_path) }}"
                         class="img-fluid w-100 rounded shadow-sm"
                         style="max-height:500px;object-fit:cover;"
                         alt="{{ $post->title }}">
                </div>
                @endif

                <div class="mb-4 d-flex align-items-center">
                    <strong class="mr-3 small text-uppercase text-muted">Share article:</strong>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-success mr-2 rounded-circle">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ url()->current() }}" target="_blank" class="btn btn-sm btn-dark mr-2 rounded-circle">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="btn btn-sm btn-primary rounded-circle">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </div>

                <div class="blog-content text-dark" style="line-height:1.9; font-size: 1.05rem;">
                    {!! $post->content !!}
                </div>
            </article>

            <div class="bg-light p-4 mb-30 shadow-sm">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">You Might Also Like</span>
                </h4>
                <div class="row">
                    @php
                        // Fetching 3 related items from same category excluding current
                        $related = \App\Models\Blog::where('blog_category_id', $post->blog_category_id)
                                    ->where('id', '!=', $post->id)
                                    ->limit(3)->get();
                    @endphp
                    @foreach($related as $rel)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($rel->blogPhoto)
                                <img src="{{ asset('storage/' . $rel->blogPhoto->file_path) }}" class="card-img-top" style="height:140px; object-fit:cover;">
                            @endif
                            <div class="card-body p-3">
                                <h6 class="card-title small font-weight-bold">
                                    <a href="{{ route('blogs.show', $rel->slug) }}" class="text-dark">{{ $rel->title }}</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-light p-4 mb-30 shadow-sm border-top border-primary">
                <h4 class="section-title position-relative text-uppercase mb-4">
                    <span class="bg-secondary pr-3">Discussion ({{ $post->comments->count() }})</span>
                </h4>

                @if(session('message'))
                    <div class="alert alert-success small py-2 shadow-sm">{{ session('message') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger small py-2 shadow-sm">{{ session('error') }}</div>
                @endif

                <div class="comments-list mb-4">
                    @forelse($post->comments->sortByDesc('created_at') as $comment)
                        <div class="media mb-4 p-3 bg-white rounded shadow-sm border-left border-primary">
                            <div class="rounded-circle bg-secondary text-primary d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px; font-weight: bold;">
                                {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="media-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 font-weight-bold">{{ $comment->user->name ?? 'Anonymous' }}</h6>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mt-2 mb-0 text-dark" style="font-size: 0.95rem;">
                                    {{ $comment->comment }}
                                </p>
                                
                                {{-- Show Delete Button only if the user owns the comment --}}
                                @if(auth()->check() && auth()->id() == $comment->user_id)
                                    <form action="{{ route('comment.delete', $comment->id) }}" method="POST" class="mt-2 text-right">
                                        @csrf
                                        <button type="submit" class="btn btn-link text-danger p-0 small" onclick="return confirm('Delete this comment?')">
                                            <i class="fa fa-trash-alt mr-1"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fa fa-comments fa-2x mb-2 d-block opacity-5"></i>
                            <p>No comments yet. Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>

                <hr class="my-4">

                @auth
                    <form action="{{ route('comment.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $post->id }}">
                        <div class="form-group">
                            <label class="small font-weight-bold text-uppercase">Leave a reply</label>
                            <textarea name="comment" class="form-control border-0 shadow-sm" rows="4" placeholder="Share your thoughts..." required></textarea>
                            @error('comment')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm text-dark font-weight-bold">
                            Post Comment <i class="fa fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                @else
                    <div class="text-center p-4 bg-white rounded border border-dashed">
                        <p class="mb-2">You must be logged in to join the discussion.</p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary px-4">Login Now</a>
                    </div>
                @endauth
            </div>
                
                {{-- Add your existing comment loop here if you have a comments table --}}
                
                <form action="{{ route('comment.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="blog_id" value="{{ $post->id }}">
                    <div class="form-group">
                        <label class="small font-weight-bold text-uppercase">Leave a reply</label>
                        <textarea name="comment" class="form-control border-0 shadow-sm" rows="4" placeholder="Share your thoughts..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm text-dark">Submit Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #reading-progress { position:fixed; top:0; left:0; height:4px; background:#FFD333; width:0; z-index:9999; transition: width 0.1s ease; }
    .blog-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 20px 0; }
    .blog-content blockquote { font-style: italic; color: #666; padding: 20px; background: #f8f9fa; border-left: 5px solid #FFD333; margin: 30px 0; }
    .hover-text-primary:hover { color: #FFD333 !important; text-decoration: none; padding-left: 5px; transition: 0.2s; }
</style>

<script>
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('reading-progress').style.width = scrolled + "%";
    });
</script>

@endsection