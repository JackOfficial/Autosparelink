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

<!-- Toyota Model List Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h4 class="text-uppercase mb-1" style="font-weight: 600;">
                    @if($model->brand->brand_logo)
                        <img src="{{ asset('storage/' . $model->brand->brand_logo) }}" style="width: 50px; height:auto;">
                    @endif
                    {{ $model->brand->brand_name }} Part Catalog {{ $model->model_name }}
                </h4>
            </div>

            <!-- 🔥 Updated count (no more $models) -->
            <div class="mt-3 mt-md-0">
                <span class="text-muted small">
                    Showing <strong>{{ $model->variants->count() }}</strong> models
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Toyota Models Grid (Filters) -->
<div class="container-fluid px-xl-5">
    <div class="card">
       <div class="card-header">Filters</div>
       <div class="card-body">
            <div class="form-group">
               <input type="text" class="form-control" placeholder="Frame" name="frame">
            </div>
            <div class="form-group">
               <input type="text" class="form-control" placeholder="Year" name="year">
            </div>
            <div class="form-group">
               <input type="text" class="form-control" placeholder="Body" name="body">
            </div>
            <div class="form-group">
               <input type="text" class="form-control" placeholder="Driver's Position" name="position">
            </div>
            <div class="form-group">
               <input type="text" class="form-control" placeholder="Engine" name="engine">
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
       </div>
    </div>
</div>

<!-- Page Header -->
<div class="container-fluid px-xl-5 my-2">
    <div class="bg-white p-4 shadow-sm rounded mb-4">
        <h6 class="text-uppercase mb-1">
            Specifications for {{ $model->brand->brand_name }} {{ $model->model_name }},
            Total: 7
        </h6>
    </div>

    <!-- 🔥 TABLE LEFT EXACTLY AS YOU WROTE IT -->
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Model</th>
            <th>Options</th>
            <th>Prod Period</th>
            <th><i class="fas fa-info"></i></th>
        </tr>

        @for($i = 0; $i < 7; $i++)
        <tr>
            <td><a href="/spare-parts/{{ $model->id }}"></a>{{ $model->brand->brand_name }}1000</td>
            <td>KP3#</td>
            <td>KP30-</td>
            <td>Driver's Position: RIGHT-HAND DR</td>
            <td>04.1969 - 02.1978</td>
            <td><button class="btn btn-sm btn-info"><i class="fas fa-info"></i></button></td>
        </tr>
        @endfor
    </table>
</div>

@endsection
