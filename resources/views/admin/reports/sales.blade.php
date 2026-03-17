@extends('admin.layouts.app')

@section('content')
<div class="container-fluid pt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="font-weight-bold mb-0">Sales Analysis</h3>
            <p class="text-muted small">Viewing data for the last {{ $days }} days</p>
        </div>
        
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.reports.sales.pdf', ['period' => $days]) }}" class="btn btn-outline-danger shadow-sm mr-3 px-3 rounded-pill">
                <i class="fa fa-file-pdf mr-2"></i> Export PDF
            </a>

            <div class="btn-group shadow-sm">
                <a href="{{ route('admin.reports.sales', ['period' => 7]) }}" class="btn btn-white border {{ $days == 7 ? 'active btn-primary text-white' : '' }}">7 Days</a>
                <a href="{{ route('admin.reports.sales', ['period' => 30]) }}" class="btn btn-white border {{ $days == 30 ? 'active btn-primary text-white' : '' }}">30 Days</a>
                <a href="{{ route('admin.reports.sales', ['period' => 90]) }}" class="btn btn-white border {{ $days == 90 ? 'active btn-primary text-white' : '' }}">90 Days</a>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-xl bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-uppercase small font-weight-bold opacity-75">Total Revenue</h6>
                    <h2 class="mb-0 font-weight-bold">${{ number_format($totalRevenue, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-xl bg-dark text-white">
                <div class="card-body">
                    <h6 class="text-uppercase small font-weight-bold opacity-75">Total Orders</h6>
                    <h2 class="mb-0 font-weight-bold">{{ number_format($totalOrders) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="font-weight-bold">Revenue Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded-xl">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="font-weight-bold">Top Selling Parts</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($topParts as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-4 py-3">
                            <div>
                                <h6 class="mb-0 font-weight-bold">{{ $item->part->part_name ?? 'Unknown Part' }}</h6>
                                <small class="text-muted">SKU: {{ $item->part->sku ?? 'N/A' }}</small>
                            </div>
                            <span class="badge badge-pill badge-light px-3 py-2">{{ $item->times_sold }} sold</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesData->pluck('date')) !!},
            datasets: [{
                label: 'Revenue ($)',
                data: {!! json_encode($salesData->pluck('total_revenue')) !!},
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
@endsection