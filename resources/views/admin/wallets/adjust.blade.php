@extends('admin.layouts.app')

@section('title', 'Manual Wallet Adjustment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            
            {{-- Breadcrumbs/Back --}}
            <div class="mb-4">
                <a href="{{ route('admin.wallets.index') }}" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Back to Wallets
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark py-3">
                    <h5 class="card-title text-white mb-0 fw-bold">Manual Balance Adjustment</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.wallets.store') }}" method="POST">
                        @csrf
                        
                        {{-- Shop Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Select Shop</label>
                            <select name="shop_id" class="form-select form-select-lg rounded-3 @error('shop_id') is-invalid @enderror">
                                <option value="" selected disabled>Choose a verified shop...</option>
                                @foreach($shops as $id => $name)
                                    <option value="{{ $id }}" {{ old('shop_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-4 mb-4">
                            {{-- Action Type --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Adjustment Type</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="type" id="type_credit" value="credit" checked>
                                    <label class="btn btn-outline-success w-100 py-2 rounded-3" for="type_credit">
                                        <i class="fas fa-plus-circle me-1"></i> Credit
                                    </label>

                                    <input type="radio" class="btn-check" name="type" id="type_debit" value="debit">
                                    <label class="btn btn-outline-danger w-100 py-2 rounded-3" for="type_debit">
                                        <i class="fas fa-minus-circle me-1"></i> Debit
                                    </label>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Amount (RWF)</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted" style="font-size: 0.9rem;">RWF</span>
                                    <input type="number" name="amount" class="form-control border-start-0 rounded-end-3 @error('amount') is-invalid @enderror" 
                                           placeholder="0" value="{{ old('amount') }}" step="0.01">
                                </div>
                                @error('amount')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Description/Reason --}}
                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark">Reason for Adjustment</label>
                            <textarea name="description" rows="3" class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                      placeholder="e.g., Refund for order #1234 or manual service fee correction">{{ old('description') }}</textarea>
                            <div class="form-text text-muted">This description will be visible to the shop owner in their history.</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold py-3 shadow">
                                Confirm Adjustment
                            </button>
                            <a href="{{ route('admin.wallets.index') }}" class="btn btn-link text-muted text-decoration-none small mt-2">
                                Cancel and Go Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security Notice --}}
            <div class="mt-4 p-3 bg-soft-warning rounded-4 border-start border-warning border-4">
                <div class="d-flex">
                    <i class="fas fa-shield-alt text-warning me-3 mt-1"></i>
                    <p class="small text-dark mb-0">
                        <strong>Important:</strong> All manual adjustments are logged with your Admin ID ({{ auth()->user()->name }}). Ensure you have verified the request before proceeding.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
    
    /* Custom Focus Effects */
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    .btn-check:checked + .btn-outline-success {
        background-color: #198754;
        color: #fff;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
    }

    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        color: #fff;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
    }
</style>
@endsection