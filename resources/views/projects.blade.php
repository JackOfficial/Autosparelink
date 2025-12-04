@extends('layouts.app')
@section('content')
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('storage/headers/team.jpg') }});
        background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Our Project</h1>
                <p class="fs-5 text-white mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active text-white">Projects</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

        <!-- Causes Start -->
        <div class="container-fluid causes py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Recent Projects</h5>
                    <h1 class="mb-4">Our Projects</h1>
                    <p class="mb-0">
                        At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.
                    </p>
                </div>
                <div class="row g-4">
                    @foreach ($projects as $project)
                    <div class="col-lg-6 col-md-6 col-xl-4">
                        <div class="causes-item">
                            <div class="causes-img">
                                <img src="{{ asset('storage/'.$project->photo) }}" class="img-fluid w-100" alt="{{ $project->project }}">
                                <div class="causes-link pb-2 px-3">
                                    <small class="text-white"><i class="fas fa-chart-bar text-primary me-2"></i>Goal: $3600</small>
                                    <small class="text-white"><i class="fa fa-thumbs-up text-primary me-2"></i>Raised: $4000</small>
                                </div>
                                <div class="causes-dination p-2">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="#">Donate Now</a>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                    <span>65%</span>
                                </div>
                            </div>
                            <div class="causes-content p-4">
                                <h4 class="mb-3">{{ $project->project }}</h4>
                                <p class="mb-4">
                                    {{ Str::limit(strip_tags($project->description), 100) }}
                                </p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="project/{{ $project->id }}">Read More</a>
                            </div>
                        </div>
                    </div> 
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Causes End -->

@endsection