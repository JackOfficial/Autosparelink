@extends('layouts.app')

@section('content')
<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <span class="badge bg-primary-soft text-primary mb-3">Become a Partner</span>
                <h1 class="display-4 fw-bold mb-4">Grow your Spare Parts Business in Rwanda.</h1>
                <p class="lead text-muted mb-5">Join the most trusted marketplace for auto parts. Reach thousands of mechanics and car owners across the country.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('shop.register.form') }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow">
                        Start Selling Now
                    </a>
                    <a href="#benefits" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                <img src="{{ asset('images/onboarding-hero.png') }}" class="img-fluid" alt="Marketplace" style="max-height: 400px;">
            </div>
        </div>

        <div id="benefits" class="row g-4 py-5">
           <div class="col-md-4">
    <div class="card h-100 border-0 shadow-sm p-3 text-center rounded-4">
        <div class="mb-3 text-primary"><i class="fas fa-users fa-3x"></i></div>
        <h5 class="fw-bold">Wider Reach</h5>
        <p class="small text-muted">Don't wait for walk-in customers. Expand your reach from Kigali to every province across Rwanda and beyond.</p>
    </div>
</div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-3 text-center rounded-4">
                    <div class="mb-3 text-success"><i class="fas fa-percentage fa-3x"></i></div>
                    <h5 class="fw-bold">Low Commissions</h5>
                    <p class="small text-muted">Keep more of your profit. We only take a small fee when you make a sale.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm p-3 text-center rounded-4">
                    <div class="mb-3 text-warning"><i class="fas fa-shield-alt fa-3x"></i></div>
                    <h5 class="fw-bold">Secure Payments</h5>
                    <p class="small text-muted">Get paid safely through Mobile Money or Bank Transfer on every successful delivery.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-4 p-5 shadow-sm mt-5">
            <h4 class="fw-bold mb-4 text-center">What you'll need to join:</h4>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-primary mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">RDB Business Registration</h6>
                            <p class="small text-muted">A clear digital copy (PDF or Image) of your certificate.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-check-circle text-primary mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">TIN Number</h6>
                            <p class="small text-muted">Your valid RRA Tax Identification Number.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="fas fa-check-circle text-primary mt-1 me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Personal ID</h6>
                            <p class="small text-muted">A copy of your National ID or Passport for owner verification.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection