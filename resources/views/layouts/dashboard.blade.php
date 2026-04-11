<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | AutoSpare Link Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Layout Structure */
        body { background-color: #f8f9fa; overflow-x: hidden; }
        .dashboard-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { 
            width: 280px; 
            min-width: 280px;
            background: #ffffff; 
            transition: all 0.3s ease;
            z-index: 1000;
            border-right: 1px solid #dee2e6;
        }

        /* Content Area */
        .main-content { 
            flex: 1; 
            width: 100%;
            transition: all 0.3s ease;
        }

        /* BS5 Utility Colors (Soft UI) */
        .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1) !important; color: #0d6efd; }
        .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; color: #997404; }
        .bg-soft-success { background-color: rgba(25, 135, 84, 0.1) !important; color: #198754; }
        .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1) !important; color: #dc3545; }

        /* Nav Link Effects */
        .sidebar .nav-link {
            border-radius: 8px;
            margin: 4px 15px;
            padding: 10px 15px;
            color: #495057;
            font-weight: 500;
            transition: 0.2s;
        }
        .sidebar .nav-link:hover {
            background-color: #f1f4f9;
            color: #0d6efd;
        }
        .sidebar .nav-link.active {
            background-color: #0d6efd;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .sidebar { 
                margin-left: -280px; 
                position: fixed; 
                height: 100%;
            }
            .sidebar.show { margin-left: 0; }
        }
    </style>
    @livewireStyles
</head>
<body x-data="{ sidebarOpen: false }">
    
    {{-- Top Navigation --}}
    @include('partials.dashboard-nav')

    <div class="dashboard-wrapper">
        {{-- Side Navigation --}}
        {{-- We use Alpine's :class to toggle sidebar on mobile --}}
        <div :class="sidebarOpen ? 'show' : ''" class="sidebar shadow-sm">
            @include('partials.sidebar')
        </div>

        {{-- Main View Container --}}
        <main class="main-content">
            <div class="container-fluid py-4">
                @yield('content')
            </div>
            @include('partials.dashboard-footer')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>