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
    <div class="row px-xl-5 mt-2">
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
                <small class="text-muted">Detailed Technical Specifications & Market Configurations</small>
            </div>
        </div>
    </div>
</div>

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
                            <option value="">Drivetrain (All)</option>
                            @foreach($driveTypes as $drive)
                                <option value="{{ $drive->id }}" {{ request('drive') == $drive->id ? 'selected' : '' }}>{{ $drive->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="query" class="form-control" placeholder="Search by destination or color..." value="{{ request('query') }}">
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
                        <tr class="text-muted" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">
                            <th class="ps-4">Identification / Period</th>
                            <th>Performance</th>
                            <th>Efficiency & Fuel</th>
                            <th>Configuration & Drive</th>
                            <th>Exterior</th>
                            <th class="text-center pe-4">Catalog</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr>
                                {{-- Market, Chassis, Model Code & Period --}}
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $spec->model_code ?? 'MODEL-CODE' }}</div>
                                    <div class="text-primary small fw-bold">{{ $spec->chassis_code ?? 'CHASSIS' }}</div>
                                    <small class="text-muted d-block mt-1">
                                        {{ $spec->destination ?? 'Global' }} | {{ $spec->production_start ?? 'N/A' }} — {{ $spec->production_end ?? 'Present' }}
                                    </small>
                                </td>

                                {{-- Performance --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-primary">{{ $spec->horsepower ?? '-' }} HP</span>
                                        <small class="text-muted">{{ $spec->torque ?? '-' }} Nm Torque</small>
                                    </div>
                                </td>

                                {{-- Efficiency & Fuel --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark">{{ $spec->fuel_efficiency ?? '-' }}L/100 KM</span>
                                        <small class="text-muted">{{ $spec->fuel_capacity ?? '-' }}L Capacity</small>
                                    </div>
                                </td>

                                {{-- Configuration & DRIVE TYPE --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold">{{ $spec->driveType->name ?? 'Drive N/A' }}</span>
                                        <small class="text-muted">{{ $spec->steering_position }} Hand Drive</small>
                                        <small class="text-muted">{{ $spec->seats ?? '-' }} Seats / {{ $spec->doors ?? '-' }} Doors</small>
                                    </div>
                                </td>

                                {{-- Exterior --}}
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($spec->color && str_starts_with($spec->color, '#'))
                                            <span class="me-2" style="display:inline-block; width:15px; height:15px; border-radius:50%; background-color:{{ $spec->color }}; border:1px solid #ddd;"></span>
                                        @endif
                                        <span class="text-muted small text-uppercase">{{ $spec->color ?? 'Standard' }}</span>
                                    </div>
                                </td>

                                {{-- Action --}}
                                <td class="text-center pe-4">
                                    {{-- {{ route('parts.index', ['spec' => $spec->id]) }} --}}
                                    <a href="" class="btn btn-dark btn-sm px-4 rounded-pill fw-bold">
                                        View Parts <i class="fa-solid fa-gears ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-muted mb-0">No technical specs found matching your filters.</p>
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