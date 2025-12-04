@extends('layouts.app')
@section('content')
           <!-- Carousel Start -->
           <div class="container-fluid carousel-header vh-100 px-0">
            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/banner1.png') }}" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Building Awareness, One Step at a Time</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Building Awareness</h1>
                                <p class="mb-5 fs-5">
                                    Join us in spreading awareness and preventing teenage pregnancy through knowledge and empowerment.
                                </p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#">Join With Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('storage/carousel/Happy Family Rwanda Survey.jpeg') }}" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Transforming Education</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Education is Key</h1>
                                <p class="mb-5 fs-5">
                                    Our programs educate teens on reproductive health, making informed choices, and building a better future.
                                </p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#">Join With Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner2.jpg') }}" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Your Future, Our Mission</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Building a Brighter Future</h1>
                                <p class="mb-5 fs-5">
                                    Dedicated to reducing teenage pregnancy, we address its root causes through entertaining and educational content, guiding teens towards a safer and healthier future.
                                </p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#">Join With Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->

        <!-- About Start -->
        <div class="container-fluid about  py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-xl-5">
                        <div class="h-100">
                            <img src="{{ asset('images/welcome photo.png') }}" class="img-fluid w-100 h-100" alt="Image">
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <h5 class="text-uppercase text-primary">About Us</h5>
                        <h1 class="mb-4">Welcome to Happy Family Rwanda Organization (HFRO)</h1>
                        <p class="fs-5 mb-4">
                            a compassionate NGO dedicated to creating positive change and 
                            making a lasting impact in communities worldwide. We believe in the 
                            power of collective action and the ability of individuals to transform lives 
                            through compassion, generosity, and empowerment.
                        </p>
                        <div class="tab-class bg-secondary p-4">
                            <ul class="nav d-flex mb-2">
                                <li class="nav-item mb-3">
                                    <a class="d-flex py-2 text-center bg-white active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 150px;">About</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-3">
                                    <a class="d-flex py-2 mx-3 text-center bg-white" data-bs-toggle="pill" href="#tab-2">
                                        <span class="text-dark" style="width: 150px;">Mission</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-3">
                                    <a class="d-flex py-2 text-center bg-white" data-bs-toggle="pill" href="#tab-3">
                                        <span class="text-dark" style="width: 150px;">Vision</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane fade show p-0 active">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="text-start my-auto">
                                                    <h5 class="text-uppercase mb-3">About Us</h5>
                                                    <p>
                                                     {!! $organization->about !!}
                                                     </p>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-2" class="tab-pane fade show p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="text-start my-auto">
                                                    <h5 class="text-uppercase mb-3">Our Mission</h5>
                                                    <p>
                                                       {!! $organization->mission !!}
                                                    </p>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-3" class="tab-pane fade show p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="text-start my-auto">
                                                    <h5 class="text-uppercase mb-3">Our Vision</h5>
                                                    <p>
                                                    {!! $organization->vision !!}
                                                    </p>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Services Start -->
        <div class="container-fluid service py-5 bg-light">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Our Impacts</h5>
                    <h1 class="mb-0">Empowering Change, Transforming Lives</h1>
                </div>
                <div class="row g-4">
                    @foreach ($causes as $cause)
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="service-item">
                            <img src="{{ asset('storage/'.$cause->mainPhoto->file_path) }}" class="img-fluid w-100" alt="{{ $cause->cause }}">
                            <div class="service-link">
                                <a href="#" class="h4 mb-0">{{ $cause->cause }}</a>
                            </div>
                        </div>
                        <p class="my-4">
                            {!! Str::limit(strip_tags($cause->description), 100) !!}
                        </p>
                    </div>   
                    @endforeach
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services End -->

        <!-- Donation Start -->
        <div class="container-fluid donation py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Discover our impactful stories</h5>
                    
                    <p class="mb-0">Find out how Happy Family is fostering resilience and making impact in the local communities. Join us in building brighter fututre together</p>
                </div>
                <div class="row g-4">
                    @foreach ($stories as $story)
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="{{ asset('storage/' . $story->photo->file_path) }}" class="img-fluid w-100" alt="{{ $story->title  }}">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">{{  $story->cause->name ?? '' }}</h5>
                                <h4 class="text-white mb-4">{{ Str::limit($story->title, 50) }}</h4>
                                <p class="text-white mb-4">
                                    {!! Str::limit(strip_tags($story->summary), 100) !!}
                                </p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-sm rounded-pill btn-primary text-white py-2 px-4" href="/story/{{ $story->id }}">More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">More Stories</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Donation End -->

        <!-- Counter Start -->
        <div class="container-fluid counter py-5" style="background: linear-gradient(rgba(0, 0, 0, .4), rgba(0, 0, 0, 0.4)), url(frontend/img/volunteers-bg.jpg) center center; background-size: cover;">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Achievements</h5>
                    <p class="text-white mb-0">
                        We take pride in the milestones we've reached through our dedicated efforts. From expanding our programs to impacting countless lives, our achievements highlight the positive change we're creating. Each success reflects our commitment and the invaluable support of our partners and volunteers. We are excited to build on these accomplishments as we continue our mission to make a difference.</p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border shadow p-5">
                            <i class="fas fa-thumbs-up fa-4x text-white"></i>
                            <h3 class="text-white my-4">Successful Projects</h3>
                            <div class="counter-counting">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">3600</span>
                                <span class="h1 fw-bold text-primary">+</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-file-invoice-dollar fa-4x text-white"></i>
                            <h3 class="text-white my-4">Funds Collected</h3>
                            <div class="counter-counting text-center border-white w-100" style="border-style: dotted; font-size: 30px;">
                                <span class="h1 fw-bold text-primary">$</span>
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">100,000</span>
                             </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-user fa-4x text-white"></i>
                            <h3 class="text-white my-4">Volunteers</h3>
                            <div class="counter-counting text-center border-white w-100" style="border-style: dotted; font-size: 30px;">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">50</span>
                                <span class="h1 fw-bold text-primary">+</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-heart fa-4x text-white"></i>
                            <h3 class="text-white my-4">Events</h3>
                            <div class="counter-counting text-center border-white w-100" style="border-style: dotted; font-size: 30px;">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">17</span>
                                <span class="h1 fw-bold text-primary">+</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Join With Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Counter End -->

        <!-- Causes Start -->
        <div class="container-fluid causes py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Recent Projects</h5>
                    <h1 class="mb-4">Our Transformative Efforts</h1>
                    <p class="mb-0">
                        At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.
                    </p>
                </div>
                <div class="row g-4">
                    @foreach($projects as $project)
                    <div class="col-lg-6 col-md-6 col-xl-4 mb-4">
    <div class="card shadow-sm border-0 h-100 overflow-hidden project-card">
        <!-- Project Image with Overlay -->
        <div class="position-relative">
            <img src="{{ $project->project_photo?->file_path ? asset('storage/' . $project->project_photo->file_path) : asset('images/default.png') }}" 
                class="card-img-top img-fluid" alt="{{ $project->title }}">
            
            <div class="overlay d-flex flex-column justify-content-between p-3">
                <div>
                    <small class="text-white d-block">
                        <i class="fas fa-chart-bar text-primary me-2"></i> Goal: {{ $project->budget ? number_format($project->budget, 2) : '-' }}
                    </small>
                    <small class="text-white d-block">
                        <i class="fa fa-thumbs-up text-primary me-2"></i> Raised: 0
                    </small>
                </div>
                <div class="text-end">
                    <a href="#" class="btn btn-sm btn-primary text-white py-1 px-3 btn-hover-bg">Donate Now</a>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="progress" style="height:6px;">
            <div class="progress-bar {{ $project->progress == 100 ? 'bg-success' : 'bg-info' }}" 
                 role="progressbar" style="width: {{ $project->progress }}%;" 
                 aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>

        <!-- Project Content -->
        <div class="card-body p-4 d-flex flex-column">
            <h5 class="card-title mb-2">{{ $project->title }}</h5>
            <p class="card-text text-muted mb-4">{{ Str::limit(strip_tags($project->summary), 120) }}</p>
            <div class="mt-auto d-flex justify-content-between align-items-center">
                <span class="badge {{ $project->progress == 100 ? 'bg-success' : 'bg-warning' }}">
                    {{ $project->progress == 100 ? 'Completed' : ucfirst($project->status) }}
                </span>
                <a href="{{ url('project/'.$project->id) }}" class="btn btn-sm btn-outline-primary btn-hover-bg">
                    Read More
                </a>
            </div>
        </div>
    </div>
