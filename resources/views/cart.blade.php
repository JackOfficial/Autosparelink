@extends('layouts.app')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <a class="breadcrumb-item text-dark" href="#">Shop</a>
                <span class="breadcrumb-item active">Shopping Cart</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Cart Start -->
<div class="container-fluid">
    <div class="row px-xl-5">

        <!-- Cart Table Start -->
        <div class="col-lg-8 mb-5">
            <div class="table-responsive shadow-sm bg-white rounded">
                <table class="table table-hover text-center mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">

                        @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td class="align-middle text-left">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('frontend/img/product-'.$i.'.jpg') }}" alt="" style="width: 80px; height: 80px; object-fit: cover;" class="mr-3 rounded">
                                    <div>
                                        <h6 class="mb-1">Product Name {{ $i }}</h6>
                                        <small class="text-muted">Brand Name • SKU12345</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <div class="input-group quantity mx-auto">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-primary btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    </div>
                                    <input type="text" class="form-control text-center" value="1">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">$150</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-danger rounded-circle"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                        @endfor

                    </tbody>
                </table>
            </div>
        </div>
        <!-- Cart Table End -->

        <!-- Cart Summary Start -->
        <div class="col-lg-4">
            <div class="cart-summary-sticky-top">
                <form class="mb-3" action="">
                    <div class="input-group">
                        <input type="text" class="form-control border-0 py-3" placeholder="Coupon Code">
                        <div class="input-group-append">
                            <button class="btn btn-primary">Apply Coupon</button>
                        </div>
                    </div>
                </form>

                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3">Cart Summary</span>
                </h5>
                <div class="bg-white shadow-sm rounded p-4">
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <h6>Subtotal</h6>
                            <h6>$750</h6>
                        </div>
                        <div class="d-flex justify-content-between">
                            <h6>Shipping</h6>
                            <h6>$10</h6>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3 mb-4">
                        <h5>Total</h5>
                        <h5>$760</h5>
                    </div>
                    <button class="btn btn-primary btn-block py-3 font-weight-bold">Proceed To Checkout</button>
                    <a href="/shop" class="btn btn-outline-secondary btn-block py-2 mt-2">Continue Shopping</a>
                </div>
            </div>
        </div>
        <!-- Cart Summary End -->

    </div>
</div>
<!-- Cart End -->

<style>
/* Hover effect on table rows */
.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: 0.3s;
}

/* Quantity buttons and input */
.input-group.quantity {
    width: 120px;
}

.input-group.quantity .form-control {
    height: 36px;
    padding: 0;
    font-size: 14px;
}

.input-group.quantity .btn-minus,
.input-group.quantity .btn-plus {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.input-group.quantity .btn-minus:hover,
.input-group.quantity .btn-plus:hover {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

/* Sticky summary */
.cart-summary-sticky-top {
    position: sticky;
    top: 100px; /* Adjust according to navbar height */
    z-index: 3;
}

/* Mobile adjustments */
@media (max-width: 992px) {
    .cart-summary-sticky-top {
        position: static !important;
        top: auto;
        margin-top: 20px;
    }
}
</style>

@endsection
