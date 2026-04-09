<aside id="sidebar" class="sidebar shadow-sm">
    <div class="logo-area border-bottom px-4 py-3">
        <a href="/" class="d-flex align-items-center text-decoration-none">
            @if($shop && $shop->logo)
                <img src="{{ asset('storage/' . $shop->logo) }}" 
                     alt="{{ $shop->name }}" 
                     class="rounded-circle me-2" 
                     style="width: 32px; height: 32px; object-fit: cover;">
            @else
                <i class="ti ti-building-store fs-3 text-primary me-2"></i>
            @endif

            <span class="fw-bold fs-6 text-dark logo-text">
                {{ $shop->name ?? 'Vendor Panel' }}
            </span>
        </a>
    </div>
    
    <div class="sidebar-nav">
        <ul class="nav flex-column mt-3">
            <li class="px-4 mb-2 nav-text">
                <small class="text-uppercase text-muted fw-bold smaller">Main Menu</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                    <i class="ti ti-home"></i>
                    <span class="nav-text">Overview</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('inventory*') ? 'active' : '' }}" href="/inventory">
                    <i class="ti ti-package"></i>
                    <span class="nav-text">My Inventory</span>
                </a>
            </li>
            </ul>
    </div>
</aside>