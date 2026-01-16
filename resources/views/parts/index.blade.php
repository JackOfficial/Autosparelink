@extends('layouts.app')

@push('styles')
<style>
/* ====================== CARD BASE ====================== */
.product-item{
    border-radius: 12px;
    overflow: hidden; /* keeps content inside */
    background: #fff;
    transition: transform .18s ease, box-shadow .18s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-item:hover{
    transform: translateY(-4px);
    box-shadow: 0 15px 30px rgba(0,0,0,.08);
}

/* ====================== IMAGE ====================== */
.product-img{
    position: relative;
    overflow: hidden; /* prevents image overflow */
}

.product-img img{
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
    transition: transform .3s ease;
}

.product-item:hover .product-img img{
    transform: scale(1.08);
}

/* ====================== ACTION BUTTONS ====================== */
.product-action{
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 8px;
    opacity: 0;
    z-index: 10;
    transition: opacity .15s ease;
}

.product-item:hover .product-action{
    opacity: 1;
}

.btn-square{
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ====================== BADGE ====================== */
.badge-custom{
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.8rem;
    color: #fff;
    background: linear-gradient(90deg,#28a745,#20c997);
    z-index: 5;
}

/* ====================== RATINGS ====================== */
.product-rating i{
    font-size: 0.85rem;
    color: #ffc107; /* gold */
}

.product-rating small{
    color: #6c757d;
    margin-left: 4px;
    font-size: 0.8rem;
}

/* ====================== CARD BODY ====================== */
.product-body{
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    text-align: center;
}

.product-body a.h6{
    display: block;
    margin-bottom: 0.3rem;
    font-weight: 600;
    color: #212529;
    text-decoration: none;
}

.product-body a.h6:hover{
    color: #0d6efd;
    text-decoration: underline;
}

.product-price{
    font-weight: 700;
    color: #0d6efd;
    margin-bottom: 0.5rem;
}
</style>
@endpush

@section('content')

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="bg-white rounded shadow-sm p-4 mb-4">
        <h4 class="fw-bold mb-1">Spare Parts Catalog</h4>
        <p class="text-muted mb-0">
            Compatible parts for
            <strong>
                @if($type === 'variant')
                    {{ $context->vehicleModel->brand->brand_name }}
                    {{ $context->vehicleModel->model_name }}
                    – {{ $context->name }}
                @else
                    {{ $context->brand->brand_name }}
                    {{ $context->model_name }}
                @endif
            </strong>
        </p>
    </div>

    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-lg-3 mb-4">
            <!-- Search -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET">
                        <input type="text"
                               name="q"
                               class="form-control"
                               placeholder="Search part name / number"
                               value="{{ request('q') }}">
                    </form>
                </div>
            </div>

            <!-- Categories -->
            <div class="card mb-3">
                <div class="card-header fw-bold">Categories</div>
                <div class="card-body">
                    @foreach($categories as $category)
                        <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}"
                           class="d-flex justify-content-between text-decoration-none mb-2">
                            <span>{{ $category->category_name }}</span>
                            <span class="text-muted">({{ $category->parts_count }})</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Price Filter -->
            <div class="card">
                <div class="card-header fw-bold">Filter</div>
                <div class="card-body">
                    <form method="GET">
                        <input type="hidden" name="q" value="{{ request('q') }}">

                        <div class="mb-2">
                            <input type="number"
                                   name="min_price"
                                   class="form-control"
                                   placeholder="Min price"
                                   value="{{ request('min_price') }}">
                        </div>

                        <div class="mb-3">
                            <input type="number"
                                   name="max_price"
                                   class="form-control"
                                   placeholder="Max price"
                                   value="{{ request('max_price') }}">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="in_stock"
                                   value="1"
                                   {{ request('in_stock') ? 'checked' : '' }}>
                            <label class="form-check-label">
                                In stock only
                            </label>
                        </div>

                        <button class="btn btn-primary w-100">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- PARTS GRID -->
        <div class="col-lg-9">

            <!-- Sort -->
            <div class="d-flex justify-content-end mb-3">
                <form method="GET">
                    <select name="sort"
                            class="form-select"
                            onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A → Z</option>
                    </select>
                </form>
            </div>

            <div class="row">
                @forelse($parts as $part)
                    @php
                        $mainPhoto = $part->photos->first()
                            ? asset('storage/'.$part->photos->first()->file_path)
                            : asset('images/no-part.png');

                        $rating = $part->rating ?? 0;
                    @endphp

                    <div class="col-md-4 mb-4">
                        <div class="product-item h-100">

                            <!-- IMAGE -->
                            <div class="product-img">
                                <img src="{{ $mainPhoto }}" alt="{{ $part->part_name }}">
                                <span class="badge-custom">Compatible</span>

                                <!-- ACTIONS -->
                                <div class="product-action">
                                    <a href="{{ route('spare-parts.show', $part->id) }}"
                                       class="btn btn-light btn-square" title="View">
                                        <i class="fa fa-search"></i>
                                    </a>
                                    <button class="btn btn-light btn-square" title="Wishlist">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="btn btn-light btn-square" title="Add to Cart">
                                        <i class="fa fa-shopping-cart"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- BODY -->
                            <div class="product-body">
                                <a href="{{ route('spare-parts.show', $part->id) }}" class="h6 text-truncate">
                                    {{ Str::limit($part->part_name, 35) }}
                                </a>

                                <small class="text-muted d-block mb-2">Part No: {{ $part->part_number }}</small>

                                <!-- RATINGS -->
                                <div class="product-rating mb-2">
                                    @for($i=1;$i<=5;$i++)
                                        <i class="fa fa-star {{ $i <= $rating ? 'text-warning' : '' }}"></i>
                                    @endfor
                                    <small>({{ $part->reviews_count ?? 0 }})</small>
                                </div>

                                <!-- PRICE -->
                                <div class="product-price">
                                    {{ number_format($part->price) }} RWF
                                </div>

                                <!-- BUTTONS -->
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('spare-parts.show', $part->id) }}"
                                       class="btn btn-outline-primary btn-sm">Details</a>
                                    <button class="btn btn-primary btn-sm">Add to Cart</button>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            No parts found for this specification.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $parts->links() }}
            </div>
        </div>

    </div>
</div>

@endsection
