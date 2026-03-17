@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-0">Payment Transaction</h2>
            <span class="text-muted font-weight-bold">Reference: {{ $payment->transaction_reference ?? 'N/A' }}</span>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Payments
            </a>
            <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="btn btn-primary">
                <i class="fas fa-box mr-1"></i> View Related Order
            </a>
        </div>
    </div>  

    <div class="row">
        {{-- Payment Status Control --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-left-success">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Update Status</div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <select name="status" class="form-control form-control-lg" onchange="this.form.submit()">
                                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $payment->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="successful" {{ $payment->status == 'successful' ? 'selected' : '' }}>Successful</option>
                                <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $payment->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <p class="small text-muted mb-0"><i class="fas fa-info-circle mr-1"></i> Updating to <b>Successful</b> will automatically move the order to <b>Processing</b>.</p>
                    </form>
                </div>
            </div>

            {{-- Customer Overview --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Customer Information</div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-3 mr-3"><i class="fas fa-user text-muted"></i></div>
                        <div>
                            <h6 class="mb-0">{{ $payment->order->user->name }}</h6>
                            <span class="text-muted small">{{ $payment->order->user->email }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Customer ID:</span>
                            <span class="font-weight-bold">#{{ $payment->order->user->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Transaction Details --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Financial Summary</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="bg-light text-muted border-0 px-4" width="30%">Payment ID</td>
                                    <td class="border-0 font-weight-bold">#{{ $payment->id }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Order Linked</td>
                                    <td><a href="{{ route('admin.orders.show', $payment->order->id) }}" class="font-weight-bold text-primary">Order #{{ $payment->order->id }}</a></td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Amount Paid</td>
                                    <td class="h4 mb-0 text-dark font-weight-bold">{{ number_format($payment->amount) }} RWF</td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Payment Method</td>
                                    <td>
                                        <span class="badge badge-outline-secondary border py-1 px-3">
                                            <i class="fas fa-university mr-1"></i> {{ strtoupper($payment->method) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Transaction Ref</td>
                                    <td><code>{{ $payment->transaction_reference ?? 'INTERNAL_CASH_OR_PENDING' }}</code></td>
                                </tr>
                                <tr>
                                    <td class="bg-light text-muted px-4">Processed Date</td>
                                    <td>{{ $payment->created_at->format('d F Y \a\t H:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-right px-4">
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print Receipt
                    </button>
                </div>
            </div>

            {{-- Order Items Quick View --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white font-weight-bold text-uppercase small">Items Covered by this Payment</div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($payment->order->orderItems as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="font-weight-bold">{{ $item->part->part_name }}</span>
                                    <br><small class="text-muted">{{ number_format($item->unit_price) }} RWF x {{ $item->quantity }}</small>
                                </div>
                                <span class="font-weight-bold">{{ number_format($item->unit_price * $item->quantity) }} RWF</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection