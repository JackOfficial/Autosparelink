@extends('admin.layouts.app')

@section('title', 'Manual Wallet Adjustment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            
            {{-- Breadcrumbs/Back --}}
            <div class="mb-4">
                <a href="{{ route('admin.wallets.index') }}" class="text-muted small">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Wallets
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark py-3">
                    <h5 class="card-title text-white mb-0 font-weight-bold">Manual Balance Adjustment</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 mb-4">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.wallets.store') }}" method="POST">
                        @csrf
                        
                        {{-- Shop Selection --}}
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark">Select Shop</label>
                            <select name="shop_id" class="form-control form-control-lg rounded-3 @error('shop_id') is-invalid @enderror">
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

                        <div class="row mb-4">
                            {{-- Action Type --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark d-block">Adjustment Type</label>
                                <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                    <label class="btn btn-outline-success w-50 py-2 rounded-left active">
                                        <input type="radio" name="type" id="type_credit" value="credit" autocomplete="off" checked> 
                                        <i class="fas fa-plus-circle mr-1"></i> Credit
                                    </label>
                                    <label class="btn btn-outline-danger w-50 py-2 rounded-right">
                                        <input type="radio" name="type" id="type_debit" value="debit" autocomplete="off"> 
                                        <i class="fas fa-minus-circle mr-1"></i> Debit
                                    </label>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <div class="col-md-6">
                                <label class="font-weight-bold text-dark">Amount (RWF)</label>
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0 rounded-left-3 text-muted" style="font-size: 0.9rem;">RWF</span>
                                    </div>
                                    <input type="number" name="amount" class="form-control border-left-0 rounded-right-3 @error('amount') is-invalid @enderror" 
                                           placeholder="0" value="{{ old('amount') }}" step="0.01">
                                </div>
                                @error('amount')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Description/Reason --}}
                        <div class="form-group mb-5">
                            <label class="font-weight-bold text-dark">Reason for Adjustment</label>
                            <textarea name="description" rows="3" class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                      placeholder="e.g., Refund for order #1234 or manual service fee correction">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">This description will be visible to the shop owner in their history.</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Action Buttons --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block rounded-pill font-weight-bold py-3 shadow">
                                Confirm Adjustment
                            </button>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.wallets.index') }}" class="text-muted small">
                                    Cancel and Go Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Security Notice --}}
            <div class="mt-4 p-3 bg-soft-warning rounded-4 border-left border-warning shadow-sm" style="border-left-width: 4px !important;">
                <div class="media">
                    <i class="fas fa-shield-alt text-warning mr-3 mt-1"></i>
                    <div class="media-body">
                        <p class="small text-dark mb-0">
                            <strong>Important:</strong> All manual adjustments are logged with your Admin ID ({{ auth()->user()->name }}). Ensure you have verified the request before proceeding.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* BS4 Compatibility & Soft UI */
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .rounded-3 { border-radius: 0.75rem !important; }
    .rounded-left-3 { border-top-left-radius: 0.75rem !important; border-bottom-left-radius: 0.75rem !important; }
    .rounded-right-3 { border-top-right-radius: 0.75rem !important; border-bottom-right-radius: 0.75rem !important; }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    /* BS4 Button Toggle Styling Override */
    .btn-group-toggle .btn.active {
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-outline-success.active { background-color: #28a745 !important; }
    .btn-outline-danger.active { background-color: #dc3545 !important; }
</style>
@endsection