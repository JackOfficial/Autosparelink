@extends('layouts.app')

@section('style')
<style>
/* ===== Filters Panel ===== */
.filter-section {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
}
.filter-header {
    font-weight: bold;
    margin-bottom: 8px;
    text-transform: uppercase;
    font-size: 14px;
}
.part-card {
    border: 1px solid #e6e6e6;
    border-radius: 8px;
    transition: 0.3s;
}
.part-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}
.part-image {
    object-fit: contain;
    height: 140px;
}
.badge-compat { background: #007bff; color: #fff; font-size: 10px; padding: 3px 6px; border-radius: 4px; }
</style>
@endsection

@section('content')

<!-- Breadcrumbs -->
<div class="container-fluid mb-3">
    <nav class="breadcrumb bg-white px-3 py-2 rounded">
        <a class="breadcrumb-item text-dark" href="{{ route('home') }}">Home</a>
        <a class="breadcrumb-item text-dark" href="{{ route('brand.models', $specification->brand->id) }}">
            {{ $specification->brand->brand_name }}
        </a>
        <span class="breadcrumb-item active">
            {{ $specification->vehicleModel->model_name }}
            @if($type === 'variant')
                - {{ $specification->variant->name }}
            @endif
        </span>
    </nav>
</div>

<!-- Header & Search + Sort -->
<div class="container-fluid mb-4">
    <div class="d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
        <div>
            <h4 class="fw-bold mb-1 text-uppercase">
                {{ $specification->vehicleModel->brand->brand_name }} –
                {{ $specification->vehicleModel->model_name }}
                @if($type === 'variant') ({{ $specification->variant->name }}) @endif
            </h4>
            <small class="text-muted">Compatible Spare Parts</small>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <input
                type="text"
                id="searchParts"
                class="form-control"
                placeholder="Search parts..."
                value="{{ request('q') }}">
            <select id="sortParts" class="form-control">
                <option value="">Sort By</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
                <option value="newest">Newest</option>
                <option value="popularity">Most Popular</option>
            </select>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        <!-- Filters (Sidebar) -->
        <aside class="col-lg-3 mb-4">
            <div class="filter-section shadow-sm">

                <!-- Categories -->
                <div class="mb-4">
                    <div class="filter-header">Categories</div>
                    @foreach($categories as $cat)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="category[]" value="{{ $cat->id }}"
                                {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $cat->name }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <div class="filter-header">Price Range</div>
                    <input type="number" name="min_price" class="form-control mb-2" placeholder="Min" value="{{ request('min_price') }}">
                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                </div>

                <!-- Brands -->
                <div class="mb-4">
                    <div class="filter-header">Brands</div>
                    @foreach($brands as $brand)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="brand_filter[]" value="{{ $brand->id }}"
                                {{ in_array($brand->id, request('brand_filter', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $brand->brand_name }}</label>
                        </div>
                    @endforeach
                </div>

                <!-- Availability -->
                <div class="mb-4">
                    <div class="filter-header">Availability</div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}>
                        <label class="form-check-label">In Stock Only</label>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Parts Grid -->
        <section class="col-lg-9">
            <div class="row g-3">
                @forelse ($parts as $part)
                <div class="col-md-4">
                    <div class="part-card p-3 h-100">
                        <div class="text-center mb-3">
                            <a href="">
                                <img
                                    src="{{ asset('storage/' . ($part->mainPhoto?->file_path ?? 'placeholder.png')) }}"
                                    class="img-fluid part-image"
                                    alt="{{ $part->name }}">
                            </a>
                        </div>

                        <h6 class="fw-bold text-truncate">
                            <a href="" class="text-dark">
                                {{ $part->name }}
                            </a>
                        </h6>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-primary fw-bold">${{ number_format($part->price, 2) }}</span>

                            @if ($part->in_stock)
                                <span class="badge bg-success">In Stock</span>
                            @else
                                <span class="badge bg-secondary">Out of Stock</span>
                            @endif
                        </div>

                        @if($part->category)
                            <span class="badge badge-compat">{{ $part->category->name }}</span>
                        @endif

                        <div class="d-flex justify-content-between mt-3">
                            <a href="" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                            <form action="" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-primary">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4 text-muted">
                    No compatible parts found.
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $parts->links() }}
            </div>

        </section>
    </div>
</div>

@endsection
