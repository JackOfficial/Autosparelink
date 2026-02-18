@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Order #{{ $order->id }}</h2>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mb-3">
        Back to Orders
    </a>

    <div class="row">

        {{-- Order Info --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Order Info</div>
                <div class="card-body">
                    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                    <p><strong>Total:</strong> {{ number_format($order->total_amount, 2) }} RWF</p>
                    <p><strong>Date:</strong> {{ $order->created_at }}</p>
                </div>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Customer</div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Address --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Shipping Address</div>
                <div class="card-body">
                    <p>{{ $order->address->full_name }}</p>
                    <p>{{ $order->address->street_address }}</p>
                    <p>{{ $order->address->city }}, {{ $order->address->country }}</p>
                    <p>{{ $order->address->phone }}</p>
                </div>
            </div>
        </div>

        {{-- Payment --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Payment</div>
                <div class="card-body">
                    @if($order->payment)
                        <p><strong>Status:</strong> {{ ucfirst($order->payment->status) }}</p>
                        <p><strong>Method:</strong> {{ $order->payment->method }}</p>
                        <p><strong>Amount:</strong> {{ number_format($order->payment->amount, 2) }} RWF</p>
                    @else
                        <p>No payment record found.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Shipping --}}
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">Shipping</div>
                <div class="card-body">
                    @if($order->shipping)
                        <p><strong>Carrier:</strong> {{ $order->shipping->carrier }}</p>
                        <p><strong>Tracking:</strong> {{ $order->shipping->tracking_number }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($order->shipping->status) }}</p>
                    @else
                        <p>No shipping info yet.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">Order Items</div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Part</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->part->part_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }} RWF</td>
                                    <td>{{ number_format($item->quantity * $item->unit_price, 2) }} RWF</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection