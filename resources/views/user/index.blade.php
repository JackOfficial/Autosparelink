@extends('layouts.dashboard')

@section('title', 'User Dashboard')

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 small">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">Hello, {{ $user->name }}!</h2>
            <p class="text-muted">Welcome back to your dashboard. Here is what's happening with your account.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-75">Total Orders</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-75">Total Spent</h6>
                    <h3 class="fw-bold mb-0">{{ number_format($stats['total_spent']) }} RWF</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-75">Open Tickets</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['open_tickets'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 {{ $stats['pending_tickets'] > 0 ? 'bg-warning text-dark' : 'bg-white text-muted border' }} h-100">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-75">Needs Your Reply</h6>
                    <h3 class="fw-bold mb-0">{{ $stats['pending_tickets'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Orders Table --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('user.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-muted">
                                <tr class="small text-uppercase">
                                    <th class="border-0 px-4">Order #</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-end px-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allOrders as $order)
                                    <tr>
                                        <td class="px-4 align-middle fw-bold">#{{ $order->order_number ?? $order->id }}</td>
                                        <td class="align-middle small">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="align-middle text-center">
                                            @php
                                                $statusColor = match($order->status) {
                                                    'completed' => 'bg-success',
                                                    'pending' => 'bg-warning text-dark',
                                                    'processing' => 'bg-info text-white',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $statusColor }} px-3">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end px-4 fw-bold text-primary">
                                            {{ number_format($order->total_amount ?? $order->grand_total) }} RWF
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Pagination for Orders --}}
                <div class="card-footer bg-white border-0 px-4 pb-4">
                    {{ $allOrders->appends(['tickets_page' => $tickets->currentPage()])->links() }}
                </div>
            </div>

            {{-- Support Tickets Table --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Support Tickets</h5>
                    <a href="{{ route('user.tickets.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">+ New Ticket</a>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="px-4 align-middle">
                                            <div class="fw-bold mb-0 text-truncate" style="max-width: 250px;">{{ $ticket->subject }}</div>
                                            <small class="text-muted">#TK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</small>
                                        </td>
                                        <td class="align-middle text-center">
                                            @php
                                                $ticketStatus = match($ticket->status) {
                                                    'pending' => 'bg-warning text-dark',
                                                    'replied' => 'bg-info text-white',
                                                    'open' => 'bg-success',
                                                    default => 'bg-light border text-muted'
                                                };
                                            @endphp
                                            <span class="badge rounded-pill {{ $ticketStatus }} px-2">
                                                {{ $ticket->status == 'pending' ? 'Waiting' : ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end px-4">
                                            <a href="{{ route('user.tickets.show', $ticket) }}" class="btn btn-sm btn-light border rounded-pill px-3">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">No support tickets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- Pagination for Tickets --}}
                <div class="card-footer bg-white border-0 px-4 pb-4">
                    {{ $tickets->appends(['orders_page' => $allOrders->currentPage()])->links() }}
                </div>
            </div>
        </div>

        {{-- Sidebar: Cart & Account Summary --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-dark text-white">
                <div class="card-body p-4">
                    <h6 class="small text-uppercase text-muted mb-3">Cart Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Items:</span>
                        <span class="fw-bold">{{ count($cartItems) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Subtotal:</span>
                        <h5 class="fw-bold text-primary mb-0">{{ number_format($cartTotal) }} RWF</h5>
                    </div>
                    <a href="{{ url('/cart') }}" class="btn btn-primary w-100 rounded-pill py-2 shadow-sm fw-bold">Checkout Now</a>
                </div>
            </div>

            <div class="card shadow-sm rounded-3 border-top border-primary border-4">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 80px; height: 80px;">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" class="img-fluid rounded-circle" alt="Avatar">
                        @else
                            <h3 class="mb-0 fw-bold text-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</h3>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <a href="{{ route('user.profile.edit') }}" class="btn btn-sm w-100 btn-light border rounded-pill py-2">Account Settings</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-3 { border-radius: 1rem !important; }
    .shadow-sm { box-shadow: 0 .125rem .5rem rgba(0,0,0,.05)!important; }
    .table td { border-bottom: 1px solid #f8f9fa; border-top: 0; }
    .badge { font-weight: 500; font-size: 0.75rem; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
</style>
@endsection