@extends('admin.layouts.app')
@section('title', 'Vehicle Models')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vehicle Models ({{ $vehicleModels->count() }})</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Vehicle Models</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.vehicle-models.create') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Add Vehicle Model
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Brand</th>
                        <th>Model Name</th>
                           <th>Photo</th>
                        <th>Production Years</th>
                        <th>Status</th>
                        <th style="width:150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicleModels as $model)
                    <tr>
                        <td>{{ $model->id }}</td>
                        <td>{{ $model->brand->brand_name ?? '—' }}</td>
                        <td>
    @if($model->photo)
        <img src="{{ asset('storage/' . $model->photo) }}" class="img-thumbnail" style="width:80px;">
    @else
        <span class="text-muted">No photo</span>
    @endif
</td>

                        <td>{{ $model->model_name }}</td>
                        <td>
                            {{ $model->production_start_year ?? '—' }} 
                            - {{ $model->production_end_year ?? '—' }}
                        </td>
                        <td>
                            @if($model->status)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-warning">Inactive</span>
                            @endif
                        </td>
                        <td class="d-flex">
                            <a href="{{ route('admin.vehicle-models.edit', $model->id) }}"
                               class="btn btn-info btn-sm mr-2"><i class="fas fa-edit"></i></a>

                            <form action="{{ route('admin.vehicle-models.destroy', $model->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this vehicle model?');">
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
                        <td colspan="6" class="text-center text-muted">No vehicle models available.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection
