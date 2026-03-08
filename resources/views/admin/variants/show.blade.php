@extends('admin.layouts.app')

@section('title', 'Variant Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-3">
            <div class="col-sm-6">
                <h1 class="m-0 fw-bold">{{ $variant->name ?? $variant->vehicleModel->model_name }}</h1>
                <span class="text-muted">Management for {{ $variant->vehicleModel->brand->brand_name }} {{ $variant->vehicleModel->model_name }}</span>
            </div>
            <div class="col-sm-6 text-end">
                <div class="btn-group">
                    <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-outline-primary shadow-sm">
                        <i class="fas fa-edit me-1"></i> Edit Variant
                    </a>
                    <a href="{{ route('admin.specifications.create', ['variant_id' => $variant->id]) }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus me-1"></i> Add Spec
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        {{-- Variant Overview Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-3 bg-light d-flex align-items-center justify-content-center p-3 border-end">
                        @php $photo = $variant->photo ? asset('storage/' . $variant->photo) : asset('images/placeholder.png'); @endphp
                        <img src="{{ $photo }}" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: contain;">
                    </div>
                    <div class="col-md-9 p-4">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-uppercase text-muted small fw-bold d-block">Brand & Model</label>
                                <span class="fs-5">{{ $variant->vehicleModel->brand->brand_name }} {{ $variant->vehicleModel->model_name }}</span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-uppercase text-muted small fw-bold d-block">Codes</label>
                                <span>Chassis: <strong>{{ $variant->chassis_code ?? '-' }}</strong> / Model: <strong>{{ $variant->model_code ?? '-' }}</strong></span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-uppercase text-muted small fw-bold d-block">Status</label>
                                <span class="badge rounded-pill {{ $variant->status ? 'bg-success' : 'bg-secondary' }} px-3">
                                    {{ $variant->status ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-md-4">
                                <label class="text-uppercase text-muted small fw-bold d-block">Trim Level</label>
                                <span class="text-primary fw-bold">{{ $variant->trim_level ?? 'Standard' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Specifications Section --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title fw-bold mb-0">
                    <i class="fas fa-microchip me-2 text-info"></i> Technical Specifications ({{ $variant->specifications->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="min-width: 1200px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Engine / Body</th>
                                <th>Transmission</th>
                                <th>Years</th>
                                <th>Power & Torque</th>
                                <th>Capacity</th>
                                <th class="text-center">Utility</th>
                                <th class="text-center">Color</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variant->specifications as $spec)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $spec->engineType->name ?? '-' }}</div>
                                    <div class="small text-muted">{{ $spec->bodyType->name ?? '-' }} ({{ $spec->driveType->name ?? '-' }})</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $spec->transmissionType->name ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="small">{{ $spec->production_start ?? '?' }} – {{ $spec->production_end ?? 'Pres.' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <small class="badge bg-soft-info text-info"><i class="fas fa-bolt me-1"></i>{{ $spec->horsepower ?? '-' }} hp</small>
                                        <small class="badge bg-soft-warning text-warning"><i class="fas fa-wrench me-1"></i>{{ $spec->torque ?? '-' }} Nm</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i class="fas fa-gas-pump me-1 text-muted"></i> {{ $spec->fuel_capacity ?? '-' }}L</div>
                                    <div class="small text-muted">{{ $spec->fuel_efficiency ?? '-' }} km/L</div>
                                </td>
                                <td class="text-center">
                                    <div class="small"><i class="fas fa-users me-1"></i>{{ $spec->seats }} / <i class="fas fa-door-open me-1"></i>{{ $spec->doors }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="rounded-circle border mx-auto shadow-sm" style="width: 20px; height: 20px; background-color: {{ $spec->color ?? '#eee' }}" title="{{ $spec->color }}"></div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm shadow-sm">
                                        <a href="{{ route('admin.specifications.edit', $spec->id) }}" class="btn btn-white text-warning" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.specifications.destroy', $spec->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-white text-danger" onclick="return confirm('Delete this specification?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <img src="{{ asset('images/empty-box.png') }}" width="60" class="opacity-25 mb-3 d-block mx-auto">
                                    <span class="text-muted">No specifications found. Click "Add Spec" to get started.</span>
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
    .bg-soft-info { background-color: #e0f2fe; color: #0369a1; }
    .bg-soft-warning { background-color: #fef3c7; color: #92400e; }
    .table thead th { font-size: 11px; letter-spacing: 0.5px; border-top: none; }
    .btn-group .btn-white { background: #fff; border: 1px solid #e2e8f0; }
    .btn-group .btn-white:hover { background: #f8fafc; }
</style>
@endsection