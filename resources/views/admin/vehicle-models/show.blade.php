@extends('admin.layouts.app')

@section('title', 'Vehicle Model Details')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $vehicleModel->model_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.vehicle-models.index') }}">Vehicle Models</a></li>
                    <li class="breadcrumb-item active">{{ $vehicleModel->model_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ================= Vehicle Model Details ================= --}}
    <div class="card card-primary mb-4">
        <div class="card-body d-flex align-items-center">
            <div class="me-4">
                @if($vehicleModel->photo)
                    <img src="{{ asset('storage/' . $vehicleModel->photo) }}" alt="{{ $vehicleModel->model_name }}" class="img-thumbnail" style="width:150px; height:auto; object-fit:contain;">
                @else
                    <img src="{{ asset('images/placeholder.png') }}" alt="Placeholder" class="img-thumbnail" style="width:150px; height:auto; object-fit:contain;">
                @endif
            </div>
            <div>
                <p><strong>Brand:</strong> {{ $vehicleModel->brand->brand_name ?? '-' }}</p>
                <p><strong>Model Name:</strong> {{ $vehicleModel->model_name }}</p>
                <p><strong>Production Years:</strong> {{ $vehicleModel->production_start_year ?? '?' }} - {{ $vehicleModel->production_end_year ?? 'Present' }}</p>
                <p><strong>Description:</strong> {{ $vehicleModel->description ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $vehicleModel->status ? 'Active' : 'Inactive' }}</p>
            </div>
        </div>
    </div>

    {{-- ================= Variants Table ================= --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $vehicleModel->variants->count() }} {{ $vehicleModel->model_name }} {{ $vehicleModel->variants->count() > 1 ? 'Variants' : 'Variant' }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.variants.create', ['vehicle_model_id' => $vehicleModel->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Variant
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Variant Name</th>
                            <th>Chassis code</th>
                            <th>Model Code</th>
                            <th>Trim Level</th>
                            <th>Status</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicleModel->variants as $variant)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($variant->photo)
                                    <img src="{{ asset('storage/' . $variant->photo) }}" class="img-thumbnail" style="width:80px; height:auto;">
                                @else
                                    <span class="text-muted">No photo</span>
                                @endif
                            </td>
                            <td>{{ $variant->name ?? '-' }}</td>
                            <td>{{ $variant->chassis_code ?? '-' }}</td>
                             <td>{{ $variant->model_code ?? '-' }}</td>
                             <td>{{ $variant->trim_level ?? '-' }}</td>
                            <td>{{ $variant->status ? 'Active' : 'Inactive' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.variants.show', $variant->id) }}" class="btn btn-info btn-sm me-2">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this variant?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No variants available for this model.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection
