@extends('layouts.dashboard')

@section('title', 'My Orders')

@section('content')
<div class="container py-4 py-lg-5" x-data="{ showFilters: false }">
    
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Order History</h2>
            <p class="text-muted small mb-0">Track and manage your recent purchases and service requests.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button @click="showFilters = !showFilters" class="btn btn-light rounded-pill px-3 border shadow-sm">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            <a href="/" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                New Order
            </a>
        </div>
    </div>

    {{-- Alpine.js Filter Bar --}}
    <div x-show="showFilters" x-collapse x-cloak class="mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-light">
            <form action="{{ route('user.orders.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm border-0 rounded-3" placeholder="Search Order # or Part Name...">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm border-0 rounded-3">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100 rounded-3">Apply</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Order Info</th>
                        <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Items/Service</th>
                        <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Amount</th>
                        <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end small text-uppercase fw-bold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr x-data="{ 
                            copied: false, 
                            copyToClipboard() { 
                                navigator.clipboard.writeText('#{{ $order->order_number }}');
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            } 
                        }">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center">
                                            #{{ $order->order_number }}
                                            <button @click="copyToClipboard" class="btn btn-link p-0 ms-2 text-muted" title="Copy Order ID">
                                                <i class="fas" :class="copied ? 'fa-check text-success' : 'fa-copy'"></i>
                                            </button>
                                        </div>
                                        <div class="small text-muted">{{ $order->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="small fw-medium text-dark">{{ Str::limit($order->item_name ?? 'Multiple Items', 35) }}</div>
                                <div class="text-muted" style="font-size: 12px;">{{ $order->category ?? 'General' }}</div>
                            </td>
                            <td class="py-3 text-center fw-bold text-dark">
                                RWF {{ number_format($order->total_amount, 0) }}
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning-subtle text-warning border-warning',
                                        'completed' => 'bg-success-subtle text-success border-success',
                                        'processing' => 'bg-primary-subtle text-primary border-primary',
                                        'cancelled' => 'bg-danger-subtle text-danger border-danger',
                                    ][$order->status] ?? 'bg-light text-muted';
                                @endphp
                                <span class="badge {{ $statusClass }} border rounded-pill px-3 py-2 fw-normal text-capitalize">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                                        <li><a class="dropdown-item py-2" href="{{ route('user.orders.show', $order->id) }}"><i class="fas fa-eye me-2 text-primary"></i> View Details</a></li>
                                        <li><a class="dropdown-item py-2" href="{{ route('user.tickets.create', ['order_id' => $order->id]) }}"><i class="fas fa-headset me-2 text-info"></i> Support Ticket</a></li>
                                        @if($order->status == 'pending')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('user.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Cancel this order?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-times me-2"></i> Cancel Order</button>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="fas fa-box-open fa-3x"></i>
                                </div>
                                <h6 class="text-muted">No orders found.</h6>
                                <p class="small text-muted">When you place an order, it will appear here.</p>
                                <a href="/" class="btn btn-primary btn-sm rounded-pill px-4 mt-2">Start Shopping</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .ls-1 { letter-spacing: 0.5px; }
    .table thead th { font-size: 11px; letter-spacing: 0.5px; }
    .rounded-4 { border-radius: 1rem !important; }
    .badge { font-size: 11px; }
    
    /* Subtly highlight rows on hover */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,0.01);
    }
    
    /* Responsive adjustment */
    @media (max-width: 768px) {
        .table-responsive { border: 0; }
    }
</style>
@endsection