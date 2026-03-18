@extends('admin.layouts.app')

@section('content')
@php
    // Guest-aware logic
    $customerName = $payment->order->guest_name ?? $payment->order->user->name ?? 'Guest Customer';
    $customerEmail = $payment->order->guest_email ?? $payment->order->user->email ?? 'N/A';
    $customerId = $payment->order->user->id ?? 'GUEST';
    $isGuest = !$payment->order->user_id;
@endphp

<div class="container-fluid py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <div class="d-flex align-items-center">
                <h2 class="h3 mb-0 mr-3">Payment Transaction</h2>
                @php
                    $statusClass = [
                        'successful' => 'badge-success',
                        'pending'    => 'badge-warning',
                        'failed'     => 'badge-danger',
                        'refunded'   => 'badge-dark'
                    ][$payment->status] ?? 'badge-secondary';
                @endphp
                <span class="badge {{ $statusClass }} px-3 py-2 text-uppercase">{{ $payment->status }}</span>
            </div>
            <span class="text-muted font-weight-bold">Reference: {{ $payment->transaction_reference ?? 'N/A' }}</span>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-white border shadow-sm mr-2">
                <i class="fas fa-arrow-left mr-1"></i> Back to Payments
            </a>
            <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-box mr-1"></i> View Related Order
            </a>
        </div>
    </div>  

    <div class="row">
        {{-- Payment Status Control --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-0" style="border-left: 4px solid #4e73df;">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Update Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <select name="status" class="form-control form-control-lg font-weight-bold" onchange="this.form.submit()">
                                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="successful" {{ $payment->status == 'successful' ? 'selected' : '' }}>Successful</option>
                                <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $payment->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <div class="p-3 bg-light rounded">
                            <p class="small text-muted mb-0">
                                <i class="fas fa-info-circle mr-1 text-primary"></i> 
                                Updating to <b>Successful</b> will automatically move Order #{{ $payment->order->id }} to <b>Processing</b>.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Customer Overview --}}
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Customer Information</div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-soft-primary rounded-circle p-3 mr-3 text-primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 font-weight-bold text-dark">
                                {{ $customerName }}
                                @if($isGuest) <span class="badge badge-secondary ml-1" style="font-size: 0.6rem;">GUEST</span> @endif
                            </h6>
                            <span class="text-muted small">{{ $customerEmail }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Account Type:</span>
                            <span class="font-weight-bold text-dark">{{ $isGuest ? 'Guest Checkout' : 'Registered Member' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Customer ID:</span>
                            <span class="font-weight-bold">#{{ $customerId }}</span>
                        </div>
                        @if($payment->order->phone)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phone:</span>
                            <span class="font-weight-bold">{{ $payment->order->phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold text-uppercase small d-flex justify-content-between">
                    Financial Summary
                    <span class="text-muted">#{{ $payment->id }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="bg-light text-muted border-0 px-4" width="30%">Order Linked</td>
                                    <td class="border-0"><a href="{{ route('admin.orders.show', $payment->order->id) }}" class="font-weight-bold text-primary">Order #{{ $payment->order->id }}</a></td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Amount Paid</td>
                                    <td class="h4 mb-0 text-success font-weight-bold">{{ number_format($payment->amount) }} RWF</td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Payment Method</td>
                                    <td>
                                        <span class="badge badge-light border py-2 px-3 text-uppercase">
                                            <i class="fas fa-university mr-2 text-muted"></i> {{ $payment->method }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Transaction Ref</td>
                                    <td><code class="text-primary font-weight-bold">{{ $payment->transaction_reference ?? 'INTERNAL_OR_PENDING' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Processed Date</td>
                                    <td>
                                        <span class="text-dark font-weight-bold">{{ $payment->created_at->format('d F Y') }}</span>
                                        <span class="text-muted ml-2">at {{ $payment->created_at->format('H:i A') }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-right px-4">
                    <button class="btn btn-sm btn-white border" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print Receipt
                    </button>
                </div>
            </div>

            {{-- Order Items Quick View --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Items Covered by this Payment</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-flush mb-0">
                            <thead class="bg-light small text-muted text-uppercase">
                                <tr>
                                    <th class="px-4 py-2 border-0">Item</th>
                                    <th class="text-center py-2 border-0">Qty</th>
                                    <th class="text-right px-4 py-2 border-0">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payment->order->orderItems as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <span class="font-weight-bold text-dark d-block">{{ $item->part->part_name }}</span>
                                            <small class="text-muted">Unit: {{ number_format($item->unit_price) }} RWF</small>
                                        </td>
                                        <td class="text-center py-3">{{ $item->quantity }}</td>
                                        <td class="text-right px-4 py-3 font-weight-bold text-dark">
                                            {{ number_format($item->unit_price * $item->quantity) }} RWF
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection