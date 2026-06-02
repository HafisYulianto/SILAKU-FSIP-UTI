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
        .swal2-popup {
            border-radius: 1.25rem !important;
            font-family: 'Inter', sans-serif !important;
            padding: 1.75rem !important;
        }
        .swal2-title {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
            color: #111827 !important;
        }
        .swal2-html-container {
            font-size: 0.95rem !important;
            color: #4b5563 !important;
            margin: 0.75rem 0 0 0 !important;
            line-height: 1.5 !important;
        }
        .swal2-actions {
            margin-top: 1.5rem !important;
            gap: 0.75rem !important;
        }
        .swal2-confirm, .swal2-cancel {
            padding: 0.625rem 1.25rem !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            border-radius: 0.75rem !important;
            margin: 0 !important;
            transition: all 0.2s !important;
        }
        .swal2-confirm:hover {
            transform: translateY(-1px);
        }
        .swal2-cancel:hover {
            transform: translateY(-1px);
        }
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
            {{-- Flash messages --}}
            @include('partials.flash-message')

            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cari semua form yang memiliki atribut onsubmit berisi confirm(
            const forms = document.querySelectorAll('form[onsubmit*="confirm("]');
            
            forms.forEach(form => {
                const onsubmitAttr = form.getAttribute('onsubmit');
                if (!onsubmitAttr) return;

                // Ekstrak string pesan di dalam confirm('...')
                const match = onsubmitAttr.match(/confirm\((['"`])(.*?)\1\)/);
                if (match) {
                    const message = match[2];
                    
                    // Hapus onsubmit inline agar tidak memicu dialog bawaan browser
                    form.removeAttribute('onsubmit');
                    
                    // Tentukan tema visual SweetAlert2 berdasarkan jenis pesan
                    let confirmButtonColor = '#059669'; // Emerald-600 (Primary)
                    let cancelButtonColor = '#6b7280'; // Gray-500
                    let iconType = 'question';
                    let confirmButtonText = 'Ya, Lanjutkan';
                    let titleText = 'Konfirmasi Tindakan';
                    
                    const lowerMsg = message.toLowerCase();
                    
                    if (lowerMsg.includes('hapus') || lowerMsg.includes('delete') || lowerMsg.includes('bersihkan')) {
                        confirmButtonColor = '#dc2626'; // Red-600
                        iconType = 'warning';
                        confirmButtonText = 'Ya, Hapus';
                        titleText = 'Konfirmasi Penghapusan';
                    } else if (lowerMsg.includes('tolak') || lowerMsg.includes('reject')) {
                        confirmButtonColor = '#dc2626'; // Red-600
                        iconType = 'warning';
                        confirmButtonText = 'Ya, Tolak';
                        titleText = 'Konfirmasi Penolakan';
                    } else if (lowerMsg.includes('setujui') || lowerMsg.includes('approve') || lowerMsg.includes('terima')) {
                        confirmButtonColor = '#059669'; // Emerald-600
                        iconType = 'success';
                        confirmButtonText = 'Ya, Setujui';
                        titleText = 'Konfirmasi Persetujuan';
                    }
                    
                    // Daftarkan listener submit kustom
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        
                        Swal.fire({
                            title: titleText,
                            text: message,
                            icon: iconType,
                            showCancelButton: true,
                            confirmButtonColor: confirmButtonColor,
                            cancelButtonColor: cancelButtonColor,
                            confirmButtonText: confirmButtonText,
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // Submit form secara programmatis
                            }
                        });
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
