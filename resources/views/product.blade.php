@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

@push('styles')
<style>
[x-cloak]{ display:none !important; }

/* Wishlist */
.product-card:hover .wishlist-btn { opacity: 1; }
.wishlist-btn { opacity: 0; transition: opacity .3s; }

/* Tables */
.table-hover tbody tr:hover { background:#f1f1f1; cursor:pointer; }

/* Gallery */
.main-image { overflow:hidden; }
.main-image img { cursor: zoom-in; transition: transform .3s ease; }
.main-image:hover img { transform: scale(1.15); }

.gallery-btn {
    position:absolute; top:50%; transform:translateY(-50%);
    width:42px; height:42px; border-radius:50%;
    background:rgba(0,0,0,.6); color:#fff;
    border:none; font-size:26px;
    opacity:0; transition:opacity .3s;
}
.main-image:hover .gallery-btn { opacity:1; }
.prev-btn{ left:10px; } .next-btn{ right:10px; }

.thumbnail-wrapper{ display:flex; gap:10px; overflow-x:auto; margin-top:10px; }
.thumbnail-img{
    width:70px; height:70px; object-fit:cover;
    border-radius:6px; border:2px solid transparent;
    cursor:pointer;
}
.thumbnail-img.active-thumb{ border-color:#007bff; }
</style>
@endpush

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

        <!-- Product Gallery -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="bg-light p-3 rounded shadow-sm"
                 x-data="{
                    images: @json(
                        $part->photos->map(fn($p) => asset('storage/'.$p->photo_url))
                    ),
                    index: 0,
                    init(){
                        if(!this.images.length){
                            this.images = ['{{ asset('frontend/img/parts.jpg') }}']
                        }
                    }
                 }"
                 x-cloak
            >

                <div class="main-image position-relative rounded">
                    <img :src="images[index]" class="img-fluid w-100" alt="{{ $part->part_name }}">

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

                <template x-if="images.length > 1">
                    <div class="thumbnail-wrapper">
                        <template x-for="(img, i) in images" :key="i">
                            <img :src="img"
                                 class="thumbnail-img"
                                 :class="{ 'active-thumb': index === i }"
                                 @click="index = i">
                        </template>
                    </div>
                </template>

            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm product-card">

                <h2 class="font-weight-bold mb-3">{{ $part->part_name }}</h2>

                <p><strong>Make:</strong> {{ optional($part->partBrand)->name ?? '—' }}</p>
                <p><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
                <p><strong>Weight:</strong> {{ $part->weight ?? '—' }} kg</p>

                <h3 class="text-primary mb-3">
                    {{ number_format($part->price, 2) }} RWF
                </h3>

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
