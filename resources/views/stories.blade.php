@extends('layouts.app')
@section('content')
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('frontend/img/breadcrumb-bg.jpg') }});
        background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Stories</h1>
                <p class="fs-5 text-white mb-4">DISCOVER OUR IMPACTFUL STORIES</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active text-white">Stories</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

        <!-- Events Start -->
        <div class="container-fluid event py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Stories</h5>
                    <h5 class="mb-0">Find out how Happy Family is fostering resilience and making impact in the local communities. Join us in building brighter fututre together</h5>
                </div>
                <div class="event-carousel owl-carousel">
                    @forelse($stories as $story)
                    <div class="event-item">
                        <img src="{{ asset('storage/'.$story->photo) }}" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-user me-2"></i>{{ $story->first_name }} {{ $story->last_name }}</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($story->date)->format('d M, Y') }}</span>
                            </div>
                            <h4 class="mb-4">{{ Str::limit($story->heading, 50) }}</h4>
                            <p class="mb-4">{!! Str::limit(strip_tags($story->content), 200) !!}</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="/story/{{ $story->id }}">Read More</a>
                        </div>
                    </div>  
                    @empty
                    <div>
                        No Story available at the moment!
                    </div>
                    @endforelse
                   
                </div>
            </div>
            	<!-- End container -->
        </div>
        <!-- Events End -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center bg-secondary">
                    <h2 class="mt-4">Together, we can make a difference.</h2>
                    <p>Together, let's build a better future, 
                        empower those in need, and create a world where everyone can thrive.</p>
                    <p>We cannot achieve our goals alone. 
                        We rely on the support and generosity of individuals, corporations, and 
                        foundations to continue our life-changing work. Your contributions, whether 
                        through financial donations, in-kind support, or volunteering, make a real difference 
                        in the lives of those we serve. Together, we can bring hope and transform communities, 
                        one step at a time.</p>
                    <a href="/contact" class="btn text-white btn-primary rounded-pill mb-4">Contact Us</a>
                </div>
            </div> 		<!-- End row -->
        </div> 

@endsection