</div> 
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Causes End -->

        <!-- Events Start -->
        <div class="container-fluid event py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h4 class="text-uppercase text-primary">Upcoming Events</h4>
                    <h5 class="mb-0">At Happy Family Rwanda Organization (HFRO), We are excited to share with you the upcoming events and activities that aim to make a positive impact in our community. Join us in creating meaningful change and be part of these inspiring initiatives</h5>
                </div>
                <div class="event-carousel owl-carousel">
                    @foreach ($events as $event)
                    <div class="event-item">
                        <img src="{{ asset('storage/'.$event->photo) }}" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>{{ Str::limit($event->location, 20) }}</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($event->date)->format('d M, Y') }}</span>
                            </div>
                            <h4 class="mb-4">{{ Str::limit($event->event, 50) }}</h4>
                            <p class="mb-4">{!! Str::limit(strip_tags($event->description), 200) !!}</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="/events/{{ $event->event }}">Read More</a>
                        </div>
                    </div>  
                    @endforeach
                   
                </div>
            </div>
        </div>
        <!-- Events End -->

        <!-- Blog Start -->
        <div class="container-fluid blog py-5 mb-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Latest News</h5>
                    <h1 class="mb-0">
                        Stay updated with our latest projects and inspiring stories.
                    </h1>
                </div>
                <div class="row g-4">
                    @foreach ($blogs as $blog)
                    <div class="col-lg-6 col-xl-3">
                        <div class="blog-item">
                            <div class="blog-img">
                                @if($blog->blogPhoto)
                                <img src="{{ asset('storage/'. $blog->blogPhoto->file_path) }}" class="img-fluid w-100" alt="{{ $blog->title }}">
                                @else
                                <div>No photo</div>
                                @endif
                                <div class="blog-info">
                                    <span><i class="fa fa-clock"></i> {{ \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</span>
                                    <div class="d-flex">
                                        <span class="me-3"> {{ $blog->likes->count() }} <i class="fa fa-heart"></i></span>
                                        <a href="#" class="text-white">{{ $blog->comments->count() }} <i class="fa fa-comment"></i></a>
                                    </div>
                                </div>
                                <div class="search-icon">
                                    <a href="{{ asset('storage/'. $blog->blogPhoto->file_path) }}" data-lightbox="Blog-1" class="my-auto"><i class="fas fa-search-plus btn-primary text-white p-3"></i></a>
                                </div>
                            </div>
                            <div class="text-dark border p-4 ">
                                <h4 class="mb-4">{{ $blog->title }}</h4>
                                <p class="mb-4">{{ Str::limit($blog->title, 200) }}</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="/blog/{{ $blog->slug }}">Read More</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                   
                </div>
            </div>
        </div>
        <!-- Blog End -->

        <!-- Gallery Start -->
        <div class="container-fluid gallery py-5 my-5">
            <div class="mx-auto text-center mb-5" style="max-width: 900px;">
                <h5 class="text-uppercase text-primary">Our Gallery</h5>
                <h1 class="mb-4">Capture the Magic with us</h1>
                <p class="mb-0">
                    Welcome to the Happy Family Gallery, where moments of inspiration, talent, and adventure come to life. Explore our curated collection of photos and videos showcasing the vibrant experiences we create.
                </p>
            </div>
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center mb-5">
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" href="#GalleryTab-1">
                            <span class="text-dark" style="width: 150px;">All</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex py-2 mx-3 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#VolunteeringTab">
                            <span class="text-dark" style="width: 150px;">Volunteering</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#EntertainmentTab">
                            <span class="text-dark" style="width: 150px;">Entertainment</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#WorkshopTab">
                            <span class="text-dark" style="width: 150px;">Workshop</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#SportTab">
                            <span class="text-dark" style="width: 150px;">Sport</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="GalleryTab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-2">
                            @foreach ($gallery as $photo)
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                                <div class="gallery-item h-100">
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" class="img-fluid w-100 h-100 rounded" alt="Image">
                                    <div class="gallery-content">
                                        <div class="gallery-info">
                                            <h5 class="text-white text-uppercase mb-2">{{ Str::limit($photo->caption, 50) }}</h5>
                                            <a href="#" class="btn-hover text-white">View more <i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-plus-icon">
                                        <a href="{{ asset('storage/' . $photo->file_path) }}" data-lightbox="gallery-1" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div> 
                            @endforeach
                        </div>
                    </div>
                    <div id="VolunteeringTab" class="tab-pane fade show p-0">
                        <div class="row g-2">
                            @foreach ($gallery as $photo)
                            @if ($photo->category == 'Volunteering')
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                                <div class="gallery-item h-100">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" class="img-fluid w-100 h-100 rounded" alt="Image">
                                    <div class="gallery-content">
                                        <div class="gallery-info">
                                            <h5 class="text-white text-uppercase mb-2">{{ Str::limit($photo->description, 50) }}</h5>
                                            <a href="#" class="btn-hover text-white">View All Place <i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-plus-icon">
                                        <a href="{{ asset('storage/' . $photo->photo) }}" data-lightbox="gallery-1" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>   
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="EntertainmentTab" class="tab-pane fade show p-0">
                        <div class="row g-2">
                            @foreach ($gallery as $photo)
                            @if ($photo->category == 'Entertainment')
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                                <div class="gallery-item h-100">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" class="img-fluid w-100 h-100 rounded" alt="Image">
                                    <div class="gallery-content">
                                        <div class="gallery-info">
                                            <h5 class="text-white text-uppercase mb-2">{{ Str::limit($photo->description, 50) }}</h5>
                                            <a href="#" class="btn-hover text-white">View All Place <i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-plus-icon">
                                        <a href="{{ asset('storage/' . $photo->photo) }}" data-lightbox="gallery-1" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>   
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="WorkshopTab" class="tab-pane fade show p-0">
                        <div class="row g-2">
                            @foreach ($gallery as $photo)
                            @if ($photo->category == 'Workshop')
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                                <div class="gallery-item h-100">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" class="img-fluid w-100 h-100 rounded" alt="Image">
                                    <div class="gallery-content">
                                        <div class="gallery-info">
                                            <h5 class="text-white text-uppercase mb-2">{{ Str::limit($photo->description, 50) }}</h5>
                                            <a href="#" class="btn-hover text-white">View All Place <i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-plus-icon">
                                        <a href="{{ asset('storage/' . $photo->photo) }}" data-lightbox="gallery-1" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>   
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div id="SportTab" class="tab-pane fade show p-0">
                        <div class="row g-2">
                            @foreach ($gallery as $photo)
                            @if ($photo->category == 'Sport')
                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-2">
                                <div class="gallery-item h-100">
                                    <img src="{{ asset('storage/' . $photo->photo) }}" class="img-fluid w-100 h-100 rounded" alt="Image">
                                    <div class="gallery-content">
                                        <div class="gallery-info">
                                            <h5 class="text-white text-uppercase mb-2">{{ Str::limit($photo->description, 50) }}</h5>
                                            <a href="#" class="btn-hover text-white">View All Place <i class="fa fa-arrow-right ms-2"></i></a>
                                        </div>
                                    </div>
                                    <div class="gallery-plus-icon">
                                        <a href="{{ asset('storage/' . $photo->photo) }}" data-lightbox="gallery-1" class="my-auto"><i class="fas fa-plus fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>   
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gallery End -->

        <!-- Volunteers Start -->
        <div class="container-fluid volunteer py-5 mt-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-5">
                        <img src="{{ asset('frontend/img/Volunteers.jpg') }}" class="w-100" />
                        </div>
                    <div class="col-lg-7">
                        <h5 class="text-uppercase text-primary">Become a Volunteer</h5>
                        <h1 class="mb-4">Together, we can make a difference.</h1>
                        <p class="mb-4">
                            Together, let's build a better future, empower those in need, and create a world where everyone can thrive.
                            We cannot achieve our goals alone. We rely on the support and generosity of individuals, corporations, and foundations to continue our life-changing work. Your contributions, whether through financial donations, in-kind support, or volunteering, make a real difference in the lives of those we serve. Together, we can bring hope and transform communities, one step at a time.
                        </p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> We are friendly to each other.</p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> If you join with us,We will give you free training.</p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> Its an opportunity to help poor Environments.</p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> No goal requirements.</p>
                        <p class="text-dark mb-5"><i class=" fa fa-check text-primary me-2"></i> Joining is tottaly free. We dont need any money from you.</p>
                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Join With Us</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Volunteers End -->

         <!-- Our Team Start -->
         <div class="container-fluid event py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h4 class="text-uppercase text-primary">Our Partners</h4>
                    <h5 class="mb-0">We understand the importance of networking, partnership and affiliations, We are currently partnering with</h5>
                </div>
                <div class="event-carousel owl-carousel">
                    @foreach ($partners as $partner)
                    <div class="event-item border border-rounded shadow pt-4">
                        <img src="{{ asset('storage/'.$partner->logo) }}" class="img-fluid w-50 mx-auto" alt="{{ $partner->partner }}">
                        <div class="event-content bg-white text-center p-4">
                            <div class="d-flex justify-content-between mb-4 d-none">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>{{ Str::limit($partner->location, 20) }}</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($partner->date)->format('d M, Y') }}</span>
                            </div>
                            <h4 class="mb-4">{{ Str::limit($partner->partner, 50) }}</h4>
                            <p class="mb-4">{{ Str::limit($partner->title, 200) }}</p>
                            <a href="{{ $partner->link }}" target="__blank" class="btn btn-primary rounded-pill">Visit</a>
                          </div>
                    </div>  
                    @endforeach
                   
                </div>
            </div>
        </div>
        <!-- Our Team End -->
<style>
.project-card {
    transition: transform 0.3s, box-shadow 0.3s;
    border-radius: 12px;
}
.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.project-card .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    opacity: 0;
    transition: opacity 0.3s;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}
.project-card:hover .overlay {
    opacity: 1;
}
.btn-hover-bg:hover {
    background-color: #0056b3 !important;
    color: #fff !important;
}
.progress {
    border-radius: 0;
    margin-bottom: 0;
}
</style>

        
@endsection