@extends('layouts.app')

@section('title', $part->part_name . ' | AutoSpareLink')
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
.main-image:hover img { transform: scale(1.15); }
.gallery-btn { position:absolute; top:50%; transform:translateY(-50%); width:42px; height:42px; border-radius:50%; background:rgba(0,0,0,.6); color:#fff; border:none; font-size:26px; opacity:0; transition:opacity .3s; }
.main-image:hover .gallery-btn { opacity:1; }
.prev-btn { left:10px; } .next-btn { right:10px; }
.thumbnail-wrapper { display:flex; gap:10px; overflow-x:auto; margin-top:10px; }
.thumbnail-img { width:70px; height:70px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:pointer; }
.thumbnail-img.active-thumb { border-color:#007bff; }
</style>
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

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Quantity buttons
    const input = document.getElementById('quantity-input');
    document.querySelector('.btn-plus').addEventListener('click', () => input.value = parseInt(input.value)+1);
    document.querySelector('.btn-minus').addEventListener('click', () => { if(input.value>1) input.value--; });

    // Gallery
    const mainImage = document.getElementById('currentImage');
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');

    if (thumbnails.length > 0) {
        let images = Array.from(thumbnails).map(t => t.dataset.full);
        let index = 0;

        function showImage(i) {
            index = i;
            mainImage.src = images[i];
            thumbnails.forEach((t,k) => t.classList.toggle('active-thumb', k===i));
        }

        showImage(0);

        thumbnails.forEach((thumb,i) => thumb.addEventListener('click', () => showImage(i)));

        prevBtn?.addEventListener('click', () => showImage((index-1+images.length)%images.length));
        nextBtn?.addEventListener('click', () => showImage((index+1)%images.length));

        // Fullscreen on click
        const modal = document.createElement('div');
        modal.style = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);justify-content:center;align-items:center;z-index:9999;';
        const modalImg = document.createElement('img');
        modalImg.style.maxWidth='90%';
        modalImg.style.maxHeight='90%';
        modal.appendChild(modalImg);
        document.body.appendChild(modal);
        mainImage.addEventListener('click', () => { modal.style.display='flex'; modalImg.src = mainImage.src; });
        modal.addEventListener('click', () => modal.style.display='none');
    }

});
</script>


@endsection
