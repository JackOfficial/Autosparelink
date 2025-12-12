@extends('layouts.app')

@section('content')

<!-- Breadcrumb Start -->
<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30">
                <a class="breadcrumb-item text-dark" href="#">Home</a>
                <span class="breadcrumb-item active">Terms & Conditions</span>
            </nav>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Terms & Conditions Start -->
<div class="container-fluid">
    <div class="row px-xl-5">
        <div class="col-12">

            <div class="mb-5">
                <h3 class="text-uppercase mb-3">Terms & Conditions</h3>
                <p style="line-height: 1.6;">
                    Customers take full responsibility for ordering items and all its substitutions, whatever was chosen by customers. All information provided regarding the spare parts and their substitutions is for customer reference only. In case a customer orders a discontinued part, AutoSpareLink reserves the right to supply an OEM substitution part number. No refund will be given if the price for the substitution is cheaper.
                </p>
                
                <p style="line-height: 1.6;">
                    By ordering spare parts or their substitutions, customers take full responsibility for the correctness and suitability of the part number ordered — AutoSpareLink does not carry responsibility for customer mistakes in part numbers or spare parts ordered by users.
                </p>
                
                <p style="line-height: 1.6;">
                    We do not guarantee that all ordered parts will be available in stock. Your order might be canceled or partially shipped. For unavailable items, you will receive a refund for the item and shipping charges if they apply. In some cases, the refund will be only for the cost of the items because the item could be very light and will not affect the minimum shipping charges or courier fees (minimum criteria of 1 KG).
                </p>
                
                <p style="line-height: 1.6;">
                    Payment covers only the item and shipping cost. Customers take full responsibility for any import duties, taxes, fees (brokerage, admin, airport handling, import), or charges that may be collected by the destination country courier company, if applicable.
                </p>
                
                <p style="line-height: 1.6;">
                    By confirming these terms and conditions, you agree to allow AutoSpareLink to open and inspect the product for safety purposes, to prevent damages. Some product packaging might be opened and then re-sealed by AutoSpareLink warehouse staff.
                </p>
                
                <p style="line-height: 1.6;">
                    All parts sold by AutoSpareLink do not carry manufacturer warranties. AutoSpareLink provides its own warranty protection for 3 months (90 days) from the time you receive the product.
                </p>
                
            </div>

          <!-- Related Pages Start -->
<div class="related-pages mb-5">
    <h5 class="mb-3 text-uppercase">Related Pages</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <a href="/about" class="text-decoration-none">
                <div class="card hover-shadow border-0 text-center p-3">
                    <i class="fa fa-info-circle fa-2x mb-2 text-primary"></i>
                    <h6 class="mb-0">About Us</h6>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a class="text-decoration-none">
                <div class="card hover-shadow border-0 text-center p-3">
                    <i class="fa fa-file fa-2x mb-2 text-primary"></i> <!-- Changed to fa-file -->
                    <h6 class="mb-0">Terms & Conditions</h6>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="/policies" class="text-decoration-none">
                <div class="card hover-shadow border-0 text-center p-3">
                    <i class="fa fa-shield-alt fa-2x mb-2 text-primary"></i> <!-- Changed to fa-shield-alt -->
                    <h6 class="mb-0">Policies</h6>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- Related Pages End -->


        </div>
    </div>
</div>
<!-- Terms & Conditions End -->

<style>
    h3 {
        font-weight: 700;
        font-size: 22px;
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 6px;
    }

    p {
        font-size: 14px;
    }

    /* Related Pages Cards */
    .hover-shadow:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: all 0.3s ease;
    }

    .related-pages h5 {
        font-weight: 600;
    }

    .related-pages .card h6 {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
</style>

@endsection
