@extends('layouts.app')

@section('content')
<div class="container py-5" x-data="{ step: 1 }">
    <div class="row align-items-center">
        <div class="col-lg-5 mb-5 mb-lg-0">
            <div class="mb-4">
                <span class="badge badge-pill px-3 py-2 fw-bold text-uppercase mb-3 shadow-sm" 
                      style="font-size: 0.7rem; letter-spacing: 1.5px; background: rgba(0, 123, 255, 0.1); color: #007bff; border: 1px solid rgba(0, 123, 255, 0.2);">
                    <i class="fas fa-star mr-1"></i> Official Partner Portal
                </span>
                <h1 class="display-4 font-weight-bold text-dark mb-3">Expand your reach in Rwanda.</h1>
                <p class="lead text-muted mb-4">Join Kigali's largest network of spare parts vendors and start receiving orders today.</p>
            </div>

            <div class="row no-gutters mb-5">
                <div class="col-6 pr-2">
                    <div class="p-3 bg-white border-0 shadow-sm rounded-4 mb-3 h-100 transition-hover">
                        <div class="icon-box bg-light-warning text-warning mb-2">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h6 class="font-weight-bold mb-1">Fast Onboarding</h6>
                        <p class="small text-muted mb-0">Live in 24 hours.</p>
                    </div>
                </div>
                <div class="col-6 pl-2">
                    <div class="p-3 bg-white border-0 shadow-sm rounded-4 mb-3 h-100 transition-hover">
                        <div class="icon-box bg-light-success text-success mb-2">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h6 class="font-weight-bold mb-1">Daily Payouts</h6>
                        <p class="small text-muted mb-0">Direct to MoMo/Bank.</p>
                    </div>
                </div>
            </div>

            <div class="position-relative d-none d-lg-block pt-3">
                <div class="blob-bg position-absolute"></div>
                <img src="{{ asset('frontend/img/part.png') }}" 
                     class="img-fluid rounded-5 shadow-2xl border-white position-relative" 
                     alt="Auto Parts Shop" 
                     style="transform: rotate(-3deg); border: 10px solid white; z-index: 2;">
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-2xl rounded-5 overflow-hidden">
                <div class="bg-primary p-1">
                    <div class="progress rounded-0" style="height: 6px; background: rgba(255,255,255,0.2);">
                        <div class="progress-bar bg-white transition-all" role="progressbar" 
                             :style="`width: ${step === 1 ? '33' : step === 2 ? '66' : '100'}%`" 
                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div x-show="step === 1" x-transition:enter="fade-in">
                            <div class="d-flex align-items-center mb-4">
                                <div class="step-num mr-3">1</div>
                                <h4 class="font-weight-bold mb-0">Business Identity</h4>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark small">Official Shop Name</label>
                                <div class="input-group premium-input">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-tag text-primary"></i></span>
                                    </div>
                                    <input type="text" name="shop_name" class="form-control bg-light border-0 py-4 font-weight-bold" placeholder="e.g. Kigali Motor Hub" required>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark small">Specialization & Brands</label>
                                <textarea name="description" class="form-control bg-light border-0 rounded-4" rows="4" placeholder="Mention brands like Toyota, Mercedes, etc."></textarea>
                            </div>

                            <button type="button" @click="step = 2" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold shadow-primary">
                                Continue <i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        </div>

                        <div x-show="step === 2" x-transition:enter="fade-in" style="display: none;">
                            <div class="d-flex align-items-center mb-4">
                                <div class="step-num mr-3">2</div>
                                <h4 class="font-weight-bold mb-0">Contact & Legal</h4>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-4">
                                    <label class="small font-weight-bold">Business Email</label>
                                    <input type="email" name="shop_email" class="form-control bg-light border-0 py-4" placeholder="vendor@shop.rw" required>
                                </div>
                                <div class="col-md-6 form-group mb-4">
                                    <label class="small font-weight-bold">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control bg-light border-0 py-4" placeholder="+250 78..." required>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold">TIN (Tax Identification Number)</label>
                                <div class="input-group premium-input">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-primary"></i></span>
                                    </div>
                                    <input type="text" name="tin_number" class="form-control bg-light border-0 py-4" placeholder="9-digit TIN" required>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label class="small font-weight-bold">Physical Shop Location</label>
                                <input type="text" name="address" class="form-control bg-light border-0 py-4" placeholder="Kigali, Nyarugenge..." required>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <button type="button" @click="step = 1" class="btn btn-link text-muted font-weight-bold py-3">Back</button>
                                </div>
                                <div class="col-8">
                                    <button type="button" @click="step = 3" class="btn btn-primary btn-block rounded-pill py-3 font-weight-bold shadow-primary">Next Step</button>
                                </div>
                            </div>
                        </div>

                        <div x-show="step === 3" x-transition:enter="fade-in" style="display: none;">
                            <div class="d-flex align-items-center mb-4">
                                <div class="step-num mr-3">3</div>
                                <h4 class="font-weight-bold mb-0">Final Verification</h4>
                            </div>

                            <div class="p-4 rounded-4 border-dashed mb-4 bg-light-primary">
                                <div class="form-group mb-4">
                                    <label class="small font-weight-bold text-primary"><i class="fas fa-file-pdf mr-2"></i>RDB Certificate</label>
                                    <input type="file" name="rdb_certificate" class="form-control-file">
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold text-primary"><i class="fas fa-id-card mr-2"></i>Owner's National ID</label>
                                    <input type="file" name="owner_id" class="form-control-file">
                                </div>
                            </div>

                            <div class="alert alert-info border-0 rounded-4 small">
                                <i class="fas fa-info-circle mr-2"></i> Our team will review your application and respond within 24 hours.
                            </div>

                            <div class="row mt-4">
                                <div class="col-4">
                                    <button type="button" @click="step = 2" class="btn btn-link text-muted font-weight-bold py-3">Back</button>
                                </div>
                                <div class="col-8">
                                    <button type="submit" class="btn btn-success btn-block rounded-pill py-3 font-weight-bold shadow-lg">
                                        Launch My Shop <i class="fas fa-rocket ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <p class="text-center mt-4 small text-muted">
                Secure 256-bit encrypted application. <i class="fas fa-lock ml-1"></i>
            </p>
        </div>
    </div>
</div>

<style>
    /* Premium Shadows & Radii */
    .rounded-5 { border-radius: 2.5rem !important; }
    .rounded-4 { border-radius: 1.2rem !important; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); }
    .shadow-primary { box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3); }
    
    /* Input Styling */
    .form-control { transition: all 0.3s ease; }
    .form-control:focus { background-color: #ffffff !important; box-shadow: 0 0 0 3px rgba(0,123,255,0.1) !important; }
    .premium-input .input-group-text { border-radius: 1.2rem 0 0 1.2rem !important; }
    .premium-input .form-control { border-radius: 0 1.2rem 1.2rem 0 !important; }

    /* Step UI */
    .step-num { 
        width: 40px; height: 40px; background: #007bff; color: white; 
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.1rem;
    }

    /* Visual Background Blob */
    .blob-bg {
        width: 300px; height: 300px; background: #007bff; filter: blur(80px);
        opacity: 0.1; top: 0; left: 0; border-radius: 50%; z-index: 1;
    }

    /* Icon Boxes */
    .icon-box {
        width: 45px; height: 45px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }
    .bg-light-warning { background: rgba(255, 193, 7, 0.15); }
    .bg-light-success { background: rgba(40, 167, 69, 0.15); }
    .bg-light-primary { background: rgba(0, 123, 255, 0.05); }
    .border-dashed { border: 2px dashed #007bff22 !important; }

    /* Animations */
    .transition-hover:hover { transform: translateY(-5px); transition: 0.3s; }
    .transition-all { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .fade-in { animation: fadeIn 0.4s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection