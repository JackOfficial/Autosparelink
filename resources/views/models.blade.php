@extends('layouts.app')

@section('style')
<style>
/* -------------------------- */
/* GENERAL PAGE REFINEMENTS   */
/* -------------------------- */
body {
    background: #f5f7fa !important;
}

/* Breadcrumb */
.breadcrumb {
    background: #ffffff !important;
    border-radius: 6px;
}

/* Search Box */
.genuine-search-box {
    border-left: 4px solid #007bff;
}

/* -------------------------- */
/* MODEL CARD                 */
/* -------------------------- */
.model-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    transition: all .25s ease-in-out;
    border: 1px solid #e6e6e6;
}

.model-card:hover {
    transform: translateY(-4px);
    box-shadow: 0px 8px 18px rgba(0,0,0,0.08);
}

/* Model Name */
.model-card h6 a {
    font-weight: 600;
    color: #222;
}

.model-card h6 a:hover {
    color: #007bff !important;
}

/* Production Years */
.model-years {
    font-size: 13px;
    color: #888;
}

/* Toggle button */
.toggle-btn {
    border: none;
    background: transparent;
    padding: 4px;
    cursor: pointer;
}

.toggle-btn i {
    font-size: 14px;
    transition: transform .3s ease;
}

.toggle-btn i.rotate {
    transform: rotate(180deg);
}

/* Variant items */
.variant-item {
    font-size: 13px;
    padding: 6px 0;
    color: #444;
    display: flex;
    align-items: center;
}

.variant-item:hover {
    background: #f1f5ff;
}

/* Bullet */
.variant-item i {
    font-size: 6px;
    margin-right: 8px;
    color: #007bff;
}

/* -------------------------- */
/* BRAND DESCRIPTION          */
/* -------------------------- */
.brand-description-box {
    border-left: 5px solid #007bff;
}

.brand-description-text {
    font-size: 14px;
    line-height: 1.8;
    color: #555;
}

/* Grid spacing */
.model-container {
    margin-bottom: 25px;
}

@media(max-width: 768px) {
    h3 { font-size: 20px; }
}
</style>
@endsection


@section('content')

<!-- Breadcrumb -->
<div class="container-fluid px-xl-5 mt-3">
    <nav class="breadcrumb mb-3">
        <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
        <span class="breadcrumb-item active">Locate Genuine Parts</span>
    </nav>
</div>

<!-- Page Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded genuine-search-box mb-4">
        <h3 class="text-uppercase mb-1 font-weight-bold">
            Genuine Parts Locator
        </h3>
        <small class="text-muted">
            Enter your VIN or Frame Number to search genuine {{ $brand->brand_name }} parts
        </small>
    </div>
</div>

<!-- Search Box -->
<div class="container-fluid px-xl-5 mb-4">
    <div class="bg-white p-4 shadow-sm rounded">
        <form class="row align-items-center">
            <div class="col-lg-10 col-md-9 col-sm-12 mb-2">
                <input type="text"
                       class="form-control form-control-lg"
                       placeholder="Enter VIN or Frame Number">
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 mb-2">
                <button class="btn btn-primary btn-lg w-100">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Models Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="text-uppercase mb-1 font-weight-bold">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/'.$brand->brand_logo) }}"
                         style="width:45px; margin-top:-5px">
                @endif
                {{ $brand->brand_name }} Models
            </h4>
            <small class="text-muted">
                Select your vehicle model to browse the parts catalog
            </small>
        </div>

        <small class="text-muted">
            Showing <strong>{{ $models->count() }}</strong> models
        </small>
    </div>
</div>

<!-- MODELS GRID -->
<div class="container-fluid px-xl-5">
    <div class="row">

        @forelse($models as $model)
            <div class="col-md-3 col-sm-6 model-container">

                <div class="model-card h-100">

                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-car-side text-primary mr-2"></i>
                            <a href="/model-specification/{{ $model->id }}">
                                {{ $model->model_name }}
                            </a>
                        </h6>

                        @if($model->variants->count() > 0)
                        <button class="toggle-btn"
                                data-toggle="collapse"
                                data-target="#variants{{ $model->id }}"
                                aria-expanded="false"
                                aria-controls="variants{{ $model->id }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        @endif
                    </div>

                    <div class="model-years mt-2">
                        {{ $model->production_start_year ?? '?' }}
                        -
                        {{ $model->production_end_year ?? 'Present' }}
                    </div>

                    @if($model->variants->count() > 0)
                    <div class="collapse mt-3" id="variants{{ $model->id }}">
                        <div class="list-group list-group-flush">
                            @foreach($model->variants as $variant)
                                <a href="/variant-specification/{{ $variant->id }}"
                                   class="list-group-item bg-transparent border-0 variant-item">
                                    <i class="fas fa-circle"></i>
                                    {{ $variant->name ?? 'Variant' }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

            </div>
        @empty
            <div class="col-12 py-4">
                <p class="text-center text-muted">
                    No models found for {{ $brand->brand_name }}.
                </p>
            </div>
        @endforelse

    </div>
</div>

<!-- BRAND DESCRIPTION -->
@if($brand->description)
<div class="container-fluid px-xl-5 mt-4 mb-5">
    <div class="bg-white p-4 shadow-sm rounded brand-description-box">
        <div class="d-flex align-items-center mb-2">
            @if($brand->brand_logo)
                <img src="{{ asset('storage/'.$brand->brand_logo) }}"
                     style="width:50px" class="mr-3">
            @endif
            <h4 class="mb-0 font-weight-bold text-uppercase">
                About {{ $brand->brand_name }}
            </h4>
        </div>
        <p class="brand-description-text mb-0">
            {{ $brand->description }}
        </p>
    </div>
</div>
@endif

@endsection


@section('scripts')
<script>
$(document).ready(function () {

    $('.collapse').on('show.bs.collapse', function () {
        $(this).closest('.model-card')
               .find('.toggle-btn i')
               .addClass('rotate');
    });

    $('.collapse').on('hide.bs.collapse', function () {
        $(this).closest('.model-card')
               .find('.toggle-btn i')
               .removeClass('rotate');
    });

});
</script>
@endsection
