@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-5" style="border-radius: 20px;">
                <div class="card-body">
                    {{-- Professional Loading Animation --}}
                    <div class="spinner-border text-primary mb-4" role="status" style="width: 3rem; height: 3rem;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    
                    <h3 class="font-weight-bold">Preparing Secure Payment</h3>
                    <p class="text-muted">
                        Please wait while we redirect you to the payment gateway.<br>
                        <strong>Order #{{ $order->id }}</strong> - {{ number_format($order->total_amount, 0) }} RWF
                    </p>

                    {{-- Hidden form that auto-submits via JavaScript --}}
                    <form id="paymentForm" action="{{ route('payment.initialize') }}" method="POST">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                        <input type="hidden" name="amount" value="{{ $order->total_amount }}">
                        
                        {{-- Fallback button shown only if JS is slow or disabled --}}
                        <div id="fallback-button" class="mt-4" style="display: none;">
                            <p class="small text-muted">Taking too long?</p>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Click to Proceed to Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show the fallback button after 5 seconds if the page hasn't redirected
        setTimeout(function() {
            var fallback = document.getElementById('fallback-button');
            if (fallback) fallback.style.display = 'block';
        }, 5000);

        // Auto-submit the form
        document.getElementById('paymentForm').submit();
    });
</script>
@endsection