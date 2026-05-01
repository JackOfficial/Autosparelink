@extends('layouts.dashboard')

@section('title', 'My Shipping Addresses')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Header Section --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1">Shipping Addresses</h2>
            <p class="text-muted">Manage locations for your spare parts deliveries.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i>Add New Address
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Addresses Grid --}}
    <div class="row g-4">
        @forelse($addresses as $address)
            <div class="col-md-6 col-xl-4" x-data="{ confirmingDelete: false }">
                <div class="card border-0 shadow-sm rounded-4 h-100 position-relative transition-hover {{ $address->is_default ? 'border-start border-primary border-4' : '' }}">
                    
                    {{-- Default Badge (Absolute Positioned for cleaner UI) --}}
                    @if($address->is_default)
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">
                                <i class="fas fa-star me-1 small"></i> Default
                            </span>
                        </div>
                    @endif

                    <div class="card-body p-4">
                        {{-- Address Label & Type Icon --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-light rounded-3 d-flex align-items-center justify-content-center text-primary" style="width: 45px; height: 45px;">
                                <i class="fas {{ $address->address_name == 'Home' ? 'fa-home' : ($address->address_name == 'Office' ? 'fa-briefcase' : 'fa-map-marker-alt') }} fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold text-dark mb-0">{{ $address->address_name }}</h6>
                                <small class="text-muted">{{ $address->full_name }}</small>
                            </div>
                        </div>

                        <hr class="opacity-50">

                        {{-- Detailed Info --}}
                        <div class="mb-4">
                            <div class="d-flex mb-2">
                                <i class="fas fa-map-pin text-muted me-3 mt-1"></i>
                                <span class="small text-secondary">{{ $address->details }}</span>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="fas fa-city text-muted me-3 mt-1"></i>
                                <span class="small text-secondary">{{ $address->sector ? $address->sector . ', ' : '' }}{{ $address->district }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <i class="fas fa-globe text-muted me-3 mt-1"></i>
                                <span class="small text-uppercase fw-bold text-dark">{{ $address->city }}</span>
                            </div>
                            <div class="d-flex">
                                <i class="fas fa-phone-alt text-success me-3 mt-1"></i>
                                <a href="tel:{{ $address->phone }}" class="small text-decoration-none fw-bold text-dark">{{ $address->phone }}</a>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2 border-top pt-3">
                            <a href="{{ route('user.addresses.edit', $address->id) }}" class="btn btn-light btn-sm flex-grow-1 rounded-3 py-2 fw-semibold">
                                <i class="fas fa-edit me-1 text-primary"></i> Edit
                            </a>
                            
                            <div class="flex-grow-1">
                                <button @click="confirmingDelete = true" x-show="!confirmingDelete" class="btn btn-light btn-sm w-100 rounded-3 py-2 fw-semibold text-danger">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>

                                <div x-show="confirmingDelete" x-cloak class="d-flex gap-1 animate__animated animate__fadeIn">
                                    <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST" class="w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100 rounded-3 py-2">Confirm</button>
                                    </form>
                                    <button @click="confirmingDelete = false" class="btn btn-secondary btn-sm w-100 rounded-3 py-2">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                        <i class="fas fa-map-marked-alt fa-3x text-muted"></i>
                    </div>
                    <h4 class="fw-bold">No Shipping Addresses Found</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 450px;">Please add a delivery location to ensure accurate shipping calculation for your spare parts.</p>
                    <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                        Create Your First Address
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    
    .transition-hover {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }

    /* Alpine.js cloak */
    [x-cloak] { display: none !important; }

    /* Custom Border for Default Address */
    .border-primary.border-4 {
        border-left-width: 6px !important;
    }
</style>
@endsection