@extends('layouts.dashboard')

@section('title', 'My Garage')

@section('content')
<div class="container py-4 py-lg-5">
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">My Garage</h2>
            <p class="text-muted small">Manage your saved vehicles for personalized part recommendations.</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Add Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        @forelse($vehicles as $vehicle)
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 position-relative overflow-hidden">
                    {{-- Primary Badge --}}
                    @if($vehicle->is_primary)
                        <span class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 small fw-bold rounded-start-pill mt-3 shadow-sm">
                            <i class="fas fa-star me-1"></i> Primary
                        </span>
                    @endif

                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-light rounded-circle p-3 me-3">
                                <i class="fas fa-car-side fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0">{{ $vehicle->brand->brand_name }}</h5>
                                <span class="text-muted small">{{ $vehicle->vehicleModel->model_name }} ({{ $vehicle->production_start }})</span>
                            </div>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded-3">
                                    <label class="d-block text-muted x-small fw-bold text-uppercase">Body</label>
                                    <span class="small fw-semibold">{{ $vehicle->bodyType->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded-3">
                                    <label class="d-block text-muted x-small fw-bold text-uppercase">Fuel</label>
                                    <span class="small fw-semibold">{{ $vehicle->engineType->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2 border-top pt-3">
                            <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill flex-grow-1">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Remove this vehicle from your garage?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill w-100">
                                    <i class="fas fa-trash-alt me-1"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <div class="mb-3">
                        <i class="fas fa-car-crash fa-3x text-light"></i>
                    </div>
                    <h5 class="fw-bold">Your garage is empty</h5>
                    <p class="text-muted small">Add your first vehicle to get started.</p>
                    <a href="{{ route('vehicles.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                        Add New Vehicle
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .x-small { font-size: 0.65rem; }
    .rounded-4 { border-radius: 1rem !important; }
    .btn-sm { padding: 0.4rem 1rem; }
    .card { transition: transform 0.2s ease; }
    .card:hover { transform: translateY(-5px); }
</style>
@endsection