@extends('admin.layouts.app')

@section('title', 'Variants')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vehicle Variants</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Variants</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
<div class="container-fluid">

    {{-- ACTION BAR --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <a href="{{ route('admin.variants.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Variant
            </a>
        </div>
        <div class="col-md-6 text-end">
            <span class="text-muted">
                Total Brands: <strong>{{ $brands->count() }}</strong>
            </span>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- GROUPED VARIANTS --}}
    @forelse($brands as $brand)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    {{ $brand->brand_name }} 
                    <small class="text-light">({{ $brand->vehicleModels->sum(fn($m) => $m->variants->count()) }} Variants)</small>
                </h3>
            </div>

            <div class="card-body">
                @forelse($brand->vehicleModels as $model)
                    <div class="mb-3">
                        <h5 class="text-info">
                            {{ $model->model_name }} 
                            <small class="text-muted">({{ $model->variants->count() }} Variants)</small>
                        </h5>

                        @if($model->variants->isEmpty())
                            <p class="text-muted">No variants for this model.</p>
                        @else
                            <table class="table table-striped table-hover text-nowrap align-middle mb-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Variant Name</th>
                                        <th>Trim</th>
                                        <th>Chassis Code</th>
                                        <th>Status</th>
                                        <th width="170">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($model->variants as $variant)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            {{-- Photo --}}
                                            <td>
                                                @if($variant->photo)
                                                    <a href="{{ asset('storage/'.$variant->photo) }}" target="_blank">
                                                        <img src="{{ asset('storage/'.$variant->photo) }}" alt="Variant Photo"
                                                            class="img-thumbnail" style="width:60px;height:60px;object-fit:cover;">
                                                    </a>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td><strong>{{ $variant->name ?? '—' }}</strong></td>
                                            <td>{{ $variant->trim_level ?? '—' }}</td>
                                            <td>{{ $variant->chassis_code ?? '—' }}</td>
                                            <td>
                                                <span class="badge {{ $variant->status ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $variant->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="d-flex gap-1">
                                                <a href="{{ route('admin.variants.show', $variant->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-cogs"></i>
                                                </a>
                                                <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST"
                                                      onsubmit="return confirm('Delete this variant?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No vehicle models for this brand.</p>
                @endforelse
            </div>
        </div>
    @empty
        <p class="text-center text-muted">No brands or variants available.</p>
    @endforelse

</div>
</section>

@endsection
