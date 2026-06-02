<x-layouts.app :title="'Edit: ' . $entity->name">
    <div class="max-w-3xl mx-auto fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Kategori</h1>
                <p class="page-subtitle">{{ $entity->name }}</p>
            </div>
            <a href="{{ route('entities.show', $entity) }}" class="btn-secondary">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('entities.update', $entity) }}">
            @csrf
            @method('PUT')

            <div class="card p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label" for="name">Nama Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $entity->name) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label" for="root_category">Kategori Utama <span class="text-red-500">*</span></label>
                        <select name="root_category" id="root_category" class="form-select" required>
                            <option value="dosen" {{ $entity->root_category === 'dosen' ? 'selected' : '' }}>📚 Dosen</option>
                            <option value="mahasiswa" {{ $entity->root_category === 'mahasiswa' ? 'selected' : '' }}>🎓 Mahasiswa</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea name="description" id="description" rows="2" class="form-input">{{ old('description', $entity->description) }}</textarea>
                    </div>

                    <div class="flex items-center pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $entity->is_active ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary-600 rounded focus:ring-primary-500">
                            <span class="text-sm text-gray-700 font-medium">Kategori aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Current Fields (read-only) --}}
            <div class="card p-6 mb-6">
                <h3 class="font-semibold text-gray-900 mb-4">Field/Kolom Saat Ini</h3>
                <p class="text-xs text-gray-400 mb-3">Field tidak dapat diubah setelah kategori dibuat untuk menjaga integritas data.</p>
                <div class="space-y-2">
                    @foreach($entity->fields as $field)
                    <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-3">
                            <span class="font-medium text-sm text-gray-700">{{ $field->name }}</span>
                            <span class="text-xs text-gray-400 bg-gray-200 px-2 py-0.5 rounded">{{ $field->type }}</span>
                            @if($field->is_required) <span class="text-xs text-red-500">Wajib</span> @endif
                        </div>
                        <div class="flex gap-3 text-xs text-gray-400">
                            @if($field->show_in_table) <span>📊 Tabel</span> @endif
                            @if($field->is_aggregatable) <span>📈 Chart</span> @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('entities.show', $entity) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-layouts.app>
