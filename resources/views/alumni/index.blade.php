<x-layouts.app :title="'Data Alumni'">
    <div class="space-y-6 fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Data Alumni</h1>
                <p class="page-subtitle font-medium text-gray-500 dark:text-gray-400">Daftar alumni Fakultas Sastra dan Ilmu Pendidikan Universitas Teknokrat Indonesia</p>
            </div>
            @hasanyrole('BAAK|Kaprodi|Dosen')
            <a href="{{ route('alumni.create') }}" class="btn-primary flex items-center gap-1.5 transition-all duration-300 hover:scale-105" id="add-alumni-btn">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Tambah Alumni</span>
            </a>
            @endhasanyrole
        </div>

        {{-- Filter Card --}}
        <div class="card p-5">
            <form method="GET" action="{{ route('alumni.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
                <div class="flex-1 w-full">
                    <label for="program_studi_id" class="form-label text-xs font-semibold text-gray-400 uppercase tracking-wider">Filter Program Studi</label>
                    <select name="program_studi_id" id="program_studi_id" class="form-select w-full" onchange="this.form.submit()">
                        <option value="">— Semua Program Studi —</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ $prodiId == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->name }} ({{ $prodi->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @if($prodiId)
                <a href="{{ route('alumni.index') }}" class="btn-secondary w-full sm:w-auto text-xs py-2 px-3">Reset</a>
                @endif
            </form>
        </div>

        {{-- Data Table Card --}}
        <div class="card overflow-hidden">
            @if($alumnis->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">#</th>
                            <th>Nama</th>
                            <th>Nama Perusahaan</th>
                            <th>Posisi</th>
                            <th>Lokasi</th>
                            <th>Program Studi</th>
                            <th>Diinput Oleh</th>
                            @hasanyrole('BAAK|Kaprodi|Dosen')
                            <th class="w-24 text-center">Aksi</th>
                            @endhasanyrole
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumnis as $index => $alumni)
                        <tr>
                            <td class="text-center font-medium text-gray-400">{{ $alumnis->firstItem() + $index }}</td>
                            <td class="font-semibold text-gray-900 dark:text-white">{{ $alumni->nama }}</td>
                            <td>{{ $alumni->nama_perusahaan }}</td>
                            <td>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                    {{ $alumni->posisi }}
                                </span>
                            </td>
                            <td>
                                <span class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 dark:text-primary-400">
                                    📍 {{ $alumni->lokasi }}
                                </span>
                            </td>
                            <td>
                                @if($alumni->programStudi)
                                <span class="badge badge-info">{{ $alumni->programStudi->code }}</span>
                                @else
                                <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-xs text-gray-500">
                                    <p class="font-medium text-gray-700 dark:text-gray-300">{{ $alumni->creator->name ?? 'Unknown' }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $alumni->created_at->diffForHumans() }}</p>
                                </div>
                            </td>
                            @hasanyrole('BAAK|Kaprodi|Dosen')
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('alumni.edit', $alumni) }}" class="btn-icon text-gray-400 hover:text-primary-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('alumni.destroy', $alumni) }}" onsubmit="return confirm('Hapus data alumni &quot;{{ $alumni->nama }}&quot;?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-gray-400 hover:text-red-600" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endhasanyrole
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $alumnis->appends(request()->query())->links() }}
            </div>
            @else
            <div class="empty-state py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-base font-semibold text-gray-600 mb-1">Belum Ada Data Alumni</h3>
                <p class="text-sm text-gray-400 mb-6">Mulai tambahkan data pekerjaan alumni baru</p>
                @hasanyrole('BAAK|Kaprodi|Dosen')
                <a href="{{ route('alumni.create') }}" class="btn-primary">Tambah Alumni Pertama</a>
                @endhasanyrole
            </div>
            @endif
        </div>
    </div>
</x-layouts.app>
