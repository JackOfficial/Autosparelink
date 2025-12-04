@extends('layouts.app')
@section('content')
        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb" style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url({{ asset('frontend/img/breadcrumb-bg.jpg') }});
        background-position: center center;
         background-repeat: no-repeat;
         background-size: cover;
         padding: 100px 0 0 0;">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Upcoming Events</h1>
                <p class="fs-5 text-white mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active text-white">Events</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

        <!-- Events Start -->
        <div class="container-fluid event py-5">
            <div class="container py-5 {{ $upcomingEvents->count() == 0 ? 'd-none' : '' }}">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Upcoming Events</h5>
                    <h1 class="mb-0 ">Help today because tomorrow you may be the one who needs more helping!</h1>
                </div>
                <div class="event-carousel owl-carousel">
                    @forelse($upcomingEvents as $event)
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
                    @empty
                    <div>
                        No Upcomming event!
                    </div>
                    @endforelse
                   
                </div>
            </div>
            <div class="container py-5">
           <h4 class="text-center">No upcomming event!</h4>
            </div>

            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Passed Events</h5>
                    <h1 class="mb-0">Help today because tomorrow you may be the one who needs more helping!</h1>
                </div>
                <div class="event-carousel owl-carousel">
                    @forelse($passedEvents as $event)
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
                    @empty
                    <div>
                        No Passed event!
                    </div>
                    @endforelse
                   
                </div>
            </div>
        </div>
        <!-- Events End -->

@endsection