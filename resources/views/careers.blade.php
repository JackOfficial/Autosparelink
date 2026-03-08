@extends('layouts.app')
@section('content')

     <!-- Header Start -->
     <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('frontend/img/breadcrumb-bg.jpg') }});
     background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="text-white display-3 mb-4">Career</h1>
            <p class="fs-5 text-white mb-4">
                Our Careers
At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.
            </p>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active text-white">Career</li>
            </ol>    
        </div>
    </div>
    <!-- Header End -->

<div class="container mt-5">
        <div class="title text-center mb-5">
                  <h2>{{ $careers->count() }} Job {{ ($careers->count()) ? 'Posts' : 'Post' }}</h2>
                  <div class="border"></div>
              </div>
   <div class="row">
    @forelse ($careers as $career)
    <div class="col-md-4 mb-5">
        <div class="card bg-dark">
            <div class="card-body">
              <h5 class="card-title text-white">{{ $career->title }}</h5>
              <p class="card-text"><div class="badge badge-light">{{ $career->jobtype }}</div></p>
              <p class="card-text"><i class="fa fa-calendar"></i> Posted on: {{ $career->created_at }}</p>
              <a href="/job-details/{{ $career->id}}" class="btn btn-sm rounded-pill btn-outline-primary">Read More</a>
            </div>
          </div>
      </div> 
      @empty
      <div class="col-md-12 mb-5 text-center">
        No Job available at the moment!
    </div>
    @endforelse
 
   </div>
  </div>
  
  <div class="container jumbotron bg-dark p-3 border border-rounded mb-4 text-center">
    <div class="">
        <p>Each cause we support is carefully chosen based on the needs of the communities we work with and the potential for sustainable impact.</p> 
        <p>With your support, we can continue to make a difference and bring hope to those who need it most.</p> 
        <p>Join us in our mission by donating, volunteering, or spreading awareness about the causes we champion. Together, we can build a better future for all.</p>
      </div>
     <div>
      <a href="/donate" class="btn btn-primary rounded">Donate Now</a>
    </div>
  </div>

@endsection