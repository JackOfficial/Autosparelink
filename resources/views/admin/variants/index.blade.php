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
        <div class="col-md-6 text-right">
            <span class="text-muted">
                Total: <strong>{{ $variants->count() }}</strong> variants
            </span>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Variant List</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Variant Name</th>
                        <th>Model</th>
                        <th>Brand</th>
                        <th>Trim</th>
                        <th>Chassis Code</th>
                        <th>Status</th>
                        <th width="160">Actions</th>
                    </tr>
                </thead>
                <tbody>

                @forelse($variants as $variant)
                    <tr>
                        <td>{{ $loop->iteration + ($variants->currentPage() - 1) * $variants->perPage() }}</td>

                        <td>
                            <strong>{{ $variant->name ?? '—' }}</strong>
                        </td>

                        <td>
                            {{ $variant->vehicleModel->model_name ?? '—' }}
                        </td>

                        <td>
                            {{ $variant->vehicleModel->brand->brand_name ?? '—' }}
                        </td>

                        <td>
                            {{ $variant->trim_level ?? '—' }}
                        </td>

                        <td>
                            {{ $variant->chassis_code ?? '—' }}
                        </td>

                        <td>
                            @if($variant->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('admin.variants.edit', $variant->id) }}"
                               class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>

                            <a href="{{ route('admin.specifications.index', ['variant_id' => $variant->id]) }}"
                               class="btn btn-sm btn-info"
                               title="Specifications">
                                <i class="fa fa-cogs"></i>
                            </a>

                            <form action="{{ route('admin.variants.destroy', $variant->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Delete this variant?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            No variants found.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>
</section>

@endsection
