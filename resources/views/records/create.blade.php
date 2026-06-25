<x-layouts.app :title="'Tambah Data — ' . $entity->name">
    <div class="max-w-3xl mx-auto fade-in">
        <div class="page-header">
            <div>
                <h1 class="page-title">Tambah Data Baru</h1>
                <p class="page-subtitle">{{ $entity->name }}</p>
            </div>
            <a href="{{ route('entities.view', $entity) }}" class="btn-secondary">← Kembali</a>
        </div>

        {{-- Approval info banner for Kaprodi/Dosen --}}
        @hasanyrole('Kaprodi|Dosen')
        <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl mb-4">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">Perlu Persetujuan BAAK</p>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-0.5">Data yang Anda input akan dikirim sebagai permintaan dan harus disetujui oleh BAAK sebelum aktif di sistem.</p>
            </div>
        </div>
        @endhasanyrole

        <form method="POST" action="{{ route('records.store', $entity) }}" enctype="multipart/form-data" id="record-form">
            @csrf

            <div class="card p-6 mb-6">
                {{-- Program Studi selection --}}
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <label class="form-label" for="program_studi_id">Program Studi</label>
                    <select name="program_studi_id" id="program_studi_id" class="form-select">
                        <option value="">— Umum (Semua Prodi) —</option>
                        @foreach($programStudiList as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->name }} ({{ $prodi->code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Dynamic Fields --}}
                <div class="space-y-5">
                    @foreach($entity->fields as $field)
                    <div>
                        <label class="form-label" for="field_{{ $field->slug }}">
                            {{ $field->name }}
                            @if($field->is_required) <span class="text-red-500">*</span> @endif
                        </label>

                        @switch($field->type)
                            @case('text')
                            @case('email')
                            @case('phone')
                            @case('url')
                                <input type="{{ $field->getInputType() }}"
                                       name="field_{{ $field->slug }}"
                                       id="field_{{ $field->slug }}"
                                       value="{{ old('field_' . $field->slug) }}"
                                       class="form-input"
                                       placeholder="Masukkan {{ strtolower($field->name) }}"
                                       {{ $field->is_required ? 'required' : '' }}>
                                @break

                            @case('textarea')
                                <textarea name="field_{{ $field->slug }}"
                                          id="field_{{ $field->slug }}"
                                          rows="3"
                                          class="form-input"
                                          placeholder="Masukkan {{ strtolower($field->name) }}"
                                          {{ $field->is_required ? 'required' : '' }}>{{ old('field_' . $field->slug) }}</textarea>
                                @break

                            @case('number')
                                <input type="number"
                                       name="field_{{ $field->slug }}"
                                       id="field_{{ $field->slug }}"
                                       value="{{ old('field_' . $field->slug) }}"
                                       class="form-input"
                                       @if(isset($field->options['min'])) min="{{ $field->options['min'] }}" @endif
                                       @if(isset($field->options['max'])) max="{{ $field->options['max'] }}" @endif
                                       placeholder="Masukkan angka"
                                       {{ $field->is_required ? 'required' : '' }}>
                                @break

                            @case('date')
                                <input type="date"
                                       name="field_{{ $field->slug }}"
                                       id="field_{{ $field->slug }}"
                                       value="{{ old('field_' . $field->slug) }}"
                                       class="form-input"
                                       {{ $field->is_required ? 'required' : '' }}>
                                @break

                            @case('select')
                                <select name="field_{{ $field->slug }}"
                                        id="field_{{ $field->slug }}"
                                        class="form-select"
                                        {{ $field->is_required ? 'required' : '' }}>
                                    <option value="">— Pilih —</option>
                                    @foreach($field->options['choices'] ?? [] as $choice)
                                    <option value="{{ $choice }}" {{ old('field_' . $field->slug) == $choice ? 'selected' : '' }}>
                                        {{ $choice }}
                                    </option>
                                    @endforeach
                                </select>
                                @break

                            @case('file')
                                <div class="flex items-center gap-3">
                                    <input type="file"
                                           name="field_{{ $field->slug }}"
                                           id="field_{{ $field->slug }}"
                                           class="form-input text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary-100 file:text-primary-700 file:font-medium hover:file:bg-primary-200 file:cursor-pointer"
                                           {{ $field->is_required ? 'required' : '' }}>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Maks. {{ $field->options['max_size'] ?? 10240 }} KB</p>
                                @break
                        @endswitch

                        @error('field_' . $field->slug)
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('entities.show', $entity) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" id="submit-record">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
