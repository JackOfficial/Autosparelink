@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold">Hello, {{ $user->name }}!</h2>
            <p class="text-muted">Welcome back to your dashboard. Here is what's happening with your account.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg bg-primary text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2">Total Orders</h6>
                    <h3 class="font-weight-bold mb-0">{{ $stats['total_orders'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg bg-success text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2">Total Spent</h6>
                    <h3 class="font-weight-bold mb-0">{{ number_format($stats['total_spent']) }} RWF</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg bg-info text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2">Open Tickets</h6>
                    <h3 class="font-weight-bold mb-0">{{ $stats['open_tickets'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm rounded-lg {{ $stats['pending_tickets'] > 0 ? 'bg-warning text-dark' : 'bg-white text-muted border' }}">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2">Needs Your Reply</h6>
                    <h3 class="font-weight-bold mb-0">{{ $stats['pending_tickets'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Orders Table --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="small text-uppercase text-muted">
                                    <th class="border-0 px-4">Order #</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 text-right px-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allOrders as $order)
                                    <tr>
                                        <td class="px-4 align-middle font-weight-bold">#{{ $order->order_number }}</td>
                                        <td class="align-middle small">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="align-middle">
                                            <span class="badge badge-pill badge-{{ $order->status == 'completed' ? 'success' : 'secondary' }} px-3">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-right px-4 font-weight-bold text-primary">
                                            {{ number_format($order->grand_total) }} RWF
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
            </div>

            {{-- Support Tickets Table --}}
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">Support Tickets</h5>
                    <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-primary rounded-pill px-3">+ New Ticket</a>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td class="px-4 align-middle">
                                            <div class="font-weight-bold mb-0">{{ $ticket->subject }}</div>
                                            <small class="text-muted">#TK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</small>
                                        </td>
                                        <td class="align-middle">
                                            @if($ticket->status == 'pending')
                                                <span class="badge badge-warning">Admin Replied</span>
                                            @elseif($ticket->status == 'open')
                                                <span class="badge badge-success">Open</span>
                                            @else
                                                <span class="badge badge-light border">Closed</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-right px-4">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-light border rounded-pill px-3">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted small">You haven't opened any support tickets yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar: Cart & Account Summary --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-lg mb-4 bg-dark text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase text-muted mb-3">Cart Summary</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Items:</span>
                        <span class="font-weight-bold">{{ count($cartItems) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span class="font-weight-bold text-primary">{{ number_format($cartTotal) }} RWF</span>
                    </div>
                    <a href="{{ url('/cart') }}" class="btn btn-primary btn-block rounded-pill shadow-sm">Checkout Now</a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" class="img-fluid rounded-circle" alt="Avatar">
                        @else
                            <h4 class="mb-0 font-weight-bold text-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</h4>
                        @endif
                    </div>
                    <h5 class="font-weight-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-block btn-light border rounded-pill">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-lg { border-radius: 0.75rem !important; }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .table td { border-top: 1px solid #f8f9fa; }
    .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1); }
</style>
@endsection