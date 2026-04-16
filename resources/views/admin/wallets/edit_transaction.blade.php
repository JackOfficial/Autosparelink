@extends('admin.layouts.app')

@section('title', 'Edit Transaction Metadata')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            
            <div class="mb-4">
                <a href="{{ route('admin.wallets.show', $transaction->wallet_id) }}" class="text-muted small">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Transaction History
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="media align-items-center mb-4">
                        <div class="bg-soft-primary rounded-circle p-3 mr-3 text-primary">
                            <i class="fas fa-pen-fancy fa-lg"></i>
                        </div>
                        <div class="media-body">
                            <h4 class="font-weight-bold mb-0 text-dark">Update Description</h4>
                            <p class="text-muted small mb-0">Transaction #{{ $transaction->id }}</p>
                        </div>
                    </div>

                    {{-- Read-Only Financial Data Summary --}}
                    <div class="row mb-4 p-3 bg-light rounded-3 mx-0">
                        <div class="col-6">
                            <span class="text-muted small d-block text-uppercase font-weight-bold">Amount</span>
                            <span class="font-weight-bold text-dark">{{ number_format($transaction->amount) }} RWF</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted small d-block text-uppercase font-weight-bold">Type</span>
                            <div>{!! $transaction->type_badge !!}</div>
                        </div>
                    </div>

                    <form action="{{ route('admin.wallets.update', $transaction->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Transaction Description</label>
                            <textarea name="description" rows="4" 
                                      class="form-control rounded-3 border-light shadow-sm @error('description') is-invalid @enderror" 
                                      placeholder="Update the reason or details for this transaction...">{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle mr-1"></i> 
                                Note: You are only editing the description. The amount and type cannot be changed.
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg btn-block rounded-pill font-weight-bold shadow-sm">
                                Save Changes
                            </button>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.wallets.show', $transaction->wallet_id) }}" class="btn btn-link text-muted text-decoration-none small">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Audit Trail Warning --}}
            <div class="mt-4 text-center">
                <p class="text-muted small">
                    <i class="fas fa-lock mr-1"></i> 
                    Financial records for <strong>{{ $transaction->wallet->shop->shop_name }}</strong> are logged.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Soft UI & Compatibility Fixes for BS4 */
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.08) !important; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .rounded-3 { border-radius: 0.75rem !important; }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    /* Ensuring the type badge renders correctly in the small box */
    .badge {
        font-weight: 600;
        padding: 0.4em 0.8em;
    }
</style>
@endsection