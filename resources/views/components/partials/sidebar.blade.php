<aside id="sidebar" class="sidebar shadow-sm d-flex flex-column">
    <div class="logo-area border-bottom px-4 py-3 flex-shrink-0">
        <a href="/" class="d-flex align-items-center text-decoration-none">
            @if($shop && $shop->logo)
                <img src="{{ asset('storage/' . $shop->logo) }}" 
                     alt="{{ $shop->name }}" 
                     class="rounded-circle me-2" 
                     style="width: 32px; height: 32px; object-fit: cover;">
            @else
                <i class="ti ti-settings-automation fs-3 text-primary me-2"></i>
            @endif

            <span class="fw-bold fs-6 text-dark logo-text">
                {{ $shop->name ?? 'Vendor Panel' }}
            </span>
        </a>
    </div>
    
    <div class="sidebar-nav flex-grow-1 overflow-y-auto custom-scrollbar">
        <ul class="nav flex-column mt-3">
            <li class="px-4 mb-2 nav-text">
                <small class="text-uppercase text-muted fw-bold smaller">Analytics</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shop.dashboard') ? 'active' : '' }}" href="{{ route('shop.dashboard') }}">
                    <i class="ti ti-chart-pie"></i>
                    <span class="nav-text">Overview</span>
                </a>
            </li>

            <li class="px-4 mb-2 mt-3 nav-text">
                <small class="text-uppercase text-muted fw-bold smaller">Parts Management</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shop.parts.index') ? 'active' : '' }}" href="{{ route('shop.parts.index') }}">
                    <i class="ti ti-package"></i>
                    <span class="nav-text">All Spare Parts</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shop.parts.create') ? 'active' : '' }}" href="{{ route('shop.parts.create') }}">
                    <i class="ti ti-circle-plus"></i>
                    <span class="nav-text">Add New Part</span>
                </a>
            </li>

            <li class="px-4 mb-2 mt-3 nav-text">
                <small class="text-uppercase text-muted fw-bold smaller">Sales & Orders</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shop.orders.index') ? 'active' : '' }}" href="{{ route('shop.orders.index') }}">
                    <i class="ti ti-shopping-cart"></i>
                    <span class="nav-text">Recent Orders</span>
                    @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingOrdersCount }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shop.sales.index') ? 'active' : '' }}" href="{{ route('shop.sales.index') }}">
                    <i class="ti ti-receipt"></i>
                    <span class="nav-text">Sales History</span>
                </a>
            </li>

            <li class="px-4 mb-2 mt-3 nav-text">
                <small class="text-uppercase text-muted fw-bold smaller">Business Growth</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('reviews*') ? 'active' : '' }}" href="/reviews">
                    <i class="ti ti-star"></i>
                    <span class="nav-text">Part Reviews</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('payouts*') ? 'active' : '' }}" href="/payouts">
                    <i class="ti ti-wallet"></i>
                    <span class="nav-text">Earnings & Payouts</span>
                </a>
            </li>

            <li class="px-4 mb-2 mt-3 nav-text">
                <small class="text-uppercase text-muted fw-bold smaller">Configuration</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('shop-settings*') ? 'active' : '' }}" href="/shop-settings">
                    <i class="ti ti-settings"></i>
                    <span class="nav-text">Shop Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/support">
                    <i class="ti ti-help"></i>
                    <span class="nav-text">Help Center</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer mt-auto p-4 flex-shrink-0 border-top">
        <a href="/" class="btn btn-light w-100 rounded-pill border shadow-sm btn-sm">
            <i class="ti ti-arrow-left me-1"></i> Back to Market
        </a>
    </div>
</aside>