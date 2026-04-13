@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <div class="text-center mb-4">
                <h2 class="fw-bold">Open Your Shop</h2>
                <p class="text-muted">Fill out the details below to start selling spare parts on our platform.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <h5 class="fw-bold mb-3"><i class="fas fa-store me-2 text-primary"></i>Basic Information</h5>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Shop Name</label>
                                <input type="text" name="shop_name" class="form-control @error('shop_name') is-invalid @enderror" value="{{ old('shop_name') }}" placeholder="e.g., Kigali Auto Parts" required>
                                @error('shop_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Business Email</label>
                                <input type="email" name="shop_email" class="form-control @error('shop_email') is-invalid @enderror" value="{{ old('shop_email') }}" placeholder="sales@yourshop.rw" required>
                                @error('shop_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}" placeholder="+250..." required>
                                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">TIN Number</label>
                                <input type="text" name="tin_number" class="form-control @error('tin_number') is-invalid @enderror" value="{{ old('tin_number') }}" placeholder="9-digit TIN" required>
                                @error('tin_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Physical Address</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="District, Sector, Street No." required>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <h5 class="fw-bold mb-3"><i class="fas fa-file-invoice me-2 text-primary"></i>Verification Documents</h5>
                        <p class="small text-muted mb-3">Please upload clear scans or photos of your official documents.</p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">RDB Certificate (PDF/Image)</label>
                                <input type="file" name="rdb_certificate" class="form-control @error('rdb_certificate') is-invalid @enderror" required>
                                <div class="form-text mt-1" style="font-size: 0.75rem;">Max size: 5MB</div>
                                @error('rdb_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Owner National ID / Passport</label>
                                <input type="file" name="owner_id" class="form-control @error('owner_id') is-invalid @enderror" required>
                                <div class="form-text mt-1" style="font-size: 0.75rem;">Max size: 2MB</div>
                                @error('owner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Shop Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Tell customers what you specialize in (e.g., Toyota suspension, Brake pads...)">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                                Submit Application <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center mt-4 small text-muted">
                By submitting, you agree to our <a href="#">Vendor Terms & Conditions</a>.
            </p>
        </div>
    </div>
</div>
@endsection