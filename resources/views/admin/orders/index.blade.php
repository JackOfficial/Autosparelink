@extends('admin.layouts.app')

@push('styles')
<style>
    /* Animation for urgent alerts */
    .blink_me { animation: blinker 1.5s cubic-bezier(.4, 0, .6, 1) infinite; }
    @keyframes blinker { 50% { opacity: 0.4; } }

    /* Modern UI Variables & Utility */
    :root {
        --primary-soft: #e7f5ff;
        --primary-deep: #1971c2;
        --success-soft: #ebfbee;
        --success-deep: #2b8a3e;
        --warning-soft: #fff9db;
        --warning-deep: #f08c00;
        --danger-soft: #fff5f5;
        --danger-deep: #c92a2a;
    }

    .card { border-radius: 12px; transition: all 0.3s ease; }
    .shadow-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }

    /* Avatar & Profile Styling */
    .avatar-wrapper { 
        width: 42px; 
        height: 42px; 
        position: relative; 
        border-radius: 10px; 
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .avatar-img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-initials { 
        width: 100%; 
        height: 100%; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-weight: 700; 
        color: var(--primary-deep);
        background: var(--primary-soft);
    }

    /* Status Badges */
    .status-pill {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
    }

    /* Table Improvements */
    .table thead th {
        background-color: #fcfcfd;
        color: #6c757d;
        font-weight: 600;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f3f5;
    }
    .table tbody tr td { border-bottom: 1px solid #f8f9fa; padding: 16px 12px; }

    /* Guest/Member Tag */
    .user-type-tag { font-size: 0.6rem; padding: 1px 6px; border-radius: 4px; font-weight: 800; }
    .tag-member { background: #e7f5ff; color: #007bff; }
    .tag-guest { background: #f1f3f5; color: #6c757d; }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" x-data="{ 
    search: '',
    showRow(rowText) { return rowText.toLowerCase().includes(this.search.toLowerCase()) }
}">
    
    <div class="row align-items-center mb-4">
        <div class="col-lg-6">
            <h1 class="h3 font-weight-bold text-dark mb-1">Order Pipeline</h1>
            <p class="text-muted small">Real-time overview of business transactions and fulfillment.</p>
        </div>
        <div class="col-lg-6">
            <div class="d-flex justify-content-lg-end align-items-center">
                <div class="input-group shadow-sm bg-white" style="border-radius: 10px; overflow: hidden; max-width: 350px;">
                    <span class="input-group-text border-0 bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" x-model="search" class="form-control border-0 shadow-none py-2" placeholder="Search client or ID...">
                    <button x-show="search.length > 0" @click="search = ''" class="btn btn-link text-muted border-0" x-cloak>
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 shadow-hover bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary-soft p-3 mr-3 text-primary"><i class="fas fa-shopping-bag fa-lg"></i></div>
                    <div>
                        <div class="text-muted small font-weight-bold uppercase">Total Orders</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $orders->total() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 shadow-hover bg-white">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning-soft p-3 mr-3 text-warning"><i class="fas fa-clock fa-lg"></i></div>
                    <div>
                        <div class="text-muted small font-weight-bold uppercase">Active Processing</div>
                        <div class="h5 mb-0 font-weight-bold">{{ $orders->where('status', 'processing')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm py-5 text-center">
            <i class="fas fa-folder-open fa-4x text-light mb-3"></i>
            <h5 class="text-secondary">No current orders</h5>
            <p class="text-muted small">New orders will pop up here as they arrive.</p>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">Order Details</th>
                            <th>Customer</th>
                            <th>Fulfillment Status</th>
                            <th>Amount</th>
                            <th class="text-center px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($orders as $order)
                            @php
                                $name = $order->user->name ?? $order->guest_name ?? 'Unknown';
                                $email = $order->user->email ?? $order->guest_email ?? 'N/A';
                                $searchText = "#{$order->id} {$name} {$email}";
                                
                                $statusStyle = match($order->status) {
                                    'pending' => 'background: var(--warning-soft); color: var(--warning-deep);',
                                    'delivered' => 'background: var(--success-soft); color: var(--success-deep);',
                                    'processing' => 'background: var(--primary-soft); color: var(--primary-deep);',
                                    'cancelled' => 'background: var(--danger-soft); color: var(--danger-deep);',
                                    'callback_requested' => 'background: #7048e8; color: #fff;',
                                    default => 'background: #f1f3f5; color: #495057;'
                                };
                            @endphp
                            <tr x-show="showRow('{{ addslashes($searchText) }}')" x-transition>
                                <td class="px-4">
                                    <div class="font-weight-bold text-dark mb-0">Order #{{ $order->id }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('M d, H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-wrapper mr-3">
                                            @if($order->user && $order->user->photo)
                                                <img src="{{ asset('storage/' . $order->user->photo) }}" class="avatar-img" alt="User Photo">
                                            @else
                                                <div class="avatar-initials">
                                                    {{ strtoupper(substr($name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center mb-0">
                                                <span class="font-weight-bold text-dark mr-2" style="font-size: 0.9rem;">{{ $name }}</span>
                                                @if($order->user_id)
                                                    <span class="user-type-tag tag-member">MEMBER</span>
                                                @else
                                                    <span class="user-type-tag tag-guest">GUEST</span>
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($order->status === 'callback_requested')
                                        <span class="status-pill blink_me" style="{{ $statusStyle }}">
                                            <i class="fas fa-phone-alt mr-2"></i> URGENT CALL
                                        </span>
                                    @else
                                        <span class="status-pill" style="{{ $statusStyle }}">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ number_format($order->total_amount) }} <span class="text-muted small">RWF</span></div>
                                </td>
                                <td class="px-4 text-center">
                                    <div class="dropdown">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-white border px-3 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                            Open <i class="fas fa-external-link-alt ml-1 small"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small font-italic">Showing {{ $orders->count() }} records on this page</div>
                    <div>{{ $orders->links('pagination::bootstrap-4') }}</div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection