@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-5">
            <div class="mb-4">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 1px;">
                    Vendor Partnership
                </span>
                <h1 class="display-5 fw-bold text-dark">Grow your spare parts business in Kigali.</h1>
                <p class="lead text-muted">Join hundreds of local vendors selling Toyota, Mercedes, and Hyundai parts to customers across Rwanda.</p>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-6">
                    <div class="p-3 border rounded-4 bg-white shadow-sm">
                        <i class="fas fa-bolt text-warning mb-2 fa-lg"></i>
                        <h6 class="fw-bold mb-1">Fast Setup</h6>
                        <p class="small text-muted mb-0">Get approved in 24 hours.</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 border rounded-4 bg-white shadow-sm">
                        <i class="fas fa-shield-alt text-success mb-2 fa-lg"></i>
                        <h6 class="fw-bold mb-1">Secure Pay</h6>
                        <p class="small text-muted mb-0">Guaranteed vendor payouts.</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 d-none d-lg-block">
                <img src="{{ asset('frontend/img/part.png') }}" 
                     class="img-fluid rounded-5 shadow-lg border-5 border-white" 
                     alt="Auto Parts Shop" style="transform: rotate(-2deg);">
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-5 overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary text-white rounded-4 p-3 me-3 shadow-primary">
                                <i class="fas fa-store fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Shop Application</h4>
                                <p class="text-muted small mb-0">Official Business Registration Required</p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Shop Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="fas fa-tag text-muted"></i></span>
                                    <input type="text" name="shop_name" class="form-control bg-light border-start-0 rounded-end-4 @error('shop_name') is-invalid @enderror" value="{{ old('shop_name') }}" placeholder="Kigali Auto Parts" required>
                                </div>
                                @error('shop_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Business Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="shop_email" class="form-control bg-light border-start-0 rounded-end-4 @error('shop_email') is-invalid @enderror" value="{{ old('shop_email') }}" placeholder="sales@shop.rw" required>
                                </div>
                                @error('shop_email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text" name="phone_number" class="form-control bg-light border-start-0 rounded-end-4 @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" placeholder="+250..." required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">TIN Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="fas fa-id-card text-muted"></i></span>
                                    <input type="text" name="tin_number" class="form-control bg-light border-start-0 rounded-end-4 @error('tin_number') is-invalid @enderror" value="{{ old('tin_number') }}" placeholder="9-digit TIN" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary small">Physical Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-4"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                    <input type="text" name="address" class="form-control bg-light border-start-0 rounded-end-4 @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Nyarugenge, Gitega..." required>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <div class="p-3 rounded-4 border border-dashed bg-light">
                                    <h6 class="fw-bold mb-3 small text-uppercase">Documents Upload</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">RDB Certificate</label>
                                            <input type="file" name="rdb_certificate" class="form-control form-control-sm rounded-3">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">National ID</label>
                                            <input type="file" name="owner_id" class="form-control form-control-sm rounded-3">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-secondary small">Specialization</label>
                                <textarea name="description" class="form-control bg-light rounded-4" rows="3" placeholder="e.g., Japanese Engine Parts, Body Parts...">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg">
                                    Launch My Shop <i class="fas fa-rocket ms-2"></i>
                                </button>
                                <p class="text-center mt-3 small text-muted">
                                    By joining, you agree to our <a href="#" class="text-decoration-none">Vendor Policy</a>.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-5 { border-radius: 2rem !important; }
    .rounded-4 { border-radius: 1.2rem !important; }
    .shadow-primary { box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2); }
    .bg-light { background-color: #f8f9fa !important; }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    .input-group-text { border: none; }
    .form-control:focus { box-shadow: none; border-color: #0d6efd; }
</style>
@endsection