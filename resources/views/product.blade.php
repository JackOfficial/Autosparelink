@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@section('content')
<div class="container-fluid mt-4">

    <!-- Breadcrumb -->
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-4 p-3 rounded">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <a class="breadcrumb-item text-dark" href="/shop">Shop</a>
                <span class="breadcrumb-item active">{{ $part->part_name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Details -->
    <div class="row px-xl-5">

        <!-- Product Image -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="bg-light p-3 rounded shadow-sm">

                <div class="main-image position-relative overflow-hidden rounded">
                    <img
                        id="currentImage"
                        src="{{ $part->photos->first() ? asset('storage/'.$part->photos->first()->photo_url) : asset('frontend/img/parts.jpg') }}"
                        class="img-fluid w-100"
                        alt="{{ $part->part_name }}"
                        loading="lazy"
                    >

                    @if($part->photos->count() > 1)
                        <button type="button" class="gallery-btn prev-btn">&lsaquo;</button>
                        <button type="button" class="gallery-btn next-btn">&rsaquo;</button>
                    @endif
                </div>

                @if($part->photos->count() > 1)
                    <div class="thumbnail-wrapper mt-3">
                        @foreach($part->photos as $photo)
                            <img
                                src="{{ asset('storage/'.$photo->photo_url) }}"
                                data-full="{{ asset('storage/'.$photo->photo_url) }}"
                                class="thumbnail-img"
                                loading="lazy"
                            >
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm product-card position-relative">

                <h2 class="font-weight-bold mb-3">{{ $part->part_name }}</h2>

                <p class="mb-1"><strong>Make:</strong> <a href="#">{{ $part->partBrand->name }}</a></p>
                <p class="mb-1"><strong>Part Number:</strong> <a href="#">{{ $part->part_number ?? 'N/A' }}</a></p>
                <p class="mb-1"><strong>Weight:</strong> {{ $part->weight ?? 'N/A' }} kg</p>

                <h3 class="text-primary mb-3">{{ number_format($part->price, 2) }} <small class="text-muted">RWF</small></h3>

                <p class="mb-3"><strong>Availability:</strong>
                    <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
                        {{ $part->stock_quantity }}
                    </span>
                </p>

                <div class="mb-4 position-relative quantity-wrapper">
                    <label class="mb-2">Quantity:</label>
                    <div class="input-group w-50">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary btn-minus" type="button"><i class="fa fa-minus"></i></button>
                        </div>
                        <input type="number" class="form-control text-center" value="1" min="1" id="quantity-input">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-plus" type="button"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </div>

                <div class="mb-4 d-flex align-items-center">
                    <button class="btn btn-primary btn-lg mr-2">
                        <i class="fa fa-shopping-cart mr-1"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg wishlist-btn">
                        <i class="fa fa-heart mr-1"></i> Add to Wishlist
                    </button>
                </div>

                <div class="mb-4">
                    <strong class="mr-2">Share:</strong>
                    <a href="#" class="btn btn-sm btn-success mr-1"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="btn btn-sm btn-dark mr-1"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-sm btn-primary"><i class="fab fa-facebook-f"></i></a>
                </div>

                <div class="border-top pt-3">
                    <h5 class="mb-2">Product Description</h5>
                    <p>{{ $part->description ?? 'No description available.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compatibility Table -->
    @if($compatibilities->count())
    <div class="row px-xl-5">
        <div class="col-12 mb-4">
            <h4 class="mb-3">Compatibility</h4>
            <div class="table-responsive bg-light p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Market</th>
                            <th>Model</th>
                            <th>Variant</th>
                            <th>Year From</th>
                            <th>Year To</th>
                            <th>Diagram</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compatibilities as $comp)
                        <tr>
                            <td>{{ optional($comp->vehicleModel->brand)->brand_name ?? '—' }}</td>
                            <td>{{ $comp->vehicleModel->model_name ?? '—' }}</td>
                            <td>{{ $comp->name ?? '—' }}</td>
                            <td>{{ $comp->pivot->year_start ?? '—' }}</td>
                            <td>{{ $comp->pivot->year_end ?? '—' }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">
                                    <i class="fas fa-info"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
