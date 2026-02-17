@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Order #{{ $order->id }}</h2>
    <p class="text-muted">Placed on: {{ $order->created_at->format('d M Y H:i') }}</p>

    {{-- Order Status --}}
    <div class="mb-4">
        <strong>Status:</strong>
        <span class="badge 
            @if($order->status === 'pending') badge-warning
            @elseif($order->status === 'processing') badge-primary
            @elseif($order->status === 'shipped') badge-info
            @elseif($order->status === 'delivered') badge-success
            @elseif($order->status === 'cancelled') badge-danger
            @endif">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="row">
        {{-- Shipping / Address --}}
        <div class="col-md-6 mb-4">
            <h4>Shipping Address</h4>
            <div class="border p-3 rounded">
                <p><strong>{{ $order->address->full_name }}</strong></p>
                <p>{{ $order->address->street_address }}, {{ $order->address->city }}, {{ $order->address->state ?? '' }}</p>
                <p>{{ $order->address->postal_code ?? '' }}, {{ $order->address->country }}</p>
                <p>Phone: {{ $order->address->phone }}</p>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="col-md-6 mb-4">
            <h4>Payment</h4>
            <div class="border p-3 rounded">
                <p><strong>Amount:</strong> {{ number_format($order->payment->amount, 2) }} RWF</p>
                <p><strong>Method:</strong> {{ ucfirst($order->payment->method) }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge 
                        @if($order->payment->status === 'pending') badge-warning
                        @elseif($order->payment->status === 'processing') badge-primary
                        @elseif($order->payment->status === 'successful') badge-success
                        @elseif($order->payment->status === 'failed') badge-danger
                        @elseif($order->payment->status === 'refunded') badge-secondary
                        @endif">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </p>
                @if($order->payment->transaction_reference)
                    <p><strong>Transaction Ref:</strong> {{ $order->payment->transaction_reference }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="mb-4">
        <h4>Order Items</h4>
        <ul class="list-group mb-3">
            @foreach($order->orderItems as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        {{ $item->part->part_name }} x {{ $item->quantity }}
                        <small class="text-muted">({{ number_format($item->unit_price, 2) }} RWF each)</small>
                    </div>
                    <span>{{ number_format($item->quantity * $item->unit_price, 2) }} RWF</span>
                </li>
            @endforeach
            <li class="list-group-item d-flex justify-content-between font-weight-bold">
                Total
                <span>{{ number_format($order->total_amount, 2) }} RWF</span>
            </li>
        </ul>
    </div>

    {{-- Shipping Info --}}
    <div class="mb-4">
        <h4>Shipping</h4>
        <div class="border p-3 rounded">
            <p><strong>Carrier:</strong> {{ $order->shipping->carrier ?? 'Not assigned yet' }}</p>
            <p><strong>Tracking Number:</strong> {{ $order->shipping->tracking_number ?? '—' }}</p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    @if($order->shipping->status === 'pending') badge-warning
                    @elseif($order->shipping->status === 'shipped') badge-primary
                    @elseif($order->shipping->status === 'in_transit') badge-info
                    @elseif($order->shipping->status === 'delivered') badge-success
                    @elseif($order->shipping->status === 'failed') badge-danger
                    @endif">
                    {{ ucfirst($order->shipping->status) }}
                </span>
            </p>
            @if($order->shipping->shipped_at)
                <p><strong>Shipped At:</strong> {{ $order->shipping->shipped_at->format('d M Y H:i') }}</p>
            @endif
            @if($order->shipping->delivered_at)
                <p><strong>Delivered At:</strong> {{ $order->shipping->delivered_at->format('d M Y H:i') }}</p>
            @endif
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
</div>
@endsection