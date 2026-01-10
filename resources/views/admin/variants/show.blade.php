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

    {{-- ================= Variant Details ================= --}}
    <div class="card card-primary mb-4">
        <div class="card-body d-flex align-items-center">
            <div class="me-4">
                @if($variant->photo)
                    <img src="{{ asset('storage/' . $variant->photo) }}" alt="{{ $variant->name ?? 'Variant' }}" class="img-thumbnail" style="width:150px; height:auto; object-fit:contain;">
                @else
                    <img src="{{ asset('images/placeholder.png') }}" alt="Placeholder" class="img-thumbnail" style="width:150px; height:auto; object-fit:contain;">
                @endif
            </div>
            <div>
                <p><strong>Brand:</strong> {{ $variant->vehicleModel->brand->brand_name ?? '-' }}</p>
                <p><strong>Model:</strong> {{ $variant->vehicleModel->model_name ?? '-' }}</p>
                <p><strong>Variant Name:</strong> {{ $variant->name ?? '-' }}</p>
                <p><strong>Chassis Code:</strong> {{ $variant->chassis_code ?? '-' }}</p>
                <p><strong>Model Code:</strong> {{ $variant->model_code ?? '-' }}</p>
                <p><strong>Trim Level:</strong> {{ $variant->trim_level ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $variant->status ? 'Active' : 'Inactive' }}</p>
            </div>
        </div>
    </div>

    {{-- ================= Specifications Table ================= --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $variant->specifications->count() }} Variant Specifications</h3>
            <div class="card-tools">
                <a href="{{ route('admin.specifications.create', ['variant_id' => $variant->id]) }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Specification
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Body Type</th>
                            <th>Engine</th>
                            <th>Transmission</th>
                            <th>Drive</th>
                            <th>Production Years</th>
                            <th>Status</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variant->specifications as $spec)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $spec->bodyType->name ?? '-' }}</td>
                            <td>{{ $spec->engineType->name ?? '-' }}</td>
                            <td>{{ $spec->transmissionType->name ?? '-' }}</td>
                            <td>{{ $spec->driveType->name ?? '-' }}</td>
                            <td>{{ $spec->production_start ?? '?' }} - {{ $spec->production_end ?? 'Present' }}</td>
                            <td>{{ $spec->status ? 'Active' : 'Inactive' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.specifications.show', $spec->id) }}" class="btn btn-info btn-sm me-2">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this specification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No specifications found for this variant.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection
