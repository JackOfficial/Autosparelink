@extends('layouts.app')

@section('style')
<style>
.table-hover tbody tr:hover { background-color: #f8f9fa; }
.filter-card .form-control { height: calc(2.2rem + 2px); }
.filter-card .btn { height: calc(2.2rem + 2px); }
</style>
@endsection

@section('content')

<!-- Breadcrumb -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
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

<!-- Header -->
<div class="container-fluid px-xl-5">
    <div class="bg-white p-4 shadow-sm rounded mb-3">
        <h4 class="text-uppercase mb-1" style="font-weight: 600;">
            @if(isset($model) && $model->brand->brand_logo)
                <img src="{{ asset('storage/' . $model->brand->brand_logo) }}" style="width:50px; height:auto;" />
            @elseif(isset($variant) && $variant->vehicleModel->brand->brand_logo)
                <img src="{{ asset('storage/' . $variant->vehicleModel->brand->brand_logo) }}" style="width:50px; height:auto;" />
            @endif

            @if(isset($model))
                {{ $model->brand->brand_name }} – {{ $model->model_name }} Parts
            @elseif(isset($variant))
                {{ $variant->vehicleModel->brand->brand_name }} – {{ $variant->name }} Parts
            @endif
        </h4>
        <small class="text-muted">Browse all spare parts available for this vehicle.</small>
    </div>
</div>

<!-- Filters -->
<div class="container-fluid px-xl-5 mb-3">
    <div class="card shadow-sm filter-card">
        <div class="card-body">
            <form method="GET" action="">
                <div class="row g-2">

                    <!-- Category -->
                    <div class="col-md-3">
                        <select name="category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Part Name -->
                    <div class="col-md-3">
                        <input type="text" name="part_name" class="form-control" placeholder="Part Name" value="{{ request('part_name') }}">
                    </div>

                    <!-- Variant / Model -->
                    <div class="col-md-3">
                        <select name="variant_id" class="form-control">
                            <option value="">Variant / Model</option>
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

                    <!-- Submit -->
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- Parts Table -->
<div class="container-fluid px-xl-5">
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Part Name</th>
                        <th>Category</th>
                        <th>Variant / Model</th>
                        <th>Part Number</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parts as $part)
                        <tr>
                            <td>{{ $part->name }}</td>
                            <td>{{ $part->category->name ?? '-' }}</td>
                            <td>{{ $part->variant->name ?? $part->vehicleModel->model_name ?? '-' }}</td>
                            <td>{{ $part->part_number ?? '-' }}</td>
                            <td>{{ $part->stock ?? '-' }}</td>
                            <td>{{ $part->price ? number_format($part->price, 2) : '-' }}</td>
                            <td>{{ $part->status ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No parts found for this {{ isset($model) ? 'model' : 'variant' }}.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
