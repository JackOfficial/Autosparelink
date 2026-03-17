@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Payment Transactions</h2>
        <div class="text-muted">
            Total Revenue this month: <span class="font-weight-bold text-dark">{{ number_format($payments->sum('amount')) }} RWF</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem;">
                        <tr>
                            <th class="border-0 px-4">Ref #</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Order</th>
                            <th class="border-0">Amount</th>
                            <th class="border-0">Method</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Date</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="px-4 font-weight-bold">#{{ $payment->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark">{{ $payment->order->user->name }}</span>
                                        <small class="text-muted">{{ $payment->order->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-primary">
                                        Order #{{ $payment->order->id }}
                                    </a>
                                </td>
                                <td class="font-weight-bold text-dark">
                                    {{ number_format($payment->amount) }} RWF
                                </td>
                                <td>
                                    <span class="badge badge-light border py-1 px-2">
                                        <i class="fas fa-credit-card mr-1 small"></i> {{ strtoupper($payment->method) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'successful' => 'badge-success',
                                            'pending'    => 'badge-warning',
                                            'processing' => 'badge-info',
                                            'failed'     => 'badge-danger',
                                            'refunded'   => 'badge-dark'
                                        ][$payment->status] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} py-2 px-3">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    {{ $payment->created_at->format('d M, Y') }}
                                </td>
                                <td class="text-center px-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-info" title="View Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-outline-warning" title="Edit Status">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record? This cannot be undone.')" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-3x mb-3 d-block"></i>
                                    No payment transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection