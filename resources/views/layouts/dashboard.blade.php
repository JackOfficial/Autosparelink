<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'InApp Inventory' }} | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/userdashboard/favicon_io/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/userdashboard/favicon_io/favicon-32x32.png') }}">
    
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
                    <input type="text" class="form-control bg-light border-0" placeholder="Search Inventory...">
                </div>
            </div>

            <ul class="list-unstyled d-flex align-items-center mb-0 ms-auto gap-2">
                <li class="dropdown">
                    <a class="position-relative btn-icon btn-sm btn-light btn rounded-circle" data-bs-toggle="dropdown" href="#">
                        <i class="ti ti-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">2</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-0" style="width: 300px;">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Notifications</h6>
                            <span class="badge bg-primary-subtle text-primary">2 New</span>
                        </div>
                        <div class="notification-list" style="max-height: 250px; overflow-y: auto;">
                            <a href="#" class="dropdown-item p-3 border-bottom d-flex gap-3">
                                <div class="icon-shape bg-success-subtle text-success rounded-circle"><i class="ti ti-shopping-cart"></i></div>
                                <div>
                                    <p class="mb-0 small fw-bold">New order received</p>
                                    <small class="text-muted">5 minutes ago</small>
                                </div>
                            </a>
                        </div>
                        <a href="#" class="dropdown-item text-center py-2 text-primary small">View all</a>
                    </div>
                </li>

                <li class="ms-2 dropdown">
                    <a href="#" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name=Jacques+M&background=0D8ABC&color=fff" alt="Avatar" class="avatar avatar-sm rounded-circle shadow-sm" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="min-width: 200px;">
                        <div class="px-3 py-2 border-bottom">
                            <p class="mb-0 fw-bold small text-truncate">MUSENGIMANA Jacques</p>
                            <p class="mb-0 text-muted smaller text-truncate">admin@autosparelink.com</p>
                        </div>
                        <a class="dropdown-item py-2 small" href="#"><i class="ti ti-user me-2"></i>Profile</a>
                        <a class="dropdown-item py-2 small" href="#"><i class="ti ti-settings me-2"></i>Settings</a>
                        <hr class="dropdown-divider">
                        <form method="POST" action="/logout">
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
                <span class="fw-bold fs-5 text-dark">InApp<span class="text-primary">Inventory</span></span>
            </a>
        </div>
        <div class="py-3">
            <ul class="nav flex-column gap-1">
                <li class="px-4 mb-2 mt-2"><small class="text-uppercase text-muted fw-bold smaller">Dashboard</small></li>
                <li><a class="nav-link active" href="#"><i class="ti ti-home"></i><span>Overview</span></a></li>
                <li><a class="nav-link" href="#"><i class="ti ti-package"></i><span>Products</span></a></li>
                <li><a class="nav-link" href="#"><i class="ti ti-shopping-cart"></i><span>Orders</span></a></li>
                
                <li class="px-4 mb-2 mt-4"><small class="text-uppercase text-muted fw-bold smaller">Management</small></li>
                <li><a class="nav-link" href="#"><i class="ti ti-users"></i><span>Suppliers</span></a></li>
                <li><a class="nav-link" href="#"><i class="ti ti-receipt"></i><span>Reports</span></a></li>
            </ul>
        </div>
    </aside>

    <main id="content" class="content py-4">
        <div class="container-fluid">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Marketplace Overview</h1>
                    <p class="text-muted small">Welcome back, Jacques. Here's what's happening today.</p>
                </div>
                <button class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Add Spare Part</button>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-primary-subtle text-primary rounded-3 me-3">
                                    <i class="ti ti-currency-dollar fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Monthly Sales</small>
                                    <h4 class="mb-0 fw-bold">$25,000</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

            <div class="row g-4">
                <div class="col-12 col-xl-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title">Revenue Statistics</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div id="salesPurchaseChart"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 col-xl-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="card-title">Recent Orders</h5>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-4 py-3 border-0 border-bottom">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm bg-light rounded me-2">JS</div>
                                            <div>
                                                <p class="mb-0 small fw-bold">Brake Pads - Toyota</p>
                                                <small class="text-muted">Order #8821</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">Paid</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-5 py-3 text-center border-top">
                <p class="text-muted small mb-0">Copyright &copy; 2026 AutoSpareLink. Built for Rwandan Automotive Markets.</p>
            </footer>
        </div>
    </main>
</body>
</html>