@extends('admin.layouts.app')
@section('title', 'Dashboard | AutoSpareLink')

@push('styles')
<style>
    .blink_me { animation: blinker 1.5s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.3; } }
    .growth-indicator { font-size: 0.9rem; font-weight: bold; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary shadow-sm">
                <div class="inner">
                    <h3>{{ $brands }}</h3>
                    <p>Total Brands</p>
                </div>
                <div class="icon"><i class="fas fa-car"></i></div>
                <a href="/admin/vehicle-brands" class="small-box-footer">Manage Brands <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-purple" style="background-color: #6f42c1 !important; color: white !important;">
                <div class="inner">
                    <h3>{{ $abandonedCount ?? 0 }}</h3>
                    <p>Abandoned Carts</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-basket"></i></div>
                <a href="/admin/carts" class="small-box-footer">Recover Sales <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3>{{ $pendingOrders }}</h3>
                    <p>Pending / Callbacks</p>
                </div>
                <div class="icon"><i class="fas fa-phone"></i></div>
                <a href="/admin/orders" class="small-box-footer">Process Orders <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $lowStockParts }}</h3>
                    <p>Low Stock Parts</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="#" class="small-box-footer">Check Inventory <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header d-flex p-0">
                    <h3 class="card-title p-3">Monthly Sales Overview</h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        <li class="nav-item">
                            <span class="nav-link active {{ $revenueChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}% vs Last Month
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="d-flex mb-3">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">{{ number_format($thisMonthRevenue) }} RWF</span>
                            <span>Sales This Month</span>
                        </p>
                    </div>
                    <canvas id="salesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inventory Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="inventoryChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Recent Orders</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
    @forelse ($recentOrders as $order)
    <tr>
        <td>#{{ $order->id }}</td>
        {{-- Accessing the name through the user relationship --}}
        <td>{{ $order->user?->name ?? 'Guest/Unknown' }}</td>
        <td>
            @if($order->status == 'callback_requested')
                <span class="badge badge-danger blink_me">
                    <i class="fas fa-phone-alt mr-1"></i> Call Requested
                </span>
            @elseif($order->status == 'Pending')
                <span class="badge badge-warning">Pending</span>
            @elseif($order->status == 'Completed')
                <span class="badge badge-success">Completed</span>
            @else
                <span class="badge badge-secondary">{{ $order->status }}</span>
            @endif
        </td>
        <td class="font-weight-bold">{{ number_format($order->total) }} RWF</td>
        <td>{{ $order->created_at->diffForHumans() }}</td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="text-center py-3 text-muted">No orders found.</td>
    </tr>
    @endforelse
</tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Quick Access</h3>
                </div>
                <div class="card-body">
                    <a href="/admin/vehicle-models" class="btn btn-app bg-info"><i class="fas fa-car"></i> Vehicles</a>
                    <a href="/admin/spare-parts" class="btn btn-app bg-warning"><i class="fas fa-boxes"></i> Spare Parts</a>
                    <a href="/admin/orders" class="btn btn-app bg-success"><i class="fas fa-shopping-cart"></i> Orders</a>
                    <a href="/admin/users" class="btn btn-app bg-primary"><i class="fas fa-users"></i> Users</a>
                    <a href="/admin/carts" class="btn btn-app bg-purple" style="background-color: #6f42c1; color:white;"><i class="fas fa-shopping-basket"></i> Carts</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesMonths),
            datasets: [{
                label: 'Revenue (RWF)',
                data: @json($salesData),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { callback: function(value) { return value.toLocaleString() + ' RWF'; } }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ' + context.parsed.y.toLocaleString() + ' RWF';
                        }
                    }
                }
            }
        }
    });

    // Inventory Chart
    var ctx2 = document.getElementById('inventoryChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: @json($inventoryData),
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });
</script>
@endsection