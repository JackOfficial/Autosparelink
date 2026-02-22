@extends('layouts.app')

@section('style')
<style>
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .filter-card .form-control, .filter-card .btn { height: calc(2.2rem + 2px); font-size: 0.9rem; }
    .brand-logo-header { width: 50px; height: auto; margin-right: 15px; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .tech-label { font-size: 0.75rem; text-transform: uppercase; color: #86868b; font-weight: 600; }
    .tech-value { font-size: 0.9rem; color: #1d1d1f; }
</style>
@endsection

@section('content')

<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="#">{{ $item->vehicleModel->brand->brand_name }}</a>
                <a class="breadcrumb-item text-dark" href="#">{{ $item->vehicleModel->model_name }}</a>
                <span class="breadcrumb-item active">{{ $item->name }}</span>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3 d-flex align-items-center border-start border-primary border-5">
        @if($item->vehicleModel->brand->brand_logo)
            <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" class="brand-logo-header" alt="Logo" />
        @endif
        <div>
            <h4 class="text-uppercase mb-1" style="font-weight: 700; letter-spacing: -0.5px;">
                {{ $item->vehicleModel->brand->brand_name }} {{ $item->name }}
            </h4>
            <div class="d-flex align-items-center">
                <span class="badge bg-dark me-2">{{ $item->vehicleModel->model_name }}</span>
                <small class="text-muted">Technical Data Sheet & Component Specifications</small>
            </div>
        </div>
    </div>
</div>

{{-- Filters remain for refining specific tech details --}}
<div class="container-fluid px-xl-5 mb-3">
    <div class="card shadow-sm filter-card border-0">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="steering_position" class="form-control">
                            <option value="">Steering Side</option>
                            <option value="LEFT" {{ request('steering_position') == 'LEFT' ? 'selected' : '' }}>Left Hand Drive (LHD)</option>
                            <option value="RIGHT" {{ request('steering_position') == 'RIGHT' ? 'selected' : '' }}>Right Hand Drive (RHD)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="drive" class="form-control">
                            <option value="">Drivetrain</option>
                            @foreach($driveTypes as $drive)
                                <option value="{{ $drive->id }}" {{ request('drive') == $drive->id ? 'selected' : '' }}>{{ $drive->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="query" class="form-control" placeholder="Search by Engine Code or Specs..." value="{{ request('query') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">Update View</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid px-xl-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Engine Code / ID</th>
                            <th>Performance</th>
                            <th>Capacities</th>
                            <th>Weights & Chassis</th>
                            <th>Technical Features</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-primary" style="font-size: 1.1rem;">{{ $spec->engine_code ?? 'M-SPEC' }}</div>
                                    <small class="text-muted">{{ $spec->steering_position }} Hand Drive</small>
                                </td>
                                
                                <td>
                                    <div class="tech-label">Power Output</div>
                                    <div class="tech-value fw-bold">{{ $spec->horsepower ?? '-' }} HP / {{ $spec->torque ?? '-' }} Nm</div>
                                    <div class="tech-label mt-1">Top Speed</div>
                                    <small class="tech-value">{{ $spec->top_speed ?? '-' }} km/h</small>
                                </td>

                                <td>
                                    <div class="tech-label">Fuel Tank</div>
                                    <div class="tech-value">{{ $spec->fuel_capacity ?? '-' }} Liters</div>
                                    <div class="tech-label mt-1">Oil Capacity</div>
                                    <small class="tech-value">{{ $spec->oil_capacity ?? '-' }} L</small>
                                </td>

                                <td>
                                    <div class="tech-label">Curb Weight</div>
                                    <div class="tech-value">{{ $spec->curb_weight ?? '-' }} kg</div>
                                    <div class="tech-label mt-1">Wheel / Tire</div>
                                    <small class="tech-value">{{ $spec->tire_size ?? '-' }}</small>
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($spec->abs) <span class="badge border text-dark fw-normal bg-light">ABS</span> @endif
                                        @if($spec->air_conditioning) <span class="badge border text-dark fw-normal bg-light">AC</span> @endif
                                        @if($spec->turbo) <span class="badge border text-dark fw-normal bg-light">Turbo</span> @endif
                                    </div>
                                    <div class="mt-2">
                                        <div class="tech-label">Emission</div>
                                        <small class="tech-value text-uppercase">{{ $spec->emission_standard ?? 'N/A' }}</small>
                                    </div>
                                </td>

                                <td class="text-center pe-4">
                                    {{-- {{ route('parts.index', ['spec' => $spec->id]) }} --}}
                                    <a href="" class="btn btn-dark btn-sm rounded-pill px-4">
                                        Exploded Views <i class="fa fa-diagram-project ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fa fa-info-circle fa-2x mb-3 text-muted opacity-50"></i>
                                    <p class="text-muted">No specific technical configurations match these filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection