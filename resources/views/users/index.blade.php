<x-layouts.app :title="'Manajemen Pengguna'">
    <div class="space-y-6 fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Manajemen Pengguna</h1>
                <p class="page-subtitle">Kelola akun Kaprodi dan Dosen</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn-primary" id="create-user-btn">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Buat Akun Baru
            </a>
        </div>

        {{-- Search & Filter --}}
        <div class="card p-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau NIP..."
                           class="form-input w-full" id="search-input">
                </div>
                <select name="role" class="form-select w-40">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengguna</th>
                            <th>NIP / NIK / NITK</th>
                            <th>Role</th>
                            <th>Program Studi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="text-gray-400">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold text-primary-700">{{ $user->initials }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-sm text-gray-600">{{ $user->nip ?? '—' }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                <span class="badge {{ $role->name === 'BAAK' ? 'badge-warning' : ($role->name === 'Pimpinan' || $role->name === 'Wakil Dekan' ? 'badge-danger' : ($role->name === 'Kaprodi' ? 'badge-primary' : 'badge-info')) }}">
                                    {{ $role->name }}
                                </span>
                                @endforeach
                            </td>
                            <td class="text-sm">{{ $user->programStudi->code ?? '—' }}</td>
                            <td>
                                @if($user->is_active)
                                <span class="badge bg-green-100 text-green-800">Aktif</span>
                                @else
                                <span class="badge bg-gray-100 text-gray-800">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('users.show', $user) }}" class="btn-icon" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @unless($user->hasRole('BAAK') || $user->hasRole('Pimpinan'))
                                    <a href="{{ route('users.edit', $user) }}" class="btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('users.toggle-active', $user) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($user->is_active)
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon text-red-400 hover:text-red-600" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400">Tidak ada pengguna ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
