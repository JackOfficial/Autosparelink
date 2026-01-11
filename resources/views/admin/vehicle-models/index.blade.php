@extends('admin.layouts.app')
@section('title', 'Vehicle Models')
@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Vehicle Models</h1>
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

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    {{-- Loop through brands --}}
    @forelse($brands as $brand)
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="card-title">{{ $brand->brand_name }} ({{ $brand->vehicleModels->count() }} {{ Str::plural('Model', $brand->vehicleModels->count()) }})</h3>
                <a href="{{ route('admin.vehicle-models.create') }}" class="btn btn-light btn-sm">
                    <i class="fa fa-plus"></i> Add Model
                </a>
            </div>

            <div class="card-body table-responsive p-0">
                @if($brand->vehicleModels->isEmpty())
                    <p class="text-center text-muted py-3">No models for this brand.</p>
                @else
                    <table class="table table-striped table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Model Name</th>
                                <th>Production Years</th>
                                <th>Status</th>
                                <th style="width:150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brand->vehicleModels as $model)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                {{-- Photo --}}
                               @if($model->mainPhoto)
    <img src="{{ asset('storage/'.$model->mainPhoto->file_path) }}"
         class="img-thumbnail"
         style="width:80px;">
@else
    <span class="text-muted">No photo</span>
@endif

                                {{-- Model Name --}}
                                <td>{{ $model->model_name ?? '-' }}</td>

                                {{-- Production Years --}}
                                <td>{{ $model->production_start_year ?? '-' }} - {{ $model->production_end_year ?? 'Present' }}</td>

                                {{-- Status --}}
                                <td>
                                    @if($model->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-warning">Inactive</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="d-flex">
                                    <a href="{{ route('admin.vehicle-models.show', $model->id) }}" class="btn btn-info btn-sm mr-2">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vehicle-models.edit', $model->id) }}" class="btn btn-warning btn-sm mr-2">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.vehicle-models.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vehicle model?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @empty
        <p class="text-center text-muted">No brands or vehicle models available.</p>
    @endforelse

</section>

@endsection
