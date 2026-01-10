@extends('admin.layouts.app')

@section('title', 'Brand Details')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $brand->brand_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.vehicle-brands.index') }}">Brands</a></li>
                    <li class="breadcrumb-item active">{{ $brand->brand_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">

    {{-- ================= Brand Details ================= --}}
    <div class="card card-primary mb-4">
        <div class="card-body d-flex align-items-center">
            <div class="me-4">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/' . $brand->brand_logo) }}" alt="{{ $brand->brand_name }}" class="img-thumbnail" style="width:150px; height:auto; object-fit:contain;">
                @else
                    <img src="{{ asset('images/placeholder.png') }}" alt="Placeholder" class="img-thumbnail" style="width:150px; height:auto; object-fit:contain;">
                @endif
            </div>
            <div>
                <p><strong>Brand Name:</strong> {{ $brand->brand_name }}</p>
                <p><strong>Country:</strong> {{ $brand->country ?? '-' }}</p>
                <p><strong>Website:</strong> 
                    @if($brand->website)
                        <a href="{{ $brand->website }}" target="_blank">{{ $brand->website }}</a>
                    @else
                        -
                    @endif
                </p>
                <p><strong>Description:</strong> {{ $brand->description ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- ================= Vehicle Models Table ================= --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Vehicle Models under {{ $brand->brand_name }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.vehicle-models.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Model
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
                            <th>Model Name</th>
                            <th>Years</th>
                            <th style="width:150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brand->vehicleModels as $model)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($model->photo)
                                    <img src="{{ asset('storage/' . $model->photo) }}" class="img-thumbnail" style="width:80px; height:auto;">
                                @else
                                    <span class="text-muted">No photo</span>
                                @endif
                            </td>
                            <td>{{ $model->model_name }}</td>
                            <td>{{ $model->year_from ?? '?' }} - {{ $model->year_to ?? 'Present' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('admin.vehicle-models.show', $model->id) }}" class="btn btn-info btn-sm me-2">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.vehicle-models.edit', $model->id) }}" class="btn btn-warning btn-sm me-2">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.vehicle-models.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this model?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No vehicle models found for this brand.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
@endsection
