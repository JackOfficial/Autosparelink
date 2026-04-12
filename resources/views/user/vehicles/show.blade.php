@extends('layouts.dashboard')

@section('title', 'Vehicle Details')

@section('content')
<div class="container py-4 py-lg-5">
    
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vehicles.index') }}" class="text-decoration-none text-muted small">Garage</a></li>
            <li class="breadcrumb-item active small fw-bold" aria-current="page">{{ $vehicle->brand?->brand_name }} {{ $vehicle->vehicleModel?->model_name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Left Column: Main Info --}}
        <div class="col-lg-8">
            {{-- Vehicle Hero Photo Card --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative">
                    @if($vehicle->vehicle_photo)
                        <img src="{{ asset('storage/' . $vehicle->vehicle_photo) }}" class="img-fluid w-100" style="height: 350px; object-fit: cover;" alt="Vehicle Image">
                    @else
                        <div class="bg-light d-flex flex-column align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-car fa-4x text-primary opacity-25 mb-3"></i>
                            <p class="text-muted small fw-bold">No photo available</p>
                        </div>
                    @endif
                    
                    @if($vehicle->is_primary)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">
                                <i class="fas fa-star me-1"></i> Default Vehicle
                            </span>
                        </div>
                    @endif
                </div>

                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                            <i class="fas fa-info-circle fa-lg"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold text-dark mb-0">{{ $vehicle->brand?->brand_name }} {{ $vehicle->vehicleModel?->model_name }}</h2>
                            <p class="text-muted small mb-0">Registered Technical Specifications</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- Technical Specs Grid --}}
                        <div class="col-sm-6 col-md-4">
                            <div class="p-3 bg-light rounded-4 border-start border-primary border-4">
                                <label class="d-block text-muted small fw-bold text-uppercase mb-1">Production Year</label>
                                <span class="h5 fw-bold text-dark mb-0">{{ $vehicle->production_start }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="p-3 bg-light rounded-4 border-start border-info border-4">
                                <label class="d-block text-muted small fw-bold text-uppercase mb-1">Trim / Variant</label>
                                <span class="h5 fw-bold text-dark mb-0 text-truncate d-block">{{ $vehicle->trim_level ?? 'Standard' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="p-3 bg-light rounded-4 border-start border-success border-4">
                                <label class="d-block text-muted small fw-bold text-uppercase mb-1">Fuel Type</label>
                                <span class="h5 fw-bold text-dark mb-0">{{ $vehicle->engineType?->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Chassis & Drivetrain</h6>
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted py-2" width="40%">Body Type</td>
                                            <td class="fw-semibold py-2">{{ $vehicle->bodyType?->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">Transmission</td>
                                            <td class="fw-semibold py-2">{{ $vehicle->transmission_type_id ? App\Models\TransmissionType::find($vehicle->transmission_type_id)->name : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">Engine Displacement</td>
                                            <td class="fw-semibold py-2">{{ $vehicle->displacement ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">Steering Position</td>
                                            <td class="fw-semibold py-2">
                                                {{ $vehicle->steering_position == 'LHD' ? 'Left Hand Drive' : 'Right Hand Drive' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted py-2">VIN / Chassis Number</td>
                                            <td class="py-2">
                                                <code class="bg-light px-2 py-1 rounded text-primary fw-bold">{{ $vehicle->vin ?? 'Not Provided' }}</code>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Actions & Quick Help --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <h6 class="fw-bold mb-3">Quick Actions</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-outline-primary rounded-pill fw-bold">
                        <i class="fas fa-edit me-2"></i> Edit Specifications
                    </a>
                    
                    <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger w-100 text-decoration-none small fw-bold mt-2">
                            <i class="fas fa-trash-alt me-2"></i> Remove from Garage
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 bg-primary text-white rounded-4 p-4 shadow-sm">
                <div class="mb-3">
                    <i class="fas fa-tools fa-2x opacity-50"></i>
                </div>
                <h6 class="fw-bold">Smart Parts Finder</h6>
                <p class="small mb-4 opacity-75">We use your vehicle's VIN and specs to filter the best spare parts for you.</p>
                <a href="#" class="btn btn-white btn-sm rounded-pill fw-bold w-100 py-2">Browse Parts for this Car</a>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-light { background-color: #f8f9fa !important; }
    .btn-white { background: #fff; color: #0d6efd; }
    .btn-white:hover { background: #f8f9fa; color: #0a58ca; }
    code { font-size: 0.9em; letter-spacing: 1px; }
</style>
@endsection