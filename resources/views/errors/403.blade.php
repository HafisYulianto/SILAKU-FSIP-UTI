<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak — SILAKU</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4 font-sans">
    <div class="text-center max-w-md fade-in">
        {{-- Icon --}}
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        {{-- Text --}}
        <h1 class="text-6xl font-bold text-gray-900 mb-2">403</h1>
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Akses Ditolak</h2>
        <p class="text-gray-500 mb-8">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
            @auth
                <br>Anda login sebagai <span class="font-semibold text-primary-600">{{ auth()->user()->roles->first()?->name ?? 'User' }}</span>.
            @endauth
        </p>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-secondary">
                ← Halaman Sebelumnya
            </a>
        </div>

        {{-- Footer --}}
        <p class="text-xs text-gray-300 mt-12">SILAKU — FSIP Universitas Teknokrat Indonesia</p>
    </div>
</body>
</html>
