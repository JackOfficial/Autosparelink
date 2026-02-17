@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2>Welcome, {{ $user->name }}</h2>
    <p class="text-muted">Your dashboard overview</p>

    <div class="row">
        {{-- Profile / Info --}}
        <div class="col-md-3 mb-4">
            <h4>Profile Info</h4>
            <div class="border p-3 rounded">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Joined:</strong> {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Orders --}}
        <div class="col-md-9 mb-4">
            <h4>My Orders</h4>
            @if($orders->isEmpty())
                <p>You have not placed any orders yet.</p>
            @else
                <div class="accordion" id="ordersAccordion">
                    @foreach($orders as $order)
                        <div class="card mb-2">
                            <div class="card-header" id="orderHeading{{ $order->id }}">
                                <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#orderCollapse{{ $order->id }}" aria-expanded="true" aria-controls="orderCollapse{{ $order->id }}">
                                        Order #{{ $order->id }} - {{ ucfirst($order->status) }}
                                    </button>
                                    <span>{{ $order->total_amount }} RWF</span>
                                </h5>
                            </div>

                            <div id="orderCollapse{{ $order->id }}" class="collapse" aria-labelledby="orderHeading{{ $order->id }}" data-parent="#ordersAccordion">
                                <div class="card-body">
                                    {{-- Shipping Address --}}
                                    <h6>Shipping Address</h6>
                                    <p>{{ $order->address->full_name }}, {{ $order->address->street_address }}, {{ $order->address->city }}, {{ $order->address->country }}</p>

                                    {{-- Payment Info --}}
                                    <h6>Payment</h6>
                                    <p>Amount: {{ number_format($order->payment->amount, 2) }} RWF</p>
                                    <p>Status: {{ ucfirst($order->payment->status) }}</p>
                                    <p>Method: {{ ucfirst($order->payment->method) }}</p>

                                    {{-- Order Items --}}
                                    <h6>Items</h6>
                                    <ul class="list-group mb-2">
                                        @foreach($order->orderItems as $item)
                                            <li class="list-group-item d-flex justify-content-between">
                                                {{ $item->part->part_name }} x {{ $item->quantity }}
                                                <span>{{ number_format($item->unit_price * $item->quantity, 2) }} RWF</span>
                                            </li>
                                        @endforeach
                                    </ul>

                                    {{-- Shipping Info --}}
                                    <h6>Shipping</h6>
                                    <p>Status: {{ ucfirst($order->shipping->status) }}</p>
                                    <p>Carrier: {{ $order->shipping->carrier ?? '-' }}</p>
                                    <p>Tracking #: {{ $order->shipping->tracking_number ?? '-' }}</p>

                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary mt-2">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Wishlist & Cart Summary --}}
    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <h4>My Wishlist</h4>
            @if($wishlistItems->isEmpty())
                <p>Your wishlist is empty.</p>
            @else
                <ul class="list-group">
                    @foreach($wishlistItems as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $item->name }}
                            <span>{{ number_format($item->price, 2) }} RWF</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="col-md-6 mb-4">
            <h4>My Cart</h4>
            @if($cartItems->isEmpty())
                <p>Your cart is empty.</p>
            @else
                <ul class="list-group mb-2">
                    @foreach($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $item->name }} x {{ $item->qty }}
                            <span>{{ number_format($item->price * $item->qty, 2) }} RWF</span>
                        </li>
                    @endforeach
                    <li class="list-group-item d-flex justify-content-between font-weight-bold">
                        Total
                        <span>{{ number_format($cartTotal, 2) }} RWF</span>
                    </li>
                </ul>
                <a href="{{ route('checkout') }}" class="btn btn-primary">Go to Checkout</a>
            @endif
        </div>
    </div>
</div>
@endsection