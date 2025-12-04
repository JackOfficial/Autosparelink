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
        <p class="fs-5 text-white mb-4">{{ $story->heading }}</p>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active text-white">Stories</li>
        </ol>    
    </div>
</div>
<!-- Header End -->
<div class="container-fluid event py-5">
<div class="container py-5">
	<div class="container">
		<div class="row no-gutters">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>{{ $story->heading }}</h2>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->
			
            <div class="col-md-12">
					<p>
						<div>{!! $story->content !!}</div>
						<div><b>Date:</b> {{ $story->created_at }}</div>
					</p>
            </div>

		</div> <!-- End row -->
	</div> <!-- End container -->
</div> 
</div>
@endsection