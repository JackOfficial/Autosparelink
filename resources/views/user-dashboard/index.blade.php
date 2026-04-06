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

    <div class="layout-wrapper">
        
        <aside id="sidebar" class="sidebar shadow-sm">
            <div class="logo-area">
                <a href="/" class="d-flex align-items-center text-decoration-none">
                    <i class="ti ti-settings-automation fs-3 text-primary me-2"></i>
                    <span class="fw-bold fs-5 text-dark logo-text">AutoSpare<span class="text-primary">Link</span></span>
                </a>
            </div>
            
            <div class="sidebar-nav">
                <ul class="nav flex-column mt-3">
                    <li class="px-4 mb-2 nav-text">
                        <small class="text-uppercase text-muted fw-bold smaller">Main Menu</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="ti ti-home"></i>
                            <span class="nav-text">Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/inventory">
                            <i class="ti ti-package"></i>
                            <span class="nav-text">My Inventory</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/sales">
                            <i class="ti ti-shopping-cart"></i>
                            <span class="nav-text">My Sales</span>
                        </a>
                    </li>
                    <li class="px-4 mb-2 mt-4 nav-text">
                        <small class="text-uppercase text-muted fw-bold smaller">Support</small>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/tickets">
                            <i class="ti ti-ticket"></i>
                            <span class="nav-text">Tickets</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <nav id="topbar" class="navbar topbar px-3">
    <div class="d-flex align-items-center w-100">
        <button id="toggleBtn" class="btn btn-light btn-sm border d-none d-lg-inline-flex me-3">
            <i class="ti ti-menu-2"></i>
        </button>
        <button id="mobileBtn" class="btn btn-light btn-sm border d-lg-none me-2">
            <i class="ti ti-menu-2"></i>
        </button>

        <div class="d-none d-md-block ms-2" style="width: 400px;">
            <div class="input-group input-group-sm border rounded-3 bg-body-tertiary">
                <span class="input-group-text bg-transparent border-0 px-3">
                    <i class="ti ti-search text-muted"></i>
                </span>
                <input type="text" class="form-control bg-transparent border-0 py-2" placeholder="Search inventory, orders, or customers...">
                <span class="input-group-text bg-transparent border-0 pe-3 d-none d-xl-block">
                    <kbd class="bg-light text-muted border fw-normal" style="font-size: 0.65rem;">Ctrl + K</kbd>
                </span>
            </div>
        </div>

        <ul class="list-unstyled d-flex align-items-center mb-0 ms-auto gap-2">
            
            <li class="dropdown">
                <button class="btn btn-light btn-sm rounded-circle border position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-mail fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white p-1">
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-0 mt-3" style="width: 300px;">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold">Messages</h6>
                        <a href="#" class="text-primary smaller text-decoration-none">Mark all as read</a>
                    </div>
                    <div class="py-2" style="max-height: 280px; overflow-y: auto;">
                        <a href="#" class="dropdown-item px-3 py-2 d-flex align-items-center">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 me-3">
                                <i class="ti ti-user fs-5"></i>
                            </div>
                            <div class="text-truncate">
                                <div class="small fw-bold text-dark">Kigali Parts Ltd</div>
                                <div class="smaller text-muted text-truncate">Is the Toyota brake pad still in stock?</div>
                            </div>
                        </a>
                    </div>
                    <a href="/messages" class="dropdown-item text-center py-2 border-top small text-primary fw-bold">View All Messages</a>
                </div>
            </li>

            <li class="dropdown">
                <button class="btn btn-light btn-sm rounded-circle border position-relative" data-bs-toggle="dropdown">
                    <i class="ti ti-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-primary border border-white rounded-circle"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-0 mt-3" style="width: 320px;">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0 fw-bold">Notifications</h6>
                    </div>
                    <div class="py-2">
                        <div class="px-3 py-2 d-flex align-items-start gap-3">
                            <div class="bg-warning-subtle text-warning rounded p-2">
                                <i class="ti ti-alert-triangle fs-5"></i>
                            </div>
                            <div>
                                <div class="small fw-bold">Low Stock Alert</div>
                                <div class="smaller text-muted">Oil Filter (High-Lux) is below 5 units.</div>
                                <div class="smaller text-primary mt-1">2 mins ago</div>
                            </div>
                        </div>
                    </div>
                    <a href="/notifications" class="dropdown-item text-center py-2 border-top small text-muted">See all activities</a>
                </div>
            </li>

            <li>
                <button id="themeToggler" class="btn btn-light btn-sm rounded-circle border">
                    <i id="themeIcon" class="ti ti-sun fs-5"></i>
                </button>
            </li>

            <li class="dropdown border-start ps-3 ms-2">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle no-caret" data-bs-toggle="dropdown">
                    <div class="position-relative me-2">
                        <img src="https://ui-avatars.com/api/?background=0D6EFD&color=fff&bold=true&name={{ urlencode(auth()->user()->name) }}" 
                             alt="User" class="avatar avatar-sm rounded-circle border" />
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                    </div>
                    <div class="d-none d-xl-block me-2">
                        <p class="mb-0 fw-bold small text-dark line-height-1" style="font-size: 0.85rem;">{{ explode(' ', auth()->user()->name)[0] }}</p>
                        <p class="mb-0 smaller text-muted" style="font-size: 0.75rem;">{{ $shop->name ?? 'Vendor' }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3">
                    <li><h6 class="dropdown-header">Manage Account</h6></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="/profile"><i class="ti ti-user-circle me-2 fs-5"></i> Profile</a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="/settings"><i class="ti ti-settings me-2 fs-5"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                <i class="ti ti-logout me-2 fs-5"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

     <main id="content" class="content d-flex flex-column">
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
                <p class="text-muted small mb-0">Live updates for <strong>{{ $shop->name }}</strong> in Kigali.</p>
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
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 border-0 smaller text-muted text-uppercase py-3">Item</th>
                                        <th class="border-0 smaller text-muted text-uppercase py-3 text-end pe-4">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSales ?? [] as $sale)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary-subtle text-primary fw-bold rounded-circle d-flex align-items-center justify-content-center me-3" style="width:38px; height:38px;">
                                                    <i class="ti ti-settings fs-6"></i>
                                                </div>
                                                <div>
                                                    <div class="small fw-bold text-dark">{{ $sale->part_name }}</div>
                                                    <div class="smaller text-muted">ID: #{{ $sale->order_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-4 py-3">
                                            <div class="small fw-bold">{{ number_format($sale->unit_price) }}</div>
                                            <div class="smaller text-success">
                                                <i class="ti ti-circle-check-filled me-1"></i>Completed
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="2" class="text-center py-5 text-muted small">No recent transactions found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-auto py-4 bg-white border-top">
        <div class="container-fluid px-4">
            <div class="row align-items-center g-3">
                <div class="col-md-6 text-center text-md-start">
                    <span class="smaller text-muted">&copy; {{ date('Y') }} <strong>AutoSpareLink</strong>. Engineered for efficiency.</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-inline-flex gap-4">
                        <a href="#" class="smaller text-muted text-decoration-none transition-color">Support</a>
                        <a href="#" class="smaller text-muted text-decoration-none transition-color">Documentation</a>
                        <a href="#" class="smaller text-muted text-decoration-none transition-color">Privacy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</main>
    </div>
</body>
</html>