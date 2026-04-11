<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm">
    <div class="container-fluid">
        {{-- Mobile Sidebar Toggle (Alpine.js controlled) --}}
        <button @click="sidebarOpen = !sidebarOpen" class="btn btn-light d-md-none me-2 border-0">
            <i class="fas fa-bars"></i>
        </button>

        {{-- Brand / Logo --}}
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
            <i class="fas fa-tools me-2"></i> AutoSpare <span class="text-dark">Link</span>
        </a>

        {{-- Standard BS5 Navbar Toggle (for the nav links themselves on mobile) --}}
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNav">
            <span class="navbar-toggler-icon" style="width: 1.2em; height: 1.2em;"></span>
        </button>

        <div class="collapse navbar-collapse" id="dashboardNav">
            {{-- Search Bar --}}
            <form class="d-none d-md-flex ms-auto me-3">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control border-0 bg-light" placeholder="Search orders..." style="width: 250px;">
                    <button class="btn btn-light border-0 bg-light text-muted" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-center">
                {{-- Notifications Dropdown --}}
                <li class="nav-item dropdown mx-lg-2">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell text-muted"></i>
                        @if($stats['pending_tickets'] > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                {{ $stats['pending_tickets'] }}
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow border-0 py-0 mt-3" aria-labelledby="notificationDropdown" style="width: 300px; overflow: hidden;">
                        <div class="p-3 border-bottom">
                            <h6 class="mb-0 fw-bold">Notifications</h6>
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @if($stats['pending_tickets'] > 0)
                                <a class="dropdown-item d-flex align-items-center py-3 border-bottom" href="{{ route('tickets.index') }}">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="bg-soft-warning p-2 rounded-circle text-warning">
                                            <i class="fas fa-envelope-open-text"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="small fw-bold d-block text-dark">Support Update</span>
                                        <p class="mb-0 small text-muted">You have {{ $stats['pending_tickets'] }} tickets awaiting reply.</p>
                                    </div>
                                </a>
                            @else
                                <div class="text-center py-4 text-muted small">
                                    <i class="fas fa-check-circle d-block mb-2 fa-2x opacity-25"></i>
                                    No new notifications
                                </div>
                            @endif
                        </div>
                    </div>
                </li>

                {{-- User Profile Dropdown --}}
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="me-2">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" class="rounded-circle border" width="35" height="35" alt="User">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 35px; height: 35px; font-size: 12px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <span class="d-none d-md-inline text-dark small fw-bold">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle me-2 text-muted"></i> Account Settings</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('orders.index') }}"><i class="fas fa-history me-2 text-muted"></i> Order History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>