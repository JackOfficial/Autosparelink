@extends('layouts.dashboard')

@section('title', 'My Shipping Addresses')

@section('content')
<div class="container py-4 py-lg-5">
    
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Shipping Addresses</h2>
            <p class="text-muted small mb-0">Manage your delivery locations for faster checkout.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-plus me-2"></i> Add New Address
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($addresses as $address)
            <div class="col-md-6 col-lg-4" x-data="{ confirmingDelete: false }">
                <div class="card border-0 shadow-sm rounded-4 h-100 position-relative {{ $address->is_default ? 'border-start border-primary border-4' : '' }}">
                    
                    @if($address->is_default)
                        <span class="badge bg-primary position-absolute top-0 end-0 m-3 rounded-pill px-3">
                            Default
                        </span>
                    @endif

                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                <i class="fas {{ $address->address_name == 'Home' ? 'fa-home' : ($address->address_name == 'Office' ? 'fa-briefcase' : 'fa-map-marker-alt') }} fa-lg"></i>
                            </div>
                            <h5 class="fw-bold mb-0 text-dark text-truncate" style="max-width: 150px;">
                                {{ $address->address_name }}
                            </h5>
                        </div>

                        <div class="mb-3">
                            <div class="fw-bold text-dark small mb-1">{{ $address->full_name }}</div>
                            <div class="text-muted small">
                                <i class="fas fa-phone-alt me-1"></i> {{ $address->phone }}
                            </div>
                        </div>

                        <div class="text-muted small mb-4 lh-base">
                            {{ $address->details }}<br>
                            {{ $address->sector ? $address->sector . ',' : '' }} {{ $address->district }}<br>
                            <strong>{{ $address->city }}</strong>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2 pt-3 border-top">
                            <a href="{{ route('user.addresses.edit', $address->id) }}" class="btn btn-light btn-sm rounded-pill px-3 flex-grow-1">
                                <i class="fas fa-edit me-1 text-muted"></i> Edit
                            </a>
                            
                            {{-- Alpine-powered Delete --}}
                            <div class="flex-grow-1">
                                <button @click="confirmingDelete = true" x-show="!confirmingDelete" class="btn btn-light btn-sm rounded-pill px-3 w-100 text-danger">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>

                                <div x-show="confirmingDelete" x-cloak class="d-flex gap-1 animate__animated animate__fadeIn">
                                    <form action="{{ route('user.addresses.destroy', $address->id) }}" method="POST" class="w-100">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-2 w-100">Confirm</button>
                                    </form>
                                    <button @click="confirmingDelete = false" class="btn btn-secondary btn-sm rounded-pill px-2 w-100">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <div class="opacity-25 mb-3">
                        <i class="fas fa-map-marked-alt fa-4x"></i>
                    </div>
                    <h5 class="text-muted">No addresses saved yet</h5>
                    <p class="small text-muted mb-4">Add your shipping details now for a smoother checkout experience.</p>
                    <div>
                        <a href="{{ route('user.addresses.create') }}" class="btn btn-primary rounded-pill px-5">Add My First Address</a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .ls-1 { letter-spacing: 0.5px; }
    .border-4 { border-left-width: 4px !important; }
    
    /* Subtle hover scale for the address cards */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }

    [x-cloak] { display: none !important; }
</style>
@endsection