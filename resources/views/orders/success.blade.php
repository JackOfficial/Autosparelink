@extends('layouts.app') {{-- Ensure this matches your main layout file name --}}
@section('title', 'Order Success - ' . $order->order_id)
@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="mb-4">
                <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
            </div>
            
            <h1 class="display-4">Murakoze!</h1>
            <p class="lead">Your order has been placed successfully.</p>
            
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
                        <div class="col-sm-8 text-primary font-weight-bold">{{ $order->order_id }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-4 font-weight-bold">Status:</div>
                        <div class="col-sm-8">
                            <span class="badge badge-pill {{ $order->status == 'pending' ? 'badge-warning' : 'badge-success' }}">
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
            
            <p class="text-muted mt-4 small">
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
</style>
@endpush