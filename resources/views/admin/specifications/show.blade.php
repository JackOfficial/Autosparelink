@extends('admin.layouts.app')

@section('title', 'Specification Details')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="fw-bold mb-0">Specification Details</h1>
                <small class="text-muted">
                    {{ $specification->variant->name
                        ?? $specification->vehicleModel->model_name
                        ?? 'Vehicle Specification' }}
                </small>
            </div>

            <div>
                <a href="{{ route('admin.specifications.edit', $specification->id) }}"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ================= HERO CARD ================= --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body d-flex align-items-center gap-4">

            {{-- Image --}}
            <div>
                @if($specification->photo)
                    <img src="{{ asset('storage/' . $specification->photo) }}"
                         class="rounded"
                         style="width:200px; height:140px; object-fit:contain;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light rounded"
                         style="width:200px; height:140px;">
                        <i class="fa fa-car fa-3x text-muted"></i>
                    </div>
                @endif
            </div>

            {{-- Identity --}}
            <div class="flex-grow-1">
                <h3 class="fw-semibold mb-1">
                    {{ $specification->variant->name ?? 'Base Specification' }}
                </h3>

                <p class="mb-2 text-muted">
                    {{ $specification->variant->vehicleModel->brand->brand_name
                        ?? $specification->vehicleModel->brand->brand_name
                        ?? '-' }}
                    —
                    {{ $specification->variant->vehicleModel->model_name
                        ?? $specification->vehicleModel->model_name
                        ?? '-' }}
                </p>

                <span class="badge rounded-pill px-3 py-2
                    {{ $specification->status ? 'bg-success' : 'bg-secondary' }}">
                    {{ $specification->status ? 'Active Specification' : 'Inactive Specification' }}
                </span>
            </div>
        </div>
    </div>

    {{-- ================= QUICK FACTS ================= --}}
    <div class="row mb-4">

        @php
            $facts = [
                'Body Type'       => $specification->bodyType->name ?? '-',
                'Engine'          => $specification->engineType->name ?? '-',
                'Transmission'    => $specification->transmissionType->name ?? '-',
                'Drive Type'      => $specification->driveType->name ?? '-',
                'Production'      => ($specification->production_start ?? '?') . ' - ' . ($specification->production_end ?? 'Present'),
            ];
        @endphp

        @foreach($facts as $label => $value)
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <small class="text-muted d-block mb-1">{{ $label }}</small>
                        <h6 class="fw-semibold mb-0">{{ $value }}</h6>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ================= PERFORMANCE ================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-0">
            <h5 class="fw-semibold mb-0">Performance & Capacity</h5>
        </div>

        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3 mb-3">
                    <h4 class="fw-bold">{{ $specification->horsepower ?? '-' }}</h4>
                    <small class="text-muted">Horsepower</small>
                </div>
                <div class="col-md-3 mb-3">
                    <h4 class="fw-bold">{{ $specification->torque ?? '-' }}</h4>
                    <small class="text-muted">Torque</small>
                </div>
                <div class="col-md-3 mb-3">
                    <h4 class="fw-bold">{{ $specification->fuel_capacity ?? '-' }}</h4>
                    <small class="text-muted">Fuel Capacity</small>
                </div>
                <div class="col-md-3 mb-3">
                    <h4 class="fw-bold">{{ $specification->fuel_efficiency ?? '-' }}</h4>
                    <small class="text-muted">Fuel Efficiency</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= ADDITIONAL DETAILS ================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-0">
            <h5 class="fw-semibold mb-0">Additional Details</h5>
        </div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Seats</small>
                    <div class="fw-semibold">{{ $specification->seats ?? '-' }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Doors</small>
                    <div class="fw-semibold">{{ $specification->doors ?? '-' }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Steering</small>
                    <div class="fw-semibold">{{ $specification->steering_position ?? '-' }}</div>
                </div>

                <div class="col-md-4 mb-3">
                    <small class="text-muted">Color</small>
                    <div class="d-flex align-items-center gap-2">
                        @if($specification->color)
                            <span style="width:22px;height:22px;
                                border-radius:50%;
                                background-color:{{ $specification->color }};
                                border:1px solid #ccc;"></span>
                            <span>{{ $specification->color }}</span>
                        @else
                            -
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
@endsection
