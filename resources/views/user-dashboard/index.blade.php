<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $shop->name ?? 'Shop' }} | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/userdashboard/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/userdashboard/favicon_io/favicon-32x32.png') }}">
    
    <script>
        window.dashboardData = {
            labels: @json($salesLabels ?? []),
            sales: @json($salesData ?? []),
            // SMM/Purchase data logic can be kept as empty array for now as per your request
            purchases: @json($purchaseData ?? []) 
        };
    </script>

    @vite(['resources/js/userdashboard/main.js'])
</head>

<body class="bg-light">
    <div id="overlay" class="overlay"></div>

    <nav id="topbar" class="navbar bg-white border-bottom fixed-top px-3">
        <div class="d-flex align-items-center w-100">
            <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm">
                <i class="ti ti-menu-2"></i>
            </button>
            <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
                <i class="ti ti-menu-2"></i>
            </button>

            <div class="ms-3 d-none d-md-block">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="ti ti-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-0" placeholder="Search your parts...">
                </div>
            </div>

            <ul class="list-unstyled d-flex align-items-center mb-0 ms-auto gap-2">
                {{-- Notifications --}}
                <li class="dropdown">
                    <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown" href="#">
                        <i class="ti ti-bell fs-5"></i>
                        @if($stats['pending_pickup'] > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                                {{ $stats['pending_pickup'] }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-0" style="width: 300px;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Shop Alerts</h6>
                        </div>
                        <div class="notification-list" style="max-height: 250px; overflow-y: auto;">
                            @if($stats['pending_pickup'] > 0)
                                <a href="#" class="dropdown-item p-3 border-bottom d-flex gap-3">
                                    <div class="icon-shape bg-warning-subtle text-warning rounded-circle"><i class="ti ti-truck"></i></div>
                                    <div>
                                        <p class="mb-0 small fw-bold">{{ $stats['pending_pickup'] }} items ready for pickup</p>
                                        <small class="text-muted">Consolidated shipping pending</small>
                                    </div>
                                </a>
                            @endif
                            @if($stats['low_stock'] > 0)
                                <a href="#" class="dropdown-item p-3 border-bottom d-flex gap-3">
                                    <div class="icon-shape bg-danger-subtle text-danger rounded-circle"><i class="ti ti-alert-triangle"></i></div>
                                    <div>
                                        <p class="mb-0 small fw-bold">{{ $stats['low_stock'] }} parts low in stock</p>
                                        <small class="text-muted">Update your inventory soon</small>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </li>

                {{-- User Profile --}}
                <li class="ms-2 dropdown">
                    <a href="#" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=0D8ABC&color=fff' }}" alt="Avatar" class="avatar avatar-sm rounded-circle shadow-sm" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="min-width: 200px;">
                        <div class="px-3 py-2 border-bottom">
                            <p class="mb-0 fw-bold small text-truncate">{{ auth()->user()->name }}</p>
                            <p class="mb-0 text-muted smaller text-truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a class="dropdown-item py-2 small" href="#"><i class="ti ti-building me-2"></i>{{ $shop->name }}</a>
                        <a class="dropdown-item py-2 small" href="#"><i class="ti ti-settings me-2"></i>Shop Settings</a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 small text-danger"><i class="ti ti-logout me-2"></i>Sign Out</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <aside id="sidebar" class="sidebar shadow-sm">
        <div class="logo-area border-bottom px-4 py-3">
            <a href="/" class="d-flex align-items-center text-decoration-none">
                <i class="ti ti-settings-automation fs-3 text-primary me-2"></i>
                <span class="fw-bold fs-5 text-dark">AutoSpare<span class="text-primary">Link</span></span>
            </a>
        </div>
        <div class="py-3">
            <ul class="nav flex-column gap-1">
                <li class="px-4 mb-2 mt-2"><small class="text-uppercase text-muted fw-bold smaller">Main Menu</small></li>
                <li><a class="nav-link active" href="#"><i class="ti ti-home"></i><span>Overview</span></a></li>
                <li><a class="nav-link" href="#"><i class="ti ti-package"></i><span>My Inventory</span></a></li>
                <li><a class="nav-link" href="#"><i class="ti ti-shopping-cart"></i><span>My Sales</span></a></li>
                
                <li class="px-4 mb-2 mt-4"><small class="text-uppercase text-muted fw-bold smaller">Help Desk</small></li>
                <li><a class="nav-link" href="#"><i class="ti ti-ticket"></i><span>Support Tickets</span></a></li>
                <li><a class="nav-link" href="#"><i class="ti ti-receipt"></i><span>Payout Reports</span></a></li>
            </ul>
        </div>
    </aside>

    <main id="content" class="content py-4">
        <div class="container-fluid">
            {{-- Header Section --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ $shop->name }} Dashboard</h1>
                    <p class="text-muted small">Professional Multi-Tenant Operations Hub | Kigali, Rwanda</p>
                </div>
                <button class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> List New Part</button>
            </div>

            {{-- Stat Cards --}}
            <div class="row g-3 mb-4">
                {{-- Total Revenue Card --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-success-subtle text-success rounded-3 me-3">
                                    <i class="ti ti-cash fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Confirmed Revenue</small>
                                    <h4 class="mb-0 fw-bold">{{ number_format($stats['total_revenue']) }} RWF</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inventory Card --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-primary-subtle text-primary rounded-3 me-3">
                                    <i class="ti ti-box fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Parts</small>
                                    <h4 class="mb-0 fw-bold">{{ $stats['total_inventory'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pickup Status Card --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-warning-subtle text-warning rounded-3 me-3">
                                    <i class="ti ti-truck fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Ready for Pickup</small>
                                    <h4 class="mb-0 fw-bold">{{ $stats['pending_pickup'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Low Stock Alert Card --}}
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-danger-subtle text-danger rounded-3 me-3">
                                    <i class="ti ti-database-exclamation fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Low Stock Items</small>
                                    <h4 class="mb-0 fw-bold text-danger">{{ $stats['low_stock'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Row --}}
            <div class="row g-4">
                {{-- Revenue Chart --}}
                <div class="col-12 col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title">Weekly Sales Performance</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div id="salesPurchaseChart"></div>
                        </div>
                    </div>
                </div>
                
                {{-- Recent Sales Table --}}
                <div class="col-12 col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between">
                            <h5 class="card-title">Recent Sales</h5>
                            <a href="#" class="small text-decoration-none">See All</a>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($recentSales as $sale)
                                <li class="list-group-item px-4 py-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center overflow-hidden">
                                            <div class="avatar avatar-sm bg-light text-primary fw-bold rounded me-2">
                                                {{ substr($sale->order->user->name ?? 'G', 0, 1) }}
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="mb-0 small fw-bold text-truncate">{{ $sale->part_name }}</p>
                                                <small class="text-muted">Order #{{ $sale->order_id }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end ms-2">
                                            <p class="mb-0 small fw-bold">{{ number_format($sale->unit_price * $sale->quantity) }} RWF</p>
                                            <span class="badge {{ $sale->order->payment && $sale->order->payment->isSuccessful() ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} smaller">
                                                {{ $sale->order->payment->status ?? 'Pending' }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                <li class="list-group-item px-4 py-5 text-center text-muted">
                                    No sales records found for this week.
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-5 py-3 text-center border-top">
                <p class="text-muted small mb-0">Copyright &copy; 2026 AutoSpareLink. Professional Multi-Tenancy Engine.</p>
            </footer>
        </div>
    </main>
</body>
</html>