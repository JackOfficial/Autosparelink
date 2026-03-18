@extends('admin.layouts.app')

{{-- @push('styles') --}}
<style>
    .blink_me { animation: blinker 1s linear infinite; }
    @keyframes blinker { 0% { opacity: 1.0; } 50% { opacity: 0.1; } 100% { opacity: 1.0; } }
    .user-badge { font-size: 0.7rem; text-transform: uppercase; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-block; }
    [x-cloak] { display: none !important; }
</style>
{{-- @endpush --}}

@section('content')
<div class="container-fluid py-4" x-data="{ 
    search: '',
    showRow(rowText) {
        return rowText.toLowerCase().includes(this.search.toLowerCase())
    }
}">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Order Management</h2>
        
        <div class="position-relative" style="width: 300px;">
            <span class="position-absolute" style="left: 10px; top: 8px;">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input 
                type="text" 
                x-model="search" 
                class="form-control pl-5 shadow-sm border-0" 
                placeholder="Search ID, Name or Email..."
                style="border-radius: 20px;"
            >
            <button x-show="search.length > 0" @click="search = ''" class="btn btn-link position-absolute text-muted" style="right: 5px; top: 0;" x-cloak>
                <i class="fas fa-times-circle"></i>
            </button>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card card-body text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <p class="text-muted">No orders have been placed yet.</p>
        </div>
    @else
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted">
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
                            @php
                                $searchText = "#{$order->id} " . ($order->user->name ?? $order->guest_name ?? '') . " " . ($order->user->email ?? $order->guest_email ?? '');
                            @endphp
                            <tr x-show="showRow('{{ addslashes($searchText) }}')" x-transition>
                                <td class="font-weight-bold text-primary">#{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-dark">
                                            {{ $order->user->name ?? $order->guest_name ?? 'Unknown' }}
                                        </span>
                                        <small class="text-muted">
                                            {{ $order->user->email ?? $order->guest_email ?? 'N/A' }}
                                        </small>
                                        
                                        @if($order->user_id)
                                            <span class="user-badge bg-success text-white">
                                                <i class="fas fa-user-check mr-1"></i> Auth
                                            </span>
                                        @else
                                            <span class="user-badge bg-light text-secondary border border-secondary">
                                                <i class="fas fa-user-secret mr-1"></i> Guest
                                            </span>
                                        @endif
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
                                        <span class="badge badge-danger blink_me py-2 px-3 shadow-sm">
                                            <i class="fas fa-phone-alt mr-1"></i> Call Requested
                                        </span>
                                    @else
                                        <span class="badge badge-secondary py-2 px-3">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                                <td class="font-weight-bold">{{ number_format($order->total_amount) }} RWF</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div x-show="search.length > 0 && document.querySelectorAll('tbody tr[style*=\'display: none\']').length === {{ count($orders) }}" class="p-5 text-center" x-cloak>
                <p class="text-muted">No matching orders found on this page.</p>
            </div>
            
            <div class="card-footer bg-white border-0">
                {{ $orders->links() }}
            </div>
        </div>
    @endif
</div>
@endsection