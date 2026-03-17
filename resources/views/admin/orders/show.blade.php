@extends('admin.layouts.app')

@push('styles')
<style>
    .blink_me { animation: blinker 1.5s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.3; } }
    .card-header { font-weight: bold; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05rem; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Order #{{ $order->id }}</h2>
            <span class="text-muted">Placed on {{ $order->created_at->format('d M Y, H:i') }}</span>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i> Edit Details
            </a>
        </div>
    </div>

    @if($order->status === 'callback_requested')
        <div class="alert alert-danger blink_me d-flex align-items-center mb-4 shadow-sm">
            <i class="fas fa-phone-alt fa-2x mr-3"></i>
            <div>
                <strong>Action Required:</strong> This customer has requested a callback for this order. 
                Contact them at <strong>{{ $order->address->phone }}</strong>.
            </div>
        </div>
    @endif

    <div class="row">
        {{-- Order Status Quick Control --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-left-primary">
                <div class="card-header bg-white text-primary">Current Status</div>
                <div class="card-body text-center">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <select name="status" class="form-control form-control-lg text-center" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="callback_requested" {{ $order->status == 'callback_requested' ? 'selected' : '' }}>Callback Requested</option>
                            </select>
                        </div>
                        <small class="text-muted font-italic">Changing this will notify the customer (if configured).</small>
                    </form>
                </div>
            </div>

            {{-- Customer Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">Customer Details</div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-3 mr-3"><i class="fas fa-user text-muted"></i></div>
                        <div>
                            <h6 class="mb-0">{{ $order->user->name }}</h6>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">Shipping To</div>
                <div class="card-body">
                    <address class="mb-0">
                        <strong>{{ $order->address->full_name }}</strong><br>
                        {{ $order->address->street_address }}<br>
                        {{ $order->address->city }}, {{ $order->address->country }}<br>
                        <hr>
                        <i class="fas fa-phone mr-1"></i> {{ $order->address->phone }}
                    </address>
                </div>
            </div>
        </div>

        {{-- Order Items Table --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">Purchased Spare Parts</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th>Part Description</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold">{{ $item->part->part_name }}</span>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">{{ number_format($item->unit_price) }} RWF</td>
                                        <td class="text-right font-weight-bold">{{ number_format($item->quantity * $item->unit_price) }} RWF</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="3" class="text-right h5">Total Amount:</th>
                                    <th class="text-right h5 text-primary">{{ number_format($order->total_amount) }} RWF</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Payment Status --}}
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            Payment info
                            @if($order->payment && $order->payment->status === 'completed')
                                <span class="badge badge-success">Paid</span>
                            @else
                                <span class="badge badge-warning">Unpaid</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($order->payment)
                                <p class="mb-1"><strong>Method:</strong> {{ strtoupper($order->payment->method) }}</p>
                                <p class="mb-1"><strong>Amount:</strong> {{ number_format($order->payment->amount) }} RWF</p>
                            @else
                                <p class="text-muted mb-0">No payment transaction recorded.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Shipping Status --}}
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">Logistics</div>
                        <div class="card-body">
                            @if($order->shipping)
                                <p class="mb-1"><strong>Carrier:</strong> {{ $order->shipping->carrier }}</p>
                                <p class="mb-1"><strong>Tracking:</strong> <code>{{ $order->shipping->tracking_number }}</code></p>
                            @else
                                <p class="text-muted mb-0 font-italic">Shipping details not yet assigned.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection