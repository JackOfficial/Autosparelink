@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="bg-white p-5 shadow-sm rounded-4 border border-light">
                <div class="mb-4">
                    <i class="fas fa-search-location fa-5x text-muted opacity-50"></i>
                </div>
                
                <h1 class="fw-bold text-dark">Brand Not Found</h1>
                <p class="text-muted mb-4">
                    We couldn't find the vehicle brand you're looking for. It may have been moved, 
                    or the link you followed might be broken.
                </p>

                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4 gap-3">
                        <i class="fas fa-home me-2"></i> Back to Catalog
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-secondary btn-lg px-4">
                        Go Back
                    </a>
                </div>

                <hr class="my-5 opacity-25">

                <h6 class="text-uppercase fw-bold small text-muted mb-3">Or Search for a Brand</h6>
                <form action="{{ route('home') }}" method="GET" class="position-relative">
                    <input type="text" name="search" class="form-control rounded-pill ps-4" 
                           placeholder="Type brand name (e.g. Toyota, BMW)...">
                    <button class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 text-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection