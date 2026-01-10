@extends('admin.layouts.app')

@section('title', 'Variant Details')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $variant->name ?? $variant->vehicleModel->model_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.variants.index') }}">Variants</a></li>
                    <li class="breadcrumb-item active">{{ $variant->name ?? 'Details' }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ================= Variant Overview ================= --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Variant Overview</h3>
        </div>
        <div class="card-body d-flex align-items-center">
            <div class="me-4">
                @if($variant->photo)
                    <img src="{{ asset('storage/' . $variant->photo) }}" alt="{{ $variant->name }}" class="img-thumbnail" style="width:180px; height:auto; object-fit:contain;">
                @else
                    <img src="{{ asset('images/placeholder.png') }}" alt="Placeholder" class="img-thumbnail" style="width:180px; height:auto; object-fit:contain;">
                @endif
            </div>
            <div class="row">
                <div class="col-md-6">
 <p><strong>Brand:</strong> {{ $variant->vehicleModel->brand->brand_name ?? '-' }}</p>
                <p><strong>Model:</strong> {{ $variant->vehicleModel->model_name ?? '-' }}</p>
                <p><strong>Variant Name:</strong> {{ $variant->name ?? '-' }}</p>
                <p><strong>Chassis Code:</strong> {{ $variant->chassis_code ?? '-' }}</p>
                </div>
               <div class="col-md-6">
<p><strong>Model Code:</strong> {{ $variant->model_code ?? '-' }}</p>
                <p><strong>Trim Level:</strong> {{ $variant->trim_level ?? '-' }}</p>
                <p><strong>Status:</strong>
                    <span class="badge {{ $variant->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $variant->status ? 'Active' : 'Inactive' }}
                    </span>
                </p>
               </div>
            </div>
        </div>
    </div>

    {{-- ================= Specifications Table ================= --}}
    <div class="card">
        <div class="card-header bg-info text-white">
            <h3 class="card-title">{{ $variant->specifications->count() }} Variant Specifications</h3>
            <div class="card-tools">
                <a href="{{ route('admin.specifications.create', ['variant_id' => $variant->id]) }}" class="btn btn-light btn-sm">
                    <i class="fa fa-plus"></i> Add Specification
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Body Type</th>
                            <th>Engine</th>
                            <th>Transmission</th>
                            <th>Drive</th>
                            <th>Years</th>
                            <th>Horsepower</th>
                            <th>Torque</th>
                            <th>Fuel Cap.</th>
                            <th>Seats</th>
                            <th>Doors</th>
                            <th>Fuel Eff.</th>
                            <th>Steering</th>
                            <th>Color</th>
                            <th>Status</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse($variant->specifications as $spec)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $spec->bodyType->name ?? '-' }}</td>
                            <td>{{ $spec->engineType->name ?? '-' }}</td>
                            <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                            <td>{{ $spec->driveType->name ?? '-' }}</td>
                            <td>{{ $spec->production_start ?? '?' }} - {{ $spec->production_end ?? 'Present' }}</td>
                            <td>{{ $spec->horsepower ?? '-' }}</td>
                            <td>{{ $spec->torque ?? '-' }}</td>
                            <td>{{ $spec->fuel_capacity ?? '-' }}</td>
                            <td>{{ $spec->seats ?? '-' }}</td>
                            <td>{{ $spec->doors ?? '-' }}</td>
                            <td>{{ $spec->fuel_efficiency ?? '-' }}</td>
                            <td>{{ $spec->steering_position ?? '-' }}</td>
                            <td>
                                 <span class="badge" style="width: 100px; height: auto; background-color: {{ $spec->color ?? 'white' }}">
                                 </span>
                                </td>
                            <td>
                                <span class="badge {{ $spec->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $spec->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="d-flex justify-content-center">
                                <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="16" class="text-center text-muted">No specifications found for this variant.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection
