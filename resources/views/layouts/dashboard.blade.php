<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title') | AutoSpare Link Dashboard</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    
    <style>
        /* Layout Structure */
        body { background-color: #f8f9fa; }
        .dashboard-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar Styling */
        .sidebar { 
            width: 280px; 
            min-width: 280px;
            background: #ffffff; 
            transition: all 0.3s;
            z-index: 1000;
        }

        /* Content Area */
        .main-content { 
            flex: 1; 
            width: 100%;
            overflow-x: hidden;
        }

        /* Helper Classes for the Spare Parts UI */
        .bg-soft-primary { background-color: rgba(0, 123, 255, 0.1) !important; color: #007bff; }
        .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; color: #ffc107; }
        .bg-soft-success { background-color: rgba(40, 167, 69, 0.1) !important; color: #28a745; }
        
        .badge-soft-primary { 
            background-color: rgba(0, 123, 255, 0.1); 
            color: #007bff; 
            font-weight: 600;
        }

        /* Nav Link Hover Effects */
        .sidebar .nav-link {
            border-radius: 8px;
            margin: 0 10px;
            transition: 0.2s;
        }
        .sidebar .nav-link:hover {
            background-color: #f1f4f9;
            color: #007bff !important;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        /* Responsive Fixes */
        @media (max-width: 768px) {
            .sidebar { display: none; }
        }
    </style>
    @livewireStyles
</head>
<body>
    {{-- Top Navigation --}}
    @include('partials.dashboard-nav')

    <div class="dashboard-wrapper">
        {{-- Side Navigation --}}
        @include('partials.sidebar')

        {{-- Main View Container --}}
        <main class="main-content">
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
</body>
</html>