<x-layouts.app :title="$category === 'dosen' ? 'Data Dosen' : ($category === 'mahasiswa' ? 'Data Mahasiswa' : 'Data Fakultas')">
    <div class="space-y-6 fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 {{ $category === 'dosen' ? 'bg-primary-100' : ($category === 'mahasiswa' ? 'bg-blue-100' : 'bg-purple-100') }} rounded-xl flex items-center justify-center">
                    <span class="text-xl">{{ $category === 'dosen' ? '📚' : ($category === 'mahasiswa' ? '🎓' : '🏢') }}</span>
                </div>
                <div>
                    <h1 class="page-title">{{ $category === 'dosen' ? 'Data Dosen' : ($category === 'mahasiswa' ? 'Data Mahasiswa' : 'Data Fakultas') }}</h1>
                    <p class="page-subtitle">
                        {{ $totalRecords }} total record &bull; {{ $entities->count() }} kategori
                    </p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-secondary">← Kembali ke Dashboard</a>
        </div>

        {{-- Category Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($entities as $entity)
            <a href="{{ route('pimpinan.browse', ['category' => $category, 'entity_id' => $entity->id]) }}"
               class="group card p-6 hover:shadow-lg hover:border-{{ $category === 'dosen' ? 'primary' : ($category === 'mahasiswa' ? 'blue' : 'purple') }}-200 hover:-translate-y-0.5 transition-all duration-300 {{ $selectedEntity && $selectedEntity->id === $entity->id ? 'ring-2 ring-' . ($category === 'dosen' ? 'primary' : ($category === 'mahasiswa' ? 'blue' : 'purple')) . '-500 border-' . ($category === 'dosen' ? 'primary' : ($category === 'mahasiswa' ? 'blue' : 'purple')) . '-200' : '' }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 {{ $category === 'dosen' ? 'bg-primary-50 group-hover:bg-primary-100' : ($category === 'mahasiswa' ? 'bg-blue-50 group-hover:bg-blue-100' : 'bg-purple-50 group-hover:bg-purple-100') }} rounded-xl flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 {{ $category === 'dosen' ? 'text-primary-600' : ($category === 'mahasiswa' ? 'text-blue-600' : 'text-purple-600') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold {{ $category === 'dosen' ? 'text-primary-600' : ($category === 'mahasiswa' ? 'text-blue-600' : 'text-purple-600') }}">{{ $entity->records_count }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-{{ $category === 'dosen' ? 'primary' : ($category === 'mahasiswa' ? 'blue' : 'purple') }}-700 transition-colors">{{ $entity->name }}</h3>
                @if($entity->description)
                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $entity->description }}</p>
                @endif
                <div class="flex flex-wrap gap-1 mt-3">
                    @foreach($entity->fields->take(3) as $field)
                    <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full">{{ $field->name }}</span>
                    @endforeach
                    @if($entity->fields->count() > 3)
                    <span class="text-[10px] px-2 py-0.5 bg-gray-100 text-gray-400 rounded-full">+{{ $entity->fields->count() - 3 }} lagi</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full card p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-400 mb-1">Belum Ada Kategori</h3>
                <p class="text-sm text-gray-300">Kategori data {{ $category }} belum dibuat oleh BAAK.</p>
            </div>
            @endforelse
        </div>

        {{-- Selected Entity Data Table --}}
        @if($selectedEntity)
        <div class="card overflow-hidden slide-up">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 {{ $category === 'dosen' ? 'bg-primary-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center">
                        <span class="text-sm">{{ $category === 'dosen' ? '📚' : '🎓' }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $selectedEntity->name }}</h3>
                        <p class="text-xs text-gray-400">{{ $records instanceof \Illuminate\Pagination\LengthAwarePaginator ? $records->total() : $records->count() }} record</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('entities.view', $selectedEntity) }}" class="text-xs text-{{ $category === 'dosen' ? 'primary' : 'blue' }}-600 hover:underline font-medium">
                        Lihat Detail Lengkap →
                    </a>
                </div>
            </div>

            @if(($records instanceof \Illuminate\Pagination\LengthAwarePaginator ? $records->count() : $records->count()) > 0)
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td class="text-gray-400">
                                @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $loop->iteration + ($records->currentPage() - 1) * $records->perPage() }}
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </td>
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $records->appends(['entity_id' => $selectedEntity->id])->links() }}
            </div>
            @endif
            @else
            <div class="px-6 py-12 text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="text-gray-400 font-medium">Belum ada data di kategori ini</p>
            </div>
            @endif
        </div>
        @elseif($entities->count() > 0)
        {{-- Prompt to select --}}
        <div class="card p-12 text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-400 mb-1">Pilih Kategori</h3>
            <p class="text-sm text-gray-300">Klik salah satu kartu di atas untuk melihat data yang terkandung di dalamnya.</p>
        </div>
        @endif
    </div>
</x-layouts.app>
