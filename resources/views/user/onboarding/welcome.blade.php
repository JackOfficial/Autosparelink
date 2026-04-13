@extends('layouts.app')

@section('content')
<style>
    /* Custom styles to replicate modern feel in Bootstrap 4 */
    .rounded-lg { border-radius: 1rem !important; }
    .bg-primary-light { background-color: rgba(0, 123, 255, 0.1); color: #007bff; }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; }
    .btn-pill { border-radius: 50px; }
    .opacity-75 { opacity: 0.75; }
    .opacity-50 { opacity: 0.5; }
</style>

<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <span class="badge badge-pill bg-primary-light px-3 py-2 mb-3">Become a Partner</span>
                <h1 class="display-4 font-weight-bold mb-4">Grow your Spare Parts Business in Rwanda.</h1>
                <p class="lead text-muted mb-5">Join the most trusted marketplace for auto parts. Reach thousands of mechanics and car owners across the country.</p>
                
                <div class="d-flex">
                    <a href="{{ route('shop.register.form') }}" class="btn btn-primary btn-lg btn-pill px-4 font-weight-bold shadow mr-3">
                        Start Selling Now
                    </a>
                    <a href="#benefits" class="btn btn-secondary btn-lg btn-pill px-4">
                        Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 mt-5 mt-lg-0 text-center">
                <img src="{{ asset('images/onboarding-hero.png') }}" class="img-fluid" alt="Marketplace" style="max-height: 400px;">
            </div>
        </div>

        <div id="benefits" class="row py-5">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm p-3 text-center rounded-lg">
                    <div class="card-body">
                        <div class="mb-3 text-primary"><i class="fas fa-users fa-3x"></i></div>
                        <h5 class="font-weight-bold">Wider Reach</h5>
                        <p class="small text-muted">Don't wait for walk-in customers. Expand your reach from Kigali to every province across Rwanda and beyond.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm p-3 text-center rounded-lg">
                    <div class="card-body">
                        <div class="mb-3 text-success"><i class="fas fa-percentage fa-3x"></i></div>
                        <h5 class="font-weight-bold">Low Commissions</h5>
                        <p class="small text-muted">Keep more of your profit. We only take a small fee when you make a sale.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm p-3 text-center rounded-lg">
                    <div class="card-body">
                        <div class="mb-3 text-warning"><i class="fas fa-shield-alt fa-3x"></i></div>
                        <h5 class="font-weight-bold">Secure Payments</h5>
                        <p class="small text-muted">Get paid safely through Mobile Money or Bank Transfer on every successful delivery.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm mt-5">
            <h4 class="font-weight-bold mb-4 text-center">What you'll need to join:</h4>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="media mb-4">
                        <i class="fas fa-check-circle text-primary fa-lg mt-1 mr-3"></i>
                        <div class="media-body">
                            <h6 class="mb-0 font-weight-bold">RDB Business Registration</h6>
                            <p class="small text-muted">A clear digital copy (PDF or Image) of your certificate.</p>
                        </div>
                    </div>
                    <div class="media mb-4">
                        <i class="fas fa-check-circle text-primary fa-lg mt-1 mr-3"></i>
                        <div class="media-body">
                            <h6 class="mb-0 font-weight-bold">TIN Number</h6>
                            <p class="small text-muted">Your valid RRA Tax Identification Number.</p>
                        </div>
                    </div>
                    <div class="media">
                        <i class="fas fa-check-circle text-primary fa-lg mt-1 mr-3"></i>
                        <div class="media-body">
                            <h6 class="mb-0 font-weight-bold">Personal ID</h6>
                            <p class="small text-muted">A copy of your National ID or Passport for owner verification.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-primary text-white border-0 rounded-lg shadow p-4 p-md-5 text-center">
                    <div class="card-body">
                        <h2 class="font-weight-bold mb-3">Ready to boost your sales?</h2>
                        <p class="lead mb-4 opacity-75">Join dozens of successful spare parts dealers in Rwanda already selling with us.</p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center">
                            <a href="{{ route('shop.register.form') }}" class="btn btn-light btn-lg btn-pill px-5 font-weight-bold text-primary m-2">
                                Create Your Shop Account
                            </a>
                            <a href="{{ route('terms') }}" class="btn btn-outline-light btn-lg btn-pill px-4 m-2">
                                View Partner Terms
                            </a>
                        </div>
                        <p class="mt-4 small opacity-50">Registration usually takes less than 5 minutes if you have your documents ready.</p>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center mt-5 text-muted small">
            &copy; {{ date('Y') }} AutoSpareLink Rwanda. All rights reserved.
        </p>
    </div>
</div>
@endsection