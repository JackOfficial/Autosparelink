@extends('admin.layouts.app')

@push('styles')
<style>
    /* Professional Blinking - smoother transition */
    .blink_me { animation: blinker 1.2s cubic-bezier(.4, 0, .6, 1) infinite; }
    @keyframes blinker { 50% { opacity: 0.3; } }

    /* Modern Status Tints */
    .status-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
    }

    /* Refined Color Palette */
    .bg-pending { background-color: #fff9db; color: #f08c00; border-color: #ffe066; }
    .bg-delivered { background-color: #ebfbee; color: #2b8a3e; border-color: #b2f2bb; }
    .bg-processing { background-color: #e7f5ff; color: #1971c2; border-color: #a5d8ff; }
    .bg-cancelled { background-color: #fff5f5; color: #c92a2a; border-color: #ffc9c9; }
    .bg-shipped { background-color: #f3f0ff; color: #6741d9; border-color: #d0bfff; }
    .bg-callback { background-color: #fff0f6; color: #c2255c; border-color: #ffdeeb; }

    /* Layout Components */
    .search-input-wrapper {
        transition: all 0.3s ease;
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }
    .search-input-wrapper:focus-within {
        background: #fff;
        border-color: #4dabf7;
        box-shadow: 0 0 0 3px rgba(77, 171, 247, 0.15);
    }
    
    .table thead th {
        background-color: #f8f9fa;
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 1px;
        padding: 15px 20px;
    }

    .btn-action {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 10px;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
    }

    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" x-data="{ 
    search: '',
    showRow(rowText) {
        return rowText.toLowerCase().includes(this.search.toLowerCase())
    }
}">
    <div class="d-md-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 font-weight-bold text-dark mb-1">Orders Portal</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 small">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Order Management</li>
                </ol>
            </nav>
        </div>
        
        <div class="search-input-wrapper d-flex align-items-center px-3 py-1" style="width: 100%; max-width: 400px;">
            <i class="fas fa-search text-muted mr-2"></i>
            <input 
                type="text" 
                x-model="search" 
                class="form-control border-0 bg-transparent shadow-none py-2" 
                placeholder="Search orders, customers..."
            >
            <button x-show="search.length > 0" @click="search = ''" class="btn btn-link btn-sm text-muted p-0" x-cloak>
                <i class="fas fa-times-circle"></i>
            </button>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <div class="small text-muted mb-1 text-uppercase font-weight-bold">Total Volume</div>
                <div class="h5 mb-0 font-weight-bold text-dark">{{ $orders->total() }} Orders</div>
            </div>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm py-5">
            <div class="card-body text-center">
                <div class="mb-4">
                    <div class="bg-light d-inline-block p-4 rounded-circle">
                        <i class="fas fa-receipt fa-3x text-muted opacity-50"></i>
                    </div>
                </div>
                <h5 class="text-dark">No orders yet</h5>
                <p class="text-muted mx-auto" style="max-width: 300px;">When customers start purchasing SMM services, they will appear here in real-time.</p>
            </div>
        </div>
    @else
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="px-4">Ref ID</th>
                            <th>Client Info</th>
                            <th>Date / Time</th>
                            <th>Fulfillment</th>
                            <th>Total Revenue</th>
                            <th class="text-right px-4">Management</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($orders as $order)
                            @php
                                $name = $order->user->name ?? $order->guest_name ?? 'Unknown';
                                $email = $order->user->email ?? $order->guest_email ?? 'N/A';
                                $searchText = "#{$order->id} {$name} {$email}";
                                
                                $statusClass = match($order->status) {
                                    'pending' => 'bg-pending',
                                    'delivered' => 'bg-delivered',
                                    'processing' => 'bg-processing',
                                    'cancelled' => 'bg-cancelled',
                                    'shipped' => 'bg-shipped',
                                    'callback_requested' => 'bg-callback blink_me',
                                    default => 'bg-light'
                                };
                            @endphp
                            <tr x-show="showRow('{{ addslashes($searchText) }}')" x-transition>
                                <td class="px-4">
                                    <div class="font-weight-bold text-primary">#{{ $order->id }}</div>
                                    <span class="text-muted" style="font-size: 0.65rem;">SMM-{{ date('Y') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-3" style="background-color: {{ $order->user_id ? '#e7f5ff' : '#f8f9fa' }}">
                                            {{ substr($name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark mb-0" style="line-height: 1.2;">{{ $name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark mb-0 font-weight-500" style="font-size: 0.85rem;">
                                        {{ $order->created_at->format('d M, Y') }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $order->created_at->format('H:i') }} CAT
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        @if($order->status === 'callback_requested')
                                            <i class="fas fa-phone-alt mr-1 small"></i> Callback
                                        @else
                                            <span class="mr-1">●</span> {{ ucfirst($order->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        {{ number_format($order->total_amount) }} 
                                        <span class="small font-weight-normal text-muted">RWF</span>
                                    </div>
                                </td>
                                <td class="px-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-action bg-white border px-3 text-primary font-weight-bold">
                                        Manage <i class="fas fa-chevron-right ml-1 small"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div x-show="search.length > 0 && !Array.from($el.closest('.card').querySelectorAll('tbody tr')).some(tr => tr.style.display !== 'none')" class="p-5 text-center bg-white" x-cloak>
                <div class="text-muted small">No orders found matching your search.</div>
            </div>

            <div class="card-footer bg-white border-top-0 py-4">
                <div class="d-md-flex justify-content-between align-items-center">
                    <p class="text-muted small mb-md-0">
                        Displaying {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} transactions
                    </p>
                    <div class="pagination-modern">
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection