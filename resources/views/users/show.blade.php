<x-layouts.app :title="'Detail Pengguna — ' . $user->name">
    <div class="space-y-6 fade-in">
        {{-- Header --}}
        <div class="page-header">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center">
                    <span class="text-lg font-bold text-primary-700">{{ $user->initials }}</span>
                </div>
                <div>
                    <h1 class="page-title">{{ $user->name }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        @foreach($user->roles as $role)
                        <span class="badge {{ $role->name === 'BAAK' ? 'badge-warning' : ($role->name === 'Pimpinan' ? 'badge-danger' : ($role->name === 'Kaprodi' ? 'badge-primary' : 'badge-info')) }}">
                            {{ $role->name }}
                        </span>
                        @endforeach
                        @if($user->is_active)
                        <span class="badge bg-green-100 text-green-800">Aktif</span>
                        @else
                        <span class="badge bg-gray-100 text-gray-800">Nonaktif</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('users.index') }}" class="btn-secondary">← Kembali</a>
                @unless($user->hasRole('BAAK') || $user->hasRole('Pimpinan'))
                <a href="{{ route('users.edit', $user) }}" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Pengguna
                </a>
                @endunless
            </div>
        </div>

        {{-- User Detail Card --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Profile Info --}}
            <div class="card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Informasi Akun</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Nama Lengkap</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->name }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->email }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">NIP</p>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $user->nip ?? '—' }}</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                        </svg>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center" x-data="{ showPw: false }">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Password</p>
                            @if($user->plain_password)
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-sm font-mono font-semibold text-gray-900" x-text="showPw ? '{{ $user->plain_password }}' : '••••••••'"></p>
                                <button @click="showPw = !showPw" type="button" class="text-primary-600 hover:text-primary-800 transition-colors" :title="showPw ? 'Sembunyikan' : 'Tampilkan'">
                                    <svg x-show="!showPw" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/></svg>
                                </button>
                            </div>
                            @else
                            <p class="text-sm text-gray-400 mt-1 italic">Tidak tersedia <span class="text-xs">(dibuat sebelum fitur ini)</span></p>
                            @endif
                        </div>
                        <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Role & System Info --}}
            <div class="space-y-6">
                <div class="card">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Role & Program Studi</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-4">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Role</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg {{ $role->name === 'BAAK' ? 'bg-amber-100 text-amber-800' : ($role->name === 'Pimpinan' ? 'bg-red-100 text-red-800' : ($role->name === 'Kaprodi' ? 'bg-primary-100 text-primary-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Program Studi</p>
                            @if($user->programStudi)
                            <div class="flex items-center gap-2">
                                <span class="badge-info">{{ $user->programStudi->code }}</span>
                                <span class="text-sm text-gray-700">{{ $user->programStudi->name }}</span>
                            </div>
                            @else
                            <p class="text-sm text-gray-400">Tidak ada program studi terkait</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900">Informasi Sistem</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-4 flex justify-between">
                            <span class="text-sm text-gray-500">Status Akun</span>
                            @if($user->is_active)
                            <span class="badge bg-green-100 text-green-800">Aktif</span>
                            @else
                            <span class="badge bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </div>
                        <div class="px-6 py-4 flex justify-between">
                            <span class="text-sm text-gray-500">Akun Dibuat</span>
                            <span class="text-sm text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="px-6 py-4 flex justify-between">
                            <span class="text-sm text-gray-500">Terakhir Diperbarui</span>
                            <span class="text-sm text-gray-700">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                @unless($user->hasRole('BAAK') || $user->hasRole('Pimpinan'))
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-primary-700 bg-primary-50 border border-primary-200 rounded-lg hover:bg-primary-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Ubah Email / Password
                        </a>
                        <form method="POST" action="{{ route('users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium {{ $user->is_active ? 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100' : 'text-green-700 bg-green-50 border-green-200 hover:bg-green-100' }} border rounded-lg transition-colors">
                                @if($user->is_active)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                Nonaktifkan Akun
                                @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Aktifkan Akun
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
                @endunless
            </div>
        </div>
    </div>
</x-layouts.app>
