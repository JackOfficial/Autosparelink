@extends('layouts.app')

@section('style')
<style>
/* Hover effect for parts cards */
.parts-card:hover { transform: translateY(-5px); transition: 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

/* Filter Card */
.filter-card .form-control { height: calc(2.2rem + 2px); }
.filter-card .btn { height: calc(2.2rem + 2px); }

/* Sidebar */
.sidebar { border-right: 1px solid #ddd; padding-right: 15px; }
.sticky-spec { position: sticky; top: 0; z-index: 10; background-color: #fff; padding: 15px; border-bottom: 1px solid #ddd; }

/* Parts Grid */
.parts-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
.parts-card img { width: 100%; height: 150px; object-fit: contain; }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid mb-3">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light">
                <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
                @if(isset($model))
                    <a class="breadcrumb-item text-dark" href="{{ route('brand.models', $model->brand->id) }}">{{ $model->brand->brand_name }}</a>
                    <span class="breadcrumb-item active">{{ $model->model_name }} Parts</span>
                @elseif(isset($variant))
                    <a class="breadcrumb-item text-dark" href="{{ route('brand.models', $variant->vehicleModel->brand->id) }}">{{ $variant->vehicleModel->brand->brand_name }}</a>
                    <span class="breadcrumb-item active">{{ $variant->name }} Parts</span>
                @endif
            </nav>
        </div>
    </div>
</div>

<!-- Chosen Variant/Model Specification (Sticky) -->
<div class="container-fluid px-xl-5 mb-3">
    <div class="sticky-spec shadow-sm rounded">
        <h5 class="mb-2">
            @if(isset($model))
                {{ $model->brand->brand_name }} – {{ $model->model_name }}
            @elseif(isset($variant))
                {{ $variant->vehicleModel->brand->brand_name }} – {{ $variant->name }}
            @endif
        </h5>
        @if(isset($variant))
        <ul class="list-inline mb-0 text-muted">
            <li class="list-inline-item">Seats: {{ $variant->seats ?? '-' }}</li>
            <li class="list-inline-item">Doors: {{ $variant->doors ?? '-' }}</li>
            <li class="list-inline-item">Engine: {{ $variant->engineType->name ?? '-' }}</li>
            <li class="list-inline-item">Transmission: {{ $variant->transmissionType->name ?? '-' }}</li>
            <li class="list-inline-item">Drive: {{ $variant->driveType->name ?? '-' }}</li>
            <li class="list-inline-item">Steering: {{ $variant->steering_position ?? '-' }}</li>
        </ul>
        @endif
    </div>
</div>

<!-- Page Layout: Sidebar Filters + Parts -->
<div class="container-fluid px-xl-5">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 sidebar mb-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="mb-3">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Part Name</label>
                            <input type="text" name="part_name" class="form-control" placeholder="Search Part" value="{{ request('part_name') }}">
                        </div>
                        <div class="mb-3">
                            <label>Variant / Model</label>
                            <select name="variant_id" class="form-control">
                                <option value="">All Variants</option>
                                @if(isset($model))
                                    @foreach($model->variants as $v)
                                        <option value="{{ $v->id }}" {{ request('variant_id') == $v->id ? 'selected' : '' }}>
                                            {{ $v->name }}
                                        </option>
                                    @endforeach
                                @elseif(isset($variant))
                                    <option value="{{ $variant->id }}" selected>{{ $variant->name }}</option>
                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Parts Grid -->
        <div class="col-lg-9">
            @if($parts->isEmpty())
                <div class="text-center text-muted">No parts found for this {{ isset($model) ? 'model' : 'variant' }}.</div>
            @else
                <div class="parts-grid">
                    @foreach($parts as $part)
                        <div class="card parts-card shadow-sm">
                            <img src="{{ $part->image ? asset('storage/' . $part->image) : asset('images/placeholder.png') }}" alt="{{ $part->name }}">
                            <div class="card-body">
                                <h6 class="card-title">{{ $part->name }}</h6>
                                <p class="mb-1 text-muted">{{ $part->category->name ?? '-' }}</p>
                                <p class="mb-1">Variant: {{ $part->variant->name ?? $part->vehicleModel->model_name ?? '-' }}</p>
                                <p class="mb-1">Stock: {{ $part->stock ?? '-' }}</p>
                                <p class="mb-1">Price: {{ $part->price ? number_format($part->price, 2) : '-' }}</p>
                                <a href="#" class="btn btn-sm btn-outline-primary w-100 mt-2">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
