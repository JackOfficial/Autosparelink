@extends('layouts.app')

@section('style')
<style>
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .filter-card .form-control, .filter-card .btn { height: calc(2.2rem + 2px); font-size: 0.9rem; }
    .brand-logo-header { width: 50px; height: auto; margin-right: 15px; }
    /* Horizontal scroll for small screens */
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
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
    <div class="bg-white p-4 shadow-sm rounded mb-3 d-flex align-items-center">
        @if($item->vehicleModel->brand->brand_logo)
            <img src="{{ asset('storage/' . $item->vehicleModel->brand->brand_logo) }}" class="brand-logo-header" alt="Logo" />
        @endif
        <div>
            <h4 class="text-uppercase mb-1" style="font-weight: 600;">
                {{ $item->vehicleModel->brand->brand_name }} {{ $item->name }}
            </h4>
            <small class="text-muted">Technical specifications and configurations for this variant.</small>
        </div>
    </div>
</div>

<div class="container-fluid px-xl-5 mb-3">
    <div class="card shadow-sm filter-card">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-2">
                    <div class="col-md-2">
                        <select name="body" class="form-control">
                            <option value="">Body Style</option>
                            @foreach($bodyTypes as $body)
                                <option value="{{ $body->id }}" {{ request('body') == $body->id ? 'selected' : '' }}>{{ $body->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="engine" class="form-control">
                            <option value="">Engine</option>
                            @foreach($engineTypes as $engine)
                                <option value="{{ $engine->id }}" {{ request('engine') == $engine->id ? 'selected' : '' }}>{{ $engine->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="transmission" class="form-control">
                            <option value="">Transmission</option>
                            @foreach($transmissionTypes as $trans)
                                <option value="{{ $trans->id }}" {{ request('transmission') == $trans->id ? 'selected' : '' }}>{{ $trans->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="drive" class="form-control">
                            <option value="">Drive Type</option>
                            @foreach($driveTypes as $drive)
                                <option value="{{ $drive->id }}" {{ request('drive') == $drive->id ? 'selected' : '' }}>{{ $drive->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="steering_position" class="form-control">
                            <option value="">Steering</option>
                            <option value="LEFT" {{ request('steering_position') == 'LEFT' ? 'selected' : '' }}>Left Hand</option>
                            <option value="RIGHT" {{ request('steering_position') == 'RIGHT' ? 'selected' : '' }}>Right Hand</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter Results</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid px-xl-5">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Configuration / Trim</th>
                            <th>Body</th>
                            <th>Engine</th>
                            <th>Transmission</th>
                            <th>Drive</th>
                            <th>Year Range</th>
                            <th>Tech Details</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specifications as $spec)
                            <tr>
                                <td class="fw-bold text-primary">
                                    {{ $item->name }}<br>
                                    <small class="text-muted">{{ $spec->steering_position }} Steering</small>
                                </td>
                                <td>{{ $spec->bodyType->name ?? '-' }}</td>
                                <td>{{ $spec->engineType->name ?? '-' }}</td>
                                <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $spec->driveType->name ?? '-' }}</span></td>
                                <td>{{ $spec->production_start ?? '-' }} - {{ $spec->production_end ?? 'Present' }}</td>
                                <td>
                                    <small>
                                        <strong>HP:</strong> {{ $spec->horsepower ?? '-' }} | 
                                        <strong>Doors:</strong> {{ $spec->doors ?? '-' }} | 
                                        <strong>Seats:</strong> {{ $spec->seats ?? '-' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="" class="btn btn-sm btn-outline-primary">
                                        View Parts <i class="fa fa-angle-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa fa-search fa-2x mb-3"></i><br>
                                    No specific configurations found for this variant matching your filters.
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