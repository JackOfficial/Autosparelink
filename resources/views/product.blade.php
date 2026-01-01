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
            <div class="bg-light p-3 rounded shadow-sm">

                <div class="main-image position-relative overflow-hidden rounded">
                    <img
                        id="currentImage"
                        src="{{ $part->photos->first()
                            ? asset('storage/'.$part->photos->first()->photo_url)
                            : asset('frontend/img/parts.jpg') }}"
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

        <!-- ================= PRODUCT INFO ================= -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm">

                <h2 class="font-weight-bold">{{ $part->part_name }}</h2>

                <p><strong>Make:</strong> {{ $part->partBrand->name }}</p>
                <p><strong>Part Number:</strong> {{ $part->part_number ?? 'N/A' }}</p>
                <p><strong>OEM Number:</strong> {{ $part->oem_number ?? 'N/A' }}</p>

                <h3 class="text-primary my-3">
                    {{ number_format($part->price, 2) }} RWF
                </h3>

                <p>
                    <strong>Availability:</strong>
                    <span class="badge badge-{{ $part->stock_quantity > 0 ? 'success' : 'warning' }}">
                        {{ $part->stock_quantity }}
                    </span>
                </p>

                <div class="border-top pt-3">
                    <h5>Description</h5>
                    <p>{{ $part->description ?? 'No description available.' }}</p>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- FULLSCREEN MODAL -->
<div id="imageModal" class="image-modal">
    <span class="close-modal">&times;</span>
    <img id="modalImage">
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    var mainImage = document.getElementById('currentImage');
    var thumbnails = document.querySelectorAll('.thumbnail-img');
    var prevBtn = document.querySelector('.prev-btn');
    var nextBtn = document.querySelector('.next-btn');

    // Only run gallery if there is more than one image
    if (thumbnails.length > 1) {

        var images = [];
        thumbnails.forEach(function (t) {
            images.push(t.getAttribute('data-full'));
        });

        var index = 0;

        function showImage(i) {
            index = i;
            mainImage.src = images[i];
            thumbnails.forEach(function (t, k) {
                t.classList.toggle('active-thumb', k === i);
            });
        }

        showImage(0);

        thumbnails.forEach(function (thumb, i) {
            thumb.addEventListener('click', function () {
                showImage(i);
            });
        });

        prevBtn.addEventListener('click', function () {
            showImage((index - 1 + images.length) % images.length);
        });

        nextBtn.addEventListener('click', function () {
            showImage((index + 1) % images.length);
        });
    }

    /* Fullscreen */
    var modal = document.getElementById('imageModal');
    var modalImg = document.getElementById('modalImage');
    var close = document.querySelector('.close-modal');

    mainImage.addEventListener('click', function () {
        modal.style.display = 'flex';
        modalImg.src = mainImage.src;
    });

    close.addEventListener('click', function () {
        modal.style.display = 'none';
    });

});
</script>
@endsection

@section('styles')
<style>
.main-image img {
    cursor: zoom-in;
    transition: transform .3s ease;
}
.main-image:hover img {
    transform: scale(1.15);
}

.gallery-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(0,0,0,.6);
    color: #fff;
    border: none;
    font-size: 26px;
    opacity: 0;
    transition: opacity .3s;
}
.main-image:hover .gallery-btn {
    opacity: 1;
}
.prev-btn { left: 10px; }
.next-btn { right: 10px; }

.thumbnail-wrapper {
    display: flex;
    gap: 10px;
    overflow-x: auto;
}
.thumbnail-img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid transparent;
    cursor: pointer;
}
.thumbnail-img.active-thumb {
    border-color: #007bff;
}

.image-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.9);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.image-modal img {
    max-width: 90%;
    max-height: 90%;
}
.close-modal {
    position: absolute;
    top: 20px;
    right: 30px;
    font-size: 36px;
    color: #fff;
    cursor: pointer;
}
</style>
@endsection
