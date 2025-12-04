@extends('layouts.app')
@section('content')
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('storage/'.$blog->photo) }});
background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">Blog</h1>
        <p class="fs-5 text-white mb-4">{{ $blog->title }}</p>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/blogs">Blogs</a></li>
            <li class="breadcrumb-item active text-white">{{ $blog->title }}</li>
        </ol>    
    </div>
</div>
<!-- Header End -->
<div class="container-fluid event py-5">
    <section class="services" id="services">
        <div class="container">
            <div class="row no-gutters">
                <!-- section title -->
                <!-- Single Service Item -->
                
                <div class="col-md-9">
                  <h3>{{ $blog->title }}</h3>
                        <p>
                            <div>{!! $blog->content !!}</div>
                            <div class="mt-2"><b>Date:</b> {{ $blog->created_at }} <br>
                                <b>Writed by:</b> {{ $blog->first_name }} {{ $blog->first_name }}
                            </div>
                        </p>
                </div>
                <div class="col-md-3">
<div>
     <h5 class="text-center">Lecent News</h5>
      <div class="card border-none mb-3" style="max-width: 540px;">
    @foreach ($latest_blogs as $latest_blog)
    <a href="/blog/{{ $latest_blog->title }}">
    <div class="row g-0">
        <div class="col-md-4">
          <img src="{{ asset('storage/'.$latest_blog->photo) }}" class="img-fluid rounded-start" alt="...">
        </div>
        <div class="col-md-8">
          <div class="card-body">
            <h6 class="card-title">{{ $latest_blog->title }}</h6>
            <p class="card-text">{{ Str::limit(strip_tags($latest_blog->content), 25) }}</p>
           </div>
        </div>
      </div>
    </a>
    @endforeach
 </div>
</div>
<hr>
<div>
    <h5>Categories</h5>
    @foreach ($categories as $category)
        <div><a href="#">{{ $category->blog_category }}</a></div>
    @endforeach
</div>
                </div>
    
            </div> <!-- End row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="py-5">
                        <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                            <h4 class="text-uppercase text-primary">Related Blogs</h4>
                        </div>
                        <div class="event-carousel owl-carousel">
                            @foreach ($related as $blog)
                            <div class="event-item">
                                <img src="{{ asset('storage/'.$blog->photo) }}" class="img-fluid w-100" alt="Image">
                                <div class="event-content p-4">
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="text-body"><i class="fas fa-folder me-2"></i>{{ Str::limit($blog->blog_category, 20) }}</span>
                                        <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($blog->date)->format('d M, Y') }}</span>
                                    </div>
                                    <h4 class="mb-4">{{ Str::limit($blog->title, 50) }}</h4>
                                    <p class="mb-4">{!! Str::limit(strip_tags($blog->content), 200) !!}</p>
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="/blog/{{ $blog->title }}">Read More</a>
                                </div>
                            </div>  
                            @endforeach
                           
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End container -->
    </section> 
</div>
@endsection