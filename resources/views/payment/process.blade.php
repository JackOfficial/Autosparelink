@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 20px;">
                <div class="card-body">
                    <div class="spinner-border text-primary mb-4" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h3 class="font-weight-bold">Preparing Secure Payment</h3>
                    <p class="text-muted">Please wait while we redirect you to the payment gateway. Do not refresh this page.</p>
                    
                    {{-- Hidden form that auto-submits via JavaScript --}}
                    <form id="paymentForm" action="{{ route('payment.initialize') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="amount" value="{{ $order->total_amount }}">
                        <button type="submit" class="btn btn-primary d-none">Click here if not redirected</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto submit the form as soon as the page loads
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('paymentForm').submit();
    });
</script>
@endsection