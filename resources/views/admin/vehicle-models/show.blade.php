@extends('admin.layouts.app')

@section('title', 'Vehicle Model Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">{{ $vehicleModel->model_name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.vehicle-models.index') }}">Vehicle Models</a></li>
                        <li class="breadcrumb-item active">{{ $vehicleModel->model_name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-sm-6 text-end">
                <div class="btn-group shadow-sm">
                    <a href="{{ route('admin.vehicle-models.edit', $vehicleModel->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i> Edit Model
                    </a>
                    <a href="{{ route('admin.variants.create', ['vehicle_model_id' => $vehicleModel->id]) }}" class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i> Add Variant
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        {{-- Model Overview Card --}}
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-3 bg-light d-flex align-items-center justify-content-center p-4 border-end">
                        @php $photo = $vehicleModel->photo ? asset('storage/' . $vehicleModel->photo) : asset('images/placeholder.png'); @endphp
                        <img src="{{ $photo }}" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: contain;">
                    </div>
                    <div class="col-md-9 p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-soft-primary text-primary text-uppercase px-3 py-2 mb-2">
                                    {{ $vehicleModel->brand->brand_name ?? 'Unknown Brand' }}
                                </span>
                                <h2 class="h4 fw-bold mb-0">{{ $vehicleModel->model_name }}</h2>
                            </div>
                            <span class="badge rounded-pill {{ $vehicleModel->status ? 'bg-success' : 'bg-secondary' }} px-3">
                                {{ $vehicleModel->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-4">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1">Production Years</small>
                                <p class="mb-0 fs-6"><i class="far fa-calendar-alt text-muted me-2"></i>{{ $vehicleModel->production_start_year ?? '?' }} — {{ $vehicleModel->production_end_year ?? 'Present' }}</p>
                            </div>
                            <div class="col-sm-8">
                                <small class="text-muted d-block text-uppercase fw-bold mb-1">Description</small>
                                <p class="mb-0 text-dark">{{ $vehicleModel->description ?? 'No description provided for this model.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Variants Table Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-car me-2 text-primary"></i> Available Variants 
                    <span class="badge bg-light text-dark ms-2 border">{{ $vehicleModel->variants->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4" width="50">#</th>
                                <th width="100">Photo</th>
                                <th>Variant Detail</th>
                                <th>Codes</th>
                                <th>Trim</th>
                                <th class="text-center">Status</th>
                                <th class="text-center pe-4" width="160">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicleModel->variants as $variant)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    @php $vPhoto = $variant->photo ? asset('storage/' . $variant->photo) : asset('images/placeholder.png'); @endphp
                                    <img src="{{ $vPhoto }}" class="rounded border shadow-sm" style="width: 70px; height: 45px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $variant->name ?? 'Standard' }}</div>
                                    <span class="badge bg-soft-info text-info small" style="font-size: 0.7rem;">
                                        <i class="fas fa-list-ul me-1"></i> {{ $variant->specifications_count ?? $variant->specifications->count() }} Specs
                                    </span>
                                </td>
                                <td>
                                    <div class="small">Chassis: <code class="text-primary">{{ $variant->chassis_code ?? '-' }}</code></div>
                                    <div class="small">Model: <code class="text-secondary">{{ $variant->model_code ?? '-' }}</code></div>
                                </td>
                                <td><span class="text-muted small">{{ $variant->trim_level ?? '-' }}</span></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill {{ $variant->status ? 'bg-success' : 'bg-secondary' }} px-2">
                                        {{ $variant->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm shadow-sm border rounded">
                                        <a href="{{ route('admin.variants.show', $variant->id) }}" class="btn btn-white text-info" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-white text-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-white text-danger" title="Delete" onclick="return confirm('Delete this variant?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3 d-block opacity-25"></i>
                                        No variants found for this model.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
    .bg-soft-primary { background-color: #e0e7ff; color: #4338ca; }
    .bg-soft-info { background-color: #e0f2fe; color: #0369a1; }
    .table thead th { font-size: 11px; letter-spacing: 0.5px; border-top: none; padding: 12px 8px; }
    .btn-white { background-color: #ffffff; border: none; }
    .btn-white:hover { background-color: #f8fafc; }
    .btn-group .btn-white:not(:last-child) { border-right: 1px solid #e2e8f0; }
    code { font-weight: 600; }
</style>
@endsection