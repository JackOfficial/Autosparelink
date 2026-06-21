@extends('layouts.app')
@section('title', 'Order Status - ' . $order->order_number)
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            
            {{-- 1. Pending Payment State --}}
            @if($order->status == 'pending')
                <div class="card shadow-sm border-0 bg-light mb-4 p-4 text-center text-dark" style="border-radius: 15px; border-left: 5px solid #ffc107 !important;">
                    <div class="d-flex flex-column align-items-center">
                        <div class="spinner-container position-relative mb-3">
                            <div class="spinner-border text-warning" role="status" style="width: 4rem; height: 4rem; border-width: 0.35em;">
                                <span class="sr-only">Waiting for payment...</span>
                            </div>
                            <i class="fas fa-mobile-alt text-warning position-absolute" style="font-size: 24px; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                        </div>
                        <h3 class="font-weight-bold text-warning-dark mb-1">Waiting for Payment...</h3>
                        <p class="mb-0 text-muted px-lg-5">
                            We have sent a Mobile Money payment prompt (push notification) to your phone. 
                            Please check your screen, enter your **PIN**, and confirm the transaction.
                        </p>
                    </div>
                </div>

            {{-- 2. Explicit Failed / Cancelled State (Option B) --}}
            @elseif($order->status == 'failed')
                <div class="card shadow-sm border-0 bg-light mb-4 p-4 text-center text-dark" style="border-radius: 15px; border-left: 5px solid #dc3545 !important;">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-3">
                            <i class="fas fa-times-circle text-danger" style="font-size: 60px;"></i>
                        </div>
                        <h3 class="font-weight-bold text-danger mb-1">Payment Failed or Cancelled</h3>
                        <p class="mb-3 text-muted px-lg-5">
                            The Mobile Money prompt was declined, timed out, or explicitly canceled on your phone.
                        </p>
                        <a href="{{ route('checkout') }}" class="btn btn-danger px-4 shadow-sm">
                            <i class="fas fa-sync-alt mr-1"></i> Try to Pay Again
                        </a>
                    </div>
                </div>

            {{-- 3. Success / Processing State --}}
            @else
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h1 class="display-4">Murakoze!</h1>
                <p class="lead">Your order has been paid and processed successfully.</p>
            @endif
            
            @if(session('message'))
                <div class="alert alert-info shadow-sm">
                    {{ session('message') }}
                </div>
            @endif

            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4 text-left">
                    <h4 class="card-title border-bottom pb-2">Order Summary</h4>
                    <div class="row mb-2">
                        <div class="col-sm-4 font-weight-bold">Order ID:</div>
                        <div class="col-sm-8 text-primary font-weight-bold">{{ $order->order_number }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 font-weight-bold">Status:</div>
                        <div class="col-sm-8">
                            <span class="badge badge-pill {{ $order->status == 'pending' ? 'badge-warning text-dark' : ($order->status == 'failed' ? 'badge-danger' : 'badge-success') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 font-weight-bold">Total Amount:</div>
                        <div class="col-sm-8 font-weight-bold">{{ number_format($order->total_amount) }} RWF</div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-sm-4 font-weight-bold">Deliver To:</div>
                        <div class="col-sm-8">
                            {{ $order->is_guest ? $order->guest_name : ($order->user->name ?? 'Customer') }}<br>
                            {{ $order->is_guest ? $order->guest_shipping_address : ($order->address->street_address ?? 'Address not found') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <a href="{{ route('spare-parts.index') }}" class="btn btn-outline-primary px-4 mr-2">
                    <i class="fas fa-shopping-bag mr-1"></i> Continue Shopping
                </a>
                @auth
                    <a href="{{ route('user.orders.index') }}" class="btn btn-primary px-4">
                        <i class="fas fa-list mr-1"></i> View My Orders
                    </a>
                @endauth
            </div>
            
            <p class="text-muted mt-4 small d-none">
                A confirmation email has been sent to <strong>{{ $order->is_guest ? $order->guest_email : $order->user->email }}</strong>.
            </p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 15px;
    }
    .badge-pill {
        padding-right: .6em;
        padding-left: .6em;
    }
    .text-warning-dark {
        color: #d39e00;
    }
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.15); }
        100% { transform: translate(-50%, -50%) scale(1); }
    }
    .spinner-container i {
        animation: pulse 1.8s infinite ease-in-out;
    }
</style>
@endpush

@push('scripts')
@if($order->status == 'pending')
<script>
    let checkInterval = setInterval(function() {
        fetch("{{ route('orders.status', $order->id) }}")
            .then(response => response.json())
            .then(data => {
                // If it switches away from pending (to processing OR failed), refresh to render state updates
                if (data.status !== 'pending') {
                    clearInterval(checkInterval);
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error fetching order status:', error));
    }, 4000);
</script>
@endif
@endpush