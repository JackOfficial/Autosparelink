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
            purchases: @json($purchaseData ?? []) 
        };
    </script>

    @vite(['resources/js/userdashboard/main.js'])
</head>

<body class="bg-light">
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
                    <li class="px-4 mb-2 mt-2 nav-text">
                        <small class="text-uppercase text-muted fw-bold smaller">Main Menu</small>
                    </li>
                    <li>
                        <a class="nav-link active" href="#">
                            <i class="ti ti-home"></i>
                            <span class="nav-text">Overview</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="ti ti-package"></i>
                            <span class="nav-text">My Inventory</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="ti ti-shopping-cart"></i>
                            <span class="nav-text">My Sales</span>
                        </a>
                    </li>
                    
                    <li class="px-4 mb-2 mt-4 nav-text">
                        <small class="text-uppercase text-muted fw-bold smaller">Help Desk</small>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="ti ti-ticket"></i>
                            <span class="nav-text">Support Tickets</span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i class="ti ti-receipt"></i>
                            <span class="nav-text">Payout Reports</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <nav id="topbar" class="navbar topbar bg-white border-bottom sticky-top px-3">
            <div class="d-flex align-items-center w-100">
                <button id="toggleBtn" class="d-none d-lg-inline-flex btn btn-light btn-icon btn-sm me-3">
                    <i class="ti ti-menu-2"></i>
                </button>
                <button id="mobileBtn" class="btn btn-light btn-icon btn-sm d-lg-none me-2">
                    <i class="ti ti-menu-2"></i>
                </button>

                <div class="d-none d-md-block flex-grow-0" style="width: 300px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-0"><i class="ti ti-search text-muted"></i></span>
                        <input type="text" class="form-control bg-light border-0" placeholder="Search parts...">
                    </div>
                </div>

                <ul class="list-unstyled d-flex align-items-center mb-0 ms-auto gap-3">
                    {{-- Notifications --}}
                    <li class="dropdown">
                        <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown" href="#">
                            <i class="ti ti-bell fs-5"></i>
                            @if($stats['pending_pickup'] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white p-1"></span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-0" style="width: 320px;">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Shop Alerts</h6>
                                <span class="badge bg-primary-subtle text-primary">{{ $stats['pending_pickup'] + $stats['low_stock'] }} New</span>
                            </div>
                            <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                                @if($stats['pending_pickup'] > 0)
                                    <a href="#" class="dropdown-item p-3 border-bottom d-flex gap-3 text-wrap">
                                        <div class="icon-shape bg-warning-subtle text-warning rounded-circle flex-shrink-0"><i class="ti ti-truck"></i></div>
                                        <div>
                                            <p class="mb-0 small fw-bold">{{ $stats['pending_pickup'] }} ready for pickup</p>
                                            <small class="text-muted">Kigali Hub awaiting dispatch</small>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>

                    {{-- User Profile --}}
                    <li class="dropdown border-start ps-3">
                        <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" alt="Avatar" class="avatar avatar-sm rounded-circle shadow-sm me-2" />
                            <div class="d-none d-xl-block">
                                <p class="mb-0 fw-bold small text-dark line-height-1">{{ explode(' ', auth()->user()->name)[0] }}</p>
                                <p class="mb-0 smaller text-muted">{{ $shop->name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="min-width: 220px;">
                            <div class="px-3 py-3 border-bottom">
                                <p class="mb-0 fw-bold small">{{ auth()->user()->name }}</p>
                                <p class="mb-0 text-muted smaller">{{ auth()->user()->email }}</p>
                            </div>
                            <a class="dropdown-item py-2 small" href="#"><i class="ti ti-settings me-2"></i>Account Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 small text-danger"><i class="ti ti-logout me-2"></i>Sign Out</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main id="content" class="content p-4">
            <div class="container-fluid p-0">
                {{-- Header --}}
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h1 class="h4 mb-1">Business Overview</h1>
                        <p class="text-muted small mb-0">Managing <strong>{{ $shop->name }}</strong> operations in Kigali.</p>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary rounded-pill shadow-sm px-4">
                            <i class="ti ti-plus me-1"></i> Add Spare Part
                        </button>
                    </div>
                </div>

                {{-- Stats Grid --}}
                <div class="row g-3 mb-4">
                    @php
                        $cards = [
                            ['Revenue', number_format($stats['total_revenue']).' RWF', 'ti-cash', 'success'],
                            ['Total Parts', $stats['total_inventory'], 'ti-package', 'primary'],
                            ['Pending Pickup', $stats['pending_pickup'], 'ti-truck', 'warning'],
                            ['Low Stock', $stats['low_stock'], 'ti-alert-triangle', 'danger']
                        ];
                    @endphp
                    @foreach($cards as $card)
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="icon-shape bg-{{ $card[3] }}-subtle text-{{ $card[3] }} rounded-3 me-3">
                                        <i class="ti {{ $card[2] }} fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ $card[0] }}</small>
                                        <h5 class="mb-0 fw-bold">{{ $card[1] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Charts/Tables Row --}}
                <div class="row g-4">
                    <div class="col-12 col-xl-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between">
                                <h6 class="fw-bold mb-0">Sales Analytics</h6>
                                <select class="form-select form-select-sm w-auto border-0 bg-light">
                                    <option>Last 7 Days</option>
                                    <option>Last 30 Days</option>
                                </select>
                            </div>
                            <div class="card-body">
                                <div id="salesPurchaseChart" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0">Recent Activity</h6>
                                <a href="#" class="smaller text-primary text-decoration-none fw-bold">View All</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <tbody>
                                            @forelse($recentSales as $sale)
                                            <tr class="border-bottom">
                                                <td class="ps-4" style="width: 50px;">
                                                    <div class="avatar avatar-xs bg-light text-primary fw-bold rounded">
                                                        {{ substr($sale->part_name, 0, 1) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="small fw-bold text-truncate" style="max-width: 140px;">{{ $sale->part_name }}</div>
                                                    <div class="smaller text-muted">#{{ $sale->order_id }}</div>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="small fw-bold">{{ number_format($sale->unit_price) }}</div>
                                                    <span class="badge bg-success-subtle text-success smaller">Paid</span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="3" class="text-center py-5 text-muted">No recent sales.</td></tr>
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
    </div>
</body>
</html>