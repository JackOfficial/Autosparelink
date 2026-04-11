@extends('layouts.app')

@section('title', 'My Dashboard | AutoSpare Link')

@section('content')
<div class="container py-5">
    {{-- Welcome Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="font-weight-bold text-dark">Hello, {{ Auth::user()->name }}!</h2>
            <p class="text-muted">Welcome back to your dashboard. Here is what's happening with your account.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm dashboard-card bg-primary text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-7">Total Orders</h6>
                    <h3 class="font-weight-bold mb-0">{{ $stats['total_orders'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm dashboard-card bg-success text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-7">Total Spent</h6>
                    <h3 class="font-weight-bold mb-0">{{ number_format($stats['total_spent'] ?? 0) }} RWF</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm dashboard-card bg-info text-white">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2 opacity-7">Open Tickets</h6>
                    <h3 class="font-weight-bold mb-0">{{ $stats['open_tickets'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm dashboard-card {{ ($stats['pending_tickets'] ?? 0) > 0 ? 'bg-warning text-dark' : 'bg-white text-muted border' }}">
                <div class="card-body">
                    <h6 class="small text-uppercase mb-2">Needs Your Reply</h6>
                    <h3 class="font-weight-bold mb-0">{{ $stats['pending_tickets'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Orders Table --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm dashboard-card mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary btn-pill">View All</a>
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
                                            <span class="badge badge-pill badge-{{ $order->status == 'completed' ? 'success' : 'secondary' }} px-3 py-2">
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
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Account Summary --}}
            <div class="card border-0 shadow-sm dashboard-card text-center p-4">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; border: 3px solid #f1f5f9;">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/'.Auth::user()->avatar) }}" class="img-fluid rounded-circle" alt="Avatar">
                    @else
                        <h3 class="mb-0 font-weight-bold text-primary">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</h3>
                    @endif
                </div>
                <h5 class="font-weight-bold mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-4">{{ Auth::user()->email }}</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-pill btn-block shadow-sm">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Local overrides to ensure dashboard looks high-end within the global layout */
    .dashboard-card {
        border-radius: 15px !important;
        transition: transform 0.2s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
    }
    .opacity-7 { opacity: 0.7; }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    
    /* Ensuring shadows look soft against your dark footer/navbar background */
    .shadow-sm { 
        box-shadow: 0 .125rem .5rem rgba(0,0,0,.05)!important; 
    }
</style>
@endsection