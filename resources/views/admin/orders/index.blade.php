@extends('admin.layouts.app')

@push('styles')
<style>
    /* Urgent Alert Animation */
    .blink_me { animation: blinker 1.5s cubic-bezier(.4, 0, .6, 1) infinite; }
    @keyframes blinker { 50% { opacity: 0.4; } }

    :root {
        --primary-soft: #e7f5ff; --primary-deep: #1971c2;
        --success-soft: #ebfbee; --success-deep: #2b8a3e;
        --warning-soft: #fff9db; --warning-deep: #f08c00;
        --danger-soft: #fff5f5; --danger-deep: #c92a2a;
        --purple-soft: #f3f0ff; --purple-deep: #6741d9;
        --info-soft: #e3fafc; --info-deep: #0c8599;
    }

    .card { border-radius: 12px; transition: all 0.3s ease; border: none !important; }
    .shadow-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }

    /* Stat Card Specifics */
    .stat-icon { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }

    /* Filter Bar Styling */
    .filter-pill {
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid #e9ecef;
        background: white;
        color: #495057;
        text-transform: uppercase;
    }
    .filter-pill.active {
        background: var(--primary-deep);
        color: white;
        border-color: var(--primary-deep);
    }

    /* User Profile */
    .avatar-wrapper { width: 42px; height: 42px; border-radius: 10px; overflow: hidden; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .avatar-img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-initials { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--primary-deep); background: var(--primary-soft); }

    /* Badges */
    .status-pill { font-size: 0.65rem; font-weight: 800; padding: 5px 12px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.02em; }
    .user-type-tag { font-size: 0.6rem; padding: 1px 6px; border-radius: 4px; font-weight: 800; margin-left: 5px; }
    .tag-member { background: var(--primary-soft); color: var(--primary-deep); }
    .tag-guest { background: #f1f3f5; color: #6c757d; }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" x-data="{ 
    search: '',
    statusFilter: 'all',
    showRow(rowText, rowStatus) {
        const matchesSearch = rowText.toLowerCase().includes(this.search.toLowerCase());
        const matchesStatus = this.statusFilter === 'all' || rowStatus === this.statusFilter;
        return matchesSearch && matchesStatus;
    }
}">
    
    <div class="row align-items-center mb-4">
        <div class="col-lg-6">
            <h1 class="h3 font-weight-bold text-dark mb-1">Order Pipeline</h1>
            <p class="text-muted small">Manage lifecycle from placement to customer acceptance.</p>
        </div>
        <div class="col-lg-6 text-lg-right">
            <div class="d-inline-flex bg-white p-1 shadow-sm rounded-lg border">
                <div class="input-group" style="width: 300px;">
                    <span class="input-group-text border-0 bg-transparent"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" x-model="search" class="form-control border-0 shadow-none bg-transparent" placeholder="ID, Name or Email...">
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Total --}}
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm p-3 shadow-hover bg-white h-100" @click="statusFilter = 'all'" style="cursor: pointer;">
                <div class="text-muted small font-weight-bold mb-2">TOTAL</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold mb-0">{{ $orders->total() }}</h4>
                    <div class="stat-icon bg-light text-secondary"><i class="fas fa-layer-group"></i></div>
                </div>
            </div>
        </div>
        {{-- Processing --}}
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm p-3 shadow-hover bg-white h-100" @click="statusFilter = 'processing'" style="cursor: pointer;">
                <div class="text-muted small font-weight-bold mb-2">PROCESSING</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold mb-0 text-primary">{{ $orders->where('status', 'processing')->count() }}</h4>
                    <div class="stat-icon bg-primary-soft text-primary"><i class="fas fa-sync fa-spin"></i></div>
                </div>
            </div>
        </div>
        {{-- Shipped --}}
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm p-3 shadow-hover bg-white h-100" @click="statusFilter = 'shipped'" style="cursor: pointer;">
                <div class="text-muted small font-weight-bold mb-2">SHIPPED</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold mb-0" style="color: var(--purple-deep);">{{ $orders->where('status', 'shipped')->count() }}</h4>
                    <div class="stat-icon bg-purple-soft" style="color: var(--purple-deep);"><i class="fas fa-truck"></i></div>
                </div>
            </div>
        </div>
        {{-- Delivered (Received but not accepted yet) --}}
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm p-3 shadow-hover bg-white h-100" @click="statusFilter = 'delivered'" style="cursor: pointer;">
                <div class="text-muted small font-weight-bold mb-2">DELIVERED</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold mb-0" style="color: var(--info-deep);">{{ $orders->where('status', 'delivered')->count() }}</h4>
                    <div class="stat-icon bg-info-soft text-info"><i class="fas fa-box-open"></i></div>
                </div>
            </div>
        </div>
        {{-- Completed (Accepted) --}}
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm p-3 shadow-hover bg-white h-100" @click="statusFilter = 'completed'" style="cursor: pointer;">
                <div class="text-muted small font-weight-bold mb-2">COMPLETED</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold mb-0 text-success">{{ $orders->where('status', 'completed')->count() }}</h4>
                    <div class="stat-icon bg-success-soft text-success"><i class="fas fa-check-double"></i></div>
                </div>
            </div>
        </div>
        {{-- Callbacks --}}
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card shadow-sm p-3 shadow-hover bg-white h-100" @click="statusFilter = 'callback_requested'" style="cursor: pointer;">
                <div class="text-muted small font-weight-bold mb-2">CALLBACKS</div>
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="font-weight-bold mb-0 text-danger">{{ $orders->where('status', 'callback_requested')->count() }}</h4>
                    <div class="stat-icon bg-danger-soft text-danger blink_me"><i class="fas fa-phone-alt"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap mb-4" style="gap: 10px;">
        <button class="filter-pill" :class="statusFilter === 'all' ? 'active' : ''" @click="statusFilter = 'all'">All</button>
        <button class="filter-pill" :class="statusFilter === 'pending' ? 'active' : ''" @click="statusFilter = 'pending'">Pending</button>
        <button class="filter-pill" :class="statusFilter === 'processing' ? 'active' : ''" @click="statusFilter = 'processing'">Processing</button>
        <button class="filter-pill" :class="statusFilter === 'shipped' ? 'active' : ''" @click="statusFilter = 'shipped'">Shipped</button>
        <button class="filter-pill" :class="statusFilter === 'delivered' ? 'active' : ''" @click="statusFilter = 'delivered'">Delivered</button>
        <button class="filter-pill" :class="statusFilter === 'completed' ? 'active' : ''" @click="statusFilter = 'completed'">Completed</button>
        <button class="filter-pill" :class="statusFilter === 'callback_requested' ? 'active' : ''" @click="statusFilter = 'callback_requested'">Callbacks</button>
        <button class="filter-pill" :class="statusFilter === 'cancelled' ? 'active' : ''" @click="statusFilter = 'cancelled'">Cancelled</button>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm py-5 text-center bg-white">
            <i class="fas fa-inbox fa-4x text-light mb-3"></i>
            <h5 class="text-secondary">No orders currently in pipeline</h5>
        </div>
    @else
        <div class="card shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Reference</th>
                            <th>Customer</th>
                            <th>Pipeline Stage</th>
                            <th>Grand Total</th>
                            <th class="text-center px-4">Management</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($orders as $order)
                            @php
                                $name = $order->user->name ?? $order->guest_name ?? 'Guest';
                                $email = $order->user->email ?? $order->guest_email ?? 'No email';
                                $searchData = "#{$order->id} {$name} {$email}";
                                
                                $style = match($order->status) {
                                    'pending' => 'background: var(--warning-soft); color: var(--warning-deep);',
                                    'processing' => 'background: var(--primary-soft); color: var(--primary-deep);',
                                    'shipped' => 'background: var(--purple-soft); color: var(--purple-deep);',
                                    'delivered' => 'background: var(--info-soft); color: var(--info-deep);',
                                    'completed' => 'background: var(--success-soft); color: var(--success-deep);',
                                    'cancelled' => 'background: var(--danger-soft); color: var(--danger-deep);',
                                    'callback_requested' => 'background: #7048e8; color: #fff;',
                                    default => 'background: #f1f3f5; color: #495057;'
                                };
                            @endphp
                            <tr x-show="showRow('{{ addslashes($searchData) }}', '{{ $order->status }}')" x-transition x-cloak>
                                <td class="px-4">
                                    <div class="font-weight-bold text-dark">#{{ $order->id }}</div>
                                    <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('M d, H:i') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-wrapper mr-3">
                                            @if($order->user && $order->user->photo)
                                                <img src="{{ asset('storage/' . $order->user->photo) }}" class="avatar-img">
                                            @else
                                                <div class="avatar-initials">{{ strtoupper(substr($name, 0, 1)) }}</div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center">
                                                <span class="font-weight-bold text-dark" style="font-size: 0.85rem;">{{ $name }}</span>
                                                <span class="user-type-tag {{ $order->user_id ? 'tag-member' : 'tag-guest' }}">
                                                    {{ $order->user_id ? 'MEMBER' : 'GUEST' }}
                                                </span>
                                            </div>
                                            <div class="text-muted small">{{ $email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $order->status === 'callback_requested' ? 'blink_me' : '' }}" style="{{ $style }}">
                                        {{ str_replace('_', ' ', strtoupper($order->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        {{ number_format($order->total_amount) }} 
                                        <span class="small text-muted font-weight-normal">RWF</span>
                                    </div>
                                </td>
                                <td class="text-center px-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary border-0 font-weight-bold">
                                        Manage <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white border-top-0 py-3">
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif
</div>
@endsection