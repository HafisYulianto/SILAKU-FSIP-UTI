<x-layouts.app :title="'Edit Alumni: ' . $alumni->nama">
    <div class="max-w-2xl mx-auto fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Alumni</h1>
                <p class="page-subtitle text-sm text-gray-500 dark:text-gray-400">Ubah data pekerjaan alumni: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $alumni->nama }}</span></p>
            </div>
            <a href="{{ route('alumni.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        {{-- Form Card --}}
        <div class="card p-6">
            <form method="POST" action="{{ route('alumni.update', $alumni) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $alumni->nama) }}" required 
                           class="form-input @error('nama') border-red-500 @enderror" 
                           placeholder="Contoh: Hafis Yulianto">
                    @error('nama')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan / Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $alumni->nama_perusahaan) }}" required 
                           class="form-input @error('nama_perusahaan') border-red-500 @enderror" 
                           placeholder="Contoh: PT. GoTo Gojek Tokopedia">
                    @error('nama_perusahaan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="posisi" class="form-label">Posisi / Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="posisi" id="posisi" value="{{ old('posisi', $alumni->posisi) }}" required 
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
                        <option value="Bandar Lampung" {{ old('lokasi', $alumni->lokasi) === 'Bandar Lampung' ? 'selected' : '' }}>Bandar Lampung</option>
                        <option value="Metro" {{ old('lokasi', $alumni->lokasi) === 'Metro' ? 'selected' : '' }}>Metro</option>
                        <option value="Lampung Selatan" {{ old('lokasi', $alumni->lokasi) === 'Lampung Selatan' ? 'selected' : '' }}>Lampung Selatan</option>
                        <option value="Lampung Tengah" {{ old('lokasi', $alumni->lokasi) === 'Lampung Tengah' ? 'selected' : '' }}>Lampung Tengah</option>
                        <option value="Lampung Timur" {{ old('lokasi', $alumni->lokasi) === 'Lampung Timur' ? 'selected' : '' }}>Lampung Timur</option>
                        <option value="Lampung Utara" {{ old('lokasi', $alumni->lokasi) === 'Lampung Utara' ? 'selected' : '' }}>Lampung Utara</option>
                        <option value="Pringsewu" {{ old('lokasi', $alumni->lokasi) === 'Pringsewu' ? 'selected' : '' }}>Pringsewu</option>
                        <option value="Tanggamus" {{ old('lokasi', $alumni->lokasi) === 'Tanggamus' ? 'selected' : '' }}>Tanggamus</option>
                        <option value="Pesawaran" {{ old('lokasi', $alumni->lokasi) === 'Pesawaran' ? 'selected' : '' }}>Pesawaran</option>
                        <option value="Way Kanan" {{ old('lokasi', $alumni->lokasi) === 'Way Kanan' ? 'selected' : '' }}>Way Kanan</option>
                        <option value="Tulang Bawang" {{ old('lokasi', $alumni->lokasi) === 'Tulang Bawang' ? 'selected' : '' }}>Tulang Bawang</option>
                        <option value="Mesuji" {{ old('lokasi', $alumni->lokasi) === 'Mesuji' ? 'selected' : '' }}>Mesuji</option>
                        <option value="Lampung Barat" {{ old('lokasi', $alumni->lokasi) === 'Lampung Barat' ? 'selected' : '' }}>Lampung Barat</option>
                        <option value="Pesisir Barat" {{ old('lokasi', $alumni->lokasi) === 'Pesisir Barat' ? 'selected' : '' }}>Pesisir Barat</option>
                        <option value="Tulang Bawang Barat" {{ old('lokasi', $alumni->lokasi) === 'Tulang Bawang Barat' ? 'selected' : '' }}>Tulang Bawang Barat</option>
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
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id', $alumni->program_studi_id) == $prodi->id ? 'selected' : '' }}>
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
                    <button type="submit" class="btn-primary">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
