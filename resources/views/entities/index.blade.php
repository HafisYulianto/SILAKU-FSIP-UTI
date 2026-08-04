<x-layouts.app :title="'Manajemen Kategori Data'">
    <div class="space-y-6 fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Manajemen Kategori Data</h1>
                <p class="page-subtitle">Kelola kategori data dinamis untuk kebutuhan akreditasi</p>
            </div>
            <a href="{{ route('entities.create') }}" class="btn-primary" id="create-entity-btn">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Buat Kategori Baru
            </a>
        </div>


        {{-- Filter tabs --}}
        <div class="flex gap-2">
            <a href="{{ route('entities.index') }}"
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ !$category ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('entities.index', ['category' => 'dosen']) }}"
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $category === 'dosen' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                📚 Dosen
            </a>
            <a href="{{ route('entities.index', ['category' => 'mahasiswa']) }}"
               class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $category === 'mahasiswa' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                🎓 Mahasiswa
            </a>
        </div>

        {{-- Pending/Rejected Entities Section (Kaprodi only) --}}
        @if($pendingEntities->count() > 0)
        <div>
            <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Status Pengajuan Anda
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">{{ $pendingEntities->count() }}</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($pendingEntities as $entity)
                <div class="card p-6 slide-up border-l-4
                    {{ $entity->approval_status === 'pending' ? 'border-l-amber-400 bg-amber-50/30' : '' }}
                    {{ $entity->approval_status === 'pending_delete' ? 'border-l-orange-400 bg-orange-50/30' : '' }}
                    {{ $entity->approval_status === 'rejected' ? 'border-l-red-400 bg-red-50/30' : '' }}"
                     style="animation-delay: {{ $loop->index * 50 }}ms">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 {{ $entity->root_category === 'dosen' ? 'bg-primary-100' : ($entity->root_category === 'mahasiswa' ? 'bg-blue-100' : ($entity->root_category === 'fakultas' ? 'bg-purple-100' : 'bg-teal-100')) }} rounded-xl flex items-center justify-center">
                                <span class="text-lg">
                                    @if($entity->root_category === 'dosen') 📚 @elseif($entity->root_category === 'mahasiswa') 🎓 @elseif($entity->root_category === 'fakultas') 🏢 @else 💼 @endif
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $entity->name }}</h3>
                                <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : ($entity->root_category === 'mahasiswa' ? 'badge-info' : ($entity->root_category === 'fakultas' ? 'badge-secondary' : 'badge-success')) }}">{{ ucfirst($entity->root_category) }}</span>
                            </div>
                        </div>

                        {{-- Status Badge --}}
                        @if($entity->approval_status === 'pending')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Persetujuan
                        </span>
                        @elseif($entity->approval_status === 'pending_delete')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                            Menunggu Hapus
                        </span>
                        @elseif($entity->approval_status === 'rejected')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Ditolak
                        </span>
                        @endif
                    </div>

                    @if($entity->description)
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $entity->description }}</p>
                    @endif

                    {{-- Rejection reason --}}
                    @if($entity->approval_status === 'rejected' && $entity->rejection_reason)
                    <div class="flex items-start gap-2 p-2.5 bg-red-50 rounded-lg border border-red-100 mb-3">
                        <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-red-700"><strong>Alasan:</strong> {{ $entity->rejection_reason }}</p>
                    </div>
                    @endif

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-4 text-xs text-gray-400">
                            <span>{{ $entity->fields_count ?? $entity->fields->count() }} field</span>
                            <span>{{ $entity->updated_at->diffForHumans() }}</span>
                        </div>
                        {{-- Delete rejected entity option --}}
                        @if($entity->approval_status === 'rejected')
                        <form method="POST" action="{{ route('entities.destroy', $entity) }}" onsubmit="return confirm('Hapus kategori yang ditolak ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">Hapus</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Approved Entities Grid --}}
        @if($entities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($entities as $entity)
            <div class="card p-6 slide-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 {{ $entity->root_category === 'dosen' ? 'bg-primary-100' : ($entity->root_category === 'mahasiswa' ? 'bg-blue-100' : ($entity->root_category === 'fakultas' ? 'bg-purple-100' : 'bg-teal-100')) }} rounded-xl flex items-center justify-center">
                            <span class="text-lg">
                                @if($entity->root_category === 'dosen') 📚 @elseif($entity->root_category === 'mahasiswa') 🎓 @elseif($entity->root_category === 'fakultas') 🏢 @else 💼 @endif
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $entity->name }}</h3>
                            <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : ($entity->root_category === 'mahasiswa' ? 'badge-info' : ($entity->root_category === 'fakultas' ? 'badge-secondary' : 'badge-success')) }}">{{ ucfirst($entity->root_category) }}</span>
                        </div>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="btn-icon">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border py-1 z-10">
                            <a href="{{ route('entities.show', $entity) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat Data
                            </a>
                            <a href="{{ route('entities.edit', $entity) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('entities.destroy', $entity) }}" onsubmit="return confirm('Hapus kategori ini dan semua datanya?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                @if($entity->description)
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $entity->description }}</p>
                @endif

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span>{{ $entity->fields_count ?? $entity->fields->count() }} field</span>
                        <span>{{ $entity->records_count ?? 0 }} record</span>
                    </div>
                    <a href="{{ route('entities.show', $entity) }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                        Lihat →
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $entities->links() }}
        </div>
        @elseif($pendingEntities->count() === 0)
        <div class="card px-6 py-16 text-center">
            <svg class="w-20 h-20 mx-auto mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-500 mb-2">Belum Ada Kategori Data</h3>
            <p class="text-sm text-gray-400 mb-6">Mulai buat kategori data baru untuk keperluan akreditasi</p>
            <a href="{{ route('entities.create') }}" class="btn-primary">Buat Kategori Pertama</a>
        </div>
        @endif
    </div>
</x-layouts.app>
