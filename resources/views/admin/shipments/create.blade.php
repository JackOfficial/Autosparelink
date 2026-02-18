@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Create Shipping</h2>

    <form method="POST" action="{{ route('shippings.store') }}">
        @csrf

        <div class="mb-3">
            <label>Order</label>
            <select name="order_id" class="form-control">
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">
                        Order #{{ $order->id }} - {{ $order->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Carrier</label>
            <input type="text" name="carrier" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tracking Number</label>
            <input type="text" name="tracking_number" class="form-control">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                @foreach(['pending','shipped','in_transit','delivered','failed'] as $status)
                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-success">Create Shipping</button>
    </form>
</div>
@endsection