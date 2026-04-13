@extends('layouts.app')

@section('title', 'Application Pending - AutoSpareLink')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <div class="display-1 text-primary">
                    <i class="fas fa-clock fa-spin-slow"></i>
                </div>
            </div>

            <h2 class="font-weight-bold mb-3">Application Under Review</h2>
            <p class="lead text-muted px-lg-5">
                Thank you for applying to join the AutoSpareLink marketplace. 
                Our team in Kigali is currently verifying your <strong>RDB Certificate</strong> and <strong>TIN details</strong>.
            </p>

            <div class="card border-0 shadow-sm rounded-lg mt-5 bg-light">
                <div class="card-body p-4 p-md-5">
                    <h5 class="font-weight-bold mb-4">What happens next?</h5>
                    
                    <div class="row text-left">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-primary badge-pill mr-2">1</span>
                                <h6 class="mb-0 font-weight-bold">Verification</h6>
                            </div>
                            <p class="small text-muted">We verify your business registration and physical shop location.</p>
                        </div>
                        
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-secondary badge-pill mr-2">2</span>
                                <h6 class="mb-0 font-weight-bold">Activation</h6>
                            </div>
                            <p class="small text-muted">Once approved, your shop dashboard and inventory tools will be unlocked.</p>
                        </div>

                        <div class="col-md-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge badge-secondary badge-pill mr-2">3</span>
                                <h6 class="mb-0 font-weight-bold">Go Live</h6>
                            </div>
                            <p class="small text-muted">Upload your spare parts and start receiving orders from across Rwanda.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <p class="text-muted">Verification usually takes <strong>12 to 24 hours</strong>.</p>
                <div class="d-flex justify-content-center">
                    <a href="{{ asset('tickets.index') }}" class="btn btn-outline-primary px-4 btn-pill mr-2">
                        <i class="fas fa-envelope mr-1"></i> Contact Support
                    </a>
                    <a href="/" class="btn btn-link text-dark">
                        Return Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fa-spin-slow {
        animation: fa-spin 3s infinite linear;
    }
    .btn-pill {
        border-radius: 50px;
    }
    .badge-pill {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection