<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <title>{{ $title ?? 'Dashboard' }} | {{ $shop->name ?? 'Shop' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Manage your shop inventory, sales, and analytics on the {{ $shop->name ?? 'Shop' }} dashboard.">
    <meta name="robots" content="noindex, nofollow"> <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/img/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/img/logo.png') }}">

    {{-- 1. Google Fonts are fine here (but preconnect first for speed) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-wx3ZPVD6pK+... (truncated) ..." crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <script>
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>

    @stack('styles')

    @vite(['resources/js/userdashboard/main.js'])
    @livewireStyles
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
    @livewireScripts
</body>
</html>