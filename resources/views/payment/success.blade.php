@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <div class="mb-4">
                <div class="display-1 text-success animate__animated animate__bounceIn">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>

            <h2 class="font-weight-bold mb-2">Payment Successful!</h2>
            <p class="text-muted mb-4">The transaction was completed successfully and your order is now being processed.</p>

            <div class="card border-0 shadow-sm mb-4 text-left" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <h5 class="card-title mb-0">Order Summary</h5>
                        <span class="badge badge-pill badge-success px-3 py-2">Verified Payment</span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6 text-muted">Transaction Reference</div>
                        <div class="col-6 font-weight-bold text-right text-primary">{{ $data['tx_ref'] }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6 text-muted">Amount Paid</div>
                        <div class="col-6 font-weight-bold text-right">{{ number_format($data['amount']) }} {{ $data['currency'] }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 text-muted">Payment Method</div>
                        <div class="col-6 font-weight-bold text-right text-capitalize">
                            {{ str_replace('_', ' ', $data['payment_type'] ?? 'Mobile Money') }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6 text-muted">Date & Time</div>
                        <div class="col-6 font-weight-bold text-right">
                            {{ \Carbon\Carbon::parse($data['created_at'])->format('M d, Y | h:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <a href="{{ route('payment.receipt', ['id' => $data['id']]) }}" class="btn btn-dark px-4 py-2">
                    <i class="fas fa-file-pdf mr-2"></i> Download Receipt
                </a>
                
                <a href="/spare-parts" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-shopping-cart mr-2"></i> Continue Shopping
                </a>

                {{-- Only show "My Orders" if the user is logged in --}}
                @auth
                    <a href="{{ route('dashboard.index') }}" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-box-open mr-2"></i> My Orders
                    </a>
                @endauth
            </div>

            <p class="mt-4 text-muted small">
                {{-- Show guest email from transaction if not logged in --}}
                A confirmation email has been sent to 
                <strong>{{ auth()->check() ? auth()->user()->email : ($data['customer']['email'] ?? 'your email') }}</strong>
            </p>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .gap-3 { gap: 1rem; }
    .card { background-color: #ffffff; }
    .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
    .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endpush
@endsection