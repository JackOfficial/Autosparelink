<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <title>{{ $shop->name ?? 'Shop' }} | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/userdashboard/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/userdashboard/favicon_io/favicon-32x32.png') }}">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        
        window.dashboardData = {
            labels: @json($salesLabels ?? []),
            sales: @json($salesData ?? []),
            purchases: @json($purchaseData ?? []) 
        };
    </script>

    @vite(['resources/js/userdashboard/main.js'])
</head>

<body class="bg-body-tertiary">
    <div id="overlay" class="overlay"></div>

    <div class="layout-wrapper d-flex"> @include('layouts.partials.sidebar')

        <main id="content" class="content d-flex flex-column flex-grow-1">
            @include('layouts.partials.navbar')

            <div class="container-fluid p-4 flex-grow-1">
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
                        <button class="btn btn-primary rounded-pill shadow-sm px-4 py-2">
                            <i class="ti ti-plus me-1"></i> Add Part
                        </button>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    @php
                        $stats_items = [
                            ['Revenue', number_format($stats['total_revenue'] ?? 0).' RWF', 'ti-cash', 'success', '+12.5%'],
                            ['Inventory', $stats['total_inventory'] ?? 0, 'ti-package', 'primary', 'Active'],
                            ['Pending', $stats['pending_pickup'] ?? 0, 'ti-truck', 'warning', '3 urgent'],
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
                            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Recent Sales</h6>
                                <a href="/sales" class="smaller text-primary text-decoration-none fw-bold">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-7">
                        </div>
                    <div class="col-12 col-xl-5">
                        </div>
                </div>

            </div> @include('layouts.partials.footer')
        </main>
    </div>
</body>
</html>