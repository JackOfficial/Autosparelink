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

/* Chevron Button */
.toggle-btn {
    border: none;
    background: transparent;
    padding: 4px;
}

.toggle-btn i {
    transition: 0.3s ease;
    font-size: 14px;
}

.collapse.show + .toggle-btn i,
.collapse.show ~ .toggle-btn i {
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

/* Small bullet */
.variant-item i {
    font-size: 6px;
    margin-right: 8px;
    color: #007bff;
}

/* -------------------------- */
/* MODEL GRID SPACING         */
/* -------------------------- */
.model-container {
    margin-bottom: 25px;
}

/* Responsive Adjustments */
@media(max-width: 768px) {
    h3 {
        font-size: 20px;
    }
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
        <h3 class="text-uppercase mb-1" style="font-weight:700;">Genuine Parts Locator</h3>
        <small class="text-muted">
            Enter your VIN or Frame Number to search genuine {{ $brand->brand_name }} parts
        </small>
    </div>
</div>

<!-- Search Box -->
<div class="container-fluid px-xl-5 mb-4">
    <div class="bg-white p-4 shadow-sm rounded">
        <form class="row g-3 align-items-center">
            <div class="col-lg-10 col-md-9 col-sm-12 mb-2">
                <input 
                    type="text" 
                    class="form-control form-control-lg" 
                    placeholder="Enter VIN or Frame Number (e.g., JTMHX09J604123456)"
                >
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 mb-2">
                <button class="btn btn-primary btn-lg w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Brand Models Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">

            <div>
                <h4 class="text-uppercase mb-1 fw-bold">
                    @if($brand->brand_logo)
                        <img src="{{ asset('storage/' . $brand->brand_logo) }}" 
                             style="width: 45px; margin-top:-5px;" />
                    @endif
                    {{ $brand->brand_name }} Models
                </h4>
                <small class="text-muted">
                    Select your vehicle model to browse the parts catalog
                </small>
            </div>

            <div class="mt-3 mt-md-0">
                <span class="text-muted small">
                    Showing <strong>{{ count($models) }}</strong> models
                </span>
            </div>

        </div>
    </div>
</div>

<!-- Models Grid -->
<div class="container-fluid px-xl-5">
    <div class="row">

        @forelse($models as $model)
            <div class="col-md-3 col-sm-6 model-container">

                <div class="model-card h-100">

                    <!-- Header -->
                    <div class="d-flex align-items-center justify-content-between">

                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-side text-primary mr-2"></i>
                            <h6 class="mb-0">
                                <a href="/model-specification/{{ $model->id }}">
                                    {{ $model->model_name }}
                                </a>
                            </h6>
                        </div>

                        @if($model->variants->count() > 0)
                        <button
                            class="toggle-btn"
                            type="button"
                            data-toggle="collapse"
                            data-target="#variants{{ $model->id }}"
                        >
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        @endif

                    </div>

                    <div class="model-years mt-2">
                        {{ $model->production_start_year }} - {{ $model->production_end_year }}
                    </div>

                    <!-- Variants -->
                    @if($model->variants->count() > 0)
                    <div class="collapse mt-3" id="variants{{ $model->id }}">
                        <div class="list-group list-group-flush">

                            @foreach($model->variants as $variant)
                            <a 
                                href="/variant-specification/{{ $variant->id }}" 
                                class="list-group-item bg-transparent border-0 variant-item"
                            >
                                <i class="fas fa-circle"></i>

                                @if($variant->name)
                                    {{ $variant->name }}
                                @else
                                    {{ $variant->body_type->name ?? 'Body' }} /
                                    {{ $variant->engine_type->name ?? 'Engine' }} /
                                    {{ $variant->transmission_type->name ?? 'Transmission' }}
                                @endif
                            </a>
                            @endforeach

                        </div>
                    </div>
                    @endif

                </div>

            </div>
        @empty
        <div class="col-md-12 py-4">
            <p class="text-center text-muted">
                No models found for {{ $brand->brand_name }} at the moment.
            </p>
        </div>
        @endforelse

    </div>
</div>

@endsection
