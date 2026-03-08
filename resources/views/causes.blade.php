@extends('layouts.app')
@section('content')
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('storage/headers/team.jpg') }}); 
        background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Our Causes</h1>
                <p class="fs-5 text-white mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active text-white">Causes</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

       <!-- Services Start -->
       <div class="container-fluid service py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                <h5 class="text-uppercase text-primary">What we do</h5>
                <h1 class="mb-0">Our Mission In Action</h1>
            </div>
            <div class="row g-4">
                @foreach ($causes as $cause)
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="service-item">
                        <img src="{{ asset('storage/'.$cause->photo) }}" class="img-fluid w-100" alt="{{ $cause->cause }}">
                        <div class="service-link">
                            <a href="#" class="h4 mb-0">{{ $cause->cause }}</a>
                        </div>
                    </div>
                    <p class="my-4">
                        {{ Str::limit($cause->description, 100) }}
                    </p>
                </div>   
                @endforeach
            </div>
        </div>
    </div>
    <!-- Services End -->

@endsection