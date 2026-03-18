@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 font-weight-bold mb-0">Payment Transactions</h2>
            <p class="text-muted small mb-0">Monitoring all financial activity across the platform.</p>
        </div>
        <div class="card border-0 shadow-sm px-3 py-2 bg-white">
            <span class="text-muted small">Total Revenue this month:</span>
            <span class="h5 font-weight-bold text-primary mb-0">{{ number_format($payments->sum('amount')) }} RWF</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle mr-3 fa-lg"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                        <tr>
                            <th class="border-0 px-4 py-3">Ref #</th>
                            <th class="border-0 py-3">Customer</th>
                            <th class="border-0 py-3">Order</th>
                            <th class="border-0 py-3">Amount</th>
                            <th class="border-0 py-3">Method</th>
                            <th class="border-0 py-3">Status</th>
                            <th class="border-0 py-3">Date</th>
                            <th class="border-0 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                // Bulletproof Customer Logic for Guests
                                $name = $payment->order->guest_name ?? $payment->order->user->name ?? 'Unknown';
                                $email = $payment->order->guest_email ?? $payment->order->user->email ?? 'N/A';
                                $isGuest = !$payment->order?->user_id;
                            @endphp
                            <tr>
                                <td class="px-4">
                                    <span class="text-dark font-weight-bold">#{{ $payment->id }}</span>
                                    <small class="d-block text-muted" style="font-size: 0.65rem;">TXN ID: {{ substr($payment->transaction_reference ?? 'MANUAL', 0, 10) }}...</small>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark font-weight-bold">
                                            {{ $name }}
                                            @if($isGuest) 
                                                <span class="badge badge-secondary p-1 ml-1" style="font-size: 0.5rem;">GUEST</span> 
                                            @endif
                                        </span>
                                        <small class="text-muted">{{ $email }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($payment->order)
                                        <a href="{{ route('admin.orders.show', $payment->order->id) }}" class="text-primary font-weight-bold">
                                            Order #{{ $payment->order->id }}
                                        </a>
                                    @else
                                        <span class="text-danger small font-italic"><i class="fas fa-trash-alt mr-1"></i> Deleted</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-dark">
                                    {{ number_format($payment->amount) }} <small>RWF</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border py-1 px-2 text-uppercase" style="font-size: 0.7rem;">
                                        <i class="fas fa-wallet mr-1 text-muted"></i> {{ $payment->method }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'successful' => 'badge-success',
                                            'completed'  => 'badge-success',
                                            'pending'    => 'badge-warning',
                                            'failed'     => 'badge-danger',
                                            'refunded'   => 'badge-dark'
                                        ][$payment->status] ?? 'badge-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} py-2 px-3" style="min-width: 85px;">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $payment->created_at->format('d M, Y') }}<br>
                                    <span style="font-size: 0.7rem;">{{ $payment->created_at->format('H:i') }}</span>
                                </td>
                                <td class="text-center px-4">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-sm btn-white border shadow-none" title="View Detail">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-white border shadow-none" data-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-muted"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                            <a class="dropdown-item" href="{{ route('admin.payments.edit', $payment->id) }}">
                                                <i class="fas fa-edit mr-2 text-warning"></i> Update Status
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger" onclick="return confirm('Archive this transaction?')">
                                                    <i class="fas fa-trash mr-2"></i> Delete Record
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0 font-weight-bold">No transactions recorded yet.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted small">Showing {{ $payments->count() }} of {{ $payments->total() }} results</span>
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection