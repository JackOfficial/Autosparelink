@extends('layouts.app')
@section('content')
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb" 
        style="
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('frontend/img/breadcrumb-bg.jpg') }});
        background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;
         ">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Latest News</h1>
                <p class="fs-5 text-white mb-4 d-none">Help today because tomorrow you may be the one who needs more helping!</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active text-white">Blogs</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

          <!-- Blog Start -->
          <div class="container-fluid blog py-5 mb-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Latest News</h5>
                    <h1 class="mb-0 d-none">Help today because tomorrow you may be the one who needs more helping!
                    </h1>
                </div>
                <div class="row g-4">
                    @foreach ($blogs as $blog)
                    <div class="col-lg-6 col-md-6 col-xl-4">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="{{ asset('storage/'. $blog->photo) }}" class="img-fluid w-100" alt="{{ $blog->title }}">
                                <div class="blog-info">
                                    <span><i class="fa fa-clock"></i> {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</span>
                                    <div class="d-flex">
                                        <span class="me-3"> 3 <i class="fa fa-heart"></i></span>
                                        <a href="#" class="text-white">0 <i class="fa fa-comment"></i></a>
                                    </div>
                                </div>
                                <div class="search-icon">
                                    <a href="{{ asset('storage/'. $blog->photo) }}" data-lightbox="Blog-1" class="my-auto"><i class="fas fa-search-plus btn-primary text-white p-3"></i></a>
                                </div>
                            </div>
                            <div class="text-dark p-4 ">
                                <h4 class="mb-4">{{ $blog->title }}</h4>
                                <p class="mb-4">{{ Str::limit($blog->title, 200) }}</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="/blog/{{ $blog->title }}">Read More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                   
                </div>

                
            </div>
        </div>
        <!-- Blog End -->

@endsection