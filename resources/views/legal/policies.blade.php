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
</style>

<div class="container-fluid mt-4" x-data="{ section: 'privacy' }">
    <div class="row px-xl-5">
        
        <div class="col-12">
            <nav class="breadcrumb bg-light mb-4 shadow-sm">
                <a class="breadcrumb-item text-dark" href="/">Home</a>
                <span class="breadcrumb-item active">Company Policies</span>
            </nav>
        </div>

        <div class="col-lg-3">
            <div class="bg-white shadow-sm rounded-lg sticky-top sticky-offset">
                <div class="p-3 bg-dark text-white rounded-top">
                    <h6 class="mb-0 text-uppercase small font-weight-bold">Policy Navigation</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a @click="section = 'privacy'" :class="{ 'active': section === 'privacy' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-shield-alt mr-2"></i> Privacy Policy
                    </a>
                    <a @click="section = 'returns'" :class="{ 'active': section === 'returns' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-undo mr-2"></i> Return & Refunds
                    </a>
                    <a @click="section = 'shipping'" :class="{ 'active': section === 'shipping' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-truck mr-2"></i> Shipping & Delivery
                    </a>
                    <a @click="section = 'cancellation'" :class="{ 'active': section === 'cancellation' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-times-circle mr-2"></i> Cancellation
                    </a>
                    <a @click="section = 'legal'" :class="{ 'active': section === 'legal' }" class="list-group-item list-group-item-action sidebar-link">
                        <i class="fa fa-gavel mr-2"></i> Legal & Jurisdiction
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="bg-white p-4 p-md-5 shadow-sm rounded-lg policy-content">
                
                <div x-show="section === 'privacy'" x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Privacy Policy</h3>
                    <p class="lead">How we handle your data at AutoSpareLink.</p>
                    <hr>
                    <h5>Information Collection</h5>
                    <p>We collect essential data to process your orders and improve your experience. This includes your name, contact details, and vehicle preferences.</p>
                    <div class="alert alert-light border small">
                        <strong>Pro Tip:</strong> You can manage your cookie preferences through your browser settings at any time.
                    </div>
                    <h5>Data Security</h5>
                    <p>All sensitive transactions are processed through <strong>SSL-encrypted</strong> channels. We do not store credit card details on our local servers in Kigali.</p>
                </div>

                <div x-show="section === 'returns'" x-cloak x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Return & Refund Policy</h3>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <span class="text-muted small d-block">Window</span>
                                <strong class="h5">14 Days</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <span class="text-muted small d-block">Condition</span>
                                <strong class="h5">Unused</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <span class="text-muted small d-block">Restocking</span>
                                <strong class="h5">20% Fee</strong>
                            </div>
                        </div>
                    </div>
                    <h5>Eligibility</h5>
                    <p>Parts must be returned in original packaging with manufacturer labels intact. Returns after 30 days are strictly not accepted.</p>
                    <h5>How to Return</h5>
                    <ul>
                        <li>Initiate a request via <a href="mailto:help@autosparelink.com">help@autosparelink.com</a>.</li>
                        <li>Ship using the original carrier to simplify customs/duty reclaims.</li>
                        <li>A refund (minus 20% restocking fee) will be issued upon inspection.</li>
                    </ul>
                </div>

                <div x-show="section === 'shipping'" x-cloak x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Shipping & Delivery</h3>
                    <p>We partner with <strong>FedEx, DHL, and TNT</strong> to deliver to nearly 180 countries.</p>
                    <h5>Estimated Timelines</h5>
                    <p>Stocked items usually ship within <strong>1-3 working days</strong>. Glass or heavy freight items may require additional handling time and special packaging fees.</p>
                    <div class="card bg-light border-0">
                        <div class="card-body py-3">
                            <i class="fa fa-info-circle text-primary mr-2"></i>
                            <strong>Note:</strong> Customers are responsible for tracking shipments and completing local customs formalities.
                        </div>
                    </div>
                </div>

                <div x-show="section === 'cancellation'" x-cloak x-transition:enter.duration.300ms>
                    <h3 class="text-primary mb-4">Cancellation Policy</h3>
                    <h5>Standard Orders</h5>
                    <p>You may cancel any order that has not yet entered the shipping phase. Once the status moves to "Shipped," our <strong>Return Policy</strong> applies.</p>
                    <h5>Non-Cancellable Items</h5>
                    <p class="text-danger">Special orders for "Remote Dealer Branch" items cannot be cancelled once the payment is processed.</p>
                </div>

                <div x-show="section === 'legal'" x-cloak x-transition.duration.300ms>
                    <h3 class="text-primary mb-4">Legal & Jurisdiction</h3>
                    <h5>Governing Law</h5>
                    <p>These terms are governed by the laws of <strong>Dubai, United Arab Emirates</strong>. By using this platform, you consent to UAE jurisdiction for all legal disputes.</p>
                    
                    <div class="mt-5 p-4 border rounded bg-light">
                        <h6 class="font-weight-bold">Contact Legal Team</h6>
                        <p class="mb-1 small">AutoSpareLink - Legal Department</p>
                        <p class="mb-0 small"><i class="fa fa-envelope mr-1"></i> privacy@autosparelink.com</p>
                    </div>
                </div>

            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <a href="/terms-and-conditions" class="card policy-card text-decoration-none p-3 h-100">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-file-contract fa-2x text-primary mr-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark">Terms of Service</h6>
                                <small class="text-muted">General usage rules</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="/faqs" class="card policy-card text-decoration-none p-3 h-100">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-question-circle fa-2x text-primary mr-3"></i>
                            <div>
                                <h6 class="mb-0 text-dark">Help Center</h6>
                                <small class="text-muted">Common questions</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection