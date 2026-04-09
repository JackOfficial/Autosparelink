<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Dashboard' }} | {{ $shop->name ?? 'Shop' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Manage your shop inventory, sales, and analytics on the {{ $shop->name }} dashboard.">
    <meta name="robots" content="noindex, nofollow"> <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/img/logo.png') }}">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
    
    @stack('styles')

    @vite(['resources/js/userdashboard/main.js'])
</head>

<body class="bg-body-tertiary">
    <div id="overlay" class="overlay"></div>

    <div class="layout-wrapper d-flex"> <x-partials.sidebar />

        <main id="content" class="content d-flex flex-column flex-grow-1">
            <x-partials.navbar />
           {{ $slot }}
            <x-partials.footer />
        </main>
    </div>
    @stack('scripts')
</body>
</html>