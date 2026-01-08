@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')

<style>
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
                    images: [
                        @forelse($part->photos as $photo)
                            '{{ asset('storage/'.$photo->photo_url) }}',
                        @empty
                            '{{ asset('frontend/img/parts.jpg') }}',
                        @endforelse
                    ],
                    index: 0
                 }"
            >

                <!-- Main Image -->
                <div class="main-image position-relative overflow-hidden rounded">
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

                <!-- Thumbnails -->
                <template x-if="images.length > 1">
                    <div class="thumbnail-wrapper">
                        <template x-for="(img,i) in images" :key="i">
                            <img :src="img"
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
            <div class="bg-light p-4 rounded shadow-sm">

                <h2>{{ $part->part_name }}</h2>
                <p><strong>Make:</strong> {{ optional($part->partBrand)->name ?? '—' }}</p>
                <p><strong>Part Number:</strong> {{ $part->part_number ?? '—' }}</p>
                <p><strong>Weight:</strong> {{ $part->weight ?? '—' }} kg</p>

                <h3 class="text-primary">{{ number_format($part->price,2) }} RWF</h3>

                <p>
                    <strong>Stock:</strong>
                    <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
                        {{ $part->stock_quantity }}
                    </span>
                </p>

                <hr>
                <h5>Description</h5>
                <p>{{ $part->description ?? 'No description available.' }}</p>

            </div>
        </div>
    </div>

</div>
@endsection
