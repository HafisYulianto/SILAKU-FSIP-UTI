<x-layouts.app :title="'Edit Pengguna — ' . $user->name">
    <div class="max-w-2xl mx-auto fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Pengguna</h1>
                <p class="page-subtitle">{{ $user->name }}</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="card p-6">
                <div class="space-y-5">
                    <div>
                        <label class="form-label" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label" for="nip">NIP / NIK / NITK</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}" class="form-input" placeholder="Masukkan NIP, NIK, atau NITK">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="password">Password Baru</label>
                            <input type="password" name="password" id="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div>
                            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="role">Role <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="program_studi_id">Program Studi</label>
                            <select name="program_studi_id" id="program_studi_id" class="form-select">
                                <option value="">— Tidak ada —</option>
                                @foreach($programStudiList as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id', $user->program_studi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                        <span class="text-sm text-gray-600">Akun aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-layouts.app>
