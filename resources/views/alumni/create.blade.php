<x-layouts.app :title="'Tambah Alumni'">
    <div class="max-w-2xl mx-auto fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Tambah Alumni</h1>
                <p class="page-subtitle text-sm text-gray-500 dark:text-gray-400">Tambahkan data pekerjaan alumni baru</p>
            </div>
            <a href="{{ route('alumni.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        {{-- Approval info banner for Kaprodi/Dosen --}}
        @hasanyrole('Kaprodi|Dosen')
        <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl mb-4">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">Perlu Persetujuan BAAK</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">Data alumni yang Anda input akan dikirim sebagai permintaan dan harus disetujui oleh BAAK sebelum aktif di sistem.</p>
            </div>
        </div>
        @endhasanyrole

        {{-- Form Card --}}
        <div class="card p-6">
            <form method="POST" action="{{ route('alumni.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                           class="form-input @error('nama') border-red-500 @enderror" 
                           placeholder="Contoh: Hafis Yulianto">
                    @error('nama')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required 
                           class="form-input @error('nama_perusahaan') border-red-500 @enderror" 
                           placeholder="Contoh: PT. GoTo Gojek Tokopedia">
                    @error('nama_perusahaan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="posisi" class="form-label">Posisi / Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="posisi" id="posisi" value="{{ old('posisi') }}" required 
                           class="form-input @error('posisi') border-red-500 @enderror" 
                           placeholder="Contoh: Software Engineer">
                    @error('posisi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="lokasi" class="form-label">Lokasi <span class="text-red-500">*</span></label>
                    <select name="lokasi" id="lokasi" required class="form-select @error('lokasi') border-red-500 @enderror">
                        <option value="">— Pilih Lokasi Kabupaten/Kota Lampung —</option>
                        <option value="Bandar Lampung" {{ old('lokasi') === 'Bandar Lampung' ? 'selected' : '' }}>Bandar Lampung</option>
                        <option value="Metro" {{ old('lokasi') === 'Metro' ? 'selected' : '' }}>Metro</option>
                        <option value="Lampung Selatan" {{ old('lokasi') === 'Lampung Selatan' ? 'selected' : '' }}>Lampung Selatan</option>
                        <option value="Lampung Tengah" {{ old('lokasi') === 'Lampung Tengah' ? 'selected' : '' }}>Lampung Tengah</option>
                        <option value="Lampung Timur" {{ old('lokasi') === 'Lampung Timur' ? 'selected' : '' }}>Lampung Timur</option>
                        <option value="Lampung Utara" {{ old('lokasi') === 'Lampung Utara' ? 'selected' : '' }}>Lampung Utara</option>
                        <option value="Pringsewu" {{ old('lokasi') === 'Pringsewu' ? 'selected' : '' }}>Pringsewu</option>
                        <option value="Tanggamus" {{ old('lokasi') === 'Tanggamus' ? 'selected' : '' }}>Tanggamus</option>
                        <option value="Pesawaran" {{ old('lokasi') === 'Pesawaran' ? 'selected' : '' }}>Pesawaran</option>
                        <option value="Way Kanan" {{ old('lokasi') === 'Way Kanan' ? 'selected' : '' }}>Way Kanan</option>
                        <option value="Tulang Bawang" {{ old('lokasi') === 'Tulang Bawang' ? 'selected' : '' }}>Tulang Bawang</option>
                        <option value="Mesuji" {{ old('lokasi') === 'Mesuji' ? 'selected' : '' }}>Mesuji</option>
                        <option value="Lampung Barat" {{ old('lokasi') === 'Lampung Barat' ? 'selected' : '' }}>Lampung Barat</option>
                        <option value="Pesisir Barat" {{ old('lokasi') === 'Pesisir Barat' ? 'selected' : '' }}>Pesisir Barat</option>
                        <option value="Tulang Bawang Barat" {{ old('lokasi') === 'Tulang Bawang Barat' ? 'selected' : '' }}>Tulang Bawang Barat</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">Lokasi kabupaten/kota ini akan digunakan untuk visualisasi pemetaan di peta sebaran.</p>
                    @error('lokasi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="program_studi_id" class="form-label">Program Studi</label>
                    <select name="program_studi_id" id="program_studi_id" class="form-select @error('program_studi_id') border-red-500 @enderror">
                        <option value="">— Pilih Program Studi —</option>
                        @foreach($programStudis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->name }} ({{ $prodi->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('program_studi_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-gray-100 dark:border-gray-800">
                    <a href="{{ route('alumni.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
