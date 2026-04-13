@extends('layouts.app')

@section('content')

<style>
    .terms-container {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }
    .term-section {
        padding: 2rem;
        border-bottom: 1px solid #edf2f7;
        transition: background 0.3s ease;
    }
    .term-section:hover {
        background: #fcfcfd;
    }
    .term-number {
        font-weight: 800;
        color: #007bff;
        font-size: 1.2rem;
        margin-right: 15px;
        display: inline-block;
        min-width: 30px;
    }
    .term-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #2d3748;
        display: inline-block;
    }
    .term-body {
        margin-left: 45px;
        font-size: 14px;
        line-height: 1.8;
        color: #4a5568;
    }
    .legal-alert {
        background-color: #fffaf0;
        border-left: 4px solid #ed8936;
        padding: 1.5rem;
        margin-left: 45px;
        border-radius: 0 8px 8px 0;
    }
    .hover-shadow:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        transform: translateY(-3px);
        transition: all 0.3s ease;
    }
</style>

<div class="container-fluid mt-4">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-30 shadow-sm rounded">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active">Terms & Conditions</span>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mb-5">
    <div class="row px-xl-5">
        <div class="col-lg-12">
            <div class="terms-container shadow-sm border">
                <div class="bg-dark p-4">
                    <h3 class="text-white text-uppercase mb-0" style="font-size: 20px; border:none;">User Agreement & Conditions of Sale</h3>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">01</span>
                        <h5 class="term-title">Product Responsibility & Substitutions</h5>
                    </div>
                    <div class="term-body">
                        <p>Customers take full responsibility for ordering items and all associated substitutions. Information regarding spare parts and substitutions is for reference purposes only.</p>
                        <div class="legal-alert mt-3">
                            <strong>Substitution Policy:</strong> If a customer orders a discontinued part, AutoSpareLink reserves the right to supply an <strong>OEM substitution</strong> part number. No refunds are provided if the substitution price is lower than the original part.
                        </div>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">02</span>
                        <h5 class="term-title">Ordering Accuracy</h5>
                    </div>
                    <div class="term-body">
                        <p>By placing an order, the customer confirms full responsibility for the correctness and suitability of the part numbers selected. <strong>AutoSpareLink is not liable</strong> for customer errors in part selection or technical compatibility.</p>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">03</span>
                        <h5 class="term-title">Stock Availability & Partial Shipments</h5>
                    </div>
                    <div class="term-body">
                        <p>Stock availability is not guaranteed. Orders may be canceled or partially shipped based on current inventory. In the event of cancellation, a refund will be issued for the item and relevant shipping charges.</p>
                        <p class="small text-muted italic">Note: If an item is below 1 KG, shipping refunds may not apply as the minimum courier fee remains unchanged.</p>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">04</span>
                        <h5 class="term-title">Import Duties & Local Taxes</h5>
                    </div>
                    <div class="term-body">
                        <p>Your payment to AutoSpareLink covers <strong>only</strong> the item and the international shipping cost. The customer is solely responsible for all:</p>
                        <ul class="mb-0">
                            <li>Import duties and national taxes.</li>
                            <li>Brokerage and administration fees.</li>
                            <li>Airport handling and destination country charges.</li>
                        </ul>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">05</span>
                        <h5 class="term-title">Inspection & Safety</h5>
                    </div>
                    <div class="term-body">
                        <p>To ensure product safety and prevent transit damage, you agree to allow AutoSpareLink staff to open and inspect products. Original packaging may be re-sealed by our warehouse team after quality verification.</p>
                    </div>
                </div>

                <div class="term-section border-bottom-0">
                    <div>
                        <span class="term-number">06</span>
                        <h5 class="term-title">Warranty Protection</h5>
                    </div>
                    <div class="term-body">
                        <p>Parts sold by AutoSpareLink do not carry manufacturer warranties. Instead, AutoSpareLink provides a <strong>90-day (3 months) limited warranty</strong> starting from the date of delivery.</p>
                    </div>
                </div>
            </div>

            <div class="related-pages mt-5">
                <h5 class="mb-4 text-uppercase font-weight-bold">Additional Resources</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="/about" class="text-decoration-none">
                            <div class="card hover-shadow border-0 shadow-sm text-center p-4 rounded-lg">
                                <i class="fa fa-info-circle fa-2x mb-3 text-primary"></i>
                                <h6 class="mb-0 font-weight-bold text-dark">About Us</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/policies" class="text-decoration-none">
                            <div class="card hover-shadow border-0 shadow-sm text-center p-4 rounded-lg">
                                <i class="fa fa-shield-alt fa-2x mb-3 text-primary"></i>
                                <h6 class="mb-0 font-weight-bold text-dark">Our Policies</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/contact" class="text-decoration-none">
                            <div class="card hover-shadow border-0 shadow-sm text-center p-4 rounded-lg">
                                <i class="fa fa-envelope fa-2x mb-3 text-primary"></i>
                                <h6 class="mb-0 font-weight-bold text-dark">Contact Support</h6>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            </div>
    </div>
</div>
@endsection