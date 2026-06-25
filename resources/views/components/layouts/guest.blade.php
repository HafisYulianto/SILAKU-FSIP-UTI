<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} — SILAKU FSIP</title>
    <meta name="description" content="Sistem Pelaporan IKU - FSIP Universitas Teknokrat Indonesia">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 sm:p-6 md:p-10 overflow-hidden">
    <!-- Fullscreen Background Image with Blur Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/hero-bg.jpg') }}" alt="Gedung FSIP UTI" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-950/90 via-primary-900/85 to-emerald-950/90 backdrop-blur-[3px]"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        {{ $slot }}
    </div>
</body>
</html>
