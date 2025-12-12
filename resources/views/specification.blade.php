@extends('layouts.app')

@section('style')
<style>
    /* ----------------------------------
        GENERAL UI IMPROVEMENTS
    ---------------------------------- */
    .page-section {
        background: #ffffff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        margin-bottom: 25px;
    }

    .breadcrumb {
        border-radius: 8px !important;
    }

    .title-logo {
        width: 55px;
        height: auto;
        margin-right: 10px;
        border-radius: 5px;
    }

    /* ----------------------------------
        FILTER UI (Partsouq Style)
    ---------------------------------- */
    .filters-grid .form-group {
        margin-bottom: 15px;
    }

    .filters-grid input {
        height: 42px;
        border-radius: 6px;
    }

    .filters-grid .btn-primary {
        padding: 10px 20px;
        border-radius: 6px;
    }

    /* ----------------------------------
        TABLE STYLING
    ---------------------------------- */
    .table-custom {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table-custom thead {
        background: #f5f7fa;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .table-custom tbody tr:hover {
        background: #f9fcff;
        cursor: pointer;
    }

    .table-custom td, .table-custom th {
        padding: 14px 12px;
        vertical-align: middle;
    }

    .badge-spec {
        background: #eef2f7;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 5px;
        font-weight: 500;
    }

    /* ----------------------------------
        ICON / TOGGLE
    ---------------------------------- */
    .icon-btn {
        border-radius: 6px;
        padding: 6px 10px;
    }

    .toggle-btn i {
        transition: 0.3s;
    }

    .collapse.show + .toggle-btn i,
    .collapse.show ~ .toggle-btn i {
        transform: rotate(180deg);
    }
</style>
@endsection


@section('content')

<!-- Breadcrumb -->
<div class="container-fluid px-xl-5">
    <div class="row">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-3 mt-3">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item text-dark" href="#">{{ $model->brand->brand_name }}</a>
                <span class="breadcrumb-item active">{{ $model->model_name }}</span>
            </nav>
        </div>
    </div>
</div>

<!-- Page Title -->
<div class="container-fluid px-xl-5">
    <div class="page-section d-flex flex-column flex-md-row align-items-md-center justify-content-between">

        <div class="d-flex align-items-center">
            @if($model->brand->brand_logo)
                <img src="{{ asset('storage/' . $model->brand->brand_logo) }}" class="title-logo">
            @endif
            <h4 class="text-uppercase mb-0 fw-bold">
                {{ $model->brand->brand_name }} Parts Catalog — {{ $model->model_name }}
            </h4>
        </div>

        <div class="mt-3 mt-md-0">
            <span class="text-muted small">
                Showing <strong>{{ $model->variants->count() }}</strong> specifications
            </span>
        </div>

    </div>
</div>

<!-- FILTERS -->
<div class="container-fluid px-xl-5">
    <div class="page-section">
        <h6 class="text-uppercase fw-bold mb-3 text-primary">Filters</h6>

        <form>
            <div class="row filters-grid">
                <div class="col-md-4 col-lg-2 form-group">
                    <input type="text" class="form-control" placeholder="Frame" name="frame">
                </div>

                <div class="col-md-4 col-lg-2 form-group">
                    <input type="text" class="form-control" placeholder="Year" name="year">
                </div>

                <div class="col-md-4 col-lg-2 form-group">
                    <input type="text" class="form-control" placeholder="Body" name="body">
                </div>

                <div class="col-md-4 col-lg-2 form-group">
                    <input type="text" class="form-control" placeholder="Driver Position" name="position">
                </div>

                <div class="col-md-4 col-lg-2 form-group">
                    <input type="text" class="form-control" placeholder="Engine" name="engine">
                </div>

                <div class="col-md-4 col-lg-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary w-100">Apply</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SPECIFICATIONS TABLE -->
<div class="container-fluid px-xl-5">
    <div class="page-section">
        <h6 class="text-uppercase fw-bold text-primary mb-3">
            Specifications for {{ $model->brand->brand_name }} {{ $model->model_name }}
            <small class="text-muted">(Total: 7)</small>
        </h6>

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Model</th>
                        <th>Options</th>
                        <th>Prod Period</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @for($i = 0; $i < 7; $i++)
                    <tr onclick="window.location='/spare-parts/{{ $model->id }}'">
                        <td class="fw-bold">{{ $model->brand->brand_name }} 1000</td>
                        <td><span class="badge-spec">KP3#</span></td>
                        <td>KP30-</td>
                        <td>Driver: RIGHT-HAND</td>
                        <td>04.1969 - 02.1978</td>
                        <td>
                            <button class="btn btn-info btn-sm icon-btn">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
