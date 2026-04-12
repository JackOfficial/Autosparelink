@extends('layouts.dashboard')

@section('title', 'My Garage')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-5">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">My Garage</h2>
            <p class="text-muted mb-0">Manage your vehicles for precise parts and service recommendations.</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center">
            <i class="fas fa-plus-circle me-2"></i> 
            <span>Add New Vehicle</span>
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-3 fa-lg"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($vehicles as $vehicle)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 position-relative transition-hover overflow-hidden">
                    
                    {{-- Primary Ribbon --}}
                    @if($vehicle->is_primary)
                        <div class="primary-badge">
                            <i class="fas fa-star me-1"></i> Default
                        </div>
                    @endif

                    <div class="card-body p-4 d-flex flex-column">
                        {{-- Vehicle Title & Photo/Icon --}}
                        <div class="d-flex align-items-start mb-4">
                            {{-- Dynamic Photo Handling --}}
                            <div class="rounded-4 overflow-hidden me-3 shadow-sm bg-light" style="width: 70px; height: 70px; min-width: 70px;">
                                @if($vehicle->photo)
                                    <img src="{{ asset('storage/' . $vehicle->photo->file_path) }}" 
                                         alt="{{ $vehicle->brand?->brand_name }}" 
                                         class="w-100 h-100" 
                                         style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 text-primary bg-primary bg-opacity-10">
                                        <i class="fas fa-car fa-2x"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="fw-bold mb-0 text-truncate">
                                    {{ $vehicle->brand?->brand_name ?? 'Unknown Brand' }}
                                </h5>
                                <p class="text-secondary mb-0 text-truncate">
                                    {{ $vehicle->vehicleModel?->model_name ?? 'General Model' }} 
                                    <span class="badge bg-light text-dark border ms-1">{{ $vehicle->production_start }}</span>
                                </p>
                                @if($vehicle->trim_level)
                                    <small class="text-muted d-block mt-1 text-truncate">{{ $vehicle->trim_level }}</small>
                                @endif
                            </div>
                        </div>

                        {{-- Specs Grid --}}
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="bg-light p-2 px-3 rounded-3 border-start border-primary border-3">
                                    <label class="d-block text-muted x-small fw-bold text-uppercase ls-1">Body</label>
                                    <span class="small fw-semibold text-dark">{{ $vehicle->bodyType?->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 px-3 rounded-3 border-start border-info border-3">
                                    <label class="d-block text-muted x-small fw-bold text-uppercase ls-1">Fuel</label>
                                    <span class="small fw-semibold text-dark">{{ $vehicle->engineType?->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="d-flex gap-2 pt-3 border-top mt-auto">
                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-light border btn-sm rounded-pill flex-grow-1 fw-bold text-secondary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Permanently remove this vehicle?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100 fw-bold">
                                    <i class="fas fa-trash-alt me-1"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                    <div class="mb-4">
                        <div class="bg-light d-inline-block rounded-circle p-4">
                            <i class="fas fa-car-side fa-4x text-muted opacity-25"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark">Your garage is looking a bit empty</h4>
                    <p class="text-muted mx-auto mb-4" style="max-width: 400px;">
                        Add your vehicle today to see parts that fit your specific make and model.
                    </p>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                        Add My First Vehicle
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .transition-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important;
    }
    .x-small { font-size: 0.65rem; }
    .ls-1 { letter-spacing: 0.5px; }
    .rounded-4 { border-radius: 1.25rem !important; }
    
    .primary-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #0d6efd;
        color: white;
        padding: 6px 16px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        border-bottom-left-radius: 1.25rem;
        box-shadow: -2px 2px 5px rgba(0,0,0,0.1);
        z-index: 10;
    }

    .border-dashed {
        border: 2px dashed #dee2e6 !important;
    }
</style>
@endsection