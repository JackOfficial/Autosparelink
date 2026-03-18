@extends('admin.layouts.app')

@push('styles')
<style>
    .blink_me { animation: blinker 1.5s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.3; } }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Order Management</h2>
        <span class="badge badge-info">{{ $orders->total() }} Total Orders</span>
    </div>

    @if($orders->isEmpty())
        <div class="card card-body text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">No orders have been placed yet.</p>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Order ID</th>
                            <th class="border-0">Customer</th>
                            <th class="border-0">Date & Time</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Total Amount</th>
                            <th class="border-0 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="font-weight-bold">#{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-dark">
                                            {{ $order->user->name ?? $order->guest_name ?? 'Guest' }}
                                        </span>
                                        <small class="text-muted">
                                            {{ $order->user->email ?? $order->guest_email ?? 'N/A' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div x-data="{ 
                                        utcTime: '{{ $order->created_at->toIso8601String() }}',
                                        formatDate(utc) {
                                            return new Date(utc).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                                        },
                                        formatTime(utc) {
                                            return new Date(utc).toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false });
                                        }
                                    }">
                                        <span class="text-dark" x-text="formatDate(utcTime)"></span><br>
                                        <small class="text-muted font-weight-bold" x-text="formatTime(utcTime)"></small>
                                    </div>
                                </td>
                                <td>
                                    @if($order->status === 'callback_requested')
                                        <span class="badge badge-danger blink_me py-2 px-3">
                                            <i class="fas fa-phone-alt mr-1"></i> Call Requested
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge badge-warning py-2 px-3">Pending</span>
                                    @elseif($order->status === 'processing')
                                        <span class="badge badge-primary py-2 px-3">Processing</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="badge badge-info py-2 px-3">Shipped</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="badge badge-success py-2 px-3">Delivered</span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="badge badge-danger py-2 px-3">Cancelled</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ number_format($order->total_amount) }} RWF</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection