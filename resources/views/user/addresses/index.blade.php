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
            <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
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
                    
                    {{-- Default Badge --}}
                    @if($address->is_default)
                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <span class="badge bg-primary-soft text-primary rounded-pill px-3 py-2 border border-primary-subtle">
                                <i class="fas fa-star me-1 small"></i> Default
                            </span>
                        </div>
                    @endif

                    <div class="card-body p-4 d-flex flex-column">
                        {{-- Address Label --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary shadow-sm" style="width: 48px; height: 48px;">
                                <i class="fas {{ $address->address_name == 'Home' ? 'fa-home' : ($address->address_name == 'Office' ? 'fa-briefcase' : 'fa-map-marker-alt') }} fa-lg"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="fw-bold text-dark mb-0">{{ $address->address_name }}</h6>
                                <small class="text-muted fw-medium">{{ $address->full_name }}</small>
                            </div>
                        </div>

                        <hr class="my-3 opacity-25">

                        {{-- Info List --}}
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-start mb-2">
                                <i class="fas fa-map-pin text-primary opacity-50 me-3 mt-1"></i>
                                <span class="small text-dark">{{ $address->details }}</span>
                            </div>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fas fa-city text-primary opacity-50 me-3 mt-1"></i>
                                <span class="small text-dark">{{ $address->sector ? $address->sector . ', ' : '' }}{{ $address->district }}</span>
                            </div>
                            <div class="d-flex align-items-start mb-2">
                                <i class="fas fa-phone-alt text-success opacity-50 me-3 mt-1"></i>
                                <a href="tel:{{ $address->phone }}" class="small text-decoration-none fw-bold text-dark">{{ $address->phone }}</a>
                            </div>
                        </div>

                        {{-- Refined Action Buttons --}}
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-grid gap-2">
                                <a href="{{ route('user.addresses.edit', $address->id) }}" class="btn btn-outline-primary border-2 btn-sm rounded-3 py-2 fw-bold">
                                    <i class="fas fa-edit me-2"></i> Edit Location
                                </a>
                                
                                <div class="w-100">
                                    <button @click="confirmingDelete = true" x-show="!confirmingDelete" class="btn btn-link text-danger text-decoration-none btn-sm w-100 py-2 fw-bold shadow-none">
                                        <i class="fas fa-trash-alt me-2"></i> Remove Address
                                    </button>

                                    <div x-show="confirmingDelete" x-cloak class="d-flex gap-2 animate__animated animate__fadeIn">
                                        <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST" class="flex-grow-1">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100 rounded-3 py-2 fw-bold">Delete Now</button>
                                        </form>
                                        <button @click="confirmingDelete = false" class="btn btn-light btn-sm flex-grow-1 rounded-3 py-2 fw-bold">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="card border-0 shadow-sm rounded-4 p-5 bg-white">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                        <i class="fas fa-map-marked-alt fa-3x text-muted"></i>
                    </div>
                    <h4 class="fw-bold">Your Address Book is Empty</h4>
                    <p class="text-muted mb-4">Add your delivery locations to expedite your checkout process.</p>
                    <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                        Add New Address
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Soft UI Styles */
    .bg-primary-soft { background-color: rgba(13, 110, 253, 0.1) !important; }
    .rounded-4 { border-radius: 1.2rem !important; }
    
    .transition-hover {
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
        border: 1px solid transparent !important;
    }

    .transition-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        border-color: rgba(13, 110, 253, 0.1) !important;
    }

    .border-primary.border-4 {
        border-left: 6px solid #0d6efd !important;
    }

    /* Alpine.js cloak */
    [x-cloak] { display: none !important; }

    /* Custom scroll for dashboard if needed */
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: #fff;
    }
</style>
@endsection