@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 text-center">
            <div class="mb-4">
                <div class="display-1 text-danger animate__animated animate__headShake">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>

            <h2 class="font-weight-bold mb-2">Payment Failed</h2>
            <p class="text-muted mb-4">We couldn't process your transaction. This could be due to a technical error or a cancellation from your provider.</p>

            <div class="card border-0 shadow-sm mb-4 text-left" style="border-radius: 15px; border-left: 5px solid #dc3545 !important;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">What happened?</h5>
                    
                    <ul class="text-muted mb-0 pl-4">
                        <li class="mb-2">The transaction was cancelled or timed out.</li>
                        <li class="mb-2">There might be insufficient funds in your account.</li>
                        <li>Mobile Money PIN was not entered in time.</li>
                    </ul>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-3">
                <a href="{{ url()->previous() }}" class="btn btn-primary px-5 py-2">
                    <i class="fas fa-redo mr-2"></i> Try Again
                </a>
                
                <a href="{{ route('parts.index') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fas fa-times mr-2"></i> Cancel & Return
                </a>
            </div>

            <div class="mt-5 p-3 bg-light rounded">
                <p class="mb-0 small text-muted">
                    Need help? Contact our support team with your reference ID if you were charged.
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    .gap-3 { gap: 1rem; }
    .btn { border-radius: 8px; font-weight: 500; }
</style>
@endpush
@endsection