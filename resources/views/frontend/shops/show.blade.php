@extends('layouts.app')
@section('content')

@php
    // Dynamic Shop Review Calculations (Only counting approved records)
    $approvedReviews = $shop->reviews->where('status', 'approved');
    $reviewCount = $approvedReviews->count();
    $averageRating = $reviewCount > 0 ? round($approvedReviews->avg('rating'), 1) : 0;
@endphp

<div class="container-fluid">
    <div class="row px-xl-5 mt-3">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ url('/') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="#">Shops</a>
                <span class="breadcrumb-item active">{{ $shop->shop_name }}</span>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
        <span class="bg-secondary pr-3">About the Shop</span>
    </h2>

    <div class="row px-xl-5">

        <div class="col-lg-7 mb-5">
            <div class="bg-white p-4 shadow-sm rounded mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
                    <h3 class="text-dark font-weight-bold mb-0">{{ $shop->shop_name }}</h3>
                    
                    {{-- Star Badging Layout --}}
                    <div class="d-flex align-items-center mt-2 mt-sm-0">
                        <div class="text-warning mr-2 small">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($averageRating) ? 'fa' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <span class="text-muted font-weight-bold small">
                            {{ $averageRating }} ({{ $reviewCount }} {{ Str::plural('Review', $reviewCount) }})
                        </span>
                    </div>
                </div>

                <p class="text-muted mb-4" style="line-height: 1.6;">
                    {{ $shop->description ?? 'Welcome to ' . $shop->shop_name . '. Your premier destination for quality automotive spare parts and reliable merchant support across Kigali.' }}
                </p>
                
                <hr>
                
                <div class="mt-3">
                    <h6 class="text-dark font-weight-bold mb-2"><i class="fa fa-boxes text-primary mr-2"></i>Inventory Scope</h6>
                    <p class="text-muted small mb-0">
                        This store currently manages <strong>{{ $shop->parts->count() }}</strong> unique products hosted on our logistics model pipeline.
                    </p>
                </div>
            </div>

            {{-- SHOP FEEDBACK REVIEWS TRACKING CONTAINER --}}
            <div class="bg-white p-4 shadow-sm rounded">
                <h5 class="text-dark font-weight-bold mb-3"><i class="fa fa-comments text-primary mr-2"></i>Merchant Performance Reviews</h5>
                
                @forelse($approvedReviews as $review)
                    <div class="media border-bottom py-3">
                        <div class="media-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 0.9rem;">
                                    {{ $review->user->name ?? 'Verified Buyer' }}
                                </h6>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    {{ $review->created_at->format('M d, Y') }}
                                </small>
                            </div>
                            
                            <div class="text-warning mb-2 small" style="font-size: 0.75rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fa' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            
                            <p class="text-secondary mb-0 small" style="line-height: 1.4;">
                                {{ $review->comment ?: 'No descriptive written feedback provided.' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="far fa-comments d-block mb-2 fa-2x opacity-5"></i>
                        No dynamic transaction ratings have been tracked for this merchant group yet.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-lg-5 mb-5">

            <div class="bg-light shadow-sm rounded overflow-hidden mb-4">
                <iframe
                    class="w-100"
                    height="260"
                    src="https://maps.google.com/maps?q={{ urlencode($shop->address ?? 'Gisozi, Kigali, Rwanda') }}&t=&z=15&ie=UTF8&iwloc=&output=embed"
                    frameborder="0"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    aria-hidden="false"
                    tabindex="0">
                </iframe>
            </div>

            <div class="bg-white shadow-sm p-4 rounded mb-4">
                <h5 class="mb-3 font-weight-bold text-dark"><i class="fa fa-info-circle text-primary mr-2"></i>Store Verification Details</h5>

                <div class="d-flex align-items-start mb-3">
                    <i class="fa fa-map-marker-alt text-primary fs-4 mr-3 mt-1"></i>
                    <p class="mb-0 text-muted small">{{ $shop->address ?? 'Gisozi, 33P7+5HW, Kigali Rwanda' }}</p>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <i class="fa fa-envelope text-primary fs-4 mr-3 mt-1"></i>
                    <p class="mb-0 text-muted small">{{ $shop->email ?? 'info@autosparelink.com' }}</p>
                </div>

                <div class="d-flex align-items-start">
                    <i class="fa fa-phone-alt text-primary fs-4 mr-3 mt-1"></i>
                    <p class="mb-0 text-muted small">{{ $shop->phone ?? '+250 788 430 122' }}</p>
                </div>
            </div>

            <div class="bg-white shadow-sm p-4 rounded">
                <h5 class="mb-3 font-weight-bold text-dark"><i class="fa fa-shield-alt text-success mr-2"></i>Platform Guarantee</h5>
                <p class="text-muted small mb-2" style="line-height: 1.5;">
                    This merchant functions under strict platform escrow rules. Payments are only clear-released to vendors once your matching tracking status updates seamlessly to <strong>'completed'</strong>.
                </p>
                <span class="badge badge-light text-success p-2 rounded-pill font-weight-semi-bold style-sm">
                    <i class="fa fa-check-circle mr-1"></i> Verified Merchant Entity
                </span>
            </div>

        </div>
    </div>

    <div class="row px-xl-5 mb-5">
        <div class="col-12">
            <div class="bg-white shadow-sm rounded p-4">
                <h4 class="mb-4 text-dark text-center font-weight-bold">Vendor Performance Metrics</h4>

                <div class="row text-center">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <i class="fa fa-cogs text-primary fa-2x mb-3"></i>
                        <h6 class="text-dark font-weight-bold">Guaranteed Fitment</h6>
                        <p class="text-muted small mb-0">Products adhere directly to specified physical vehicle variant configuration arrays.</p>
                    </div>

                    <div class="col-md-4 mb-4 mb-md-0">
                        <i class="fa fa-shipping-fast text-primary fa-2x mb-3"></i>
                        <h6 class="text-dark font-weight-bold">Logistics Fulfillment</h6>
                        <p class="text-muted small mb-0">Items are checked, safely dispatched, and ready for centralized delivery routing systems.</p>
                    </div>

                    <div class="col-md-4">
                        <i class="fa fa-lock text-primary fa-2x mb-3"></i>
                        <h6 class="text-dark font-weight-bold">Secure Escrow Protection</h6>
                        <p class="text-muted small mb-0">Double payments or unsafe distributions are programmatically blocked via lock protections.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection