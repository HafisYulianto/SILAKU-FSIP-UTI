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

                <div class="relative">
                    <label for="lokasi" class="form-label">Lokasi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $alumni->lokasi) }}" required autocomplete="off"
                               class="form-input pr-10 @error('lokasi') border-red-500 @enderror" 
                               placeholder="Contoh: Bandar Lampung, Jakarta, Singapore, Tokyo">
                        <div id="lokasi-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="animate-spin h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Dropdown Suggestion List --}}
                    <div id="autocomplete-suggestions" class="absolute z-50 left-0 right-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden">
                        <!-- Items will be populated by JS -->
                    </div>

                    <p class="text-xs text-gray-400 mt-1">Tulis nama lokasi secara bebas. Pilih saran yang muncul agar koordinat terdeteksi secara otomatis.</p>
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

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('lokasi');
        const suggestionsContainer = document.getElementById('autocomplete-suggestions');
        const loadingIcon = document.getElementById('lokasi-loading');

        if (!input || !suggestionsContainer) return;

        let debounceTimer;
        let selectedIndex = -1;
        let suggestions = [];

        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 3) {
                hideSuggestions();
                return;
            }

            debounceTimer = setTimeout(() => {
                fetchSuggestions(query);
            }, 400);
        });

        input.addEventListener('keydown', function(e) {
            const items = suggestionsContainer.querySelectorAll('.suggestion-item');
            if (!items.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % items.length;
                highlightItem(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                highlightItem(items);
            } else if (e.key === 'Enter') {
                if (selectedIndex > -1 && items[selectedIndex]) {
                    e.preventDefault();
                    selectSuggestion(suggestions[selectedIndex]);
                }
            } else if (e.key === 'Escape') {
                hideSuggestions();
            }
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                hideSuggestions();
            }
        });

        function fetchSuggestions(query) {
            if (loadingIcon) loadingIcon.classList.remove('hidden');

            fetch(`/alumni/geocode/suggest?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestions = data;
                    renderSuggestions();
                })
                .catch(err => {
                    console.error('Error fetching autocomplete suggestions:', err);
                })
                .finally(() => {
                    if (loadingIcon) loadingIcon.classList.add('hidden');
                });
        }

        function renderSuggestions() {
            suggestionsContainer.innerHTML = '';
            selectedIndex = -1;

            if (!suggestions.length) {
                const noResult = document.createElement('div');
                noResult.className = 'p-3 text-sm text-gray-500 dark:text-gray-400 italic';
                noResult.textContent = 'Lokasi tidak ditemukan. Tetap bisa disimpan, namun mungkin tidak tampil di peta.';
                suggestionsContainer.appendChild(noResult);
                suggestionsContainer.classList.remove('hidden');
                return;
            }

            suggestions.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = 'suggestion-item p-3 text-sm hover:bg-gray-100 dark:hover:bg-gray-700/50 cursor-pointer border-b border-gray-100 dark:border-gray-700/30 last:border-b-0 transition-colors flex flex-col gap-0.5';
                
                // Highlight match
                const q = input.value.trim();
                const regex = new RegExp(`(${q})`, 'gi');
                const highlightedText = item.display_name.replace(regex, '<strong class="text-primary-600 dark:text-primary-400 font-semibold">$1</strong>');
                
                div.innerHTML = `
                    <div class="font-medium text-gray-800 dark:text-gray-200">${item.name}</div>
                    <div class="text-xs text-gray-400 dark:text-gray-500">${highlightedText}</div>
                `;

                div.addEventListener('click', () => {
                    selectSuggestion(item);
                });

                suggestionsContainer.appendChild(div);
            });

            suggestionsContainer.classList.remove('hidden');
        }

        function highlightItem(items) {
            items.forEach((item, index) => {
                if (index === selectedIndex) {
                    item.classList.add('bg-gray-100', 'dark:bg-gray-700/50');
                    item.scrollIntoView({ block: 'nearest' });
                } else {
                    item.classList.remove('bg-gray-100', 'dark:bg-gray-700/50');
                }
            });
        }

        function selectSuggestion(item) {
            input.value = item.display_name;
            hideSuggestions();
        }

        function hideSuggestions() {
            suggestionsContainer.classList.add('hidden');
            selectedIndex = -1;
        }
    });
    </script>
    @endpush
</x-layouts.app>
