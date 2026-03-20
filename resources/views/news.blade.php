@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white shadow-sm rounded-pill px-4">
                    <li class="breadcrumb-item"><a class="text-primary font-weight-bold" href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active text-muted" aria-current="page">News & Updates</li>
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

            {{-- Latest Updates (Recent News) --}}
            <div class="bg-white p-4 mb-4 shadow-sm rounded">
                <h6 class="text-uppercase font-weight-bold mb-3">Recent Highlights</h6>
                @foreach($newsList->take(5) as $recent)
                    <div class="d-flex align-items-center mb-3">
                        @php
                            $sidePath = ($recent->newsPhoto && $recent->newsPhoto->file_path) 
                                ? asset('storage/' . $recent->newsPhoto->file_path) 
                                : asset('defaults/no-photo.jpg');
                        @endphp
                        <img src="{{ $sidePath }}" class="rounded shadow-sm mr-3" style="width:60px; height:60px; object-fit:cover;">
                        <div class="overflow-hidden">
                            <a href="{{ route('news.show', $recent->slug) }}" class="text-dark small font-weight-bold text-truncate d-block">
                                {{ $recent->title }}
                            </a>
                            <small class="text-muted"><i class="far fa-clock mr-1 text-primary"></i> {{ $recent->created_at->format('M d') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="row">
                @forelse($newsList as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm hover-grow bg-white">
                            {{-- News Image --}}
                            <div class="position-relative overflow-hidden">
                                @php
                                    $imagePath = ($item->newsPhoto && $item->newsPhoto->file_path) 
                                        ? asset('storage/' . $item->newsPhoto->file_path) 
                                        : asset('defaults/no-photo.jpg');
                                @endphp
                                <img class="card-img-top" src="{{ $imagePath }}" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                                <div class="badge badge-primary position-absolute px-3 py-2" style="top: 15px; left: 15px; border-radius: 50px;">
                                    {{ $item->category->name ?? 'Update' }}
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body p-4">
                                <a class="h6 text-dark font-weight-bold d-block mb-3 news-card-title text-decoration-none" href="{{ route('news.show', $item->slug) }}">
                                    {{ $item->title }}
                                </a>
                                <div class="text-muted mb-3" style="font-size: 0.9rem; line-height: 1.6;">
                                    {!! Str::limit(strip_tags($item->content), 90) !!}
                                </div>
                            </div>

                            {{-- Card Footer --}}
                            <div class="card-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt text-primary mr-1"></i> {{ $item->created_at->format('M d, Y') }}
                                </small>
                                <a href="{{ route('news.show', $item->slug) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">Read News</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('defaults/no-results.png') }}" style="width: 150px; opacity: 0.5;">
                        <h4 class="text-muted mt-3">No news articles found.</h4>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-center custom-pagination">
                        {{ $newsList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .news-card-title {
        transition: color 0.3s ease;
        height: 3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .news-card-title:hover { color: #FFD333 !important; }
    .hover-grow { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-grow:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
    .hover-link:hover { padding-left: 5px; color: #FFD333 !important; transition: 0.3s; }
    .custom-pagination .page-item.active .page-link { background-color: #FFD333; border-color: #FFD333; color: #333; }
    .custom-pagination .page-link { color: #333; border-radius: 5px; margin: 0 2px; }
</style>

@endsection