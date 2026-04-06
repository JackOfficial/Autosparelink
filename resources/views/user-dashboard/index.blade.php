<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <title>{{ $shop->name ?? 'Shop' }} | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/userdashboard/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/userdashboard/favicon_io/favicon-32x32.png') }}">
    
    <link rel="stylesheet" href="{{ asset('css/vendor-dashboard.css') }}">

    <script>
        // High-speed theme check to prevent flickering
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

    <div class="layout-wrapper">
        
        <aside id="sidebar" class="sidebar shadow-sm">
            <div class="logo-area">
                <a href="/" class="d-flex align-items-center text-decoration-none">
                    <i class="ti ti-settings-automation fs-3 text-primary me-2"></i>
                    <span class="fw-bold fs-5 text-dark logo-text">AutoSpare<span class="text-primary">Link</span></span>
                </a>
            </div>
            
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="px-4 mb-2 mt-4 nav-text">
                        <small class="text-uppercase text-muted fw-bold smaller">Main Menu</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                            <i class="ti ti-home"></i>
                            <span class="nav-text">Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}" href="#">
                            <i class="ti ti-package"></i>
                            <span class="nav-text">My Inventory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ti ti-shopping-cart"></i>
                            <span class="nav-text">My Sales</span>
                        </a>
                    </li>
                    
                    <li class="px-4 mb-2 mt-4 nav-text">
                        <small class="text-uppercase text-muted fw-bold smaller">Support</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ti ti-ticket"></i>
                            <span class="nav-text">Tickets</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="content-wrapper">
            <nav id="topbar" class="navbar topbar bg-body px-3">
                <div class="d-flex align-items-center w-100 h-100">
                    <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm me-3 border">
                        <i class="ti ti-menu-2"></i>
                    </button>
                    
                    <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2 border">
                        <i class="ti ti-menu-2"></i>
                    </button>

                    <div class="d-lg-none d-flex align-items-center me-auto">
                        <span class="fw-bold text-dark mb-0">AS<span class="text-primary">L</span></span>
                    </div>

                    <div class="d-none d-md-block ms-2" style="width: 350px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-body-tertiary border-0 px-3">
                                <i class="ti ti-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control bg-body-tertiary border-0 py-2" placeholder="Search Kigali inventory...">
                        </div>
                    </div>

                    <ul class="list-unstyled d-flex align-items-center mb-0 ms-auto gap-2 gap-md-3">
                        <li>
                            <button id="themeToggler" class="btn btn-light btn-icon btn-sm rounded-circle border">
                                <i id="themeIcon" class="ti ti-sun fs-5"></i>
                            </button>
                        </li>

                        <li class="dropdown">
                            <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle border" data-bs-toggle="dropdown" href="#">
                                <i class="ti ti-bell fs-5"></i>
                                @if(($stats['low_stock'] ?? 0) > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                                @endif
                            </a>
                        </li>

                        <li class="dropdown border-start ps-3 ms-2">
                            <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?background=0D6EFD&color=fff&name='.urlencode(auth()->user()->name) }}" 
                                     alt="User" class="avatar avatar-sm rounded-circle shadow-sm border border-2 border-white me-2" />
                                <div class="d-none d-xl-block">
                                    <p class="mb-0 fw-bold small text-dark line-height-1" style="font-size: 0.85rem;">{{ explode(' ', auth()->user()->name)[0] }}</p>
                                    <p class="mb-0 smaller text-muted" style="font-size: 0.75rem;">{{ $shop->name ?? 'Vendor' }}</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main id="content" class="p-4 flex-grow-1 content">
                <div class="container-fluid p-0">
                    <div class="row align-items-center mb-4 g-3">
                        <div class="col-12 col-md">
                            <h1 class="h3 mb-1 fw-bold text-dark">Business Overview</h1>
                            <p class="text-muted small mb-0">Managing <strong>{{ $shop->name }}</strong>.</p>
                        </div>
                        <div class="col-12 col-md-auto">
                            <button class="btn btn-primary rounded-pill shadow-sm px-4 py-2">
                                <i class="ti ti-plus me-1"></i> Add Part
                            </button>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        @foreach([
                            ['Revenue', number_format($stats['total_revenue'] ?? 0).' RWF', 'ti-cash', 'success'],
                            ['Total Parts', $stats['total_inventory'] ?? 0, 'ti-package', 'primary'],
                            ['Pending', $stats['pending_pickup'] ?? 0, 'ti-truck', 'warning'],
                            ['Low Stock', $stats['low_stock'] ?? 0, 'ti-alert-triangle', 'danger']
                        ] as $card)
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-shape bg-{{ $card[3] }}-subtle text-{{ $card[3] }} rounded-3 me-3">
                                            <i class="ti {{ $card[2] }} fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block fw-medium">{{ $card[0] }}</small>
                                            <h4 class="mb-0 fw-bold">{{ $card[1] }}</h4>
                                        </div>
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
                                </div>
                                <div class="card-body">
                                    <div id="salesPurchaseChart" style="min-height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                                    <h6 class="fw-bold mb-0">Recent Sales</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <tbody>
                                                @forelse($recentSales ?? [] as $sale)
                                                <tr class="border-bottom">
                                                    <td class="ps-4">
                                                        <div class="avatar avatar-xs bg-light text-primary fw-bold rounded">
                                                            {{ substr($sale->part_name, 0, 1) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="small fw-bold text-truncate" style="max-width: 120px;">{{ $sale->part_name }}</div>
                                                        <div class="smaller text-muted">#{{ $sale->order_id }}</div>
                                                    </td>
                                                    <td class="text-end pe-4">
                                                        <div class="small fw-bold">{{ number_format($sale->unit_price) }}</div>
                                                        <span class="badge bg-success-subtle text-success smaller border border-success-subtle">Paid</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="3" class="text-center py-5 text-muted">No sales yet.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="bg-white border-top py-4 mt-auto">
                <div class="container-fluid text-center text-md-start">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} <strong>AutoSpareLink</strong> Kigali.</p>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>