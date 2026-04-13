@extends('layouts.app')

@section('content')

<style>
    [x-cloak] { display: none !important; }
    
    .policy-content h3 { font-weight: 800; letter-spacing: -0.5px; }
    .policy-content h5 { font-weight: 700; color: #2c3e50; margin-top: 1.5rem; }
    .policy-content p, .policy-content li { font-size: 0.95rem; color: #4a5568; line-height: 1.8; }
    
    .sidebar-link {
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
        padding: 15px 20px;
    }
    .sidebar-link.active {
        background-color: #f8f9fa;
        color: #007bff !important;
        border-left-color: #007bff;
        font-weight: 700;
    }
    .sticky-offset { top: 100px; }
    
    .policy-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
    }
    .policy-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

    .market-note {
        background: #f0f7ff;
        border-radius: 8px;
        padding: 15px;
        border-left: 4px solid #007bff;
    }
</style>

<div class="container-fluid mt-4" x-data="{ section: 'privacy' }">
    <div class="row px-xl-5">
        
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-4 shadow-sm rounded">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active">Company Policies</span>
            </nav>
        </div>

        <div class="col-lg-3 mb-4">
            <div class="bg-white shadow-sm rounded-lg sticky-top sticky-offset border">
                <div class="p-3 bg-dark text-white rounded-top">
                    <h6 class="mb-0 text-uppercase small font-weight-bold">Policy Hub</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a @click="section = 'privacy'" :class="{ 'active': section === 'privacy' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-user-lock mr-2"></i> Privacy & Data
                    </a>
                    <a @click="section = 'returns'" :class="{ 'active': section === 'returns' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-sync-alt mr-2"></i> Marketplace Returns
                    </a>
                    <a @click="section = 'shipping'" :class="{ 'active': section === 'shipping' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-truck-moving mr-2"></i> Shipping & Logistics
                    </a>
                    <a @click="section = 'cancellation'" :class="{ 'active': section === 'cancellation' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-ban mr-2"></i> Order Cancellation
                    </a>
                    <a @click="section = 'dispute'" :class="{ 'active': section === 'dispute' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-gavel mr-2"></i> Dispute Resolution
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="bg-white p-4 p-md-5 shadow-sm rounded-lg policy-content border">
                
                <div x-show="section === 'privacy'" x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Privacy & Data Policy</h3>
                    <p class="lead">Your trust is our priority. Here is how we manage your information within our marketplace ecosystem.</p>
                    <hr>
                    <h5>Vendor-Buyer Data Sharing</h5>
                    <p>To facilitate delivery, we share your contact and address details only with the specific vendor you purchased from and our logistics partners. We never sell your data to third-party advertisers.</p>
                    <div class="market-note mt-3">
                        <i class="fa fa-info-circle mr-2"></i>
                        <strong>Secure Payments:</strong> We use Flutterwave and MTN MoMo for encrypted transactions. AutoSpareLink does not store your bank or card PINs.
                    </div>
                </div>

                <div x-show="section === 'returns'" x-cloak x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Marketplace Return & Refund</h3>
                    <div class="row mb-4">
                        <div class="col-md-4 mb-2">
                            <div class="text-center p-3 border rounded bg-light">
                                <span class="text-muted small d-block">Inspection Window</span>
                                <strong class="h5">48 Hours</strong>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-center p-3 border rounded bg-light">
                                <span class="text-muted small d-block">Refund Processing</span>
                                <strong class="h5">3-5 Days</strong>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="text-center p-3 border rounded bg-light">
                                <span class="text-muted small d-block">Admin Fee</span>
                                <strong class="h5">15%</strong>
                            </div>
                        </div>
                    </div>
                    <h5>Return Eligibility</h5>
                    <p>As a marketplace, returns are accepted if the part is <strong>defective, wrongly described by the vendor, or incorrect for your VIN</strong>. "Change of mind" returns are subject to the individual vendor's approval.</p>
                    <h5>The Refund Process</h5>
                    <ul>
                        <li>Report a problem within 48 hours of delivery.</li>
                        <li>Our logistics team will verify the part condition at the pickup point.</li>
                        <li>Refunds are credited to your <strong>AutoSpareLink Wallet</strong> or original payment method.</li>
                    </ul>
                </div>

                <div x-show="section === 'shipping'" x-cloak x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Shipping & Logistics</h3>
                    <p>We provide unified logistics for all vendors on our platform to ensure a consistent experience for you.</p>
                    <h5>Delivery Timelines (Rwanda)</h5>
                    <ul class="list-unstyled">
                        <li><i class="fa fa-map-marker-alt text-primary mr-2"></i> <strong>Kigali:</strong> Same day or Next day.</li>
                        <li><i class="fa fa-road text-primary mr-2"></i> <strong>Upcountry:</strong> 2-3 Working days.</li>
                    </ul>
                    <div class="alert alert-warning border-0 small mt-3">
                        <i class="fa fa-exclamation-triangle mr-2"></i>
                        <strong>Heavy Items:</strong> Engines and gearboxes require specialized handling and may take an additional 24 hours for secure crating.
                    </div>
                </div>

                <div x-show="section === 'cancellation'" x-cloak x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Order Cancellation</h3>
                    <h5>Before Dispatch</h5>
                    <p>You can cancel your order for a full refund as long as the status is "Processing." Once the vendor has handed the item to our courier, it is considered dispatched.</p>
                    <h5>Vendor Cancellations</h5>
                    <p>Occasionally, a vendor may report an item out of stock. In this case, AutoSpareLink will notify you immediately and issue a <strong>100% refund</strong> plus a discount voucher for your next purchase.</p>
                </div>

                <div x-show="section === 'dispute'" x-cloak x-transition.duration.300ms>
                    <h3 class="text-primary mb-4">Dispute Resolution</h3>
                    <p>These terms are governed by the laws of the <strong>Republic of Rwanda</strong>. Any disputes between buyers and vendors that cannot be settled via our support team will be mediated by AutoSpareLink Legal.</p>
                    
                    <div class="mt-5 p-4 border rounded bg-light">
                        <h6 class="font-weight-bold"><i class="fa fa-headset mr-2"></i>Resolution Center</h6>
                        <p class="mb-1 small">Email: support@autosparelink.com</p>
                        <p class="mb-0 small text-muted">Response Time: Within 12 working hours.</p>
                    </div>
                </div>

            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <a href="/terms-and-conditions" class="card policy-card text-decoration-none p-3 h-100">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-file-invoice fa-2x text-primary mr-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark">Terms of Sale</h6>
                                <small class="text-muted">Agreement between you and vendors</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="/contact" class="card policy-card text-decoration-none p-3 h-100">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-shield-check fa-2x text-success mr-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark">Buyer Protection</h6>
                                <small class="text-muted">How we keep your money safe</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection