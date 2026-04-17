@extends('layouts.dashboard')

@section('title', 'My Orders')

@section('content')
<div class="container py-4 py-lg-5" x-data="{ showFilters: false }">
    
    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">Order History</h2>
            <p class="text-muted small mb-0">Track and manage your spare parts orders and inspection status.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button @click="showFilters = !showFilters" class="btn btn-white border rounded-pill px-3 shadow-sm bg-white">
                <i class="fas fa-filter me-1 text-primary"></i> Filter
            </button>
            <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="fas fa-plus me-1"></i> New Order
            </a>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div x-show="showFilters" x-collapse x-cloak class="mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
            <form action="{{ route('user.orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="small fw-bold text-muted mb-1">Search</label>
                    <input type="text" name="search" class="form-control rounded-3 border-light-subtle" placeholder="Order number or part name...">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted mb-1">Status</label>
                    <select name="status" class="form-select rounded-3 border-light-subtle">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="delivered">Awaiting Inspection</option>
                        <option value="completed">Completed</option>
                        <option value="disputed">In Dispute</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-dark w-100 rounded-3">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light-subtle">
                    <tr>
                        <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Order Info</th>
                        <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Items</th>
                        <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Amount</th>
                        <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Status</th>
                        <th class="px-4 py-3 border-0 text-end small text-uppercase fw-bold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($orders as $order)
                        <tr x-data="{ 
                            copied: false, 
                            copyId() { 
                                navigator.clipboard.writeText('{{ $order->order_number }}');
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            } 
                        }">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded-3 p-2 me-3 d-none d-sm-block">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center">
                                            #{{ $order->order_number }}
                                            <i @click="copyId" 
                                               :class="copied ? 'fas fa-check text-success' : 'far fa-copy'" 
                                               class="ms-2 cursor-pointer small opacity-50" 
                                               style="cursor: pointer;"></i>
                                        </div>
                                        <div class="small text-muted">{{ $order->created_at->format('d M, Y \a\t H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @php $itemCount = $order->orderItems->count(); @endphp
                                <div class="small fw-bold text-dark">
                                    {{ Str::limit($order->orderItems->first()->part->part_name ?? 'Spare Parts', 30) }}
                                </div>
                                <div class="text-muted small">
                                    {{ $itemCount > 1 ? '+'.($itemCount-1).' other items' : 'Single Item' }}
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="fw-bold text-dark">RWF {{ number_format($order->total_amount, 0) }}</span>
                            </td>
                            <td class="py-3 text-center">
                                @php
                                    $statusConfig = [
                                        'pending'    => ['class' => 'bg-warning-subtle text-warning border-warning-subtle', 'icon' => 'fa-clock'],
                                        'processing' => ['class' => 'bg-primary-subtle text-primary border-primary-subtle', 'icon' => 'fa-cog fa-spin'],
                                        'delivered'  => ['class' => 'bg-info-subtle text-info border-info-subtle', 'icon' => 'fa-truck'],
                                        'completed'  => ['class' => 'bg-success-subtle text-success border-success-subtle', 'icon' => 'fa-check-double'],
                                        'disputed'   => ['class' => 'bg-danger-subtle text-danger border-danger-subtle', 'icon' => 'fa-exclamation-triangle'],
                                        'cancelled'  => ['class' => 'bg-secondary-subtle text-secondary border-secondary-subtle', 'icon' => 'fa-times'],
                                    ];
                                    $config = $statusConfig[$order->status] ?? ['class' => 'bg-light text-muted', 'icon' => 'fa-info-circle'];
                                @endphp
                                <span class="badge {{ $config['class'] }} border rounded-pill px-3 py-2 fw-medium">
                                    <i class="fas {{ $config['icon'] }} me-1 small"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-3 border shadow-sm px-3" type="button" data-bs-toggle="dropdown">
                                        Manage <i class="fas fa-chevron-down ms-1 small"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                                        <li><a class="dropdown-item py-2" href="{{ route('user.orders.show', $order->id) }}"><i class="fas fa-eye me-2 text-primary"></i> Order Details</a></li>
                                        <li><a class="dropdown-item py-2" href="#"><i class="fas fa-print me-2 text-muted"></i> Download Invoice</a></li>
                                        
                                        @if($order->status == 'delivered')
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item py-2 fw-bold text-success" href="{{ route('user.orders.show', $order->id) }}"><i class="fas fa-clipboard-check me-2"></i> Inspect Items</a></li>
                                        @endif

                                        @if($order->status == 'pending')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('user.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i> Cancel Order</button>
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
                                <div class="opacity-25 mb-3">
                                    <i class="fas fa-shopping-cart fa-4x"></i>
                                </div>
                                <h5 class="text-muted fw-bold">No orders yet</h5>
                                <p class="text-muted small">Your shopping history will appear here once you make a purchase.</p>
                                <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 mt-2">Start Shopping</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .table thead th { font-size: 0.75rem; letter-spacing: 0.05rem; }
    .rounded-4 { border-radius: 1.25rem !important; }
    .badge { font-size: 0.7rem; letter-spacing: 0.02rem; }
    .btn-white { background: #fff; }
    .table-hover tbody tr { transition: background-color 0.2s ease; }
    .table-hover tbody tr:hover { background-color: #fbfcfe; }
</style>
@endsection