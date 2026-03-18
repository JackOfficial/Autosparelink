@extends('admin.layouts.app')

@push('styles')
<style>
    /* Improved Blinking for Call Requests */
    .blink_me { animation: blinker 0.8s cubic-bezier(.45,.05,.55,.95) infinite; }
    @keyframes blinker { 0% { opacity: 1; } 50% { opacity: 0.2; } 100% { opacity: 1; } }

    /* Custom Badges */
    .user-badge { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; padding: 2px 8px; border-radius: 50px; display: inline-flex; align-items: center; }
    .status-badge { font-size: 0.75rem; font-weight: 600; padding: 5px 12px; border-radius: 8px; border: 1px solid transparent; }
    
    /* Search Bar focus effect */
    .search-input:focus { box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15) !important; border: 1px solid #007bff !important; }
    
    /* Avatar Circle */
    .avatar-circle { width: 35px; height: 35px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #495057; }

    [x-cloak] { display: none !important; }
    .table-hover tbody tr:hover { background-color: rgba(0,123,255, 0.02); transition: 0.2s; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" x-data="{ 
    search: '',
    showRow(rowText) {
        return rowText.toLowerCase().includes(this.search.toLowerCase())
    }
}">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="h3 font-weight-bold mb-0 text-dark">Order Management</h2>
            <p class="text-muted small mb-0">Manage and track your SMM panel orders in real-time.</p>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
            <div class="position-relative" style="width: 100%; max-width: 350px;">
                <span class="position-absolute" style="left: 15px; top: 10px; z-index: 5;">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input 
                    type="text" 
                    x-model="search" 
                    class="form-control pl-5 shadow-sm border-light search-input" 
                    placeholder="Search by ID, Name, or Email..."
                    style="border-radius: 12px; height: 45px;"
                >
                <button x-show="search.length > 0" @click="search = ''" class="btn btn-link position-absolute text-muted" style="right: 10px; top: 5px;" x-cloak>
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm py-5 text-center">
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-receipt fa-4x text-light"></i>
                </div>
                <h4 class="text-secondary">No Orders Found</h4>
                <p class="text-muted">Wait for customers to place orders or check your filters.</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3 border-0 text-uppercase small font-weight-bold text-muted">ID</th>
                            <th class="py-3 border-0 text-uppercase small font-weight-bold text-muted">Customer Details</th>
                            <th class="py-3 border-0 text-uppercase small font-weight-bold text-muted">Order Date</th>
                            <th class="py-3 border-0 text-uppercase small font-weight-bold text-muted">Status</th>
                            <th class="py-3 border-0 text-uppercase small font-weight-bold text-muted">Amount</th>
                            <th class="px-4 py-3 border-0 text-center text-uppercase small font-weight-bold text-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($orders as $order)
                            @php
                                $name = $order->user->name ?? $order->guest_name ?? 'Unknown';
                                $email = $order->user->email ?? $order->guest_email ?? 'N/A';
                                $searchText = "#{$order->id} {$name} {$email}";
                            @endphp
                            <tr x-show="showRow('{{ addslashes($searchText) }}')" x-transition>
                                <td class="px-4">
                                    <span class="badge badge-light text-primary font-weight-bold p-2">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-3">
                                            {{ substr($name, 0, 1) }}
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bold text-dark">{{ $name }}</span>
                                            <span class="text-muted small">{{ $email }}</span>
                                            
                                            @if($order->user_id)
                                                <span class="user-badge bg-info text-white mt-1">
                                                    <i class="fas fa-shield-alt mr-1" style="font-size: 8px;"></i> Member
                                                </span>
                                            @else
                                                <span class="user-badge bg-light text-secondary border border-secondary mt-1">
                                                    <i class="fas fa-shopping-cart mr-1" style="font-size: 8px;"></i> Guest
                                                </span>
                                            @endif
                                        </div>
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
                                        <div class="text-dark font-weight-bold" x-text="formatDate(utcTime)"></div>
                                        <div class="text-muted small" x-text="formatTime(utcTime)"></div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-warning-light text-warning border-warning',
                                            'delivered' => 'bg-success-light text-success border-success',
                                            'processing' => 'bg-primary-light text-primary border-primary',
                                            'cancelled' => 'bg-danger-light text-danger border-danger',
                                            'shipped' => 'bg-info-light text-info border-info'
                                        ];
                                        $class = $statusClasses[$order->status] ?? 'bg-light text-secondary';
                                    @endphp

                                    @if($order->status === 'callback_requested')
                                        <span class="status-badge bg-danger text-white blink_me">
                                            <i class="fas fa-phone-alt mr-1"></i> Urgent Call
                                        </span>
                                    @else
                                        <span class="status-badge {{ $class }} font-weight-bold">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="font-weight-bold text-dark">
                                    {{ number_format($order->total_amount) }} <span class="small text-muted">RWF</span>
                                </td>
                                <td class="px-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-white border shadow-sm px-3" style="border-radius: 8px;">
                                        <i class="fas fa-external-link-alt text-primary mr-1"></i> Details
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div x-show="search.length > 0 && document.querySelectorAll('tbody tr[style*=\'display: none\']').length === {{ count($orders) }}" class="p-5 text-center bg-white" x-cloak>
                <i class="fas fa-search fa-2x text-light mb-3"></i>
                <p class="text-muted">No orders match "<strong><span x-text="search"></span></strong>" on this page.</p>
            </div>

            <div class="card-footer bg-white border-top-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Showing page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}</span>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection