@extends('layouts.dashboard')

@section('title', 'My Shipping Addresses')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">Shipping Addresses</h2>
            <p class="text-muted mb-0">Manage your delivery locations for faster checkout on your spare parts.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-3 px-4 py-2 fw-bold shadow-sm d-flex align-items-center">
                <i class="fas fa-plus-circle me-2"></i> Add New Address
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="row g-4">
        @forelse($addresses as $address)
            <div class="col-md-6 col-xxl-4" x-data="{ confirmingDelete: false }">
                <div class="card border-0 shadow-sm rounded-4 h-100 address-card {{ $address->is_default ? 'is-default' : '' }}">
                    
                    <div class="card-body p-4">
                        {{-- Top Row: Icon & Badge --}}
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="icon-shape {{ $address->is_default ? 'bg-soft-primary text-primary' : 'bg-soft-secondary text-muted' }} rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                <i class="fas {{ $address->address_name == 'Home' ? 'fa-home' : ($address->address_name == 'Office' ? 'fa-briefcase' : 'fa-map-marker-alt') }} fa-lg"></i>
                            </div>
                            
                            @if($address->is_default)
                                <span class="badge bg-soft-primary text-primary border border-primary-subtle rounded-pill px-3 py-2 small">
                                    <i class="fas fa-star me-1"></i> Default
                                </span>
                            @endif
                        </div>

                        {{-- Address Content --}}
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-1">{{ $address->address_name }}</h5>
                            <p class="fw-semibold text-dark small mb-3">{{ $address->full_name }}</p>
                            
                            <div class="text-muted small lh-lg">
                                <p class="mb-1"><i class="fas fa-map-pin me-2 text-primary opacity-50"></i>{{ $address->details }}</p>
                                <p class="mb-1"><i class="fas fa-city me-2 text-primary opacity-50"></i>{{ $address->sector ? $address->sector . ', ' : '' }}{{ $address->district }}</p>
                                <p class="mb-3 font-monospace text-uppercase">{{ $address->city }}</p>
                                
                                <a href="tel:{{ $address->phone }}" class="text-decoration-none text-muted">
                                    <i class="fas fa-phone-alt me-2 text-success opacity-75"></i> {{ $address->phone }}
                                </a>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2 mt-auto pt-3 border-top border-light">
                            <a href="{{ route('user.addresses.edit', $address->id) }}" class="btn btn-outline-light border-light text-dark btn-sm rounded-3 flex-grow-1 py-2 action-btn">
                                <i class="fas fa-pen-nib me-1 text-primary"></i> Edit
                            </a>
                            
                            <div class="flex-grow-1">
                                <button @click="confirmingDelete = true" x-show="!confirmingDelete" class="btn btn-outline-light border-light text-danger btn-sm rounded-3 w-100 py-2 action-btn">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>

                                <div x-show="confirmingDelete" x-cloak class="d-flex gap-1 animate__animated animate__pulse">
                                    <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST" class="w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3 w-100 py-2">Confirm</button>
                                    </form>
                                    <button @click="confirmingDelete = false" class="btn btn-light btn-sm rounded-3 w-100 py-2">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                    <div class="bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="fas fa-map-marked-alt fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-dark">No shipping addresses</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">Save your home or office address to make ordering spare parts faster and easier.</p>
                    <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-3 px-5 py-2 fw-bold">
                        Add Your First Address
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Soft UI Customizations */
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.08) !important; }
    .bg-soft-secondary { background-color: #f8f9fa !important; }
    
    .address-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent !important;
    }

    .address-card.is-default {
        border: 1px solid rgba(13, 110, 253, 0.2) !important;
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
    }

    .address-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.07) !important;
    }

    .action-btn:hover {
        background-color: #ffffff !important;
        border-color: #dee2e6 !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .rounded-4 { border-radius: 1.25rem !important; }
    
    [x-cloak] { display: none !important; }
</style>
@endsection