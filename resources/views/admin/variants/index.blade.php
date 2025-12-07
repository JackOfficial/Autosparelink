@extends('admin.layouts.app')
@section('title', 'Variants')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Variants ({{ $variants->count() }})</h1>
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

<!-- Main Content -->
<section class="content">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.variants.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Variant
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Vehicle Model</th>
                        <th>Body Type</th>
                        <th>Engine Type</th>
                        <th>Transmission</th>
                        <th>Status</th>
                        <th style="width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variants as $variant)
                    <tr>
                        <td>{{ $variant->id }}</td>

                        <!-- Photo -->
                        <td>
                            @if($variant->photo)
                                <img src="{{ asset('storage/' . $variant->photo) }}" class="img-thumbnail" style="width: 80px;">
                            @else
                                <span class="text-muted">No photo</span>
                            @endif
                        </td>

                        <!-- Related Info -->
                        <td>{{ $variant->vehicleModel->model_name ?? '-' }}</td>
                        <td>{{ $variant->bodyType->name ?? '-' }}</td>
                        <td>{{ $variant->engineType->name ?? '-' }}</td>
                        <td>{{ $variant->transmissionType->name ?? '-' }}</td>

                        <!-- Status -->
                        <td>
                            @if($variant->status == 1)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="d-flex">
                            <a href="{{ route('admin.variants.edit', $variant->id) }}" class="btn btn-info btn-sm mr-2">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this variant?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No variants available at the moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>

@endsection
