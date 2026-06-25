<x-layouts.app :title="'Detail Alumni: ' . $alumni->nama">
    <div class="max-w-2xl mx-auto fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Detail Alumni</h1>
                <p class="page-subtitle text-sm text-gray-500 dark:text-gray-400">Informasi lengkap data pekerjaan alumni</p>
            </div>
            <a href="{{ route('alumni.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        {{-- Detail Card --}}
        <div class="card p-6 space-y-6">
            <div class="flex items-center gap-4 pb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="w-16 h-16 bg-teal-100 dark:bg-teal-950/30 rounded-2xl flex items-center justify-center text-3xl">
                    💼
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $alumni->nama }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Alumni FSIP Universitas Teknokrat Indonesia</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Nama Perusahaan / Instansi</h3>
                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $alumni->nama_perusahaan }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Posisi / Jabatan</h3>
                    <p class="text-base font-medium text-gray-900 dark:text-white">{{ $alumni->posisi }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Lokasi Kerja</h3>
                    <p class="text-base font-medium text-primary-600 dark:text-primary-400">📍 {{ $alumni->lokasi }}</p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Program Studi</h3>
                    <p class="text-base font-medium text-gray-900 dark:text-white">
                        @if($alumni->programStudi)
                        <span class="badge badge-info">{{ $alumni->programStudi->name }} ({{ $alumni->programStudi->code }})</span>
                        @else
                        <span class="text-gray-400">—</span>
                        @endif
                    </p>
                </div>

                <div class="md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-800 flex flex-col gap-1 text-xs text-gray-400">
                    <p>Diinput oleh: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $alumni->creator->name ?? 'System' }}</span></p>
                    <p>Tanggal Input: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $alumni->created_at->translatedFormat('d F Y, H:i') }}</span></p>
                    @if($alumni->updated_at != $alumni->created_at)
                    <p>Terakhir Diubah: <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $alumni->updated_at->translatedFormat('d F Y, H:i') }}</span></p>
                    @endif
                </div>
            </div>

            @hasanyrole('BAAK|Kaprodi|Dosen')
            <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-2">
                <form method="POST" action="{{ route('alumni.destroy', $alumni) }}" onsubmit="return confirm('Hapus data alumni &quot;{{ $alumni->nama }}&quot;?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-50 text-red-600 hover:bg-red-100 border border-red-200">Hapus</button>
                </form>
                <a href="{{ route('alumni.edit', $alumni) }}" class="btn-primary">Edit Data</a>
            </div>
            @endhasanyrole
        </div>
    </div>
</x-layouts.app>
