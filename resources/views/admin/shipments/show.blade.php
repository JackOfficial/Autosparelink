@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Shipping #{{ $shipping->id }}</h2>

    <a href="{{ route('shippings.index') }}" class="btn btn-secondary mb-3">
        Back
    </a>

    <div class="card">
        <div class="card-body">
            <p><strong>Order:</strong> #{{ $shipping->order->id }}</p>
            <p><strong>Customer:</strong> {{ $shipping->order->user->name }}</p>
            <p><strong>Carrier:</strong> {{ $shipping->carrier }}</p>
            <p><strong>Status:</strong> {{ ucfirst($shipping->status) }}</p>
            <p><strong>Tracking Number:</strong> {{ $shipping->tracking_number }}</p>
            <p><strong>Shipped At:</strong> {{ $shipping->shipped_at }}</p>
            <p><strong>Delivered At:</strong> {{ $shipping->delivered_at }}</p>
        </div>
    </div>
</div>
@endsection