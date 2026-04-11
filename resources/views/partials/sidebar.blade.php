<aside class="sidebar border-end bg-white" 
    :class="sidebarOpen ? 'd-block' : 'd-none d-md-block'"
    style="min-width: 280px; height: calc(100vh - 60px); position: sticky; top: 60px; overflow-y: auto;">
    
    <div class="py-4">
        {{-- Section 1: Fleet Management --}}
        <div class="px-4 mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing: 1px;">My Garage</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 {{ request()->is('user/dashboard') ? 'active' : '' }}" href="/user/dashboard">
                        <i class="fas fa-desktop me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 {{ request()->is('user/vehicles*') ? 'active' : 'text-dark' }}" href="/user/vehicles">
                        <i class="fas fa-car me-2"></i> Saved Vehicles
                        <span class="badge rounded-pill bg-light text-dark border ms-1 fw-normal" style="font-size: 0.7rem;">VIN</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section 2: Commerce --}}
        <div class="px-4 mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing: 1px;">Orders & Shopping</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 d-flex justify-content-between align-items-center {{ request()->is('user/orders*') ? 'active' : 'text-dark' }}" href="{{ route('orders.index') }}">
                        <span><i class="fas fa-box me-2"></i> My Orders</span>
                        <span class="badge badge-soft-primary rounded-pill">{{ $stats['total_orders'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 text-dark" href="/user/quotes">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Requested Quotes
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section 3: Support --}}
        <div class="px-4 mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing: 1px;">Support</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 d-flex justify-content-between align-items-center {{ request()->is('user/tickets*') ? 'active' : 'text-dark' }}" href="{{ route('tickets.index') }}">
                        <span><i class="fas fa-headset me-2"></i> Help Tickets</span>
                      @if(($stats['pending_tickets'] ?? 0) > 0)
            <span class="badge rounded-pill bg-warning text-dark">{{ $stats['pending_tickets'] }}</span>
        @endif
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 text-dark" href="/faq">
                        <i class="fas fa-info-circle me-2"></i> Return Policy
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section 4: Account --}}
        <div class="px-4 mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing: 1px;">Settings</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 {{ request()->is('user/profile*') ? 'active' : 'text-dark' }}" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-cog me-2"></i> Profile Settings
                    </a>
                </li>
               <li class="nav-item mb-1">
    <a class="nav-link py-2 px-3 rounded-3 {{ request()->is('addresses*') ? 'bg-primary text-white shadow-sm' : 'text-dark hover-bg-light' }}" 
       href="{{ route('addresses.index') }}">
        <i class="fas fa-map-marker-alt me-2 {{ request()->is('addresses*') ? 'text-white' : 'text-primary' }}"></i> 
        <span>Shipping Addresses</span>
    </a>
</li>
            </ul>
        </div>
    </div>

    {{-- Promotion/Action Card --}}
    <div class="px-4 mt-2 mb-4">
        <div class="p-3 rounded-3 bg-light border text-center">
            <p class="small text-muted mb-2">Wrong parts are a pain.</p>
            <button class="btn btn-sm btn-primary w-100 rounded-pill shadow-sm">
                <i class="fas fa-plus me-1 small"></i> Add a Vehicle
            </button>
        </div>
    </div>
</aside>