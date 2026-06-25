<x-layouts.app :title="'Buat Akun Baru'">
    <div class="max-w-2xl mx-auto fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Buat Akun Baru</h1>
                <p class="page-subtitle">Buat akun untuk Kaprodi atau Dosen</p>
            </div>
            <a href="{{ route('users.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('users.store') }}" id="user-form">
            @csrf

            <div class="card p-6">
                <div class="space-y-5">
                    <div>
                        <label class="form-label" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-input" placeholder="Nama">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-input" placeholder="user@teknokrat.ac.id">
                        </div>

                        <div>
                            <label class="form-label" for="nip">NIP / NIK / NITK</label>
                            <input type="text" name="nip" id="nip" value="{{ old('nip') }}" class="form-input" placeholder="Masukkan NIP, NIK, atau NITK">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="password">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" required class="form-input" placeholder="Minimal 8 karakter">
                        </div>

                        <div>
                            <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-input" placeholder="Ulangi password">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="role">Role <span class="text-red-500">*</span></label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">— Pilih Role —</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="form-label" for="program_studi_id">Program Studi</label>
                            <select name="program_studi_id" id="program_studi_id" class="form-select">
                                <option value="">— Tidak ada —</option>
                                @foreach($programStudiList as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('users.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" id="submit-user">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Akun
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
