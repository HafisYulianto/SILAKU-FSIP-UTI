<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — SILAKU FSIP</title>
    <meta name="description" content="Sistem Pelaporan IKU - FSIP Universitas Teknokrat Indonesia">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50/50" x-data="{ sidebarOpen: true, mobileMenu: false }">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Mobile overlay --}}
    <div x-show="mobileMenu" x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden" @click="mobileMenu = false">
    </div>

    {{-- Main content --}}
    <div class="lg:ml-64 min-h-screen transition-all duration-300">
        {{-- Topbar --}}
        @include('components.topbar')

        {{-- Page content --}}
        <main class="pt-20 pb-8 px-4 sm:px-6 lg:px-8">
            {{-- Flash messages --}}
            @include('partials.flash-message')

            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>
