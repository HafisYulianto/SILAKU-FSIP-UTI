<x-layouts.app :title="'Persetujuan Kategori'">
    <div class="space-y-6 fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Persetujuan Kategori</h1>
                <p class="page-subtitle">Kelola permintaan pembuatan dan penghapusan kategori dari Kaprodi</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 slide-up">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 slide-up">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
        @endif

        {{-- Pending Requests --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Menunggu Persetujuan
                @if($pendingEntities->count() > 0)
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">{{ $pendingEntities->count() }}</span>
                @endif
            </h2>

            @if($pendingEntities->count() > 0)
            <div class="space-y-4">
                @foreach($pendingEntities as $entity)
                <div class="card p-6 slide-up border-l-4 {{ $entity->approval_status === 'pending' ? 'border-l-amber-400' : 'border-l-red-400' }}" style="animation-delay: {{ $loop->index * 60 }}ms">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            {{-- Icon --}}
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $entity->approval_status === 'pending' ? 'bg-amber-100' : 'bg-red-100' }}">
                                @if($entity->approval_status === 'pending')
                                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                @else
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <h3 class="font-semibold text-gray-900">{{ $entity->name }}</h3>
                                    <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : 'badge-info' }}">
                                        {{ $entity->root_category === 'dosen' ? '📚' : '🎓' }} {{ ucfirst($entity->root_category) }}
                                    </span>
                                    @if($entity->approval_status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        Permintaan Buat Baru
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Permintaan Hapus
                                    </span>
                                    @endif
                                </div>

                                @if($entity->description)
                                <p class="text-sm text-gray-500 mb-2">{{ $entity->description }}</p>
                                @endif

                                <div class="flex items-center gap-4 text-xs text-gray-400">
                                    <span>Diajukan oleh: <strong class="text-gray-600">{{ $entity->creator->name ?? '-' }}</strong></span>
                                    <span>{{ $entity->fields->count() }} field</span>
                                    @if($entity->approval_status === 'pending_delete')
                                    <span>{{ $entity->records()->count() }} record</span>
                                    @endif
                                    <span>{{ $entity->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-2 flex-shrink-0" x-data="{ showRejectModal: false }">
                            {{-- Approve --}}
                            <form method="POST" action="{{ route('approvals.approve', $entity) }}"
                                  onsubmit="return confirm('{{ $entity->approval_status === 'pending' ? 'Setujui pembuatan kategori ini?' : 'Setujui penghapusan kategori ini? Semua data di dalamnya akan ikut terhapus.' }}')">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors" id="approve-btn-{{ $entity->id }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui
                                </button>
                            </form>

                            {{-- Reject Button --}}
                            <button @click="showRejectModal = true"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 transition-colors" id="reject-btn-{{ $entity->id }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>

                            {{-- Reject Modal --}}
                            <div x-show="showRejectModal" x-transition.opacity
                                 class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                 @click.self="showRejectModal = false">
                                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" x-transition.scale @click.stop>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900">Tolak Permintaan</h3>
                                            <p class="text-xs text-gray-500">Kategori: {{ $entity->name }}</p>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('approvals.reject', $entity) }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="rejection_reason_{{ $entity->id }}" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                                            <textarea name="rejection_reason" id="rejection_reason_{{ $entity->id }}"
                                                      rows="3" required maxlength="500"
                                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm resize-none"
                                                      placeholder="Masukkan alasan penolakan..."></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showRejectModal = false"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">
                                                Batal
                                            </button>
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium bg-red-600 text-white hover:bg-red-700 transition-colors">
                                                Tolak Permintaan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="card px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-semibold text-gray-500 mb-1">Tidak Ada Permintaan Pending</h3>
                <p class="text-sm text-gray-400">Semua permintaan kategori sudah ditangani.</p>
            </div>
            @endif
        </div>

        {{-- Rejected Requests (History) --}}
        @if($rejectedEntities->count() > 0)
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                Riwayat Ditolak
                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-medium">{{ $rejectedEntities->count() }}</span>
            </h2>

            <div class="space-y-3">
                @foreach($rejectedEntities as $entity)
                <div class="card p-5 slide-up border-l-4 border-l-gray-300 opacity-80" style="animation-delay: {{ $loop->index * 60 }}ms">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-3 flex-1">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <h3 class="font-semibold text-gray-700">{{ $entity->name }}</h3>
                                    <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : 'badge-info' }}">{{ ucfirst($entity->root_category) }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                </div>
                                @if($entity->rejection_reason)
                                <div class="flex items-start gap-2 mt-2 p-2.5 bg-red-50 rounded-lg border border-red-100">
                                    <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-xs text-red-700">{{ $entity->rejection_reason }}</p>
                                </div>
                                @endif
                                <div class="flex items-center gap-4 text-xs text-gray-400 mt-2">
                                    <span>Diajukan oleh: <strong class="text-gray-500">{{ $entity->creator->name ?? '-' }}</strong></span>
                                    <span>{{ $entity->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>
