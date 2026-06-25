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
    </style>
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
            {{ $slot }}
        </main>
    </div>

    {{-- ═══════════════════════════════════════════════ --}}
    {{-- SweetAlert2 Toast Notification System          --}}
    {{-- ═══════════════════════════════════════════════ --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        /* ─── Toast Factory ──────────────────────────── */
        const SilakuToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            showClass: {
                popup: 'swal2-show',
                backdrop: 'swal2-backdrop-show'
            },
            customClass: {
                popup: 'swal-toast-popup'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        /* ─── Flash → Toast ──────────────────────────── */
        @if(session('success'))
        SilakuToast.fire({
            icon: 'success',
            title: {!! json_encode(session('success')) !!},
            customClass: { popup: 'swal-toast-popup swal-toast-success' }
        });
        @endif

        @if(session('error'))
        SilakuToast.fire({
            icon: 'error',
            title: {!! json_encode(session('error')) !!},
            timer: 6000,
            customClass: { popup: 'swal-toast-popup swal-toast-error' }
        });
        @endif

        @if(session('info'))
        SilakuToast.fire({
            icon: 'info',
            title: {!! json_encode(session('info')) !!},
            customClass: { popup: 'swal-toast-popup swal-toast-info' }
        });
        @endif

        @if(session('warning'))
        SilakuToast.fire({
            icon: 'warning',
            title: {!! json_encode(session('warning')) !!},
            customClass: { popup: 'swal-toast-popup swal-toast-warning' }
        });
        @endif

        @if($errors->any())
        SilakuToast.fire({
            icon: 'error',
            title: 'Terdapat kesalahan pada input Anda',
            timer: 6000,
            customClass: { popup: 'swal-toast-popup swal-toast-error' }
        });
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
                    confirmText  = '🗑️ Ya, Hapus';
                    titleText    = 'Yakin Ingin Menghapus?';
                } else if (lowerMsg.includes('tolak') || lowerMsg.includes('reject')) {
                    confirmColor = '#ef4444';
                    iconType     = 'warning';
                    confirmText  = '✕ Ya, Tolak';
                    titleText    = 'Konfirmasi Penolakan';
                } else if (lowerMsg.includes('setujui') || lowerMsg.includes('approve') || lowerMsg.includes('terima')) {
                    confirmColor = '#059669';
                    iconType     = 'success';
                    confirmText  = '✓ Ya, Setujui';
                    titleText    = 'Konfirmasi Persetujuan';
                }

                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: titleText,
                        html: `<p style="margin:0;line-height:1.6">${message}</p>`,
                        icon: iconType,
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true,
                        customClass: {
                            popup: 'swal-custom-popup'
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
                                title: 'Memproses...',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                customClass: { popup: 'swal-custom-popup' },
                                didOpen: () => { Swal.showLoading(); }
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
</body>
</html>
