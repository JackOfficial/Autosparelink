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
                        src="{{ optional($part->photos->first())->photo_url
                            ? asset('storage/'.$part->photos->first()->photo_url)
                            : asset('frontend/img/parts.jpg') }}"
                        class="img-fluid w-100"
                        alt="{{ $part->part_name }}"
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
                            >
                        @endforeach
                    </div>
                @endif

            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm product-card">

                <h2 class="font-weight-bold mb-3">{{ $part->part_name }}</h2>

                <p><strong>Make:</strong> {{ optional($part->partBrand)->name ?? '—' }}</p>
                <p><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
                <p><strong>Weight:</strong> {{ $part->weight ?? '—' }} kg</p>

                <h3 class="text-primary">{{ number_format($part->price, 2) }} RWF</h3>

                <p>
                    <strong>Availability:</strong>
                    <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
                        {{ $part->stock_quantity }}
                    </span>
                </p>

                <div class="border-top pt-3">
                    <h5>Product Description</h5>
                    <p>{{ $part->description ?? 'No description available.' }}</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Substitutions -->
    @if($substitutions->count())
    <div class="row px-xl-5 mt-4">
        <div class="col-12">
            <h4>Substitutions</h4>
            <div class="table-responsive bg-light p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Make</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Weight</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($substitutions as $sub)
                        <tr>
                            <td>{{ optional($sub->partBrand)->name ?? '—' }}</td>
                            <td>{{ $sub->part_number ?? '—' }}</td>
                            <td>{{ $sub->part_name }}</td>
                            <td>{{ $sub->stock_quantity }}</td>
                            <td>{{ $sub->weight ?? '—' }}</td>
                            <td>{{ number_format($sub->price,2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Compatibility -->
    @if($compatibilities->count())
    <div class="row px-xl-5 mt-4">
        <div class="col-12">
            <h4>Compatibility</h4>
            <div class="table-responsive bg-light p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Market</th>
                            <th>Model</th>
                            <th>Variant</th>
                            <th>Year From</th>
                            <th>Year To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compatibilities as $variant)
                        <tr>
                            <td>{{ optional($variant->vehicleModel->brand)->brand_name ?? '—' }}</td>
                            <td>{{ $variant->vehicleModel->model_name ?? '—' }}</td>
                            <td>{{ $variant->name ?? '—' }}</td>
                            <td>{{ $variant->pivot->year_start ?? '—' }}</td>
                            <td>{{ $variant->pivot->year_end ?? '—' }}</td>
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
