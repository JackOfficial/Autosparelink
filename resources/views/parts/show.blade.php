@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
    <style>
/* Wishlist button appears on card hover */
.product-card:hover .wishlist-btn { opacity: 1; }
.wishlist-btn { opacity: 0; transition: opacity 0.3s ease; }

/* Quantity buttons appear on hover */
.quantity-wrapper .btn-minus,
.quantity-wrapper .btn-plus { opacity: 0; transition: opacity 0.3s ease; }
.quantity-wrapper:hover .btn-minus,
.quantity-wrapper:hover .btn-plus { opacity: 1; }

/* Table hover effect */
.table-hover tbody tr:hover { background-color: #f1f1f1; cursor: pointer; }

/* Gallery */
.main-image img { cursor: zoom-in; transition: transform .3s ease; }
.main-image:hover img { transform: scale(1.1); }
.gallery-btn {
    position:absolute; top:50%; transform:translateY(-50%);
    width:42px; height:42px; border-radius:50%;
    background:rgba(0,0,0,.6); color:#fff; border:none;
    font-size:26px; opacity:0; transition:opacity .3s;
}
.main-image:hover .gallery-btn { opacity:1; }
.prev-btn { left:10px; } .next-btn { right:10px; }
.thumbnail-wrapper { display:flex; gap:10px; margin-top:10px; overflow-x:auto; }
.thumbnail-img {
    width:70px; height:70px; object-fit:cover;
    border-radius:6px; border:2px solid transparent;
    cursor:pointer;
}
.thumbnail-img.active-thumb { border-color:#007bff; }
</style>
@endpush

@section('content')
<div class="container-fluid mt-4">

    <!-- Breadcrumb -->
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light p-3 rounded">
                <a class="breadcrumb-item" href="/">Home</a>
                <a class="breadcrumb-item" href="/shop">Shop</a>
                <span class="breadcrumb-item active">{{ $part->part_name }}</span>
            </nav>
        </div>
    </div>

    <div class="row px-xl-5">

        <!-- IMAGE GALLERY -->
       <div class="col-lg-5 col-md-6 mb-4">
    <div class="bg-light p-3 rounded shadow-sm"
         x-data="{
            images: {{ $photos->isNotEmpty() ? $photos->map(fn($p) => json_encode(asset('storage/'.$p->file_path))) : json_encode([asset('frontend/img/parts.jpg')]) }},
            index: 0,
         }"
    >
        <!-- Main Image -->
        <div class="main-image position-relative overflow-hidden rounded">
            <img :src="JSON.parse(images[index])" class="img-fluid w-100" alt="{{ $part->part_name }}">

            <template x-if="images.length > 1">
                <button class="gallery-btn prev-btn"
                        @click="index = (index - 1 + images.length) % images.length">
                    &lsaquo;
                </button>
            </template>

            <template x-if="images.length > 1">
                <button class="gallery-btn next-btn"
                        @click="index = (index + 1) % images.length">
                    &rsaquo;
                </button>
            </template>
        </div>

        <!-- Thumbnails -->
        <template x-if="images.length > 1">
            <div class="thumbnail-wrapper mt-3">
                <template x-for="(img,i) in images" :key="i">
                    <img :src="JSON.parse(img)"
                         class="thumbnail-img"
                         :class="{ 'active-thumb': index === i }"
                         @click="index = i">
                </template>
            </div>
        </template>
    </div>
</div>
        <!-- PRODUCT INFO -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm product-card">

                <h2 class="mb-3">{{ $part->part_name }}</h2>

                <p><strong>Make:</strong> {{ optional($part->partBrand)->name ?? '—' }}</p>
                <p><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
                <p class="{{ $part->weight ?? 'd-none' }}"><strong>Weight:</strong> {{ $part->weight ?? '—' }} kg</p>

                <h3 class="text-primary mb-3">{{ number_format($part->price,2) }} RWF</h3>

                <p>
                    <strong>Availability:</strong>
                    <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
                        {{ $part->stock_quantity }}
                    </span>
                </p>

                <!-- Quantity Selector -->
                <div class="quantity-wrapper mb-3" x-data="{ qty: 1 }">
                    <label class="mb-2">Quantity:</label>
                    <div class="input-group w-50">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" @click="if(qty>1) qty--">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <input type="number" class="form-control text-center" x-model="qty" min="1">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" @click="qty++">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Add to Cart / Wishlist -->
                <div class="mb-3 d-flex align-items-center">
                    <button class="btn btn-primary btn-lg mr-2">
                        <i class="fa fa-shopping-cart mr-1"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg wishlist-btn">
                        <i class="fa fa-heart mr-1"></i> Add to Wishlist
                    </button>
                </div>

                <!-- Share Buttons -->
                <div class="mb-4">
                    <strong class="mr-2">Share:</strong>
                    <a href="#" class="btn btn-sm btn-success mr-1"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="btn btn-sm btn-dark mr-1"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="btn btn-sm btn-primary"><i class="fab fa-facebook-f"></i></a>
                </div>

                <div class="border-top pt-3">
                    <h5>Product Description</h5>
                    <p>{{ $part->description ?? 'No description available.' }}</p>
                </div>

            </div>
        </div>
    </div>

    <!-- SUBSTITUTIONS -->
    @if($substitutions->count())
    <div class="row px-xl-5 mt-4">
        <div class="col-12">
            <h4>Substitutions ({{ $substitutions->count() }})</h4>
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
                            <td> {{ optional($sub->partBrand)->name ?? '—' }} </td>
                            <td><a href="{{ route('spare-parts.show', $sub->sku) }}">{{ $sub->part_number ?? '—' }}</a></td>
                            <td><a href="{{ route('spare-parts.show', $sub->sku) }}">{{ $sub->part_name }}</a></td>
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

    <!-- COMPATIBILITY -->
    @if($compatibilities->count())
    <div class="row px-xl-5 mt-4">
        <div class="col-12">
            <h4>Compatibility ({{ $compatibilities->count() }}) </h4>
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
                            <td>
                                @if($variants->count())
                                    <a href="{{ route('specifications.show', ['type' => 'variant', 'id' => $variant->variant_id]) }}">{{ $variant->vehicleModel->model_name ?? '—' }}</a>
                                @else
                                    <a href="{{ route('specifications.show', ['type' => 'model', 'id' => $variant->vehicle_model_id]) }}">{{ $variant->vehicleModel->model_name ?? '—' }}</a>
                                @endif
                                </td>
                            <td>{{ $variant->variant->name ?? '—' }}</td>
                            <td>{{ $variant->vehicleModel->production_start_year ?? 'Present' }}</td>
                            <td>{{ $variant->vehicleModel->production_end_year ?? 'Present' }}</td>
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
