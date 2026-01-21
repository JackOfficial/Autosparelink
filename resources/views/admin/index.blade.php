@extends('admin.layouts.app')
@section('title', 'Dashboard | AutoSpareLink')
@section('content')
<div class="container-fluid">
    <!-- KPI Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $brands }}</h3>
                    <p>Total Brands</p>
                </div>
                <div class="icon"><i class="fas fa-car"></i></div>
                <a href="/admin/vehicle-brands" class="small-box-footer">Manage Brands <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $vehicle_models }}</h3>
                    <p>Total Models</p>
                </div>
                <div class="icon"><i class="fas fa-cogs"></i></div>
                <a href="/admin/vehicle-models" class="small-box-footer">Manage Models <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingOrders }}</h3>
                    <p>Pending Orders</p>
                </div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <a href="#" class="small-box-footer">View Orders <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $lowStockParts }}</h3>
                    <p>Low Stock Parts</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="#" class="small-box-footer">Check Inventory <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Monthly Sales Overview</h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inventory Status</h3>
                </div>
                <div class="card-body">
                    <canvas id="inventoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Shortcuts -->
    <div class="row">
        <!-- Recent Orders -->
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
                            @foreach ($recentOrders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    <span class="badge 
                                        @if($order->status == 'Pending') badge-warning 
                                        @elseif($order->status == 'Completed') badge-success 
                                        @else badge-secondary @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Access Shortcuts -->
        <div class="col-md-4">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Quick Access</h3>
                </div>
                <div class="card-body">
                    <a href="/admin/vehicle-models" class="btn btn-app bg-info">
                        <i class="fas fa-car"></i> Vehicles
                    </a>
                    <a href="/admin/spare-parts" class="btn btn-app bg-warning">
                        <i class="fas fa-boxes"></i> Spare Parts
                    </a>
                    <a href="#" class="btn btn-app bg-success">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                    <a href="/admin/users" class="btn btn-app bg-primary">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <a href="#" class="btn btn-app bg-danger">
                        <i class="fas fa-chart-pie"></i> Reports
                    </a>
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
                label: 'Sales',
                data: @json($salesData),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.2)',
                fill: true,
                tension: 0.3
            }]
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
                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
            }]
        }
    });
</script>
@endsection
