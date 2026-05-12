<x-layouts.app :title="$entity->name">
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
</x-layouts.app>
