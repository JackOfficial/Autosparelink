@extends('layouts.app')

@section('content')

<style>
    .terms-container {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
    }
    .term-section {
        padding: 2.5rem;
        border-bottom: 1px solid #edf2f7;
        transition: background 0.3s ease;
    }
    .term-section:hover {
        background: #f8fafc;
    }
    .term-number {
        font-weight: 800;
        color: #007bff;
        font-size: 1.2rem;
        margin-right: 15px;
        display: inline-block;
        min-width: 35px;
    }
    .term-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #1a202c;
        display: inline-block;
    }
    .term-body {
        margin-left: 50px;
        font-size: 15px;
        line-height: 1.8;
        color: #4a5568;
    }
    .marketplace-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 800;
        text-uppercase;
        margin-bottom: 10px;
        margin-left: 50px;
    }
    .badge-vendor { background: #e2e8f0; color: #4a5568; }
    .badge-platform { background: #ebf8ff; color: #3182ce; }

    .legal-alert {
        background-color: #fffaf0;
        border-left: 4px solid #ed8936;
        padding: 1.5rem;
        margin-top: 1rem;
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
                <div class="bg-dark p-4 d-flex justify-content-between align-items-center">
                    <h3 class="text-white text-uppercase mb-0" style="font-size: 18px; letter-spacing: 1px;">Marketplace User Agreement</h3>
                    <span class="text-muted small">Last Updated: April 2026</span>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">01</span>
                        <h5 class="term-title">The Marketplace Model</h5>
                    </div>
                    <div class="marketplace-badge badge-platform">Platform Policy</div>
                    <div class="term-body">
                        <p>AutoSpareLink operates as a multivendor marketplace. While we verify the identity of our vendors, the actual contract for sale is directly between the <strong>Buyer</strong> and the <strong>Vendor (Shop)</strong>. AutoSpareLink provides the platform, payment processing, and delivery logistics.</p>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">02</span>
                        <h5 class="term-title">Technical Compatibility & VIN Matching</h5>
                    </div>
                    <div class="marketplace-badge badge-vendor">Buyer Responsibility</div>
                    <div class="term-body">
                        <p>Customers are responsible for ensuring part numbers match their vehicle specifications. We highly recommend using our <strong>VIN Matching Service</strong> before checkout. 
                        <strong>AutoSpareLink is not liable</strong> for technical incompatibility if the customer orders a part without verified VIN matching from the vendor.</p>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">03</span>
                        <h5 class="term-title">Safe Delivery Guarantee (Rwanda)</h5>
                    </div>
                    <div class="marketplace-badge badge-platform">Our Promise</div>
                    <div class="term-body">
                        <p>We ensure safe delivery across all Rwandan provinces. Risk of loss passes to the customer only upon physical delivery to the specified address. If a vendor fails to provide the part to our logistics team, a full refund including shipping fees will be issued immediately.</p>
                        <div class="legal-alert">
                            <strong>Inspection:</strong> You agree to let our logistics team inspect parts for "Genuine Quality" before they leave the vendor's shop to ensure they meet our premium standards.
                        </div>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">04</span>
                        <h5 class="term-title">Pricing & Stock Discrepancies</h5>
                    </div>
                    <div class="marketplace-badge badge-vendor">Vendor Policy</div>
                    <div class="term-body">
                        <p>Vendors manage their own inventory. If a part is listed but found to be out of stock, the order will be canceled. Prices are subject to change based on vendor updates; however, the price at the time of "Order Confirmation" is final.</p>
                    </div>
                </div>

                <div class="term-section">
                    <div>
                        <span class="term-number">05</span>
                        <h5 class="term-title">Secure Payment & Escrow</h5>
                    </div>
                    <div class="marketplace-badge badge-platform">Financial Security</div>
                    <div class="term-body">
                        <p>To protect both parties, AutoSpareLink holds payments in escrow. Funds are only released to the vendor once delivery is confirmed and the 48-hour "Initial Inspection Period" has passed without a dispute being raised by the buyer.</p>
                    </div>
                </div>

                <div class="term-section border-bottom-0">
                    <div>
                        <span class="term-number">06</span>
                        <h5 class="term-title">90-Day Marketplace Warranty</h5>
                    </div>
                    <div class="marketplace-badge badge-platform">Limited Warranty</div>
                    <div class="term-body">
                        <p>Manufacturer warranties often do not apply to resale via third parties. AutoSpareLink provides a <strong>90-day limited warranty</strong> on premium parts. This warranty covers mechanical failure but excludes labor costs, improper installation, or "change of mind" returns.</p>
                    </div>
                </div>
            </div>

            <div class="related-pages mt-5 text-center">
                <h5 class="mb-4 text-uppercase font-weight-bold" style="color: #2d3748;">Partner & Buyer Resources</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="/about" class="text-decoration-none">
                            <div class="card hover-shadow border-0 shadow-sm p-4 rounded-lg">
                                <i class="fa fa-handshake fa-2x mb-3 text-primary"></i>
                                <h6 class="mb-0 font-weight-bold text-dark">Join as a Vendor</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/policies" class="text-decoration-none">
                            <div class="card hover-shadow border-0 shadow-sm p-4 rounded-lg">
                                <i class="fa fa-truck-loading fa-2x mb-3 text-primary"></i>
                                <h6 class="mb-0 font-weight-bold text-dark">Shipping Policy</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/contact" class="text-decoration-none">
                            <div class="card hover-shadow border-0 shadow-sm p-4 rounded-lg">
                                <i class="fa fa-user-shield fa-2x mb-3 text-primary"></i>
                                <h6 class="mb-0 font-weight-bold text-dark">Buyer Protection</h6>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection