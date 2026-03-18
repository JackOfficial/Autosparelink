@extends('admin.layouts.app')

@push('styles')
<style>
    /* Improved Blinking for Call Requests */
    .blink_me { animation: blinker 0.8s cubic-bezier(.45,.05,.55,.95) infinite; }
    @keyframes blinker { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }

    .card { border-radius: 12px; border: none; }
    .card-header { font-weight: 700 !important; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05rem; padding: 1.25rem; border-bottom: 1px solid rgba(0,0,0,.05); }
    
    /* Order Timeline/Status Colors */
    .status-control-card { background: linear-gradient(45deg, #4e73df, #224abe); color: white; }
    .status-select { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 8px; font-weight: bold; }
    .status-select option { color: #333; }

    /* Custom labels */
    .info-label { font-size: 0.7rem; text-uppercase; color: #95aac9; font-weight: 600; margin-bottom: 2px; display: block; }
    .info-value { font-size: 0.95rem; color: #12263f; font-weight: 500; }
    
    .table thead th { background-color: #f9fbfd; text-transform: uppercase; font-size: 0.7rem; letter-spacing: .02em; color: #95aac9; border-top: none; }
    .total-row { background-color: #f9fbfd; }
</style>
@endpush

@section('content')
@php
    // Logic to handle Guest vs Registered User
    $customerName = $order->user->name ?? $order->guest_name ?? 'Guest Customer';
    $customerEmail = $order->user->email ?? $order->guest_email ?? 'No email provided';
    $initial = strtoupper(substr($customerName, 0, 1));
@endphp

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
                </ol>
            </nav>
            <h2 class="h3 mb-0 font-weight-bold">Order Details</h2>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-white border shadow-sm mr-2">
                <i class="fas fa-chevron-left mr-1"></i> Back to List
            </a>
            <button onclick="window.print()" class="btn btn-white border shadow-sm mr-2">
                <i class="fas fa-print mr-1"></i> Print
            </button>
            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-edit mr-1"></i> Edit Order
            </a>
        </div>
    </div>

    @if($order->status === 'callback_requested')
        <div class="alert alert-danger blink_me d-flex align-items-center mb-4 shadow-sm border-0" style="border-radius: 12px;">
            <div class="bg-white rounded-circle p-2 mr-3 text-danger">
                <i class="fas fa-phone-alt fa-lg"></i>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold">Urgent Call Requested!</h6>
                <span>The customer needs a callback at <strong>{{ $order->address->phone }}</strong> regarding this order.</span>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Items Table --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <i class="fas fa-shopping-cart mr-2 text-primary"></i> Order Items
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="px-4">Description</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Unit Price</th>
                                    <th class="text-right px-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td class="px-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 mr-3">
                                                    <i class="fas fa-tools text-muted"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block font-weight-bold text-dark">{{ $item->part->part_name }}</span>
                                                    <small class="text-muted">SKU: {{ $item->part->sku ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center font-weight-bold">{{ $item->quantity }}</td>
                                        <td class="text-right text-muted">{{ number_format($item->unit_price) }} RWF</td>
                                        <td class="text-right px-4 font-weight-bold text-dark">{{ number_format($item->quantity * $item->unit_price) }} RWF</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="total-row">
                                <tr>
                                    <td colspan="3" class="text-right text-muted py-3">Subtotal:</td>
                                    <td class="text-right px-4 py-3">{{ number_format($order->total_amount) }} RWF</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right h5 font-weight-bold py-3 border-0">Total Amount:</td>
                                    <td class="text-right px-4 h5 font-weight-bold text-primary py-3 border-0">{{ number_format($order->total_amount) }} RWF</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Payment --}}
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4 h-100">
                        <div class="card-header bg-white d-flex justify-content-between">
                            <span>Payment Info</span>
                            @php $isPaid = $order->payment && $order->payment->status === 'completed'; @endphp
                            <span class="badge {{ $isPaid ? 'badge-success' : 'badge-warning' }}">
                                {{ $isPaid ? 'Completed' : 'Pending' }}
                            </span>
                        </div>
                        <div class="card-body">
                            @if($order->payment)
                                <div class="mb-3">
                                    <span class="info-label">Transaction ID</span>
                                    <span class="info-value text-uppercase">{{ $order->payment->transaction_id ?? 'Manual Entry' }}</span>
                                </div>
                                <div class="mb-3">
                                    <span class="info-label">Payment Method</span>
                                    <span class="info-value"><i class="fas fa-credit-card mr-1 text-muted"></i> {{ strtoupper($order->payment->method) }}</span>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-exclamation-circle text-warning fa-2x mb-2"></i>
                                    <p class="text-muted small">No payment data found.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4 h-100">
                        <div class="card-header bg-white">Logistics & Shipping</div>
                        <div class="card-body">
                            @if($order->shipping)
                                <div class="mb-3">
                                    <span class="info-label">Carrier</span>
                                    <span class="info-value font-weight-bold text-primary">{{ $order->shipping->carrier }}</span>
                                </div>
                                <div>
                                    <span class="info-label">Tracking Number</span>
                                    <code class="bg-light p-1 rounded">{{ $order->shipping->tracking_number }}</code>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-truck-loading text-light fa-2x mb-2"></i>
                                    <p class="text-muted small font-italic">Logistics not yet assigned.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Status Quick Update --}}
            <div class="card shadow-sm status-control-card mb-4 border-0">
                <div class="card-body text-center p-4">
                    <h6 class="text-uppercase mb-3 font-weight-bold" style="opacity: 0.8; font-size: 0.7rem;">Update Order Status</h6>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-control form-control-lg status-select shadow-none mb-3" onchange="this.form.submit()">
                            @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'callback_requested'] as $stat)
                                <option value="{{ $stat }}" {{ $order->status == $stat ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $stat)) }}</option>
                            @endforeach
                        </select>
                        <p class="small mb-0" style="opacity: 0.7;">
                            <i class="fas fa-info-circle mr-1"></i> Notifications are sent instantly.
                        </p>
                    </form>
                </div>
            </div>

            {{-- Customer Information (Null-Safe) --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    Customer Data
                    @if(!$order->user_id)
                        <span class="badge badge-secondary badge-pill" style="font-size: 0.6rem;">GUEST</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3 shadow-sm" style="width: 45px; height: 45px;">
                            <span class="h5 mb-0">{{ $initial }}</span>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark">{{ $customerName }}</h6>
                            <span class="text-muted small">{{ $customerEmail }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <span class="info-label">Shipping Address</span>
                        <address class="info-value mb-0">
                            <strong>{{ $order->address->full_name }}</strong><br>
                            {{ $order->address->street_address }}<br>
                            {{ $order->address->city }}, {{ $order->address->country }}
                        </address>
                    </div>
                    <div>
                        <span class="info-label">Contact Phone</span>
                        <a href="tel:{{ $order->address->phone }}" class="info-value font-weight-bold text-primary">
                            <i class="fas fa-phone-alt mr-1 small"></i> {{ $order->address->phone }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection