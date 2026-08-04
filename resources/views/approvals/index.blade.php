<x-layouts.app :title="'Persetujuan'">
    <div class="space-y-6 fade-in" x-data="{
        selectedIds: [],
        showRejectDataModal: false,
        rejectionReason: '',
        toggleAll(ids) {
            if (this.selectedIds.length === ids.length) {
                this.selectedIds = [];
            } else {
                this.selectedIds = [...ids];
            }
        }
    }">
        <div class="page-header">
            <div>
                <h1 class="page-title">Persetujuan</h1>
                <p class="page-subtitle">Kelola permintaan pembuatan/penghapusan kategori dan data dari Kaprodi & Dosen</p>
            </div>
        </div>

        {{-- ==================== SECTION 1: DATA APPROVAL REQUESTS (Alumni / Records) ==================== --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Permintaan Data (Alumni / Dosen / Mahasiswa)
                @if($pendingDataRequests->count() > 0)
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">{{ $pendingDataRequests->count() }}</span>
                @endif
            </h2>

            @if($pendingDataRequests->count() > 0)

            {{-- Sticky Bulk Action Bar --}}
            <div x-show="selectedIds.length > 0"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="sticky top-4 z-30 mb-4">
                <div class="bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-700 rounded-xl shadow-lg px-4 py-3 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-blue-100 rounded-md flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <span x-text="selectedIds.length"></span> permintaan dipilih
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Bulk Approve --}}
                        <form method="POST" action="{{ route('approvals.data.bulk-approve') }}"
                              onsubmit="return confirm('Setujui ' + document.querySelectorAll('[name=\'request_ids[]\']:checked').length + ' permintaan yang dipilih?')"
                              x-ref="bulkApproveForm">
                            @csrf
                            <template x-for="id in selectedIds" :key="id">
                                <input type="hidden" name="request_ids[]" :value="id">
                            </template>
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-emerald-500 text-white hover:bg-emerald-600 transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui Terpilih
                            </button>
                        </form>

                        {{-- Bulk Reject (opens modal) --}}
                        <button type="button" @click="showRejectDataModal = true"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold bg-red-500 text-white hover:bg-red-600 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tolak Terpilih
                        </button>

                        <button type="button" @click="selectedIds = []"
                                class="text-sm text-gray-400 hover:text-gray-600 transition-colors px-2 py-1">
                            Batalkan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Select All Toggle --}}
            @php $allIds = $pendingDataRequests->pluck('id')->toArray(); @endphp
            <div class="flex items-center gap-3 mb-3 px-1">
                <button type="button"
                        @click="toggleAll({{ json_encode($allIds) }})"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                    <span x-show="selectedIds.length < {{ count($allIds) }}">Pilih Semua ({{ count($allIds) }})</span>
                    <span x-show="selectedIds.length === {{ count($allIds) }}" x-cloak>Batal Pilih Semua</span>
                </button>
            </div>

            <div class="space-y-3">
                @foreach($pendingDataRequests as $dr)
                @php
                    $isCreate = $dr->action === 'create';
                    $isAlumni = $dr->type === 'alumni';
                    $entityName = $isAlumni ? 'Alumni' : ($dr->entity?->name ?? 'Kategori');
                    $dataName = '-';
                    if ($isCreate && $isAlumni) {
                        $dataName = $dr->payload['nama'] ?? '-';
                    } elseif (!$isCreate && $isAlumni && $dr->alumni) {
                        $dataName = $dr->alumni->nama;
                    } elseif ($dr->type === 'record') {
                        $dataName = $dr->entity?->name ?? 'Record';
                    }
                @endphp
                <div class="card p-5 border-l-4 {{ $isCreate ? 'border-l-blue-400' : 'border-l-red-400' }} slide-up"
                     style="animation-delay: {{ $loop->index * 40 }}ms">
                    <div class="flex items-start gap-4">
                        {{-- Checkbox --}}
                        <div class="flex items-center pt-1">
                            <input type="checkbox"
                                   :value="{{ $dr->id }}"
                                   x-model="selectedIds"
                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                   id="req-{{ $dr->id }}">
                        </div>

                        {{-- Icon --}}
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isCreate ? 'bg-blue-50' : 'bg-red-50' }}">
                            @if($isCreate)
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            @else
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $isCreate ? 'Tambah Data' : 'Hapus Data' }}: <span class="text-blue-700 dark:text-blue-300">{{ $dataName }}</span>
                                </span>
                                <span class="badge {{ $isCreate ? 'badge-info' : 'badge-danger' }} text-xs">
                                    {{ $isCreate ? '+ Buat' : '× Hapus' }}
                                </span>
                                <span class="badge badge-secondary text-xs">{{ $entityName }}</span>
                                @if($dr->type === 'record' && $dr->entity)
                                <span class="text-xs text-gray-400">({{ ucfirst($dr->entity->root_category ?? '') }})</span>
                                @endif
                            </div>

                            {{-- Payload preview for create --}}
                            @if($isCreate && $dr->payload)
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400 mb-2">
                                @foreach(collect($dr->payload)->reject(fn($v, $k) => str_starts_with($k, '_')) as $key => $val)
                                @if($val && !is_array($val))
                                <span><strong class="text-gray-600 dark:text-gray-300">{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $val }}</span>
                                @endif
                                @endforeach
                            </div>
                            @endif

                            {{-- Delete record snippet --}}
                            @if(!$isCreate && $dr->type === 'record' && $dr->record)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                Data ID #{{ $dr->record->id }}
                                @if($dr->record->programStudi)
                                | Prodi: {{ $dr->record->programStudi->name }}
                                @endif
                            </div>
                            @endif

                            <div class="flex items-center gap-3 text-xs text-gray-400">
                                <span>Diajukan oleh: <strong class="text-gray-600 dark:text-gray-300">{{ $dr->requester_name }}</strong> ({{ $dr->requester_role }})</span>
                                <span>{{ $dr->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Bulk Reject Modal --}}
            <div x-show="showRejectDataModal" x-transition.opacity
                 class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                 @click.self="showRejectDataModal = false" x-cloak>
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-6" x-transition.scale @click.stop>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Tolak Permintaan</h3>
                            <p class="text-xs text-gray-500" x-text="`${selectedIds.length} permintaan akan ditolak`"></p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('approvals.data.bulk-reject') }}">
                        @csrf
                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="request_ids[]" :value="id">
                        </template>
                        <div class="mb-4">
                            <label for="bulk_rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="rejection_reason" id="bulk_rejection_reason"
                                      rows="3" required maxlength="500"
                                      x-model="rejectionReason"
                                      class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm resize-none dark:bg-gray-800 dark:text-gray-100"
                                      placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showRejectDataModal = false; rejectionReason = ''"
                                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-colors">
                                Tolak Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @else
            <div class="card px-6 py-10 text-center">
                <svg class="w-14 h-14 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-base font-semibold text-gray-500 mb-1">Tidak Ada Permintaan Data Pending</h3>
                <p class="text-sm text-gray-400">Semua permintaan tambah/hapus data sudah ditangani.</p>
            </div>
            @endif
        </div>

        {{-- ==================== SECTION 2: KATEGORI APPROVAL (existing) ==================== --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Permintaan Kategori (Buat / Hapus Kategori)
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
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $entity->name }}</h3>
                                    <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : ($entity->root_category === 'mahasiswa' ? 'badge-info' : ($entity->root_category === 'fakultas' ? 'badge-secondary' : 'badge-success')) }}">
                                        @if($entity->root_category === 'dosen') 📚 @elseif($entity->root_category === 'mahasiswa') 🎓 @elseif($entity->root_category === 'fakultas') 🏢 @else 💼 @endif {{ ucfirst($entity->root_category) }}
                                    </span>
                                    @if($entity->approval_status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Permintaan Buat Baru</span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Permintaan Hapus</span>
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
                            <form method="POST" action="{{ route('approvals.approve', $entity) }}"
                                  onsubmit="return confirm('{{ $entity->approval_status === 'pending' ? 'Setujui pembuatan kategori ini?' : 'Setujui penghapusan kategori ini? Semua data di dalamnya akan ikut terhapus.' }}')">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors" id="approve-btn-{{ $entity->id }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui
                                </button>
                            </form>

                            <button @click="showRejectModal = true"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 transition-colors" id="reject-btn-{{ $entity->id }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>

                            {{-- Reject Modal --}}
                            <div x-show="showRejectModal" x-transition.opacity
                                 class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                                 @click.self="showRejectModal = false">
                                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl max-w-md w-full p-6" x-transition.scale @click.stop>
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-900 dark:text-white">Tolak Permintaan</h3>
                                            <p class="text-xs text-gray-500">Kategori: {{ $entity->name }}</p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('approvals.reject', $entity) }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="rejection_reason_{{ $entity->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                Alasan Penolakan <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="rejection_reason" id="rejection_reason_{{ $entity->id }}"
                                                      rows="3" required maxlength="500"
                                                      class="w-full rounded-xl border-gray-300 dark:border-gray-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm resize-none dark:bg-gray-800 dark:text-gray-100"
                                                      placeholder="Masukkan alasan penolakan..."></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="showRejectModal = false"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">Batal</button>
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-lg text-sm font-medium bg-red-600 text-white hover:bg-red-700 transition-colors">Tolak Permintaan</button>
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
                <h3 class="text-base font-semibold text-gray-500 mb-1">Tidak Ada Permintaan Kategori Pending</h3>
                <p class="text-sm text-gray-400">Semua permintaan kategori sudah ditangani.</p>
            </div>
            @endif
        </div>

        {{-- Rejected Categories History --}}
        @if($rejectedEntities->count() > 0)
        <div>
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                Riwayat Kategori Ditolak
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
                                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">{{ $entity->name }}</h3>
                                    <span class="badge {{ $entity->root_category === 'dosen' ? 'badge-primary' : ($entity->root_category === 'mahasiswa' ? 'badge-info' : ($entity->root_category === 'fakultas' ? 'badge-secondary' : 'badge-success')) }}">{{ ucfirst($entity->root_category) }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                </div>
                                @if($entity->rejection_reason)
                                <div class="flex items-start gap-2 mt-2 p-2.5 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-800">
                                    <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-xs text-red-700 dark:text-red-300">{{ $entity->rejection_reason }}</p>
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
