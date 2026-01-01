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

    <div class="row px-xl-5">

        <!-- ================= PRODUCT GALLERY ================= -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="product-gallery bg-light p-3 rounded shadow-sm">

                <!-- MAIN IMAGE -->
                <div class="main-image position-relative overflow-hidden rounded">
                    <img
                        id="currentImage"
                        src="{{ $mainPhoto ? asset('storage/'.$mainPhoto->photo_url) : asset('frontend/img/parts.jpg') }}"
                        data-zoom="{{ $mainPhoto ? asset('storage/'.$mainPhoto->photo_url) : asset('frontend/img/parts.jpg') }}"
                        class="img-fluid w-100 zoom-image"
                        loading="lazy"
                        alt="{{ $part->part_name }}"
                    >

                    <!-- NAV BUTTONS -->
                    <button class="gallery-btn prev-btn">&lsaquo;</button>
                    <button class="gallery-btn next-btn">&rsaquo;</button>
                </div>

                <!-- THUMBNAILS -->
                <div class="d-flex mt-3 thumbnail-wrapper">
                    @foreach($photos as $photo)
                        <img
                            src="{{ asset('storage/'.$photo->photo_url) }}"
                            data-full="{{ asset('storage/'.$photo->photo_url) }}"
                            class="thumbnail-img"
                            loading="lazy"
                            alt="thumbnail"
                        >
                    @endforeach
                </div>

            </div>
        </div>

        <!-- ================= PRODUCT INFO ================= -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm product-card">

                <h2 class="font-weight-bold mb-3">{{ $part->part_name }}</h2>

                <p class="mb-1"><strong>Make:</strong> {{ $part->partBrand->name }}</p>
                <p class="mb-1"><strong>Part Number:</strong> {{ $part->part_number ?? 'N/A' }}</p>
                <p class="mb-1"><strong>OEM Number:</strong> {{ $part->oem_number ?? 'N/A' }}</p>

                <h3 class="text-primary my-3">
                    ${{ number_format($part->price, 2) }} <small class="text-muted">USD</small>
                </h3>

                <p>
                    <strong>Availability:</strong>
                    <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
                        {{ $part->stock_quantity }}
                    </span>
                </p>

                <!-- Quantity -->
                <div class="mb-4">
                    <label>Quantity</label>
                    <div class="input-group w-50">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary btn-minus">-</button>
                        </div>
                        <input type="number" class="form-control text-center" value="1" min="1" max="{{ $part->stock_quantity }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary btn-plus">+</button>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <button class="btn btn-primary btn-lg mr-2">
                        <i class="fa fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg">
                        <i class="fa fa-heart"></i>
                    </button>
                </div>

                <div class="border-top pt-3">
                    <h5>Description</h5>
                    <p>{{ $part->description ?? 'No description available.' }}</p>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ================= FULLSCREEN MODAL ================= -->
<div id="imageModal" class="image-modal">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const mainImage = document.getElementById('currentImage');
    const thumbnails = document.querySelectorAll('.thumbnail-img');
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const closeModal = document.querySelector('.close-modal');
    let currentIndex = 0;

    const images = Array.from(thumbnails).map(t => t.dataset.full);

    function showImage(index) {
        currentIndex = index;
        mainImage.src = images[index];
        mainImage.dataset.zoom = images[index];
    }

    thumbnails.forEach((thumb, index) => {
        thumb.addEventListener('click', () => showImage(index));
    });

    document.querySelector('.next-btn').onclick = () =>
        showImage((currentIndex + 1) % images.length);

    document.querySelector('.prev-btn').onclick = () =>
        showImage((currentIndex - 1 + images.length) % images.length);

    // Fullscreen
    mainImage.onclick = () => {
        modal.style.display = 'flex';
        modalImg.src = mainImage.src;
    };

    closeModal.onclick = () => modal.style.display = 'none';

    // Swipe (mobile)
    let startX = 0;
    mainImage.addEventListener('touchstart', e => startX = e.touches[0].clientX);
    mainImage.addEventListener('touchend', e => {
        let diff = e.changedTouches[0].clientX - startX;
        if (diff > 50) document.querySelector('.prev-btn').click();
        if (diff < -50) document.querySelector('.next-btn').click();
    });

});
</script>
@endsection


@section('styles')
<style>
/* ===== Gallery ===== */
.product-gallery img { cursor: pointer; }

.main-image img {
    transition: transform .3s ease;
}
.main-image:hover img {
    transform: scale(1.15);
}

/* Buttons */
.gallery-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,.5);
    color: #fff;
    border: none;
    font-size: 30px;
    width: 45px;
    height: 45px;
    border-radius: 50%;
}
.prev-btn { left: 10px; }
.next-btn { right: 10px; }

/* Thumbnails */
.thumbnail-wrapper {
    gap: 10px;
    overflow-x: auto;
}
.thumbnail-img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid transparent;
}
.thumbnail-img:hover {
    border-color: #007bff;
}

/* ===== Modal ===== */
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    inset: 0;
    background: rgba(0,0,0,.9);
    justify-content: center;
    align-items: center;
}
.modal-content {
    max-width: 90%;
    max-height: 90%;
}
.close-modal {
    position: absolute;
    top: 20px;
    right: 30px;
    color: #fff;
    font-size: 35px;
    cursor: pointer;
}
</style>
@endsection
