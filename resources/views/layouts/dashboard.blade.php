<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | AutoSpare Link Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .dashboard-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #212529; color: #fff; padding-top: 20px; }
        .sidebar .nav-link { color: rgba(255,255,255,.7); padding: 12px 20px; transition: 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #0d6efd; }
        .main-content { flex: 1; background: #f8f9fa; padding: 30px; }
    </style>
    @livewireStyles
</head>
<body>
    @include('partials.dashboard-nav')

    <div class="dashboard-wrapper">
        <nav class="sidebar d-none d-md-block">
            <div class="px-4 mb-4">
                <h5 class="text-primary">Menu</h5>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('user/dashboard') ? 'active' : '' }}" href="/user/dashboard">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/user/orders">
                        <i class="fas fa-shopping-bag mr-2"></i> My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/user/tickets">
                        <i class="fas fa-ticket-alt mr-2"></i> Support
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/profile">
                        <i class="fas fa-user-cog mr-2"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    @livewireScripts
</body>
</html>