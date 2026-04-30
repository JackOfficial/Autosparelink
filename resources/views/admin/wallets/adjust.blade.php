@extends('admin.layouts.app')

@section('title', 'Manual Wallet Adjustment')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-7">
            
            {{-- Breadcrumbs/Back --}}
            <div class="mb-4">
                <a href="{{ route('admin.wallets.index') }}" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Wallet Directory
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-dark py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-soft-warning rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 35px; height: 35px;">
                            <i class="fas fa-edit text-warning small"></i>
                        </div>
                        <h5 class="card-title text-white mb-0 font-weight-bold">Manual Balance Adjustment</h5>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm">
                            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.wallets.store') }}" method="POST">
                        @csrf
                        
                        {{-- Shop Selection --}}
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark mb-2">Target Shop</label>
                            <select name="shop_id" class="form-control form-control-lg rounded-3 @error('shop_id') is-invalid @enderror" style="height: calc(1.5em + 1rem + 2px);">
                                <option value="" selected disabled>Select the shop to adjust...</option>
                                @foreach($shops as $id => $name)
                                    <option value="{{ $id }}" {{ old('shop_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback font-weight-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            {{-- Action Type --}}
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="font-weight-bold text-dark d-block mb-2">Adjustment Direction</label>
                                <div class="btn-group btn-group-toggle w-100 shadow-sm" data-toggle="buttons">
                                    <label class="btn btn-outline-success w-50 py-3 rounded-left active border-2">
                                        <input type="radio" name="type" id="type_credit" value="credit" autocomplete="off" checked> 
                                        <i class="fas fa-plus-circle mr-1"></i> Credit
                                    </label>
                                    <label class="btn btn-outline-danger w-50 py-3 rounded-right border-2">
                                        <input type="radio" name="type" id="type_debit" value="debit" autocomplete="off"> 
                                        <i class="fas fa-minus-circle mr-1"></i> Debit
                                    </label>
                                </div>
                                <small class="text-muted mt-2 d-block">Credit adds money; Debit removes it.</small>
                            </div>

                            {{-- Amount --}}
                            <div class="col-md-6">
                                <label class="font-weight-bold text-dark mb-2">Amount (RWF)</label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light border-right-0 rounded-left-3 text-muted font-weight-bold" style="font-size: 0.85rem;">RWF</span>
                                    </div>
                                    <input type="number" name="amount" class="form-control border-left-0 rounded-right-3 @error('amount') is-invalid @enderror" 
                                           placeholder="0.00" value="{{ old('amount') }}" step="0.01" min="1">
                                </div>
                                @error('amount')
                                    <div class="text-danger small font-weight-bold mt-2"><i class="fas fa-info-circle mr-1"></i> {{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Description/Reason --}}
                        <div class="form-group mb-5">
                            <label class="font-weight-bold text-dark mb-2">Internal Reason / Memo</label>
                            <textarea name="description" rows="3" class="form-control rounded-3 shadow-sm @error('description') is-invalid @enderror" 
                                      placeholder="Explain why this adjustment is being made (e.g., Refund for order #SKU-99, Correction of duplicate payout)">{{ old('description') }}</textarea>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">This will appear in the shop's ledger.</small>
                                @error('description')
                                    <span class="text-danger small font-weight-bold">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg btn-block rounded-pill font-weight-bold py-3 shadow border-0 transition-3d-hover">
                                <i class="fas fa-check-circle mr-2"></i> Commit Adjustment
                            </button>
                            <div class="text-center mt-4">
                                <a href="{{ route('admin.wallets.index') }}" class="text-muted small font-weight-bold text-uppercase" style="letter-spacing: 1px;">
                                    Discard Changes
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Audit Alert --}}
            <div class="mt-4 p-3 bg-soft-primary rounded-4 border-left border-primary shadow-sm" style="border-left-width: 4px !important;">
                <div class="media">
                    <i class="fas fa-fingerprint text-primary mr-3 mt-1"></i>
                    <div class="media-body">
                        <p class="small text-dark mb-0">
                            <strong>System Audit Active:</strong> This adjustment is logged under <strong>{{ auth()->user()->name }}</strong>. 
                            Unauthorized financial tampering is recorded and monitored.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    /* Styling consistent with HappyFamilyRW UI */
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.08) !important; }
    .bg-soft-warning { background-color: rgba(255, 193, 7, 0.15) !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .rounded-3 { border-radius: 0.6rem !important; }
    .rounded-left-3 { border-top-left-radius: 0.6rem !important; border-bottom-left-radius: 0.6rem !important; }
    .rounded-right-3 { border-top-right-radius: 0.6rem !important; border-bottom-right-radius: 0.6rem !important; }
    
    .border-2 { border-width: 2px !important; }

    .form-control {
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.05);
    }

    /* Professional Button States for BS4 */
    .btn-group-toggle .btn {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .btn-group-toggle .btn.active {
        color: #fff !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .btn-outline-success.active { background-color: #28a745 !important; border-color: #28a745 !important; }
    .btn-outline-danger.active { background-color: #dc3545 !important; border-color: #dc3545 !important; }

    .transition-3d-hover {
        transition: all 0.2s ease;
    }
    .transition-3d-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
    }
</style>
@endsection