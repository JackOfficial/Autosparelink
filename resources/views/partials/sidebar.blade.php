<aside class="sidebar border-end bg-white" 
    :class="sidebarOpen ? 'd-block' : 'd-none d-md-block'"
    style="min-width: 280px; height: calc(100vh - 60px); position: sticky; top: 60px; overflow-y: auto;">
    
    <div class="py-4">
        {{-- Section 1: Fleet Management --}}
        <div class="px-4 mb-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3" style="letter-spacing: 1px;">My Garage</h6>
            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                        <i class="fas fa-desktop me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link py-2 px-3 {{ request()->is('user/vehicles*') ? 'active' : 'text-dark' }}" href="/user/vehicles">
                        <i class="fas fa-car me-2"></i> Saved Vehicles
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
        <span>My Addresses</span>
    </a>
</li>
            </ul>
        </div>
    </div>

{{-- Seller Area: Switch between Promotion, Pending, and Management --}}
@if(auth()->user()->canBecomeVendor() && !auth()->user()->shop)
    {{-- Promotion Card for regular users --}}
    <div class="px-4 mt-2 mb-4">
        <div class="p-3 rounded-4 bg-dark text-white border-0 shadow-sm text-center position-relative overflow-hidden">
            <i class="fas fa-handshake position-absolute opacity-10" style="font-size: 4rem; right: -10px; bottom: -10px;"></i>
            
            <div class="position-relative">
                <span class="badge bg-primary rounded-pill mb-2 px-3" style="font-size: 0.65rem;">PARTNER WITH US</span>
                <p class="small fw-bold mb-1">Launch Your Auto Shop</p>
                <p class="text-white-50 mb-3" style="font-size: 0.75rem; line-height: 1.2;">
                    Join our marketplace and reach thousands of buyers searching for parts.
                </p>
                
                {{-- Updated to shop.index (The Landing/Welcome route) --}}
                <a href="{{ route('shop.index') }}" class="btn btn-sm btn-primary w-100 rounded-pill fw-bold py-2">
                    <i class="fas fa-rocket me-1 small"></i> Become a Vendor
                </a>
            </div>
        </div>
    </div>

@elseif(auth()->user()->shop && !auth()->user()->hasActiveShop())
    {{-- Pending Verification Card --}}
    <div class="px-4 mt-2 mb-4">
        <div class="p-3 rounded-4 bg-white border border-warning text-center shadow-sm">
            <div class="mb-2">
                <i class="fas fa-user-shield text-warning mb-2" style="font-size: 1.5rem;"></i>
                <p class="small fw-bold mb-1 text-dark">Verification in Progress</p>
                <p class="text-muted mb-3" style="font-size: 0.7rem; line-height: 1.2;">
                    Our team is reviewing your RDB documents. We'll notify you once your shop is live.
                </p>
            </div>
            
            {{-- Updated to shop.status --}}
            <a href="{{ route('shop.status') }}" class="btn btn-sm btn-warning w-100 rounded-pill fw-bold text-white">
                <i class="fas fa-clock me-1 small"></i> Check Status
            </a>
        </div>
    </div>

@elseif(auth()->user()->hasActiveShop())
    {{-- Management Card for Shop Owners --}}
    <div class="px-4 mt-2 mb-4">
        <div class="p-3 rounded-4 bg-light border text-center shadow-sm">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3 text-start">
                    <p class="small fw-bold mb-0 text-truncate" style="max-width: 120px;">
                        {{ auth()->user()->shop->shop_name }}
                    </p>
                    <span class="text-success" style="font-size: 0.7rem;">
                        <i class="fas fa-check-circle me-1"></i>Active Shop
                    </span>
                </div>
            </div>

            {{-- Merchant Panel remains the same, ensure this route exists elsewhere --}}
            <a href="{{ route('shop.dashboard') }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill fw-bold">
                <i class="fas fa-external-link-alt me-1 small"></i> Merchant Panel
            </a>
        </div>
    </div>
@endif
</aside>