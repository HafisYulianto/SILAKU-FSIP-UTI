<x-layouts.app :title="$entity->name">
    <div x-data="{ showChart: false }">
    <div class="space-y-6 fade-in">
        {{-- Header --}}
        <div class="page-header">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $entity->root_category === 'dosen' ? 'bg-primary-100' : 'bg-blue-100' }} rounded-xl flex items-center justify-center">
                    <span class="text-xl">{{ $entity->root_category === 'dosen' ? '📚' : '🎓' }}</span>
                </div>
                <div>
                    <h1 class="page-title">{{ $entity->name }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : 'badge-info' }}">{{ ucfirst($entity->root_category) }}</span>
                        @if($entity->description)
                        <span class="text-sm text-gray-400">— {{ $entity->description }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @unlessrole('Pimpinan|Wakil Dekan')
                @can('records.create')
                <a href="{{ route('records.create', $entity) }}" class="btn-primary" id="add-record-btn">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Data
                </a>
                @endcan
                @hasanyrole('BAAK|Kaprodi')
                <a href="{{ route('entities.edit', $entity) }}" class="btn-secondary">Edit Kategori</a>
                <form method="POST" action="{{ route('entities.delete', $entity) }}" onsubmit="return confirm('Yakin ingin menghapus kategori &quot;{{ $entity->name }}&quot; beserta seluruh datanya? Tindakan ini tidak bisa dibatalkan!')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Kategori
                    </button>
                </form>
                @endhasanyrole
                @endunlessrole

                <!-- Export & Chart Buttons - Visible for all roles -->
                <div class="flex items-center gap-2 border-l border-gray-200 pl-2 ml-2">
                    @if($records->count() > 0)
                        <button type="button" class="btn-secondary" @click="showChart = true; loadCharts({{ $entity->id }})" title="Lihat Visualisasi Data">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Chart
                        </button>
                        <a href="{{ route('entities.export-excel', $entity) }}" class="btn-secondary" title="Export ke Excel">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('entities.export-pdf', $entity) }}" class="btn-secondary" title="Export ke PDF">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Field Structure Info --}}
        <div class="card p-4">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm font-medium text-gray-600">Struktur Data</span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($entity->fields as $field)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 rounded-lg text-xs text-gray-600">
                    <span class="font-medium">{{ $field->name }}</span>
                    <span class="text-gray-400">({{ $field->type }})</span>
                    @if($field->is_required)
                    <span class="text-red-400">*</span>
                    @endif
                </span>
                @endforeach
            </div>
        </div>

        {{-- Data Table --}}
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Data ({{ $records->total() }} record)</h3>
            </div>

            @if($records->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            @foreach($tableFields as $field)
                            <th>{{ $field->name }}</th>
                            @endforeach
                            <th>Prodi</th>
                            <th>Dibuat oleh</th>
                            <th>Tanggal</th>
                            <th class="w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td class="text-gray-400">{{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}</td>
                            @foreach($tableFields as $field)
                            <td>
                                @php $val = $record->getFieldValue($field->slug); @endphp
                                @if($field->type === 'file' && $val)
                                    <a href="{{ Storage::url($val) }}" target="_blank" class="text-primary-600 hover:underline text-xs">📎 Lihat File</a>
                                @elseif($field->type === 'url' && $val)
                                    <a href="{{ $val }}" target="_blank" class="text-primary-600 hover:underline text-sm inline-flex items-center gap-1 max-w-[200px] truncate">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        {{ Str::limit($val, 30) }}
                                    </a>
                                @elseif($field->type === 'email' && $val)
                                    <a href="mailto:{{ $val }}" class="text-primary-600 hover:underline text-sm">{{ $val }}</a>
                                @elseif($field->type === 'phone' && $val)
                                    <a href="tel:{{ $val }}" class="text-primary-600 hover:underline text-sm">{{ $val }}</a>
                                @elseif($field->type === 'date' && $val)
                                    <span>{{ \Carbon\Carbon::parse($val)->format('d/m/Y') }}</span>
                                @elseif($val)
                                    <span class="truncate max-w-[200px] block">{{ $val }}</span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            @endforeach
                            <td>
                                @if($record->programStudi)
                                <span class="badge-info">{{ $record->programStudi->code }}</span>
                                @else
                                <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="text-sm">{{ $record->creator->name ?? '-' }}</td>
                            <td class="text-xs text-gray-400">{{ $record->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @hasanyrole('Pimpinan|Wakil Dekan')
                                    <a href="{{ route('records.detail', [$entity, $record]) }}" class="btn-icon" title="Lihat">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @else
                                    <a href="{{ route('records.show', [$entity, $record]) }}" class="btn-icon" title="Lihat">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @can('records.edit')
                                    <a href="{{ route('records.edit', [$entity, $record]) }}" class="btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    @endcan
                                    @can('records.delete')
                                    <form method="POST" action="{{ route('records.destroy', [$entity, $record]) }}" onsubmit="return confirm('Hapus data ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-400 hover:text-red-600" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endcan
                                    @endhasanyrole
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $records->links() }}
            </div>
            @else
            <div class="px-6 py-16 text-center empty-state">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-400 mb-2">Belum Ada Data</h3>
                <p class="text-sm text-gray-300 mb-6">Mulai tambahkan data ke kategori ini</p>
                @unlessrole('Pimpinan|Wakil Dekan')
                @can('records.create')
                <a href="{{ route('records.create', $entity) }}" class="btn-primary">Tambah Data Pertama</a>
                @endcan
                @endunlessrole
            </div>
            @endif
        </div>
    </div>

    <!-- Chart Modal (Tailwind + Alpine.js) -->
    <div x-show="showChart" x-cloak class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showChart = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl transform transition-all"
                 x-show="showChart"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 @click.stop>
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Visualisasi Data — {{ $entity->name }}
                    </h3>
                    <button @click="showChart = false" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <!-- Body -->
                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto">
                    <div id="chartsContainer">
                        <div class="flex items-center justify-center py-8">
                            <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Footer -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button @click="showChart = false" class="btn-secondary">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        function loadCharts(entityId) {
            const container = document.getElementById('chartsContainer');
            container.innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <svg class="animate-spin h-8 w-8 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </div>
            `;

            fetch(`/api/entity/${entityId}/chart-data`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = '';

                    if (data.charts && data.charts.length > 0) {
                        data.charts.forEach((chart, chartIndex) => {
                            const chartDiv = document.createElement('div');
                            chartDiv.className = 'mb-5 p-4 border rounded-xl bg-gray-50';
                            chartDiv.innerHTML = `
                                <h6 class="mb-3 font-semibold text-gray-700">${chart.field_name}</h6>
                                <div class="relative h-64 w-full">
                                    <canvas id="chart-${chartIndex}"></canvas>
                                </div>
                            `;
                            container.appendChild(chartDiv);

                            setTimeout(() => {
                                const ctx = document.getElementById(`chart-${chartIndex}`).getContext('2d');
                                new Chart(ctx, {
                                    type: chart.chart_type,
                                    data: {
                                        labels: chart.data.labels || [],
                                        datasets: [{
                                            label: chart.field_name,
                                            data: chart.data.values || [],
                                            backgroundColor: generateColors(chart.data.values.length),
                                            borderColor: generateBorderColors(chart.data.values.length),
                                            borderWidth: 1,
                                            tension: 0.4,
                                            fill: true
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: chart.chart_type === 'doughnut' || chart.chart_type === 'pie',
                                                position: 'bottom'
                                            }
                                        },
                                        scales: (chart.chart_type !== 'doughnut' && chart.chart_type !== 'pie') ? {
                                            y: {
                                                beginAtZero: true
                                            }
                                        } : undefined
                                    }
                                });
                            }, 350);
                        });
                    } else {
                        container.innerHTML = '<p class="text-center text-gray-400 py-4">Tidak ada data untuk ditampilkan dalam chart</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<p class="text-center text-red-500 bg-red-50 rounded-lg px-4 py-3">Gagal memuat data chart</p>';
                });
        }

        function generateColors(count) {
            const colors = [
                'rgba(255, 107, 107, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)',
                'rgba(199, 199, 199, 0.6)',
                'rgba(83, 102, 255, 0.6)',
                'rgba(75, 227, 140, 0.6)',
                'rgba(255, 99, 132, 0.6)',
            ];
            let result = [];
            for (let i = 0; i < count; i++) {
                result.push(colors[i % colors.length]);
            }
            return result;
        }

        function generateBorderColors(count) {
            const colors = [
                'rgba(255, 107, 107, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(83, 102, 255, 1)',
                'rgba(75, 227, 140, 1)',
                'rgba(255, 99, 132, 1)',
            ];
            let result = [];
            for (let i = 0; i < count; i++) {
                result.push(colors[i % colors.length]);
            }
            return result;
        }
    </script>
    @endpush
</x-layouts.app>
