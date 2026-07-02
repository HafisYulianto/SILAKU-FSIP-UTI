<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Capaian IKU - SILAKU FSIP</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: white;
            color: #111827;
            font-family: 'Outfit', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Double border styling for Kop Surat */
        .kop-border {
            border-bottom: 3px double #000;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
            body {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body class="p-8 max-w-4xl mx-auto">

    {{-- Floating control bar (hidden in print) --}}
    <div class="no-print mb-6 p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between">
        <div>
            <h4 class="font-bold text-gray-900 text-sm">Mode Pratinjau Laporan IKU</h4>
            <p class="text-xs text-gray-500">Gunakan dialog print browser Anda untuk menyimpan sebagai PDF atau cetak ke printer fisik.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-lg text-xs transition-colors">
                 Kembali
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-xs transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    {{-- KOP SURAT --}}
    <div class="flex items-center gap-4 pb-4 mb-6 kop-border">
        <img src="{{ asset('images/Logo FSIP 1.png') }}" alt="Logo FSIP" class="w-20 h-auto object-contain">
        <div class="flex-1 text-center pr-10">
            <h1 class="text-xs font-semibold tracking-wider text-gray-700 uppercase">YAYASAN PENDIDIKAN TEKNOKRAT</h1>
            <h2 class="text-lg font-bold text-gray-950 uppercase leading-none">UNIVERSITAS TEKNOKRAT INDONESIA</h2>
            <h3 class="text-sm font-bold text-gray-800 uppercase mt-0.5">FAKULTAS SASTRA DAN ILMU PENDIDIKAN</h3>
            <p class="text-[9px] text-gray-600 mt-1 leading-tight">
                Jl. H. ZA. Pagar Alam No. 9-11 Labuhan Ratu, Bandar Lampung | Telp: (0721) 70 20 22<br>
                Website: <span class="underline">www.teknokrat.ac.id</span> | Email: <span class="underline">fsip@teknokrat.ac.id</span>
            </p>
        </div>
    </div>

    {{-- REPORT TITLE --}}
    <div class="text-center mb-6">
        <h2 class="text-base font-bold text-gray-950 tracking-wide uppercase">LAPORAN CAPAIAN INDIKATOR KINERJA UTAMA (IKU)</h2>
        <p class="text-xs text-gray-500 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-5 gap-4 mb-8 text-center">
        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50/50">
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Dosen</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_dosen']) }}</p>
        </div>
        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50/50">
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Mahasiswa</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_mahasiswa']) }}</p>
        </div>
        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50/50">
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Alumni</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_alumni']) }}</p>
        </div>
        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50/50">
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Kategori</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_entities']) }}</p>
        </div>
        <div class="p-3 border border-gray-200 rounded-lg bg-gray-50/50">
            <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Total Prodi</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_prodi']) }}</p>
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="p-4 border border-gray-200 rounded-lg">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b pb-2 mb-3">Sebaran Per Program Studi</h3>
            <div id="chart-prodi-distribution" class="h-64"></div>
        </div>
        <div class="p-4 border border-gray-200 rounded-lg">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b pb-2 mb-3">Sebaran Kategori Data</h3>
            <div id="chart-entity-summary" class="h-64 flex items-center justify-center"></div>
        </div>
    </div>

    {{-- RECENT LOG ENTRIES --}}
    <div class="mb-8">
        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wide border-b pb-2 mb-3">Catatan Pengisian Data Terbaru</h3>
        <table class="w-full text-xs text-left border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase text-[9px] tracking-wider">
                    <th class="p-2 border-r border-gray-200 text-center w-8">No</th>
                    <th class="p-2 border-r border-gray-200 w-32">Waktu Pengisian</th>
                    <th class="p-2 border-r border-gray-200 w-36">Pelapor</th>
                    <th class="p-2 border-r border-gray-200 w-44">Kategori Data</th>
                    <th class="p-2 border-r border-gray-200 w-28">Program Studi</th>
                    <th class="p-2">Keterangan Ringkas</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentRecords as $idx => $record)
                <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                    <td class="p-2 border-r border-gray-200 text-center">{{ $idx + 1 }}</td>
                    <td class="p-2 border-r border-gray-200 text-gray-600">{{ $record->created_at->translatedFormat('d F Y, H:i') }}</td>
                    <td class="p-2 border-r border-gray-200 font-semibold text-gray-800">{{ $record->creator->name ?? 'System' }}</td>
                    <td class="p-2 border-r border-gray-200 text-gray-800">{{ $record->entity->name }}</td>
                    <td class="p-2 border-r border-gray-200 text-gray-600">{{ $record->programStudi->name ?? 'FSIP' }}</td>
                    <td class="p-2 text-gray-500 truncate max-w-xs">{{ Str::limit($record->description ?? 'Input data rutin', 60) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">Belum ada catatan pengisian data terdaftar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- SIGNATURE SECTION --}}
    <div class="mt-12 flex justify-between text-xs">
        <div>
            <p>Dibuat Oleh,</p>
            <p class="font-bold text-gray-900 mt-1">Administrator BAAK FSIP</p>
            <div class="h-16"></div>
            <p class="underline">________________________</p>
            <p class="text-gray-500 mt-1">Staf Adm. Akademik</p>
        </div>
        <div class="text-right pr-6">
            <p>Bandar Lampung, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p class="font-bold text-gray-900 mt-1">Dekan Fakultas Sastra dan Ilmu Pendidikan</p>
            <div class="h-16"></div>
            <p class="underline font-bold text-gray-950">(Dr. Heri Kuswoyo, M.Hum.)</p>
            <p class="text-gray-500 mt-1">NIK: 02.04.14.01</p>
        </div>
    </div>

    {{-- Chart Scripts --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Program Studi Distribution Chart
        const prodiData = @json($prodiDistribution);
        if (prodiData.labels.length > 0) {
            const prodiChart = new ApexCharts(document.querySelector('#chart-prodi-distribution'), {
                chart: { 
                    type: 'bar', 
                    height: 250, 
                    toolbar: { show: false }, 
                    fontFamily: 'Outfit, sans-serif' 
                },
                series: [
                    { name: 'Dosen', data: prodiData.dosen },
                    { name: 'Mahasiswa', data: prodiData.mahasiswa },
                    { name: 'Alumni', data: prodiData.alumni }
                ],
                xaxis: { categories: prodiData.labels },
                colors: ['#10b981', '#3b82f6', '#14b8a6'],
                plotOptions: { bar: { borderRadius: 6, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top', fontSize: '10px' },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 }
            });
            prodiChart.render();
        } else {
            document.querySelector('#chart-prodi-distribution').innerHTML = '<div class="text-gray-400">Belum ada data</div>';
        }

        // Entity Summary Donut
        const totalDosen = {{ $stats['total_dosen'] }};
        const totalMahasiswa = {{ $stats['total_mahasiswa'] }};
        const totalAlumni = {{ $stats['total_alumni'] }};
        if (totalDosen > 0 || totalMahasiswa > 0 || totalAlumni > 0) {
            const entityChart = new ApexCharts(document.querySelector('#chart-entity-summary'), {
                chart: { 
                    type: 'donut', 
                    height: 250, 
                    toolbar: { show: false },
                    fontFamily: 'Outfit, sans-serif' 
                },
                series: [totalDosen, totalMahasiswa, totalAlumni],
                labels: ['Dosen', 'Mahasiswa', 'Alumni'],
                colors: ['#10b981', '#3b82f6', '#14b8a6'],
                legend: { position: 'bottom', fontSize: '10px' },
                dataLabels: { enabled: true, style: { fontSize: '10px' } },
                plotOptions: { pie: { donut: { size: '55%', labels: { show: true, total: { show: true, label: 'Total Data', fontSize: '12px', fontWeight: 700 } } } } }
            });
            entityChart.render();
        } else {
            document.querySelector('#chart-entity-summary').innerHTML = '<div class="text-gray-400">Belum ada data</div>';
        }

        // Auto print window trigger after chart renders and animations complete
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 1800); // 1.8s is perfect to let charts render completely
        };
    });
    </script>
</body>
</html>
