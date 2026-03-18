@extends('admin.layouts.app')

@section('content')
@php
    $order = $shipping->order;
    $customerName = $order?->guest_name ?? $order?->user?->name ?? 'Unknown Customer';
    $customerEmail = $order?->guest_email ?? $order?->user?->email ?? 'N/A';
    $isGuest = $order && !($order->user_id);
@endphp

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Shipment Details</h2>
            <span class="text-muted font-weight-bold">Internal ID: #{{ $shipping->id }}</span>
        </div>
        <div class="btn-group shadow-sm">
            <a href="{{ route('admin.shippings.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            @if($order)
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-primary">
                    <i class="fas fa-box mr-1"></i> View Order #{{ $order->id }}
                </a>
            @else
                <button class="btn btn-secondary" disabled>Order Missing</button>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Status & Customer --}}
        <div class="col-md-4">
            {{-- Quick Status Update Card --}}
            <div class="card shadow-sm mb-4 border-left-primary">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Update Logistics Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.shippings.update', $shipping->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Current Status</label>
                            <select name="status" class="form-control form-control-lg mb-3" onchange="this.form.submit()">
                                <option value="pending" {{ $shipping->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ $shipping->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="in_transit" {{ $shipping->status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="delivered" {{ $shipping->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="failed" {{ $shipping->status == 'failed' ? 'selected' : '' }}>Failed / Returned</option>
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted">Carrier & Tracking</label>
                            <input type="text" name="carrier" class="form-control form-control-sm mb-2" value="{{ $shipping->carrier }}" placeholder="Carrier (e.g. DHL)">
                            <input type="text" name="tracking_number" class="form-control form-control-sm" value="{{ $shipping->tracking_number }}" placeholder="Tracking #">
                            <button type="submit" class="btn btn-sm btn-block btn-primary mt-2">Save Details</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Destination Address</div>
                <div class="card-body">
                    <h6 class="font-weight-bold mb-1">
                        {{ $customerName }}
                        @if($isGuest) <span class="badge badge-secondary ml-1" style="font-size: 0.6rem;">GUEST</span> @endif
                    </h6>
                    <p class="text-muted small mb-3">{{ $customerEmail }}</p>
                    <hr>
                    <p class="mb-0 text-dark">
                        <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                        {{-- Handle guest address fields on order OR a related address model --}}
                        @if($order?->address)
                            {{ $order->address->street }}, {{ $order->address->city }}
                        @elseif($order?->shipping_address)
                            {{ $order->shipping_address }}
                        @else
                            <span class="text-muted italic">No address provided</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Right Column: Detailed Info --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Logistics Timeline</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="bg-light text-muted border-0 px-4" width="30%">Carrier</td>
                                    <td class="border-0 font-weight-bold text-uppercase">{{ $shipping->carrier ?? 'NOT SET' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Tracking Number</td>
                                    <td>
                                        <code class="h6">{{ $shipping->tracking_number ?? 'NOT_GENERATED' }}</code>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Dispatched At</td>
                                    <td>{{ $shipping->shipped_at ? $shipping->shipped_at->format('d M Y, h:i A') : '--' }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Delivery Confirmed</td>
                                    <td class="{{ $shipping->delivered_at ? 'text-success' : '' }} font-weight-bold">
                                        {{ $shipping->delivered_at ? $shipping->delivered_at->format('d M Y, h:i A') : 'Pending Arrival' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Contents Quick View --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Package Contents</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if($order && $order->orderItems->count() > 0)
                            @foreach($order->orderItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-box text-muted mr-2 small"></i>
                                        {{ $item->part?->part_name ?? 'Deleted Product' }}
                                    </span>
                                    <span class="badge badge-light border">Qty: {{ $item->quantity }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item text-center text-muted py-4">
                                <i class="fas fa-ghost mr-2"></i> No item data found for this order.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection