@extends('layouts.app')
@section('content')
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('storage/'.$event->photo) }});
background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">Event</h1>
        <p class="fs-5 text-white mb-4">{{ $event->event }}</p>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active text-white">Event</li>
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
                <div class="col-lg-12 col-sm-12 mb-12 mb-lg-12 {{ $event->link == null ? 'd-none' : '' }}">
                    <div class="service-block p-2 text-center">
                        <div class="service-icon text-center">
                            <div class="embed-responsive embed-responsive-16by9">
                            <div>{!! $event->link !!}</div>
                    </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <h3>{{ $event->event }}</h3>
                        <p>
                            <div>{!! $event->description !!}</div>
                            <div><b>Date:</b> {{ $event->date }}</div>
                            <div><b>Time:</b> {{ $event->time }}</div>
                            <div><b>Location:</b> {{ $event->location }}</div>
                        </p>
                </div>
    
            </div> <!-- End row -->
        </div> <!-- End container -->
    </section> 
</div>
@endsection