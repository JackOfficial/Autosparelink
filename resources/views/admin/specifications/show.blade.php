@extends('admin.layouts.app')

@section('title', 'Specification Details')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    {{ $specification->variant->name
                        ?? $specification->vehicleModel->model_name
                        ?? 'Specification Details' }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.specifications.index') }}">Specifications</a>
                    </li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ================= Specification Overview ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Specification Overview</h3>
        </div>

        <div class="card-body d-flex align-items-center">
            <div class="me-4">
                @if($specification->photo)
                    <img src="{{ asset('storage/' . $specification->photo) }}"
                         class="img-thumbnail"
                         style="width:180px; object-fit:contain;">
                @else
                    <img src="{{ asset('images/placeholder.png') }}"
                         class="img-thumbnail"
                         style="width:180px; object-fit:contain;">
                @endif
            </div>

            <div class="row w-100">
                <div class="col-md-6">
                    <p><strong>Brand:</strong>
                        {{ $specification->variant->vehicleModel->brand->brand_name
                            ?? $specification->vehicleModel->brand->brand_name
                            ?? '-' }}
                    </p>

                    <p><strong>Model:</strong>
                        {{ $specification->variant->vehicleModel->model_name
                            ?? $specification->vehicleModel->model_name
                            ?? '-' }}
                    </p>

                    <p><strong>Variant:</strong>
                        {{ $specification->variant->name ?? 'N/A' }}
                    </p>

                    <p><strong>Body Type:</strong>
                        {{ $specification->bodyType->name ?? '-' }}
                    </p>

                    <p><strong>Engine Type:</strong>
                        {{ $specification->engineType->name ?? '-' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p><strong>Transmission:</strong>
                        {{ $specification->transmissionType->name ?? '-' }}
                    </p>

                    <p><strong>Drive Type:</strong>
                        {{ $specification->driveType->name ?? '-' }}
                    </p>

                    <p><strong>Production Years:</strong>
                        {{ $specification->production_start ?? '?' }}
                        -
                        {{ $specification->production_end ?? 'Present' }}
                    </p>

                    <p><strong>Status:</strong>
                        <span class="badge {{ $specification->status ? 'bg-success' : 'bg-secondary' }}">
                            {{ $specification->status ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= Technical Details ================= --}}
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">Technical Specifications</h3>
            <div class="card-tools">
                <a href="{{ route('admin.specifications.edit', $specification->id) }}"
                   class="btn btn-light btn-sm">
                    <i class="fa fa-edit"></i> Edit Specification
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <tbody>
                        <tr>
                            <th width="25%">Horsepower</th>
                            <td>{{ $specification->horsepower ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Torque</th>
                            <td>{{ $specification->torque ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fuel Capacity</th>
                            <td>{{ $specification->fuel_capacity ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fuel Efficiency</th>
                            <td>{{ $specification->fuel_efficiency ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Seats</th>
                            <td>{{ $specification->seats ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Doors</th>
                            <td>{{ $specification->doors ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Steering Position</th>
                            <td>{{ $specification->steering_position ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Color</th>
                            <td>
                                @if($specification->color)
                                    <span class="badge"
                                          style="width:30px;height:30px;
                                          background-color:{{ $specification->color }};
                                          border:1px solid #ccc;">
                                    </span>
                                    <span class="ms-2">{{ $specification->color }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection
