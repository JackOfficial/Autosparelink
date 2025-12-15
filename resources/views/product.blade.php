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
                <p class="mb-1"><strong>Weight:</strong> 0.138 kg</p>

                <!-- Price -->
                <h4 class="text-primary mb-3">$71.16 USD</h4>

                <!-- Availability -->
                <p class="mb-3"><strong>Availability:</strong> 1</p>

                <!-- Quantity Selector -->
                <div class="mb-4">
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

                <!-- Add to Cart / Wishlist -->
                <div class="mb-4">
                    <button class="btn btn-primary btn-lg mr-2">
                        <i class="fa fa-shopping-cart mr-1"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg">
                        <i class="fa fa-heart mr-1"></i> Add to Wishlist
                    </button>
                </div>

                <!-- Share Buttons -->
                <div class="mb-4">
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

    <!-- Substitutions Table -->
    <div class="row px-xl-5">
        <div class="col-12 mb-4">
            <h4 class="mb-3">Substitutions</h4>
            <div class="table-responsive bg-light p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Make</th>
                            <th>Number</th>
                            <th>Name</th>
                            <th>Availability</th>
                            <th>Weight, kg</th>
                            <th>Processing, days</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K01133200A</td>
                            <td>HUB-FREE WHEEL</td>
                            <td>0</td>
                            <td>0.023</td>
                            <td>-</td>
                            <td>179.96$</td>
                        </tr>
                        <tr>
                            <td>Hyundai / KIA</td>
                            <td>0K01133200</td>
                            <td>HUB-FREE WHEEL</td>
                            <td>0</td>
                            <td>0.138</td>
                            <td>-</td>
                            <td>132.46$</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Compatibility Table -->
    <div class="row px-xl-5">
        <div class="col-12 mb-4">
            <h4 class="mb-3">Compatibility</h4>
            <div class="table-responsive bg-light p-3 rounded shadow-sm">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Market</th>
                            <th>Model</th>
                            <th>Year From</th>
                            <th>Year To</th>
                            <th>Diagram</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>DOM</td>
                            <td>Sportage</td>
                            <td>1999</td>
                            <td>2002</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Quantity Button Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('quantity-input');
    document.querySelector('.btn-plus').addEventListener('click', () => {
        input.value = parseInt(input.value) + 1;
    });
    document.querySelector('.btn-minus').addEventListener('click', () => {
        if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    });
});
</script>

@endsection
