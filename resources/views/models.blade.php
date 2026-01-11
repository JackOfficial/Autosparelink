@extends('layouts.app')

@php
    $brand = $models->first()?->brand;
@endphp

@section('style')
<style>
body { background: #f5f7fa !important; }
.breadcrumb { background: #ffffff !important; border-radius: 6px; }
.genuine-search-box { border-left: 4px solid #007bff; }

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

.model-card h6 a { font-weight: 600; color: #222; }
.model-card h6 a:hover { color: #007bff !important; }

.model-years { font-size: 13px; color: #888; }

.toggle-btn { border: none; background: transparent; padding: 4px; }
.toggle-btn i { transition: 0.3s ease; font-size: 14px; }

.variant-item {
    font-size: 13px;
    padding: 6px 0;
    color: #444;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.variant-item:hover { background: #f1f5ff; }

.variant-badge {
    font-size: 10px;
    padding: 2px 5px;
    margin-left: 3px;
    border-radius: 4px;
}

.model-name-disabled {
    cursor: default;
    opacity: 0.85;
}

.model-container { margin-bottom: 25px; }

#faqAccordion i { transition: transform .3s ease; }
#faqAccordion i.rotate { transform: rotate(180deg); }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid px-xl-5 mt-3">
    <nav class="breadcrumb mb-3">
        <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
        @if($brand)
            <span class="breadcrumb-item active">{{ $brand->brand_name }}</span>
        @endif
    </nav>
</div>

<!-- Page Header -->
@if($brand)
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded genuine-search-box mb-4">
        <h3 class="text-uppercase mb-1 fw-bold">Genuine Parts Locator</h3>
        <small class="text-muted">
            Enter VIN or Frame Number to search genuine {{ $brand->brand_name }} parts
        </small>
    </div>
</div>
@endif

<!-- Search Box -->
@if($brand)
<div class="container-fluid px-xl-5 mb-4">
    <div class="bg-white p-4 shadow-sm rounded">
        <form class="row g-3 align-items-center" method="GET"
              action="{{ route('brand.models', $brand->id) }}">
            <div class="col-lg-10 col-md-9 col-sm-12 mb-2">
                <input type="text" name="query"
                       class="form-control form-control-lg"
                       placeholder="Enter VIN or Frame Number"
                       value="{{ request('query') }}">
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 mb-2">
                <button class="btn btn-primary btn-lg w-100">Search</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Brand Header -->
@if($brand)
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3 d-flex justify-content-between">
        <div>
            <h4 class="fw-bold text-uppercase mb-1">
                @if($brand->brand_logo)
                    <img src="{{ asset('storage/'.$brand->brand_logo) }}"
                         style="width:45px;margin-top:-5px;">
                @endif
                {{ $brand->brand_name }} Models
            </h4>
            <small class="text-muted">Select your vehicle model</small>
        </div>
        <span class="text-muted small">
            Showing <strong>{{ $models->count() }}</strong> models
        </span>
    </div>
</div>
@endif

<!-- Models Grid -->
<div class="container-fluid px-xl-5">
    <div class="row">
        @forelse($models as $model)
            <div class="col-md-3 col-sm-6 model-container">
                <div class="model-card h-100">

                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            @if($model->variants->count())
                                <span class="model-name-disabled">{{ $model->model_name }}</span>
                            @else
                                <a href="{{ route('model.specification', $model->id) }}">
                                    {{ $model->model_name }}
                                </a>
                            @endif
                        </h6>

                        @if($model->variants->count())
                        <button class="toggle-btn" data-toggle="collapse"
                                data-target="#variants{{ $model->id }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        @endif
                    </div>

                    <div class="model-years mt-2">
                        {{ $model->production_start_year ?? '?' }}
                        -
                        {{ $model->production_end_year ?? '?' }}
                    </div>

                    @if($model->variants->count())
                    <div class="collapse mt-3" id="variants{{ $model->id }}">
                        @foreach($model->variants as $variant)
                            <a href="{{ route('variant.specification', $variant->id) }}"
                               class="variant-item list-group-item border-0 bg-transparent">
                                <span>{{ $variant->name ?? 'Variant' }}</span>
                                <span>
                                    @if($variant->engine_type)
                                        <span class="badge bg-info variant-badge">{{ $variant->engine_type->name }}</span>
                                    @endif
                                    @if($variant->transmission_type)
                                        <span class="badge bg-secondary variant-badge">{{ $variant->transmission_type->name }}</span>
                                    @endif
                                </span>
                            </a>
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">
                No models found.
            </div>
        @endforelse
    </div>
</div>

<!-- Brand Description -->
@if($brand && $brand->description)
<div class="container-fluid px-xl-5 mt-4 mb-5">
    <div class="bg-white p-4 shadow-sm rounded">
        <h4 class="fw-bold text-uppercase">About {{ $brand->brand_name }}</h4>
        <p class="mb-0">{{ $brand->description }}</p>
    </div>
</div>
@endif

@endsection
