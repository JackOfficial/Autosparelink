@extends('layouts.app')

@section('style')
<style>
.toggle-btn i {
    transition: transform 0.3s;
}
.collapse.show + .toggle-btn i,
.collapse.show ~ .toggle-btn i {
    transform: rotate(180deg);
}
.model-row:hover {
    background: #f8f9fa;
    cursor: pointer;
}
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="#">{{ $model->brand->brand_name }}</a>
                <span class="breadcrumb-item active">{{ $model->model_name }}</span>
            </nav>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

<!-- Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h4 class="text-uppercase mb-1" style="font-weight: 600;">
                    @if($model->brand->brand_logo)
                        <img src="{{ asset('storage/' . $model->brand->brand_logo) }}"
                             style="width: 50px; height:auto;" />
                    @endif
                    {{ $model->brand->brand_name }} Part Catalog – {{ $model->model_name }}
                </h4>
            </div>

            <div class="mt-3 mt-md-0">
                <span class="text-muted small">
                    Showing <strong>1</strong> Variant Specification
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="container-fluid px-xl-5">
    <div class="card">
        <div class="card-header">Filters</div>
        <div class="card-body">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Frame">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Year">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Body">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Driver's Position">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Engine">
            </div>
            <button class="btn btn-sm btn-primary">Filter</button>
        </div>
    </div>
</div>

<!-- Variant Specification Table -->
<div class="container-fluid px-xl-5 my-2">
    <div class="bg-white p-4 shadow-sm rounded mb-4">
        <h6 class="text-uppercase mb-1">
            Specifications for {{ $model->brand->brand_name }} {{ $model->model_name }}
        </h6>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Variant Name</th>
                <th>Body</th>
                <th>Engine</th>
                <th>Transmission</th>
                <th>Horsepower</th>
                <th>Torque</th>
                <th><i class="fas fa-info"></i></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $variant->name ?? 'No name' }}</td>
                <td>{{ $variant->bodyType->name ?? '-' }}</td>
                <td>{{ $variant->engineType->name ?? '-' }}</td>
                <td>{{ $variant->transmissionType->name ?? '-' }}</td>
                <td>{{ $variant->horsepower ?? 'N/A' }}</td>
                <td>{{ $variant->torque ?? 'N/A' }}</td>

                <td>
                    <a href="#" class="btn btn-sm btn-info">
                        <i class="fas fa-info"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
