@extends('layouts.dashboard')

@section('title', 'Edit Address: ' . $address->address_name)

@section('content')
<div class="container py-4 py-lg-5">
    
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('addresses.index') }}" class="text-decoration-none text-muted small">Addresses</a></li>
            <li class="breadcrumb-item active small fw-bold" aria-current="page">Edit Address</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h4 fw-bold text-dark mb-1">Edit Address</h2>
                        <p class="text-muted small mb-0">Update your shipping details below.</p>
                    </div>
                    <form action="{{ route('addresses.destroy', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger text-decoration-none small">
                            <i class="fas fa-trash-alt me-1"></i> Delete Address
                        </button>
                    </form>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('addresses.update', $address->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Label --}}
                            <div class="col-md-6 mb-3">
                                <label for="address_name" class="form-label small fw-bold text-muted text-uppercase">Address Label</label>
                                <input type="text" name="address_name" id="address_name" value="{{ old('address_name', $address->address_name) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('address_name') is-invalid @enderror" required>
                                @error('address_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Full Name --}}
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label small fw-bold text-muted text-uppercase">Receiver's Full Name</label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $address->full_name) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('full_name') is-invalid @enderror" required>
                                @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- Phone --}}
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label small fw-bold text-muted text-uppercase">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('phone') is-invalid @enderror" required>
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label small fw-bold text-muted text-uppercase">City / Province</label>
                                <input type="text" name="city" id="city" value="{{ old('city', $address->city) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('city') is-invalid @enderror" required>
                                @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            {{-- District --}}
                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label small fw-bold text-muted text-uppercase">District</label>
                                <input type="text" name="district" id="district" value="{{ old('district', $address->district) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('district') is-invalid @enderror" required>
                                @error('district') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Sector --}}
                            <div class="col-md-6 mb-3">
                                <label for="sector" class="form-label small fw-bold text-muted text-uppercase">Sector</label>
                                <input type="text" name="sector" id="sector" value="{{ old('sector', $address->sector) }}" 
                                       class="form-control border-0 bg-light rounded-3 @error('sector') is-invalid @enderror">
                                @error('sector') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Directions --}}
                        <div class="mb-4">
                            <label for="details" class="form-label small fw-bold text-muted text-uppercase">Detailed Directions</label>
                            <textarea name="details" id="details" rows="3" 
                                      class="form-control border-0 bg-light rounded-3 @error('details') is-invalid @enderror" required>{{ old('details', $address->details) }}</textarea>
                            @error('details') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Default Toggle --}}
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                            <label class="form-check-label small fw-bold text-dark" for="is_default">
                                Mark as my default address
                            </label>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('addresses.index') }}" class="btn btn-link text-muted text-decoration-none px-0">
                                <i class="fas fa-times me-1"></i> Cancel Changes
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                Update Address <i class="fas fa-check-circle ms-2 small"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light { background-color: #f8f9fa !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .form-control {
        padding: 0.75rem 1rem;
    }
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        border: 1px solid #0d6efd !important;
    }
</style>
@endsection