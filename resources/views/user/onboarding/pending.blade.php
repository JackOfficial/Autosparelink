@extends('layouts.dashboard') {{-- Or your specific user dashboard layout --}}

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 text-center">
            
            {{-- Status Illustration/Icon --}}
            <div class="mb-4">
                <div class="display-1 text-primary opacity-25">
                    <i class="fas fa-store-slash"></i>
                </div>
                <div class="mt-n5">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm">
                        <i class="fas fa-clock me-1"></i> Review in Progress
                    </span>
                </div>
            </div>

            {{-- Main Content Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-3">Application Received!</h2>
                    <p class="text-muted mb-4">
                        Thank you for applying to join our marketplace. Our team is currently reviewing 
                        <strong>{{ auth()->user()->shop->shop_name }}</strong> to ensure it meets our 
                        vendor quality standards.
                    </p>

                    <div class="bg-light rounded-3 p-3 mb-4 text-start">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">What happens next?</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i> 
                                Verification of your shop details.
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i> 
                                Approval of your vendor account (usually within 24-48 hours).
                            </li>
                            <li>
                                <i class="fas fa-check-circle text-success me-2"></i> 
                                Access to your Merchant Dashboard to upload spare parts.
                            </li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('vehicles.index') }}" class="btn btn-primary rounded-pill py-2 fw-bold">
                            Back to My Garage
                        </a>
                        <p class="small text-muted mt-3">
                            Need help? <a href="{{ route('tickets.create') }}" class="text-decoration-none">Contact Support</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Progress Indicator --}}
            <div class="mt-4 px-5">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mt-2 small text-muted">
                    <span>Applied</span>
                    <span class="fw-bold text-primary">Verification</span>
                    <span>Live</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection