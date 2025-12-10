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
                <span class="breadcrumb-item active">Locate Genuine Parts</span>
            </nav>
        </div>
    </div>
</div>
<!-- End Breadcrumb -->

<!-- Page Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-4">
        <h3 class="text-uppercase mb-1">Genuine Parts Locator</h3>
        <small class="text-muted">Enter your VIN or Frame Number to search genuine {{ $brand->brand_name }} parts</small>
    </div>
</div>

<!-- Single Large Search Box -->
<div class="container-fluid px-xl-5 mb-4">
    <div class="bg-white p-4 shadow-sm rounded">
        <form class="row g-3 align-items-center">
            <div class="col-lg-10 col-md-9 col-sm-12 mb-2">
                <input 
                    type="text" 
                    class="form-control form-control-lg" 
                    placeholder="Enter VIN or Frame Number (e.g., JTMHX09J604123456 or J100-012345)"
                >
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 mb-2">
                <button class="btn btn-primary btn-lg w-100">
                    Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toyota Model List Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h4 class="text-uppercase mb-1" style="font-weight: 600;">
                   @if($brand->brand_logo)
                        <img src="{{ asset('storage/' . $brand->brand_logo) }}" style="width: 50px; height:auto;" />
                    @endif
                    {{ $brand->brand_name }} Models
                </h4>
                <small class="text-muted">
                    Select your vehicle model to browse parts catalog
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

<!-- Toyota Models Grid -->
<div class="container-fluid px-xl-5">
    <div class="row">

        @forelse($models as $model)
            <div class="col-md-3 col-sm-6 mb-4">

                <div class="bg-white shadow-sm rounded p-3 h-100 hover-shadow">

                    <!-- Model Header -->
                    <div class="d-flex align-items-center justify-content-between">

                        <!-- Vehicle Icon + Model Name -->
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-side mr-2"></i>
                            <h6 class="mb-0 ml-1 text-uppercase fw-semibold">
                                <a href="/model-specification/{{ $model->id }}">{{ $model->model_name }}</a>
                            </h6>
                        </div>

                        <!-- Collapse Icon (only if model has variants) -->
                        @if($model->variants->count() > 0)
                            <button 
                                class="btn btn-sm border-0 bg-transparent toggle-btn"
                                type="button"
                                data-toggle="collapse"
                                data-target="#variants{{ $model->id }}"
                                aria-expanded="false"
                                aria-controls="variants{{ $model->id }}"
                            >
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        @endif

                    </div>

                    <!-- Production Years -->
                    <small class="text-muted d-block mt-1">
                        {{ $model->production_start_year }} - {{ $model->production_end_year }}
                    </small>

                    <!-- Variants Collapse Section -->
                    @if($model->variants->count() > 0)
    <div class="collapse mt-3" id="variants{{ $model->id }}">
        <div class="list-group list-group-flush">

            @foreach($model->variants as $variant)
                <a href="/variant-specification/{{ $variant->id }}" class="list-group-item px-2 py-1 small">
                    <i class="fas fa-circle text-primary mr-2"></i>
                    @if(!empty($variant->name))
                        <a href="#">{{ $variant->name }}</a>
                    @else
                       <a href="#">
                        {{ $variant->body_type->name ?? 'Body' }} /
                        {{ $variant->engine_type->name ?? 'Engine' }} /
                        {{ $variant->transmission_type->name ?? 'Transmission' }}
                       </a> 
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endif

                </div>

            </div>
        @empty    
        <div class="col-md-12 col-sm-12 py-4">
             <p class="text-center">No Model found for {{  $brand->brand_name}} at the moment!</p>
        </div>
        @endforelse

    </div>
</div>

@endsection
