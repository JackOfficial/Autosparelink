@extends('layouts.app')

@section('style')
<style>
:root{--primary:#0d6efd;--muted:#6c757d;--card-radius:12px;}
.sidebar { border-right: 1px solid #ddd; padding-right: 15px; }
.sticky-spec { position: sticky; top: 0; z-index: 10; background-color: #fff; padding: 15px; border-bottom: 1px solid #ddd; border-radius: var(--card-radius); }
.product-item { border-radius: var(--card-radius); overflow: hidden; transition: transform .18s ease, box-shadow .18s ease; }
.product-item:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(2,6,23,0.08); }
.product-img { position: relative; }
.product-img img { width: 100%; height: 180px; object-fit: cover; }
.product-action { position: absolute; top: 10px; right: 10px; display:flex; gap:6px; opacity:0; transition:opacity .15s ease; }
.product-item:hover .product-action { opacity:1; }
.btn-square { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; }
.badge-custom { position:absolute; left:10px; top:10px; padding:5px 10px; border-radius:8px; font-weight:700; font-size:0.75rem; color:#fff; }
.badge-new { background: linear-gradient(90deg,#28a745,#20c997); }
.badge-discount { background: linear-gradient(90deg,#ff7b00,#ff4d4d); }
.price-old { color: var(--muted); font-size:0.85rem; text-decoration:line-through; margin-left:5px; }
.parts-grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(220px,1fr)); gap:20px; }
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

<!-- Sticky Variant/Model -->
<div class="container-fluid px-xl-5 mb-3">
    <div class="sticky-spec shadow-sm">
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
        </ul>
        @endif
    </div>
</div>

<!-- Page Layout -->
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
                                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
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
                                        <option value="{{ $v->id }}" {{ request('variant_id')==$v->id?'selected':'' }}>{{ $v->name }}</option>
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
                <div class="text-center text-muted py-4">No parts found for this {{ isset($model)?'model':'variant' }}.</div>
            @else
                <div class="parts-grid">
                    @foreach($parts as $part)
                        @php $mainPhoto = $part->image ?? 'frontend/img/placeholder.png'; @endphp
                        <div class="product-item bg-white">
                            <div class="product-img position-relative">
                                @if(!empty($part->is_new)) <div class="badge-custom badge-new">NEW</div> @endif
                                <img loading="lazy" src="{{ asset('storage/'.$mainPhoto) }}" alt="{{ $part->name }}">
                                <div class="product-action">
                                    <a class="btn btn-light btn-square" href="#" title="Add to cart"><i class="fa fa-shopping-cart"></i></a>
                                    <a class="btn btn-light btn-square" href="#" title="Wishlist"><i class="far fa-heart"></i></a>
                                    <a class="btn btn-light btn-square" href="#" title="Compare"><i class="fa fa-sync-alt"></i></a>
                                    <a class="btn btn-light btn-square" href="shop/products/{{ $part->id }}" title="View"><i class="fa fa-search"></i></a>
                                </div>
                            </div>
                            <div class="text-center py-3 px-2">
                                <a class="h6 text-truncate d-block mb-1 text-dark" href="shop/products/{{ $part->id }}">{{ Str::limit($part->name, 30) }}</a>
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <h5 class="mb-0">{{ number_format($part->price,2) }} {{ $currencySymbol ?? 'RWF' }}</h5>
                                    @if(!empty($part->old_price)) <h6 class="price-old mb-0">{{ number_format($part->old_price,2) }}</h6> @endif
                                </div>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="shop/products/{{ $part->id }}" class="btn btn-outline-primary btn-sm">View details</a>
                                    <a href="#" class="btn btn-primary btn-sm">Add to cart</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
