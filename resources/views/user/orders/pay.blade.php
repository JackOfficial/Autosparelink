@extends('layouts.dashboard')

@section('title', 'Pay Order #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h4 class="fw-bold text-dark mb-1">Make Payment</h4>
                    <p class="text-muted small">Complete payment for Order #{{ $order->order_number }}</p>
                </div>
                <div class="card-body px-4 pb-4">
                    
                    @if(session('error'))
                        <div class="alert alert-danger rounded-3 small mb-3">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="bg-light rounded-3 p-3 mb-4 border">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Total Order Amount</span>
                            <span class="fw-bold text-dark">RWF {{ number_format($order->total_amount, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Payment Method</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-medium px-3">
                                <i class="fas fa-mobile-alt me-1"></i> Mobile Money (MoMo)
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('user.orders.process-payment', $order->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="phone" class="form-label small fw-bold text-muted">Phone Number for Payment Prompt</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-light-subtle text-muted"><i class="fas fa-phone"></i></span>
                                <input type="text" 
                                       name="phone" 
                                       id="phone" 
                                       class="form-control rounded-end-3 border-light-subtle @error('phone') is-invalid @enderror" 
                                       placeholder="e.g., 078XXXXXXX" 
                                       value="{{ old('phone', $order->address?->phone ?? $order->guest_phone) }}" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text mt-2 text-muted" style="font-size: 0.75rem;">
                                Enter the MoMo phone number where you will receive the USSD/Push payment prompt.
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill fw-bold shadow-sm py-2 fs-6">
                                <i class="fas fa-credit-card me-1"></i> Pay Now
                            </button>
                            <a href="{{ route('user.orders.index') }}" class="btn btn-light rounded-pill border py-2 text-muted fw-bold">
                                Go Back
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection