<x-layouts.app :title="'Buat Kategori Data Baru'">
    <div class="max-w-4xl mx-auto fade-in" x-data="entityBuilder()">
        <div class="page-header">
            <div>
                <h1 class="page-title">Buat Kategori Data Baru</h1>
                <p class="page-subtitle">Definisikan struktur data baru untuk kebutuhan akreditasi</p>
            </div>
            <a href="{{ route('entities.index') }}" class="btn-secondary">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('entities.store') }}" id="entity-form">
            @csrf

            {{-- Step 1: Basic Info --}}
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">1</span>
                    Informasi Kategori
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label" for="entity-name">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="entity-name" value="{{ old('name') }}" required
                               class="form-input" placeholder="Contoh: Dosen S3, Mahasiswa Berprestasi">
                    </div>

                    <div>
                        <label class="form-label" for="root-category">Kategori Utama <span class="text-red-500">*</span></label>
                        <select name="root_category" id="root-category" class="form-select" required>
                            <option value="">— Pilih Kategori —</option>
                            <option value="dosen" {{ old('root_category') === 'dosen' ? 'selected' : '' }}>📚 Dosen</option>
                            <option value="mahasiswa" {{ old('root_category') === 'mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                            <option value="alumni" {{ old('root_category') === 'alumni' ? 'selected' : '' }}>💼 Alumni</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="2" class="form-input" placeholder="Deskripsi singkat tentang kategori data ini">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Step 2: Define Fields --}}
            <div class="card p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <span class="w-7 h-7 bg-primary-600 text-white rounded-lg flex items-center justify-center text-xs font-bold">2</span>
                        Definisi Field / Kolom
                    </h2>
                    <button type="button" @click="addField()" class="btn-outline btn-sm" id="add-field-btn">
                        + Tambah Field
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-5">Tentukan kolom data apa saja yang dibutuhkan pada kategori ini.</p>

                <div class="space-y-4">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 relative slide-up">
                            {{-- Remove button --}}
                            <button type="button" @click="removeField(index)" x-show="fields.length > 1"
                                    class="absolute top-3 right-3 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label" x-bind:for="'field-name-'+index">Nama Field <span class="text-red-500">*</span></label>
                                    <input type="text" x-bind:name="'fields['+index+'][name]'" x-bind:id="'field-name-'+index"
                                           x-model="field.name" class="form-input" placeholder="Contoh: Nama, NIDN, Tahun Lulus" required>
                                </div>

                                <div>
                                    <label class="form-label" x-bind:for="'field-type-'+index">Tipe Data <span class="text-red-500">*</span></label>
                                    <select x-bind:name="'fields['+index+'][type]'" x-bind:id="'field-type-'+index"
                                            x-model="field.type" class="form-select" required>
                                        <option value="text">Teks (Text)</option>
                                        <option value="textarea">Teks Panjang (Textarea)</option>
                                        <option value="number">Angka (Number)</option>
                                        <option value="date">Tanggal (Date)</option>
                                        <option value="select">Pilihan (Select/Dropdown)</option>
                                        <option value="file">File Upload</option>
                                        <option value="email">Email</option>
                                        <option value="phone">Telepon</option>
                                        <option value="url">URL/Link</option>
                                    </select>
                                </div>

                                {{-- Select choices --}}
                                <div x-show="field.type === 'select'">
                                    <label class="form-label">Pilihan (pisah dengan koma)</label>
                                    <input type="text" x-bind:name="'fields['+index+'][options][choices]'"
                                           class="form-input" placeholder="S1, S2, S3">
                                </div>

                                {{-- Number options --}}
                                <template x-if="field.type === 'number'">
                                    <div class="flex gap-2">
                                        <div class="flex-1">
                                            <label class="form-label">Min</label>
                                            <input type="number" x-bind:name="'fields['+index+'][options][min]'" class="form-input" placeholder="0">
                                        </div>
                                        <div class="flex-1">
                                            <label class="form-label">Max</label>
                                            <input type="number" x-bind:name="'fields['+index+'][options][max]'" class="form-input" placeholder="999">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Field options --}}
                            <div class="flex flex-wrap gap-5 mt-4 pt-4 border-t border-gray-200">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" x-bind:name="'fields['+index+'][is_required]'" x-model="field.is_required"
                                           value="1" class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                                    <span class="text-gray-600">Wajib diisi</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" x-bind:name="'fields['+index+'][show_in_table]'" x-model="field.show_in_table"
                                           value="1" class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                                    <span class="text-gray-600">Tampil di tabel</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" x-bind:name="'fields['+index+'][is_filterable]'" x-model="field.is_filterable"
                                           value="1" class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                                    <span class="text-gray-600">Dapat difilter</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" x-bind:name="'fields['+index+'][is_aggregatable]'" x-model="field.is_aggregatable"
                                           value="1" class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                                    <span class="text-gray-600">Tampil di chart dashboard</span>
                                </label>
                            </div>
                        </div>
                    </template>
                </div>

                <button type="button" @click="addField()" class="mt-4 w-full py-3 border-2 border-dashed border-gray-300 rounded-xl text-sm font-medium text-gray-400 hover:border-primary-400 hover:text-primary-500 transition-colors">
                    + Tambah Field Baru
                </button>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('entities.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" id="submit-entity">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    function entityBuilder() {
        return {
            fields: [
                { name: '', type: 'text', is_required: true, show_in_table: true, is_filterable: false, is_aggregatable: false }
            ],
            addField() {
                this.fields.push({
                    name: '', type: 'text', is_required: false, show_in_table: true, is_filterable: false, is_aggregatable: false
                });
            },
            removeField(index) {
                this.fields.splice(index, 1);
            }
        }
    }
    </script>
    @endpush
</x-layouts.app>
