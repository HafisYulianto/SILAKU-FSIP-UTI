<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — SILAKU FSIP</title>
    <!-- OpenGraph SEO Preview Metadata -->
    <meta property="og:title" content="{{ $title ?? 'Dashboard' }} — SILAKU FSIP">
    <meta property="og:description" content="Sistem Pelaporan IKU - Platform digital terpadu untuk pelaporan dan manajemen data Indikator Kinerja Utama FSIP Universitas Teknokrat Indonesia.">
    <meta property="og:image" content="{{ asset('images/Logo FSIP 1.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Dashboard' }} — SILAKU FSIP">
    <meta name="twitter:description" content="Sistem Pelaporan IKU - Platform digital terpadu untuk pelaporan dan manajemen data Indikator Kinerja Utama FSIP Universitas Teknokrat Indonesia.">
    <meta name="twitter:image" content="{{ asset('images/Logo FSIP 1.png') }}">
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <meta name="description" content="Sistem Pelaporan IKU - FSIP Universitas Teknokrat Indonesia">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ──────────────────────────────────────────────── */
        /*  Custom SweetAlert2 Theme — Premium Glassmorphism */
        /* ──────────────────────────────────────────────── */
        .swal2-popup.swal-custom-popup {
            border-radius: 1.5rem !important;
            font-family: 'Inter', sans-serif !important;
            padding: 2rem 2rem 1.5rem !important;
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(20px) saturate(180%) !important;
            -webkit-backdrop-filter: blur(20px) saturate(180%) !important;
            border: 1px solid rgba(255,255,255,0.7) !important;
            box-shadow: 0 25px 60px -12px rgba(0,0,0,0.15),
                        0 0 0 1px rgba(255,255,255,0.5) inset !important;
            transition: background 0.3s, border-color 0.3s !important;
        }
        .swal2-popup.swal-custom-popup .swal2-title {
            font-size: 1.2rem !important;
            font-weight: 800 !important;
            color: #111827 !important;
            letter-spacing: -0.01em !important;
        }
        .swal2-popup.swal-custom-popup .swal2-html-container {
            font-size: 0.9rem !important;
            color: #6b7280 !important;
            margin: 0.5rem 0 0 0 !important;
            line-height: 1.6 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon {
            margin: 0.5rem auto 1rem !important;
            border-width: 3px !important;
            width: 60px !important;
            height: 60px !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon .swal2-icon-content {
            font-size: 2rem !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-success {
            border-color: #059669 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #059669 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(5,150,105,0.3) !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-error {
            border-color: #ef4444 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
            background-color: #ef4444 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-question {
            border-color: #6366f1 !important;
            color: #6366f1 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-icon.swal2-info {
            border-color: #3b82f6 !important;
            color: #3b82f6 !important;
        }
        .swal2-popup.swal-custom-popup .swal2-actions {
            margin-top: 1.25rem !important;
            gap: 0.75rem !important;
        }
        .swal2-popup.swal-custom-popup .swal2-styled {
            padding: 0.65rem 1.5rem !important;
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            border-radius: 0.875rem !important;
            margin: 0 !important;
            letter-spacing: 0.01em !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 2px 8px -2px rgba(0,0,0,0.15) !important;
        }
        .swal2-popup.swal-custom-popup .swal2-styled:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 16px -4px rgba(0,0,0,0.2) !important;
        }
        .swal2-popup.swal-custom-popup .swal2-styled:active {
            transform: translateY(0) !important;
        }
        .swal2-popup.swal-custom-popup .swal2-styled.swal2-cancel {
            background-color: #f3f4f6 !important;
            color: #4b5563 !important;
            box-shadow: 0 1px 4px -1px rgba(0,0,0,0.1) !important;
        }
        .swal2-popup.swal-custom-popup .swal2-styled.swal2-cancel:hover {
            background-color: #e5e7eb !important;
        }

        /* Toast notifications */
        .swal2-popup.swal-toast-popup {
            font-family: 'Inter', sans-serif !important;
            border-radius: 1rem !important;
            padding: 0.875rem 1.25rem !important;
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.18),
                        0 0 0 1px rgba(255,255,255,0.3) inset !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            transition: background 0.3s !important;
        }
        .swal2-popup.swal-toast-popup .swal2-title {
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .swal2-popup.swal-toast-popup .swal2-icon {
            margin: 0 0.75rem 0 0 !important;
            width: 28px !important;
            height: 28px !important;
            border-width: 2px !important;
        }
        .swal2-popup.swal-toast-popup .swal2-icon .swal2-icon-content {
            font-size: 1rem !important;
        }

        /* Success toast */
        .swal-toast-success {
            background: linear-gradient(135deg, rgba(236,253,245,0.97) 0%, rgba(209,250,229,0.97) 100%) !important;
            border-left: 4px solid #059669 !important;
        }
        .swal-toast-success .swal2-title { color: #065f46 !important; }
        .swal-toast-success .swal2-icon.swal2-success { border-color: #059669 !important; }
        .swal-toast-success .swal2-icon.swal2-success [class^='swal2-success-line'] { background-color: #059669 !important; }
        .swal-toast-success .swal2-icon.swal2-success .swal2-success-ring { border-color: rgba(5,150,105,0.3) !important; }

        /* Error toast */
        .swal-toast-error {
            background: linear-gradient(135deg, rgba(254,242,242,0.97) 0%, rgba(254,226,226,0.97) 100%) !important;
            border-left: 4px solid #ef4444 !important;
        }
        .swal-toast-error .swal2-title { color: #991b1b !important; }
        .swal-toast-error .swal2-icon.swal2-error { border-color: #ef4444 !important; }
        .swal-toast-error .swal2-icon.swal2-error [class^='swal2-x-mark-line'] { background-color: #ef4444 !important; }

        /* Info toast */
        .swal-toast-info {
            background: linear-gradient(135deg, rgba(239,246,255,0.97) 0%, rgba(219,234,254,0.97) 100%) !important;
            border-left: 4px solid #3b82f6 !important;
        }
        .swal-toast-info .swal2-title { color: #1e40af !important; }

        /* Warning toast */
        .swal-toast-warning {
            background: linear-gradient(135deg, rgba(255,251,235,0.97) 0%, rgba(254,243,199,0.97) 100%) !important;
            border-left: 4px solid #f59e0b !important;
        }
        .swal-toast-warning .swal2-title { color: #92400e !important; }

        /* SweetAlert2 backdrop */
        .swal2-container.swal2-backdrop-show {
            background: rgba(17,24,39,0.4) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
        }

        /* Timer bar */
        .swal2-popup.swal-toast-popup .swal2-timer-progress-bar {
            height: 3px !important;
            border-radius: 999px !important;
        }
        .swal-toast-success .swal2-timer-progress-bar { background: #059669 !important; }
        .swal-toast-error .swal2-timer-progress-bar { background: #ef4444 !important; }
        .swal-toast-info .swal2-timer-progress-bar { background: #3b82f6 !important; }
        .swal-toast-warning .swal2-timer-progress-bar { background: #f59e0b !important; }

        /* Dark mode overrides for SweetAlert2 */
        .dark .swal2-popup.swal-custom-popup {
            background: rgba(17, 24, 39, 0.95) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            box-shadow: 0 25px 60px -12px rgba(0,0,0,0.5),
                        0 0 0 1px rgba(255,255,255,0.05) inset !important;
        }
        .dark .swal2-popup.swal-custom-popup .swal2-title {
            color: #ffffff !important;
        }
        .dark .swal2-popup.swal-custom-popup .swal2-html-container {
            color: #d1d5db !important;
        }
        .dark .swal2-popup.swal-custom-popup .swal2-styled.swal2-cancel {
            background-color: #374151 !important;
            color: #e5e7eb !important;
        }
        .dark .swal2-popup.swal-custom-popup .swal2-styled.swal2-cancel:hover {
            background-color: #4b5563 !important;
        }
        .dark .swal-toast-success {
            background: linear-gradient(135deg, rgba(6, 78, 59, 0.95) 0%, rgba(2, 44, 34, 0.95) 100%) !important;
            border-left: 4px solid #10b981 !important;
        }
        .dark .swal-toast-success .swal2-title { color: #ecfdf5 !important; }
        .dark .swal-toast-error {
            background: linear-gradient(135deg, rgba(127, 29, 29, 0.95) 0%, rgba(69, 10, 10, 0.95) 100%) !important;
            border-left: 4px solid #ef4444 !important;
        }
        .dark .swal-toast-error .swal2-title { color: #fef2f2 !important; }
    </style>
</head>
<body class="h-full bg-gray-50/50" x-data="{ sidebarOpen: true, mobileMenu: false, darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => { if (val) { document.documentElement.classList.add('dark'); localStorage.setItem('theme', 'dark'); } else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme', 'light'); } })">

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
            {{ $slot }}
        </main>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- SweetAlert2 Toast Notification System          --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ─── Notification Alert Factory ──────────────────────────── */
        const showSilakuAlert = (icon, title, text) => {
            let confirmColor = '#059669'; // Emerald
            let svgIcon = '';
            
            if (icon === 'success') {
                confirmColor = '#059669';
                svgIcon = `
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 mb-5 ring-8 ring-emerald-500/10 dark:ring-emerald-400/5 animate__animated animate__pulse">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                `;
            } else if (icon === 'error') {
                confirmColor = '#ef4444';
                svgIcon = `
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 mb-5 ring-8 ring-red-500/10 dark:ring-red-400/5 animate__animated animate__pulse">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                `;
            } else if (icon === 'warning') {
                confirmColor = '#f59e0b';
                svgIcon = `
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 mb-5 ring-8 ring-amber-500/10 dark:ring-amber-400/5 animate__animated animate__pulse">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                `;
            } else {
                confirmColor = '#3b82f6';
                svgIcon = `
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 mb-5 ring-8 ring-blue-500/10 dark:ring-blue-400/5 animate__animated animate__pulse">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                `;
            }

            Swal.fire({
                html: `
                    <div class="text-center pt-2">
                        ${svgIcon}
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2" style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; margin-bottom:0.5rem; margin-top:0;">
                            ${title}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400" style="font-family:'Inter',sans-serif; font-size:0.9rem; color:#6b7280; line-height:1.6; margin:0;">
                            ${text}
                        </p>
                    </div>
                `,
                confirmButtonColor: confirmColor,
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'swal-custom-popup',
                    confirmButton: 'swal-confirm-btn'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            });
        };

        /* ─── Flash → Centered Alert ──────────────────────────── */
        @if(session('success'))
        showSilakuAlert('success', 'Berhasil', {!! json_encode(session('success')) !!});
        @endif

        @if(session('error'))
        showSilakuAlert('error', 'Error', {!! json_encode(session('error')) !!});
        @endif

        @if(session('info'))
        showSilakuAlert('info', 'Informasi', {!! json_encode(session('info')) !!});
        @endif

        @if(session('warning'))
        showSilakuAlert('warning', 'Peringatan', {!! json_encode(session('warning')) !!});
        @endif

        @if($errors->any())
        showSilakuAlert('error', 'Error', 'Terdapat kesalahan pada input Anda');
        @endif

        /* ─── Confirm Dialog Interceptor ─────────────── */
        const forms = document.querySelectorAll('form[onsubmit*="confirm("]');

        forms.forEach(form => {
            const onsubmitAttr = form.getAttribute('onsubmit');
            if (!onsubmitAttr) return;

            const match = onsubmitAttr.match(/confirm\((['"`])(.*?)\1\)/);
            if (match) {
                const message = match[2];
                form.removeAttribute('onsubmit');

                let confirmColor = '#059669';
                let iconType     = 'question';
                let confirmText  = 'Ya, Lanjutkan';
                let titleText    = 'Konfirmasi Tindakan';

                const lowerMsg = message.toLowerCase();

                if (lowerMsg.includes('hapus') || lowerMsg.includes('delete') || lowerMsg.includes('bersihkan') || lowerMsg.includes('kosongkan')) {
                    confirmColor = '#ef4444';
                    iconType     = 'warning';
                    confirmText  = 'Ya, Hapus';
                    titleText    = 'Yakin Ingin Menghapus?';
                } else if (lowerMsg.includes('tolak') || lowerMsg.includes('reject')) {
                    confirmColor = '#ef4444';
                    iconType     = 'warning';
                    confirmText  = 'Ya, Tolak';
                    titleText    = 'Konfirmasi Penolakan';
                } else if (lowerMsg.includes('setujui') || lowerMsg.includes('approve') || lowerMsg.includes('terima')) {
                    confirmColor = '#059669';
                    iconType     = 'success';
                    confirmText  = 'Ya, Setujui';
                    titleText    = 'Konfirmasi Persetujuan';
                }

                let svgIcon = '';
                if (iconType === 'warning') {
                    svgIcon = `
                        <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 mb-5 ring-8 ring-red-500/10 dark:ring-red-400/5 animate__animated animate__pulse">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                    `;
                } else if (iconType === 'success') {
                    svgIcon = `
                        <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 mb-5 ring-8 ring-emerald-500/10 dark:ring-emerald-400/5 animate__animated animate__pulse">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    `;
                } else {
                    svgIcon = `
                        <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 mb-5 ring-8 ring-indigo-500/10 dark:ring-indigo-400/5 animate__animated animate__pulse">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    `;
                }

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        html: `
                            <div class="text-center pt-2">
                                ${svgIcon}
                                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2" style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; margin-bottom:0.5rem; margin-top:0;">
                                    ${titleText}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400" style="font-family:'Inter',sans-serif; font-size:0.9rem; color:#6b7280; line-height:1.6; margin:0;">
                                    ${message}
                                </p>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true,
                        customClass: {
                            popup: 'swal-custom-popup',
                            confirmButton: 'swal-confirm-btn',
                            cancelButton: 'swal-cancel-btn'
                        },
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown animate__faster'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp animate__faster'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show brief loading state
                            Swal.fire({
                                html: `
                                    <div class="text-center pt-2">
                                        <div class="mx-auto flex items-center justify-center w-16 h-16 mb-5">
                                            <div class="animate-spin rounded-full h-10 w-10 border-4 border-primary-500 border-t-transparent"></div>
                                        </div>
                                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight" style="font-family:'Inter',sans-serif; font-size:1.25rem; font-weight:800; margin:0;">
                                            Memproses...
                                        </h3>
                                    </div>
                                `,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                customClass: { popup: 'swal-custom-popup' }
                            });
                            form.submit();
                        }
                    });
                });
            }
        });
    });
    </script>

    <!-- Animate.css for SweetAlert2 animations -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4/animate.min.css">

    @stack('scripts')

    @if(auth()->check() && (auth()->user()->hasRole('BAAK') || auth()->user()->hasRole('Kaprodi') || auth()->user()->hasRole('Dosen')))
        <x-chatbot.public-faq-chatbot :is-dashboard="true" />
    @endif
</body>
</html>
