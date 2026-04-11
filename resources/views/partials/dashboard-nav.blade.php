<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top shadow-sm">
    <div class="container-fluid">
        {{-- Brand / Logo --}}
        <a class="navbar-brand font-weight-bold text-primary" href="{{ url('/') }}">
            <i class="fas fa-tools mr-2"></i> AutoSpare <span class="text-dark">Link</span>
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#dashboardNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="dashboardNav">
            {{-- Search Bar (Optional for dashboard - helpful for finding orders) --}}
            <form class="form-inline ml-auto d-none d-md-flex">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control border-0 bg-light" placeholder="Search orders..." style="width: 250px;">
                    <div class="input-group-append">
                        <button class="btn btn-light border-0 bg-light text-muted" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <ul class="navbar-nav ml-auto align-items-center">
                {{-- Notifications Dropdown --}}
                <li class="nav-item dropdown mx-2">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" data-toggle="dropdown">
                        <i class="fas fa-bell text-muted"></i>
                        @if($stats['pending_tickets'] > 0)
                            <span class="badge badge-danger position-absolute" style="top: 0; right: 0; font-size: 10px;">{{ $stats['pending_tickets'] }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow border-0" style="width: 280px;">
                        <h6 class="dropdown-header">Notifications</h6>
                        @if($stats['pending_tickets'] > 0)
                            <a class="dropdown-item d-flex align-items-center py-3" href="{{ route('tickets.index') }}">
                                <div class="mr-3">
                                    <div class="bg-soft-warning p-2 rounded-circle text-warning">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </div>
                                </div>
                                <div>
                                    <span class="small text-muted">Support</span>
                                    <p class="mb-0 small">You have {{ $stats['pending_tickets'] }} tickets awaiting your reply.</p>
                                </div>
                            </a>
                        @else
                            <div class="text-center py-3 text-muted small">No new notifications</div>
                        @endif
                    </div>
                </li>

                {{-- User Profile Dropdown --}}
                <li class="nav-item dropdown ml-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" data-toggle="dropdown">
                        <div class="avatar-sm mr-2">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" class="rounded-circle" width="35" height="35" alt="User">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px; font-size: 12px;">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <span class="d-none d-md-inline text-dark small font-weight-bold">{{ $user->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow border-0 mt-2">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-circle mr-2 text-muted"></i> Account Settings
                        </a>
                        <a class="dropdown-item" href="{{ route('orders.index') }}">
                            <i class="fas fa-history mr-2 text-muted"></i> Order History
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>