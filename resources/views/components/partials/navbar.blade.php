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