<x-shop-dashboard>
    <x-slot:title>Business Overview</x-slot:title>
    
    <div class="container-fluid p-4 flex-grow-1">
        {{-- Header Section --}}
        <div class="row align-items-center mb-4 g-3">
            <div class="col-12 col-md">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 smaller">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Overview</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-1 fw-bold text-dark">Business Overview</h1>
                <p class="text-muted small mb-0">Live updates for <strong>{{ $shop->name }}</strong></p>
            </div>
            <div class="col-12 col-md-auto d-flex gap-2">
                <button class="btn btn-white border rounded-pill shadow-sm px-3 py-2 small">
                    <i class="ti ti-download me-1"></i> Export
                </button>
                <a href="{{ route('shop.parts.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 py-2">
                    <i class="ti ti-plus me-1"></i> Add Part
                </a>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            @php
                $stats_items = [
                    ['Revenue', number_format($stats['total_revenue'] ?? 0).' RWF', 'ti-cash', 'success', 'Total Net'],
                    ['Available', number_format($stats['available_balance'] ?? 0).' RWF', 'ti-wallet', 'info', 'Ready'],
                    ['Pending Flow', number_format($stats['pending_balance'] ?? 0).' RWF', 'ti-clock', 'warning', 'Processing'],
                    ['Low Stock', $stats['low_stock'] ?? 0, 'ti-alert-triangle', 'danger', 'Restock soon']
                ];
            @endphp
            @foreach($stats_items as $item)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 transition-hover">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-shape bg-{{ $item[3] }}-subtle text-{{ $item[3] }} rounded-3 p-3">
                                <i class="ti {{ $item[2] }} fs-4"></i>
                            </div>
                            <span class="badge bg-{{ $item[3] }}-subtle text-{{ $item[3] }} border border-{{ $item[3] }} smaller">
                                {{ $item[4] }}
                            </span>
                        </div>
                        <div>
                            <small class="text-muted d-block fw-medium mb-1">{{ $item[0] }}</small>
                            <h3 class="mb-0 fw-bold">{{ $item[1] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Charts Row --}}
        <div class="row g-4 mb-4">
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Performance Analytics</h6>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border dropdown-toggle" data-bs-toggle="dropdown">Last 7 Days</button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item small" href="#">Last 30 Days</a></li>
                                <li><a class="dropdown-item small" href="#">This Year</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="salesPurchaseChart" style="min-height: 350px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0">Stock Distribution</h6>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div id="customerChart"></div>
                        <div class="mt-3 text-center">
                            <span class="badge bg-danger-subtle text-danger px-3">{{ $stats['low_stock'] }} Items below threshold</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity Row --}}
        <div class="row g-4 mb-4">
            {{-- Recent Sales Table --}}
            <div class="col-12 col-xl-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Recent Sales</h6>
                        <a href="/sales" class="smaller text-primary text-decoration-none fw-bold">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="smaller text-muted">
                                        <th class="ps-4">Item</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales as $sale)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0 small fw-bold">{{ $sale->part->part_name ?? 'Spare Part' }}</h6>
                                                    <small class="text-muted">#{{ $sale->order->order_number ?? $sale->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="small fw-bold">{{ number_format($sale->unit_price * $sale->quantity) }} RWF</span></td>
                                        <td>
                                            <span class="badge rounded-pill smaller 
                                                {{ $sale->status === 'completed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                                {{ ucfirst($sale->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="text-center py-4 text-muted">No recent sales found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pending Pickups --}}
            <div class="col-12 col-xl-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h6 class="fw-bold mb-0">Pending Pickups</h6>
                    </div>
                    <div class="card-body">
                        @forelse($pendingPickups as $pickup)
                        <div class="d-flex align-items-center mb-3 p-3 border rounded-3 bg-light-subtle">
                            <div class="icon-shape bg-warning-subtle text-warning rounded-pill p-2 me-3">
                                <i class="ti ti-truck"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">{{ $pickup->customer_name }}</div>
                                <div class="smaller text-muted">{{ $pickup->location }}</div>
                            </div>
                            <div class="text-end fw-bold small">
                                {{ $pickup->items_count }} Units
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">No pending pickups for today.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.dashboardData = {
            labels: @json($salesLabels ?? []),
            sales: @json($salesData ?? []),
            inventoryStats: @json($inventoryStats ?? [0, 100])
        };
    </script>
    @endpush
</x-shop-dashboard>