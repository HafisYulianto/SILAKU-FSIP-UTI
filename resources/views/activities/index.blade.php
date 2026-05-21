<x-layouts.app :title="'Log Aktivitas'">
    <div class="space-y-6 fade-in" x-data="{ activeTab: '{{ request()->has('dosen_page') ? 'dosen' : 'kaprodi' }}' }">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Log Aktivitas</h1>
                <p class="page-subtitle">Pantau riwayat aktivitas Kaprodi dan Dosen dalam melakukan pengisian dan pengelolaan data</p>
            </div>
        </div>

        {{-- BAAK Bulk Clear Tool --}}
        @role('BAAK')
        <div class="card p-4 flex flex-col md:flex-row justify-between items-center gap-4 bg-red-50/30 border border-red-100">
            <div>
                <h3 class="text-sm font-semibold text-red-800 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Bersihkan Log Aktivitas
                </h3>
                <p class="text-xs text-red-600">Hapus log aktivitas lama yang sudah tidak dibutuhkan secara massal</p>
            </div>
            <form action="{{ route('activities.clear') }}" method="POST" class="flex items-center gap-2 w-full md:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log aktivitas berdasarkan pilihan filter?')">
                @csrf
                <select name="filter" class="form-select text-sm py-1.5 w-full md:w-56 border-red-200 focus:border-red-400 focus:ring-red-400 rounded-lg">
                    <option value="hour">Lebih dari 1 jam yang lalu</option>
                    <option value="day">Lebih dari 1 hari yang lalu</option>
                    <option value="week">Lebih dari 1 minggu yang lalu</option>
                    <option value="month">Lebih dari 1 bulan yang lalu</option>
                    <option value="year">Lebih dari 1 tahun yang lalu</option>
                    <option value="all">Semua Log (Kosongkan)</option>
                </select>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm flex items-center gap-1.5 flex-shrink-0">
                    Bersihkan
                </button>
            </form>
        </div>
        @endrole

        {{-- Tab Buttons --}}
        <div class="flex gap-2">
            <button @click="activeTab = 'kaprodi'"
                    :class="activeTab === 'kaprodi' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'"
                    class="px-5 py-2.5 text-sm font-medium rounded-lg transition-colors border shadow-sm flex items-center gap-2">
                <span>👨‍🏫</span> Aktivitas Kaprodi
            </button>
            <button @click="activeTab = 'dosen'"
                    :class="activeTab === 'dosen' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'"
                    class="px-5 py-2.5 text-sm font-medium rounded-lg transition-colors border shadow-sm flex items-center gap-2">
                <span>👨‍🎓</span> Aktivitas Dosen
            </button>
        </div>

        {{-- Kaprodi Activities Tab --}}
        <div x-show="activeTab === 'kaprodi'" x-transition class="space-y-4">
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Aktivitas dari Role Kaprodi</h2>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">{{ $kaprodiLogs->total() }} aktivitas ditemukan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">#</th>
                                <th>Nama</th>
                                <th>Aktivitas / Apa yang diubah</th>
                                <th>Tipe Aksi</th>
                                <th>Kapan diubah nya</th>
                                @role('BAAK')
                                <th class="w-20 text-center">Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kaprodiLogs as $log)
                            @php
                                $displayName = $log->user->name ?? $log->actor_name;
                                $displayEmail = $log->user->email ?? '';
                                $words = explode(' ', $displayName);
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $word) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                            @endphp
                            <tr>
                                <td class="text-gray-400 font-medium">{{ $loop->iteration + ($kaprodiLogs->currentPage() - 1) * $kaprodiLogs->perPage() }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center border border-primary-200">
                                            <span class="text-xs font-bold text-primary-700">{{ $initials }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 leading-none mb-1">{{ $displayName }}</p>
                                            <p class="text-xs text-gray-400 leading-none">{{ $displayEmail ?: 'Pengguna dihapus' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-sm font-medium text-gray-700">
                                    {{ $log->description }}
                                </td>
                                <td>
                                    @if($log->action === 'create_category')
                                        <span class="badge bg-green-50 text-green-700 border border-green-200 flex items-center gap-1 w-max">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Kategori Baru
                                        </span>
                                    @elseif($log->action === 'delete_category')
                                        <span class="badge bg-red-50 text-red-700 border border-red-200 flex items-center gap-1 w-max">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus Kategori
                                        </span>
                                    @else
                                        <span class="badge bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1 w-max">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Isi Data
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <p class="text-gray-800 font-semibold">{{ $log->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </td>
                                @role('BAAK')
                                <td class="text-center">
                                    <form action="{{ route('activities.destroy', $log) }}" method="POST" onsubmit="return confirm('Hapus log aktivitas ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Log">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                @endrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('BAAK') ? 6 : 5 }}" class="text-center py-16 text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="font-medium">Belum ada log aktivitas dari Kaprodi</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($kaprodiLogs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $kaprodiLogs->appends(request()->except('kaprodi_page'))->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Dosen Activities Tab --}}
        <div x-show="activeTab === 'dosen'" x-transition class="space-y-4">
            <div class="card overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800">Aktivitas dari Role Dosen</h2>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">{{ $dosenLogs->total() }} aktivitas ditemukan</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">#</th>
                                <th>Nama</th>
                                <th>Aktivitas / Apa yang diubah</th>
                                <th>Tipe Aksi</th>
                                <th>Kapan diubah nya</th>
                                @role('BAAK')
                                <th class="w-20 text-center">Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dosenLogs as $log)
                            @php
                                $displayName = $log->user->name ?? $log->actor_name;
                                $displayEmail = $log->user->email ?? '';
                                $words = explode(' ', $displayName);
                                $initials = '';
                                foreach (array_slice($words, 0, 2) as $word) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                            @endphp
                            <tr>
                                <td class="text-gray-400 font-medium">{{ $loop->iteration + ($dosenLogs->currentPage() - 1) * $dosenLogs->perPage() }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center border border-primary-200">
                                            <span class="text-xs font-bold text-primary-700">{{ $initials }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 leading-none mb-1">{{ $displayName }}</p>
                                            <p class="text-xs text-gray-400 leading-none">{{ $displayEmail ?: 'Pengguna dihapus' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-sm font-medium text-gray-700">
                                    {{ $log->description }}
                                </td>
                                <td>
                                    <span class="badge bg-blue-50 text-blue-700 border border-blue-200 flex items-center gap-1 w-max">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Isi Data
                                    </span>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <p class="text-gray-800 font-semibold">{{ $log->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                    </div>
                                </td>
                                @role('BAAK')
                                <td class="text-center">
                                    <form action="{{ route('activities.destroy', $log) }}" method="POST" onsubmit="return confirm('Hapus log aktivitas ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus Log">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                                @endrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole('BAAK') ? 6 : 5 }}" class="text-center py-16 text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="font-medium">Belum ada log aktivitas dari Dosen</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($dosenLogs->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $dosenLogs->appends(request()->except('dosen_page'))->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
