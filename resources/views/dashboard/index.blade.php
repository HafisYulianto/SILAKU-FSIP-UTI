<x-layouts.app :title="'Dashboard Akreditasi'">
    <div class="space-y-8 fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Dashboard Akreditasi</h1>
                <p class="page-subtitle">Ringkasan data FSIP Universitas Teknokrat Indonesia</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400">Terakhir diperbarui:</span>
                <span class="text-xs font-medium text-gray-600">{{ now()->translatedFormat('d F Y, H:i') }}</span>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Total Dosen --}}
            <div class="stat-card slide-up" style="animation-delay: 0ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Data Dosen</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_dosen']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    <span class="text-xs text-primary-600 font-medium">{{ $dosenEntities->count() }} kategori</span>
                </div>
            </div>

            {{-- Total Mahasiswa --}}
            <div class="stat-card slide-up" style="animation-delay: 100ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Data Mahasiswa</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_mahasiswa']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    <span class="text-xs text-blue-600 font-medium">{{ $mahasiswaEntities->count() }} kategori</span>
                </div>
            </div>

            {{-- Kategori Aktif --}}
            <div class="stat-card slide-up" style="animation-delay: 200ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kategori Data Aktif</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_entities']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Program Studi --}}
            <div class="stat-card slide-up" style="animation-delay: 300ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Program Studi</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_prodi']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pimpinan & Wakil Dekan Quick Access Portal --}}
        @hasanyrole('Pimpinan|Wakil Dekan')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 slide-up" style="animation-delay: 350ms">
            <a href="{{ route('pimpinan.browse', 'dosen') }}" class="group relative block overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute right-8 bottom-8 w-20 h-20 bg-white/5 rounded-full blur-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 group-hover:bg-white/30 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">📚 Lihat Data Dosen</h3>
                    <p class="text-primary-100 text-sm">Akses seluruh kategori dan record data dosen yang telah diinput oleh BAAK, Kaprodi, dan Dosen.</p>
                    <div class="mt-6 flex items-center gap-2 text-sm font-medium text-primary-200 group-hover:text-white transition-colors">
                        <span>Buka Halaman</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('pimpinan.browse', 'mahasiswa') }}" class="group relative block overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute right-8 bottom-8 w-20 h-20 bg-white/5 rounded-full blur-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 group-hover:bg-white/30 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">🎓 Lihat Data Mahasiswa</h3>
                    <p class="text-blue-100 text-sm">Akses seluruh kategori dan record data mahasiswa yang telah diinput oleh BAAK, Kaprodi, dan Dosen.</p>
                    <div class="mt-6 flex items-center gap-2 text-sm font-medium text-blue-200 group-hover:text-white transition-colors">
                        <span>Buka Halaman</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>
        </div>
        @endhasanyrole

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Program Studi Distribution --}}
            <div class="chart-card slide-up" style="animation-delay: 400ms">
                <h3 class="chart-title">Distribusi Data per Program Studi</h3>
                <div id="chart-prodi-distribution" class="h-80"></div>
            </div>

            {{-- Entity Summary Donut --}}
            <div class="chart-card slide-up" style="animation-delay: 500ms">
                <h3 class="chart-title">Sebaran Kategori Data</h3>
                <div id="chart-entity-summary" class="h-80"></div>
            </div>
        </div>

        {{-- Dynamic Charts from aggregatable fields --}}
        @if(count($chartData) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($chartData as $index => $chart)
            <div class="chart-card slide-up" style="animation-delay: {{ 600 + ($index * 100) }}ms">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ $chart['entity_name'] }}</h3>
                        <p class="text-xs text-gray-500">Berdasarkan {{ $chart['field_name'] }}</p>
                    </div>
                    <span class="badge {{ $chart['root_category'] === 'dosen' ? 'badge-primary' : 'badge-info' }}">
                        {{ ucfirst($chart['root_category']) }}
                    </span>
                </div>
                <div id="chart-dynamic-{{ $index }}" class="h-72"></div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Entity Overview Table --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Dosen Entities --}}
            <div class="card slide-up" style="animation-delay: 700ms">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">📚 Kategori Dosen</h3>
                    @role('BAAK')
                    <a href="{{ route('entities.create') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">+ Tambah</a>
                    @endrole
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($dosenEntities as $entity)
                    <a href="{{ route('entities.view', $entity) }}" class="flex items-center justify-between px-6 py-3 hover:bg-primary-50/50 transition-colors">
                        <span class="text-sm text-gray-700">{{ $entity->name }}</span>
                        <span class="badge-primary">{{ $entity->records_count }} data</span>
                    </a>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">Belum ada kategori dosen</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Mahasiswa Entities --}}
            <div class="card slide-up" style="animation-delay: 800ms">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">🎓 Kategori Mahasiswa</h3>
                    @role('BAAK')
                    <a href="{{ route('entities.create') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">+ Tambah</a>
                    @endrole
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($mahasiswaEntities as $entity)
                    <a href="{{ route('entities.view', $entity) }}" class="flex items-center justify-between px-6 py-3 hover:bg-blue-50/50 transition-colors">
                        <span class="text-sm text-gray-700">{{ $entity->name }}</span>
                        <span class="badge-info">{{ $entity->records_count }} data</span>
                    </a>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">Belum ada kategori mahasiswa</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card slide-up" style="animation-delay: 900ms">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentRecords as $record)
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-xs font-bold text-primary-700">
                            {{ substr($record->creator->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm text-gray-700"><span class="font-medium">{{ $record->creator->name ?? 'Unknown' }}</span> menambah data ke <span class="font-medium text-primary-600">{{ $record->entity->name }}</span></p>
                            <p class="text-xs text-gray-400">{{ $record->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($record->programStudi)
                    <span class="badge-info">{{ $record->programStudi->code }}</span>
                    @endif
                </div>
                @empty
                <div class="px-6 py-12 text-center empty-state">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-400 font-medium">Belum ada aktivitas</p>
                    <p class="text-sm text-gray-300 mt-1">Data akan muncul saat Anda mulai menginput</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const emeraldPalette = ['#10b981', '#059669', '#047857', '#34d399', '#6ee7b7', '#14b8a6', '#0d9488'];
        const pastelPalette = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'];

        // Program Studi Distribution Chart
        const prodiData = @json($prodiDistribution);
        if (prodiData.labels.length > 0) {
            new ApexCharts(document.querySelector('#chart-prodi-distribution'), {
                chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                series: [
                    { name: 'Dosen', data: prodiData.dosen },
                    { name: 'Mahasiswa', data: prodiData.mahasiswa }
                ],
                xaxis: { categories: prodiData.labels },
                colors: ['#10b981', '#3b82f6'],
                plotOptions: { bar: { borderRadius: 8, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top' },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'light' },
            }).render();
        } else {
            document.querySelector('#chart-prodi-distribution').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data</div>';
        }

        // Entity Summary Donut
        const dosenEntities = @json($dosenEntities);
        const mahasiswaEntities = @json($mahasiswaEntities);
        const allEntities = [...dosenEntities, ...mahasiswaEntities];
        if (allEntities.length > 0) {
            new ApexCharts(document.querySelector('#chart-entity-summary'), {
                chart: { type: 'donut', height: 300, fontFamily: 'Inter, sans-serif' },
                series: allEntities.map(e => e.records_count || 0),
                labels: allEntities.map(e => e.name),
                colors: pastelPalette,
                legend: { position: 'bottom', fontSize: '12px' },
                dataLabels: { enabled: true, style: { fontSize: '11px' } },
                plotOptions: { pie: { donut: { size: '55%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '14px', fontWeight: 700 } } } } },
            }).render();
        } else {
            document.querySelector('#chart-entity-summary').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data</div>';
        }

        // Dynamic Charts
        const dynamicCharts = @json($chartData);
        dynamicCharts.forEach(function(chart, index) {
            const el = document.querySelector('#chart-dynamic-' + index);
            if (!el) return;

            const data = chart.data;
            if (!data.labels || data.labels.length === 0) {
                el.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data</div>';
                return;
            }

            let options = {};
            if (chart.type === 'donut') {
                options = {
                    chart: { type: 'donut', height: 270, fontFamily: 'Inter, sans-serif' },
                    series: data.values,
                    labels: data.labels,
                    colors: emeraldPalette,
                    legend: { position: 'bottom', fontSize: '11px' },
                    plotOptions: { pie: { donut: { size: '55%' } } },
                };
            } else if (chart.type === 'area') {
                options = {
                    chart: { type: 'area', height: 270, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    series: [{ name: chart.field_name, data: data.values }],
                    xaxis: { categories: data.labels },
                    colors: ['#10b981'],
                    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1 } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                };
            } else {
                options = {
                    chart: { type: 'bar', height: 270, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
                    series: [{ name: chart.field_name, data: data.values }],
                    xaxis: { categories: data.labels },
                    colors: ['#10b981'],
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '55%', distributed: true } },
                    dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 700 } },
                    legend: { show: false },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                };
            }
            new ApexCharts(el, options).render();
        });
    });
    </script>
    @endpush
</x-layouts.app>
