@extends('layouts.app')

@section('title', 'Product Details | AutoSpareLink')

@section('content')

<div class="container-fluid mt-4">

    <!-- Breadcrumb -->
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-4 p-3 rounded">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <a class="breadcrumb-item text-dark" href="/shop">Shop</a>
                <span class="breadcrumb-item active">HUB-FREE WHEEL</span>
            </nav>
        </div>
    </div>

    <!-- Product Details -->
    <div class="row px-xl-5">
        <!-- Product Image -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="bg-light p-3 rounded shadow-sm">
                <img src="{{ asset('frontend/img/parts.jpg') }}" class="img-fluid w-100" alt="HUB-FREE WHEEL">
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="bg-light p-4 rounded shadow-sm">

                <!-- Title -->
                <h2 class="font-weight-bold mb-3">HUB-FREE WHEEL</h2>

                <!-- Manufacturer / Part Number -->
                <p class="mb-1"><strong>Make:</strong> Hyundai / KIA</p>
                <p class="mb-1"><strong>Part Number:</strong> 0K01133200B</p>

                <!-- Price -->
                <h4 class="text-primary mb-3">$71.16 USD</h4>

                <!-- Availability -->
                <p class="mb-1"><strong>Availability:</strong> 1</p>
                <p class="mb-3"><strong>Ship In:</strong> 7 days</p>

                <!-- Quantity Selector -->
                <div class="mb-3 d-flex align-items-center">
                    <label class="mr-2 mb-0">Quantity:</label>
                    <input type="number" class="form-control w-25" value="1" min="1">
                </div>

                <!-- Add to Cart / Buttons -->
                <div class="mb-4">
                    <button class="btn btn-primary btn-lg mr-2">
                        <i class="fa fa-shopping-cart mr-1"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg">
                        <i class="fa fa-heart mr-1"></i> Add to Wishlist
                    </button>
                </div>

                <!-- Share Buttons -->
                <div class="mb-3">
                    <strong class="mr-2">Share:</strong>
                    <a href="#" class="btn btn-sm btn-success mr-1"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="btn btn-sm btn-dark mr-1"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" class="btn btn-sm btn-primary"><i class="fab fa-facebook-f"></i></a>
                </div>

                <!-- Product Description -->
                <div class="border-top pt-3">
                    <h5 class="mb-2">Product Description</h5>
                    <p>
                        Genuine Hyundai / KIA HUB-FREE WHEEL, high-quality OEM part ensuring perfect fit and durability.
                        Ideal for replacement and maintenance.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="row px-xl-5">
        <div class="col-12">
            <h4 class="mb-3">Related Products</h4>
            <div class="d-flex overflow-auto related-scroll pb-2">
                @for ($i = 1; $i <= 6; $i++)
                <div class="card mr-3" style="min-width: 200px;">
                    <img src="{{ asset('frontend/img/parts.jpg') }}" class="card-img-top" style="height:140px;object-fit:cover;">
                    <div class="card-body">
                        <h6 class="card-title text-truncate">Product {{ $i }}</h6>
                        <p class="text-primary mb-1">$50.00</p>
                        <a href="#" class="btn btn-sm btn-primary btn-block">View</a>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>

</div>

<!-- Styles -->
<style>
.related-scroll {
    overflow-x: auto;
    padding-bottom: 10px;
}
.related-scroll::-webkit-scrollbar {
    height: 6px;
}
.related-scroll::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
</style>

@endsection
