<x-layouts.app :title="'Dashboard Akreditasi'">
    <div class="space-y-8 fade-in" x-data="{
        tourActive: {{ (auth()->user()->hasRole('Pimpinan') || auth()->user()->hasRole('Wakil Dekan')) ? 'false' : '!localStorage.getItem(\'tour_completed\')' }},
        tourStep: {{ (auth()->user()->hasRole('Pimpinan') || auth()->user()->hasRole('Wakil Dekan')) ? '0' : '(localStorage.getItem(\'tour_completed\') ? 0 : 1)' }},
        tourPositionStyle: 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 28rem; max-width: 90vw;',
        startTour() {
            this.tourStep = 1;
            this.tourActive = true;
        },
        nextStep() {
            if (this.tourStep < 5) this.tourStep++;
        },
        prevStep() {
            if (this.tourStep > 1) this.tourStep--;
        },
        endTour() {
            this.tourActive = false;
            this.tourStep = 0;
            localStorage.setItem('tour_completed', 'true');
            document.querySelectorAll('.tour-highlight-style').forEach(el => {
                el.style.boxShadow = '';
                el.classList.remove('tour-highlight-style');
            });
        },
        updateTourPosition() {
            if (this.tourStep === 1 || this.tourStep === 0) {
                this.tourPositionStyle = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 28rem; max-width: 90vw;';
                const cutout = document.querySelector('#tour-mask-cutout');
                if (cutout) {
                    cutout.setAttribute('width', '0');
                    cutout.setAttribute('height', '0');
                }
                return;
            }
            
            let target = null;
            if (this.tourStep === 2) target = document.querySelector('#tour-stats');
            if (this.tourStep === 3) target = document.querySelector('#tour-charts');
            if (this.tourStep === 4) target = document.querySelector('#theme-toggle');
            if (this.tourStep === 5) target = document.querySelector('#user-menu-button');
            
            if (target) {
                const rect = target.getBoundingClientRect();
                const windowWidth = window.innerWidth;
                const windowHeight = window.innerHeight;
                
                const cutout = document.querySelector('#tour-mask-cutout');
                if (cutout) {
                    const pad = 12;
                    cutout.setAttribute('x', rect.left - pad);
                    cutout.setAttribute('y', rect.top - pad);
                    cutout.setAttribute('width', rect.width + pad * 2);
                    cutout.setAttribute('height', rect.height + pad * 2);
                }
                
                let top, left, transform = '';
                const targetCenterY = rect.top + rect.height / 2;
                const placeAbove = targetCenterY > windowHeight / 2;
                
                if (placeAbove) {
                    top = rect.top - 16;
                    transform = 'translateY(-100%)';
                } else {
                    top = rect.bottom + 16;
                    transform = 'translateY(0)';
                }
                
                left = rect.left + rect.width / 2;
                transform += ' translateX(-50%)';
                
                const bubbleWidth = 448;
                const minLeft = bubbleWidth / 2 + 16;
                const maxLeft = windowWidth - (bubbleWidth / 2) - 16;
                
                if (left < minLeft) {
                    left = minLeft;
                } else if (left > maxLeft) {
                    left = maxLeft;
                }
                
                this.tourPositionStyle = `position: fixed; top: ${top}px; left: ${left}px; transform: ${transform}; width: 28rem; max-width: calc(100vw - 32px);`;
            }
        }
    }" x-init="
        $watch('tourStep', step => {
            document.querySelectorAll('.tour-highlight-style').forEach(el => {
                el.style.boxShadow = '';
                el.classList.remove('tour-highlight-style');
            });
            
            if (step === 0) return;
            
            let target = null;
            if (step === 2) target = document.querySelector('#tour-stats');
            if (step === 3) target = document.querySelector('#tour-charts');
            if (step === 4) target = document.querySelector('#theme-toggle');
            if (step === 5) target = document.querySelector('#user-menu-button');
            
            if (target) {
                target.classList.add('tour-highlight-style');
                target.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.4)';
                target.scrollIntoView({ behavior: 'auto', block: 'center' });
            }
            
            setTimeout(() => {
                updateTourPosition();
            }, 100);
        });
        
        $watch('darkMode', val => {
            const chartTheme = val ? 'dark' : 'light';
            if (window.prodiChart) {
                window.prodiChart.updateOptions({ theme: { mode: chartTheme } });
            }
            if (window.entityChart) {
                window.entityChart.updateOptions({ theme: { mode: chartTheme } });
            }
            if (window.dynamicCharts) {
                window.dynamicCharts.forEach(c => {
                    if (c) c.updateOptions({ theme: { mode: chartTheme } });
                });
            }
        });
        
        setTimeout(() => {
            const isDark = document.documentElement.classList.contains('dark');
            const chartTheme = isDark ? 'dark' : 'light';
            if (window.prodiChart) window.prodiChart.updateOptions({ theme: { mode: chartTheme } });
            if (window.entityChart) window.entityChart.updateOptions({ theme: { mode: chartTheme } });
            if (window.dynamicCharts) {
                window.dynamicCharts.forEach(c => {
                    if (c) c.updateOptions({ theme: { mode: chartTheme } });
                });
            }
            if (tourActive) {
                updateTourPosition();
            }
        }, 1000);
    "
    @resize.window.debounce.50ms="updateTourPosition()"
    @scroll.window.debounce.50ms="updateTourPosition()">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Dashboard Akreditasi</h1>
                <p class="page-subtitle">Ringkasan data FSIP Universitas Teknokrat Indonesia</p>
            </div>
            <div class="flex items-center gap-3">
                @unless(auth()->user()->hasRole('Pimpinan') || auth()->user()->hasRole('Wakil Dekan'))
                <button @click="startTour()" class="btn-secondary btn-sm flex items-center gap-1.5 transition-all duration-300 hover:scale-105" id="start-tour-btn">
                    <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Panduan Portal</span>
                </button>
                @endunless
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">Terakhir diperbarui:</span>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ now()->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5" id="tour-stats">
            {{-- Total Dosen --}}
            <div class="stat-card slide-up" style="animation-delay: 0ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Data Dosen</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_dosen']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-950/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    <span class="text-xs text-primary-600 dark:text-primary-400 font-medium">{{ $dosenEntities->count() }} kategori</span>
                </div>
            </div>

            {{-- Total Mahasiswa --}}
            <div class="stat-card slide-up" style="animation-delay: 50ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Data Mahasiswa</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_mahasiswa']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-950/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ $mahasiswaEntities->count() }} kategori</span>
                </div>
            </div>

            {{-- Total Alumni --}}
            <div class="stat-card slide-up" style="animation-delay: 100ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-teal-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Data Alumni</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_alumni']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-teal-100 dark:bg-teal-950/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center gap-1 mt-3">
                    <span class="text-xs text-teal-600 dark:text-teal-400 font-medium">Data Terpusat</span>
                </div>
            </div>

            {{-- Kategori Aktif --}}
            <div class="stat-card slide-up" style="animation-delay: 200ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori Data Aktif</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_entities']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-950/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Program Studi --}}
            <div class="stat-card slide-up" style="animation-delay: 300ms">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500 rounded-full opacity-10 -translate-y-6 translate-x-6"></div>
                <div class="flex items-start justify-between relative">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Program Studi</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($stats['total_prodi']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-950/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pimpinan & Wakil Dekan Quick Access Portal --}}
        @hasanyrole('Pimpinan|Wakil Dekan')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 slide-up" style="animation-delay: 350ms">
            <a href="{{ route('pimpinan.browse', 'dosen') }}" class="group relative block overflow-hidden rounded-2xl bg-gradient-to-br from-primary-600 to-primary-800 p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute right-8 bottom-8 w-20 h-20 bg-white/5 rounded-full blur-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 group-hover:bg-white/30 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">📚 Lihat Data Dosen</h3>
                    <p class="text-primary-100 text-sm">Akses seluruh kategori dan record data dosen yang telah diinput oleh BAAK, Kaprodi, dan Dosen.</p>
                    <div class="mt-6 flex items-center gap-2 text-sm font-medium text-primary-200 group-hover:text-white transition-colors">
                        <span>Buka Halaman</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('pimpinan.browse', 'mahasiswa') }}" class="group relative block overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute right-8 bottom-8 w-20 h-20 bg-white/5 rounded-full blur-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 group-hover:bg-white/30 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">🎓 Lihat Data Mahasiswa</h3>
                    <p class="text-blue-100 text-sm">Akses seluruh kategori dan record data mahasiswa yang telah diinput oleh BAAK, Kaprodi, dan Dosen.</p>
                    <div class="mt-6 flex items-center gap-2 text-sm font-medium text-blue-200 group-hover:text-white transition-colors">
                        <span>Buka Halaman</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>
            <a href="{{ route('alumni.index') }}" class="group relative block overflow-hidden rounded-2xl bg-gradient-to-br from-teal-600 to-teal-800 p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute right-8 bottom-8 w-20 h-20 bg-white/5 rounded-full blur-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 group-hover:bg-white/30 transition-colors">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">💼 Lihat Data Alumni</h3>
                    <p class="text-teal-100 text-sm">Akses data instansi, posisi, dan sebaran lokasi kerja alumni Fakultas Sastra dan Ilmu Pendidikan.</p>
                    <div class="mt-6 flex items-center gap-2 text-sm font-medium text-teal-200 group-hover:text-white transition-colors">
                        <span>Buka Halaman</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </div>
                </div>
            </a>
        </div>
        @endhasanyrole

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="tour-charts">
            {{-- Program Studi Distribution --}}
            <div class="chart-card slide-up relative" style="animation-delay: 400ms" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)">
                <!-- Skeleton overlay -->
                <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                    <div class="skeleton h-6 w-1/3 mb-6"></div>
                    <div class="flex items-end gap-3 h-full pb-4">
                        <div class="skeleton h-[35%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[65%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[45%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[80%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[25%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[55%] flex-1 animate-pulse"></div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <h3 class="chart-title mb-0">Distribusi Data per Program Studi</h3>
                    <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-0.5 rounded-lg text-xs" x-data="{ chartType: 'bar' }">
                        <button @click="chartType = 'bar'; window.prodiChart.updateOptions({ chart: { type: 'bar' } })" :class="chartType === 'bar' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="px-2 py-0.5 rounded-md transition-all">Bar</button>
                        <button @click="chartType = 'line'; window.prodiChart.updateOptions({ chart: { type: 'line' } })" :class="chartType === 'line' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="px-2 py-0.5 rounded-md transition-all">Line</button>
                    </div>
                </div>
                <div :class="{ 'opacity-0': isLoading, 'opacity-100 transition-opacity duration-500': !isLoading }">
                    <div id="chart-prodi-distribution" class="h-80"></div>
                </div>
            </div>

            {{-- Entity Summary Donut --}}
            <div class="chart-card slide-up relative" style="animation-delay: 500ms" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)">
                <!-- Skeleton overlay -->
                <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                    <div class="skeleton h-6 w-1/3 mb-6"></div>
                    <div class="flex items-center justify-center h-full">
                        <div class="skeleton w-48 h-48 rounded-full border-[20px] border-gray-100 dark:border-gray-800 flex items-center justify-center animate-pulse"></div>
                    </div>
                </div>
                
                <h3 class="chart-title">Sebaran Kategori Data</h3>
                <div :class="{ 'opacity-0': isLoading, 'opacity-100 transition-opacity duration-500': !isLoading }">
                    <div id="chart-entity-summary" class="h-80"></div>
                </div>
            </div>
        </div>

        @php
            $mapRecords = \App\Models\Alumni::with('programStudi')->orderBy('nama', 'asc')->get();
        @endphp
        {{-- Map Row --}}
        <div class="card slide-up relative" style="animation-delay: 550ms"
             x-data="{
                isLoading: true,
                mapView: 'lampung',
                setView(view) {
                    this.mapView = view;
                    window.activeMapView = view;
                    if (window.alumniMap) {
                        window.alumniMap.closePopup();
                        const views = {
                            lampung:   { center: [-5.40, 105.26], zoom: 8.5 },
                            indonesia: { center: [-2.50, 117.00], zoom: 5 },
                            dunia:     { center: [20.0, 0.0],     zoom: 2 }
                        };
                        const v = views[view];
                        window.alumniMap.flyTo(v.center, v.zoom, { duration: 1.2 });
                    }
                }
             }"
             x-init="window.activeMapView = 'lampung'; setTimeout(() => isLoading = false, 800)">

            <!-- Skeleton overlay -->
            <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                <div class="skeleton h-6 w-1/3 mb-6 animate-pulse"></div>
                <div class="skeleton h-96 w-full animate-pulse"></div>
            </div>

            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">📍 Sebaran Geografis Alumni Bekerja</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pemetaan lokasi alumni berdasarkan data yang diinput</p>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    {{-- View Toggle --}}
                    <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-xl p-1 gap-0.5">
                        <button @click="setView('lampung')"
                                :class="mapView === 'lampung'
                                    ? 'bg-white dark:bg-gray-700 shadow-sm text-emerald-600 dark:text-emerald-400 font-semibold'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all duration-200 flex items-center gap-1.5">
                            <span>🗺️</span> Lampung
                        </button>
                        <button @click="setView('indonesia')"
                                :class="mapView === 'indonesia'
                                    ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600 dark:text-blue-400 font-semibold'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all duration-200 flex items-center gap-1.5">
                            <span>🏝️</span> Indonesia
                        </button>
                        <button @click="setView('dunia')"
                                :class="mapView === 'dunia'
                                    ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600 dark:text-purple-400 font-semibold'
                                    : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                                class="px-3 py-1.5 rounded-lg text-xs transition-all duration-200 flex items-center gap-1.5">
                            <span>🌍</span> Dunia
                        </button>
                    </div>

                    <span class="inline-flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 rounded-full font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Map
                    </span>
                </div>
            </div>

            <div :class="{ 'opacity-0': isLoading, 'opacity-100 transition-opacity duration-500': !isLoading }" class="p-6">
                {{-- Map Container --}}
                <div id="map-alumni-magang" class="h-[420px] rounded-xl border border-gray-200 dark:border-gray-800 z-0"></div>
                {{-- Alumni without coordinates notice --}}
                @php $withoutCoords = $mapRecords->whereNull('lat')->count(); @endphp
                @if($withoutCoords > 0)
                <p class="text-xs text-amber-600 dark:text-amber-400 mt-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    {{ $withoutCoords }} alumni belum memiliki koordinat. Jalankan <code class="bg-amber-50 dark:bg-amber-950/30 px-1 rounded font-mono">php artisan alumni:geocode</code> untuk mengisi koordinat.
                </p>
                @endif
            </div>
        </div>


        {{-- Dynamic Charts from aggregatable fields --}}
        @if(count($chartData) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($chartData as $index => $chart)
            <div class="chart-card slide-up relative" style="animation-delay: {{ 600 + ($index * 100) }}ms" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)">
                <!-- Skeleton overlay -->
                <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                    <div class="skeleton h-6 w-1/3 mb-6"></div>
                    <div class="flex items-end gap-3 h-full pb-4">
                        <div class="skeleton h-[55%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[30%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[75%] flex-1 animate-pulse"></div>
                        <div class="skeleton h-[45%] flex-1 animate-pulse"></div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-4" x-data="{ chartType: '{{ $chart['type'] === 'donut' ? 'donut' : 'bar' }}' }">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $chart['entity_name'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Berdasarkan {{ $chart['field_name'] }}</p>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($chart['type'] !== 'donut')
                        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 p-0.5 rounded-lg text-xs">
                            <button @click="chartType = 'bar'; window.dynamicCharts[{{ $index }}].updateOptions({ chart: { type: 'bar' } })" :class="chartType === 'bar' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="px-2 py-0.5 rounded-md transition-all">Bar</button>
                            <button @click="chartType = 'line'; window.dynamicCharts[{{ $index }}].updateOptions({ chart: { type: 'line' } })" :class="chartType === 'line' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="px-2 py-0.5 rounded-md transition-all">Line</button>
                        </div>
                        @endif
                        <span class="badge {{ $chart['root_category'] === 'dosen' ? 'badge-primary' : 'badge-info' }}">
                            {{ ucfirst($chart['root_category']) }}
                        </span>
                    </div>
                </div>
                
                <div :class="{ 'opacity-0': isLoading, 'opacity-100 transition-opacity duration-500': !isLoading }">
                    <div id="chart-dynamic-{{ $index }}" class="h-72"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Entity Overview Table --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="tour-categories">
            {{-- Dosen Entities --}}
            <div class="card slide-up relative" style="animation-delay: 700ms" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)">
                <!-- Skeleton overlay -->
                <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                    <div class="skeleton h-6 w-1/3 mb-6"></div>
                    <div class="space-y-3">
                        <div class="skeleton h-10 w-full animate-pulse"></div>
                        <div class="skeleton h-10 w-full animate-pulse"></div>
                        <div class="skeleton h-10 w-full animate-pulse"></div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-white">📚 Kategori Dosen</h3>
                    @role('BAAK')
                    <a href="{{ route('entities.create') }}" class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium">+ Tambah</a>
                    @endrole
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($dosenEntities as $entity)
                    <a href="{{ route('entities.view', $entity) }}" class="flex items-center justify-between px-6 py-3 hover:bg-primary-50/50 dark:hover:bg-primary-950/20 transition-colors">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $entity->name }}</span>
                        <span class="badge-primary">{{ $entity->records_count }} data</span>
                    </a>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">Belum ada kategori dosen</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Mahasiswa Entities --}}
            <div class="card slide-up relative" style="animation-delay: 750ms" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)">
                <!-- Skeleton overlay -->
                <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                    <div class="skeleton h-6 w-1/3 mb-6"></div>
                    <div class="space-y-3">
                        <div class="skeleton h-10 w-full animate-pulse"></div>
                        <div class="skeleton h-10 w-full animate-pulse"></div>
                        <div class="skeleton h-10 w-full animate-pulse"></div>
                    </div>
                </div>
                
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-white">🎓 Kategori Mahasiswa</h3>
                    @role('BAAK')
                    <a href="{{ route('entities.create') }}" class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium">+ Tambah</a>
                    @endrole
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($mahasiswaEntities as $entity)
                    <a href="{{ route('entities.view', $entity) }}" class="flex items-center justify-between px-6 py-3 hover:bg-blue-50/50 dark:hover:bg-blue-950/20 transition-colors">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $entity->name }}</span>
                        <span class="badge-info">{{ $entity->records_count }} data</span>
                    </a>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">Belum ada kategori mahasiswa</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card slide-up relative" style="animation-delay: 900ms" x-data="{ isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)">
            <!-- Skeleton overlay -->
            <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                <div class="skeleton h-6 w-1/3 mb-6"></div>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="skeleton skeleton-avatar animate-pulse"></div>
                        <div class="skeleton h-4 flex-1 animate-pulse"></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="skeleton skeleton-avatar animate-pulse"></div>
                        <div class="skeleton h-4 flex-1 animate-pulse"></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="skeleton skeleton-avatar animate-pulse"></div>
                        <div class="skeleton h-4 flex-1 animate-pulse"></div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($recentRecords as $record)
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-950/40 rounded-full flex items-center justify-center text-xs font-bold text-primary-700 dark:text-primary-300">
                            {{ substr($record->creator->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm text-gray-700 dark:text-gray-300"><span class="font-medium text-gray-900 dark:text-white">{{ $record->creator->name ?? 'Unknown' }}</span> menambah data ke <span class="font-medium text-primary-600 dark:text-primary-400">{{ $record->entity->name }}</span></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $record->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($record->programStudi)
                    <span class="badge-info">{{ $record->programStudi->code }}</span>
                    @endif
                </div>
                @empty
                <div class="px-6 py-12 text-center empty-state">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-400 font-medium">Belum ada aktivitas</p>
                    <p class="text-sm text-gray-300 mt-1">Data akan muncul saat Anda mulai menginput</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- SVG Spotlight Overlay -->
        <svg class="fixed inset-0 w-full h-full pointer-events-none transition-all duration-300 z-[45]" 
             x-show="tourActive && tourStep > 1" 
             x-cloak>
            <defs>
                <mask id="tour-spotlight-mask">
                    <!-- White keeps backdrop dark -->
                    <rect width="100%" height="100%" fill="white" />
                    <!-- Black cutout with rounded corners creates the spotlight cutout -->
                    <rect x="0" y="0" width="0" height="0" rx="16" fill="black" id="tour-mask-cutout" style="transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" />
                </mask>
            </defs>
            <!-- Semi-transparent backdrop -->
            <rect width="100%" height="100%" fill="rgba(15, 23, 42, 0.65)" mask="url(#tour-spotlight-mask)" class="pointer-events-auto" />
        </svg>

        <!-- General dark backdrop for Step 1 (Welcome) -->
        <div x-show="tourActive && tourStep === 1" 
             x-cloak 
             class="fixed inset-0 bg-slate-900/65 z-[45] backdrop-blur-xs transition-opacity duration-300"></div>

        <!-- Guided Tour Overlay / Card -->
        <div x-show="tourActive" 
             x-cloak 
             id="tour-bubble"
             :style="tourPositionStyle"
             class="fixed z-[50] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800/80 p-6 flex flex-col gap-4 transition-all duration-300 ease-out"
             style="display: none;">
            
            {{-- Tour Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">Panduan SILAKU • Langkah <span x-text="tourStep"></span> dari 5</span>
                <button @click="endTour()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Tour Content --}}
            <div>
                <!-- Step 1: Welcome -->
                <div x-show="tourStep === 1" class="space-y-3">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Selamat Datang di SILAKU FSIP! 🎓</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        SILAKU adalah Sistem Pelaporan IKU (Indikator Kinerja Utama) untuk Fakultas Sastra dan Ilmu Pendidikan Universitas Teknokrat Indonesia. 
                        Mari kita telusuri fitur-fitur penting dalam dashboard ini.
                    </p>
                </div>

                <!-- Step 2: Stats -->
                <div x-show="tourStep === 2" class="space-y-3">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Kartu Statistik Ringkasan 📊</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        Bagian ini menampilkan total data dosen, mahasiswa, kategori aktif, dan jumlah program studi. 
                        Anda bisa melihat pertumbuhan data IKU secara sekilas.
                    </p>
                </div>

                <!-- Step 3: Charts -->
                <div x-show="tourStep === 3" class="space-y-3">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Grafik Visual Distribusi 📈</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        Visualisasi data per Program Studi dan kategori. 
                        Anda dapat beralih tipe grafik atau mengunduh visualisasi ini untuk laporan akreditasi.
                    </p>
                </div>

                <!-- Step 4: Dark/Light Mode -->
                <div x-show="tourStep === 4" class="space-y-3">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Mode Gelap / Terang 🌗</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        Gunakan tombol matahari/bulan di atas untuk beralih antara tema Terang dan Gelap yang premium untuk kenyamanan mata Anda.
                    </p>
                </div>

                <!-- Step 5: Profil & Keluar -->
                <div x-show="tourStep === 5" class="space-y-3">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">Menu Pengguna & Logout 👤</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        Klik nama atau foto profil Anda untuk melihat detail akun yang sedang aktif, atau untuk keluar dari aplikasi secara aman.
                    </p>
                </div>
            </div>

            {{-- Tour Actions --}}
            <div class="flex items-center justify-between border-t border-gray-100 dark:border-gray-800 pt-4 mt-2">
                <button @click="endTour()" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-white underline focus:outline-none">
                    Lewati
                </button>
                
                {{-- Progress Dots --}}
                <div class="flex items-center gap-1.5 select-none">
                    <span class="h-1.5 rounded-full transition-all duration-300" :class="tourStep === 1 ? 'bg-primary-600 dark:bg-primary-400 w-3.5' : 'bg-gray-200 dark:bg-gray-700 w-1.5'"></span>
                    <span class="h-1.5 rounded-full transition-all duration-300" :class="tourStep === 2 ? 'bg-primary-600 dark:bg-primary-400 w-3.5' : 'bg-gray-200 dark:bg-gray-700 w-1.5'"></span>
                    <span class="h-1.5 rounded-full transition-all duration-300" :class="tourStep === 3 ? 'bg-primary-600 dark:bg-primary-400 w-3.5' : 'bg-gray-200 dark:bg-gray-700 w-1.5'"></span>
                    <span class="h-1.5 rounded-full transition-all duration-300" :class="tourStep === 4 ? 'bg-primary-600 dark:bg-primary-400 w-3.5' : 'bg-gray-200 dark:bg-gray-700 w-1.5'"></span>
                    <span class="h-1.5 rounded-full transition-all duration-300" :class="tourStep === 5 ? 'bg-primary-600 dark:bg-primary-400 w-3.5' : 'bg-gray-200 dark:bg-gray-700 w-1.5'"></span>
                </div>

                <div class="flex items-center gap-2">
                    <button x-show="tourStep > 1" @click="prevStep()" class="btn-secondary btn-sm py-1 px-2.5 text-xs">
                        Mundur
                    </button>
                    <button x-show="tourStep < 5" @click="nextStep()" class="btn-primary btn-sm py-1 px-3 text-xs">
                        Lanjut
                    </button>
                    <button x-show="tourStep === 5" @click="endTour()" class="btn-primary btn-sm py-1 px-3 text-xs bg-emerald-600 hover:bg-emerald-700">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const emeraldPalette = ['#10b981', '#059669', '#047857', '#34d399', '#6ee7b7', '#14b8a6', '#0d9488'];
        const pastelPalette = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'];

        // Program Studi Distribution Chart
        const prodiData = @json($prodiDistribution);
        if (prodiData.labels.length > 0) {
            window.prodiChart = new ApexCharts(document.querySelector('#chart-prodi-distribution'), {
                chart: { 
                    type: 'bar', 
                    height: 300, 
                    toolbar: { 
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }, 
                    fontFamily: 'Inter, sans-serif' 
                },
                series: [
                    { name: 'Dosen', data: prodiData.dosen },
                    { name: 'Mahasiswa', data: prodiData.mahasiswa },
                    { name: 'Alumni', data: prodiData.alumni }
                ],
                xaxis: { categories: prodiData.labels },
                colors: ['#10b981', '#3b82f6', '#14b8a6'],
                plotOptions: { bar: { borderRadius: 8, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top' },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                tooltip: { theme: 'light' },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
            });
            window.prodiChart.render();
        } else {
            document.querySelector('#chart-prodi-distribution').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data</div>';
        }

        // Entity Summary Donut
        const totalDosen = {{ $stats['total_dosen'] }};
        const totalMahasiswa = {{ $stats['total_mahasiswa'] }};
        const totalAlumni = {{ $stats['total_alumni'] }};
        if (totalDosen > 0 || totalMahasiswa > 0 || totalAlumni > 0) {
            window.entityChart = new ApexCharts(document.querySelector('#chart-entity-summary'), {
                chart: { 
                    type: 'donut', 
                    height: 300, 
                    toolbar: { 
                        show: true,
                        tools: {
                            download: true
                        }
                    },
                    fontFamily: 'Inter, sans-serif' 
                },
                series: [totalDosen, totalMahasiswa, totalAlumni],
                labels: ['Dosen', 'Mahasiswa', 'Alumni'],
                colors: ['#10b981', '#3b82f6', '#14b8a6'],
                legend: { position: 'bottom', fontSize: '12px' },
                dataLabels: { enabled: true, style: { fontSize: '11px' } },
                plotOptions: { pie: { donut: { size: '55%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '14px', fontWeight: 700 } } } } },
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
            });
            window.entityChart.render();
        } else {
            document.querySelector('#chart-entity-summary').innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data</div>';
        }

        // Dynamic Charts
        window.dynamicCharts = [];
        const dynamicCharts = @json($chartData);
        dynamicCharts.forEach(function(chart, index) {
            const el = document.querySelector('#chart-dynamic-' + index);
            if (!el) return;

            const data = chart.data;
            if (!data.labels || data.labels.length === 0) {
                el.innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data</div>';
                return;
            }

            let options = {
                theme: { mode: document.documentElement.classList.contains('dark') ? 'dark' : 'light' }
            };
            if (chart.type === 'donut') {
                options = {
                    ...options,
                    chart: { 
                        type: 'donut', 
                        height: 270, 
                        toolbar: { show: true, tools: { download: true } },
                        fontFamily: 'Inter, sans-serif' 
                    },
                    series: data.values,
                    labels: data.labels,
                    colors: emeraldPalette,
                    legend: { position: 'bottom', fontSize: '11px' },
                    plotOptions: { pie: { donut: { size: '55%' } } },
                };
            } else if (chart.type === 'area') {
                options = {
                    ...options,
                    chart: { 
                        type: 'area', 
                        height: 270, 
                        toolbar: { 
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false
                            }
                        }, 
                        fontFamily: 'Inter, sans-serif' 
                    },
                    series: [{ name: chart.field_name, data: data.values }],
                    xaxis: { categories: data.labels },
                    colors: ['#10b981'],
                    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1 } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                };
            } else {
                options = {
                    ...options,
                    chart: { 
                        type: 'bar', 
                        height: 270, 
                        toolbar: { 
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false
                            }
                        }, 
                        fontFamily: 'Inter, sans-serif' 
                    },
                    series: [{ name: chart.field_name, data: data.values }],
                    xaxis: { categories: data.labels },
                    colors: ['#10b981'],
                    plotOptions: { bar: { borderRadius: 6, columnWidth: '55%', distributed: true } },
                    dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 700 } },
                    legend: { show: false },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                };
            }
            window.dynamicCharts[index] = new ApexCharts(el, options);
            window.dynamicCharts[index].render();
        });

        // ═══════════════════════════════════════════
        // Leaflet.js Geografis Map Initialization
        // ═══════════════════════════════════════════
        const mapElement = document.getElementById('map-alumni-magang');
        if (mapElement) {
            // Include Leaflet style dynamically to keep document head clean
            const leafletLink = document.createElement('link');
            leafletLink.rel = 'stylesheet';
            leafletLink.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            document.head.appendChild(leafletLink);

            // Include Leaflet JS dynamically
            const leafletScript = document.createElement('script');
            leafletScript.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            leafletScript.onload = function() {
                // Initialize map centered in Lampung
                const map = L.map('map-alumni-magang', {
                    scrollWheelZoom: false,
                    zoomControl: true
                }).setView([-5.40, 105.26], 8.5);

                // Store globally so Alpine can control it via flyTo
                window.alumniMap = map;

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(map);

                // Map data — sorted A-Z (matches the list), with row number
                const mapRecordsData = [
                    @php $mapIdx = 0; @endphp
                    @foreach($mapRecords as $record)
                    @if($record->lat && $record->lng)
                    @php $mapIdx++; @endphp
                    {
                        no:      {{ $mapIdx }},
                        nama:    "{{ addslashes($record->nama) }}",
                        lokasi:  "{{ addslashes($record->lokasi) }}",
                        instansi:"{{ addslashes($record->nama_perusahaan) }}",
                        posisi:  "{{ addslashes($record->posisi) }}",
                        prodi:   "{{ addslashes($record->programStudi->name ?? 'FSIP') }}",
                        lat:     {{ $record->lat }},
                        lng:     {{ $record->lng }},
                    },
                    @endif
                    @endforeach
                ];

                // Group markers by coordinate (rounded to 4 decimals to merge identical locations)
                const locationGroups = {};
                mapRecordsData.forEach(item => {
                    const key = `${item.lat.toFixed(4)}_${item.lng.toFixed(4)}`;
                    if (!locationGroups[key]) {
                        locationGroups[key] = {
                            lat: item.lat,
                            lng: item.lng,
                            lokasi: item.lokasi,
                            alumni: []
                        };
                    }
                    locationGroups[key].alumni.push(item);
                });

                Object.values(locationGroups).forEach(group => {
                    const count = group.alumni.length;
                    const primaryAlumni = group.alumni[0];
                    let markerHtml = '';
                    let markerSize = [28, 28];
                    let markerAnchor = [14, 14];

                    if (count === 1) {
                        // Single alumnus marker
                        markerHtml = `
                            <div style="
                                position:relative;
                                display:flex;align-items:center;justify-content:center;
                                width:28px;height:28px;
                            ">
                                <span style="
                                    position:absolute;
                                    display:inline-flex;width:100%;height:100%;
                                    border-radius:50%;background:#10b981;opacity:0.4;
                                    animation:ping 1.5s cubic-bezier(0,0,0.2,1) infinite;
                                "></span>
                                <span style="
                                    position:relative;display:inline-flex;border-radius:50%;
                                    width:24px;height:24px;background:#10b981;
                                    color:white;font-size:10px;font-weight:700;font-family:sans-serif;
                                    align-items:center;justify-content:center;
                                    border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.25);
                                ">${primaryAlumni.no}</span>
                            </div>
                        `;
                    } else {
                        // Multi-alumni marker: showing all numbers like "1, 2" or "1,2,3"
                        const numbersStr = group.alumni.map(a => a.no).join(', ');
                        markerSize = [44, 28];
                        markerAnchor = [22, 14];
                        markerHtml = `
                            <div style="
                                position:relative;
                                display:flex;align-items:center;justify-content:center;
                                width:44px;height:28px;
                            ">
                                <span style="
                                    position:absolute;
                                    display:inline-flex;width:100%;height:100%;
                                    border-radius:14px;background:#0284c7;opacity:0.4;
                                    animation:ping 1.5s cubic-bezier(0,0,0.2,1) infinite;
                                "></span>
                                <span style="
                                    position:relative;display:inline-flex;border-radius:14px;
                                    width:40px;height:24px;background:#0284c7;
                                    color:white;font-size:9px;font-weight:700;font-family:sans-serif;
                                    align-items:center;justify-content:center;
                                    border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.25);
                                    white-space:nowrap;padding:0 4px;
                                ">${numbersStr}</span>
                            </div>
                        `;
                    }

                    // Build Popup HTML listing all alumni in this group
                    let popupHtml = `
                        <div style="padding:4px;min-width:210px;max-width:280px;font-family:sans-serif;max-height:240px;overflow-y:auto;scrollbar-width:thin;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px;padding-bottom:6px;border-bottom:1px solid #e5e7eb;">
                                <span style="font-size:11px;font-weight:700;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">📍 ${group.lokasi}</span>
                                ${count > 1 ? `<span style="background:#e0f2fe;color:#0369a1;font-size:9px;font-weight:700;padding:2px 6px;border-radius:10px;flex-shrink:0;">${count} Alumni</span>` : ''}
                            </div>
                    `;

                    group.alumni.forEach((alumni, idx) => {
                        popupHtml += `
                            <div style="margin-bottom:${idx === count - 1 ? '0' : '8'}px; padding-bottom:${idx === count - 1 ? '0' : '8'}px; border-bottom:${idx === count - 1 ? 'none' : '1px dashed #f3f4f6'};">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:3px;">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;width:18px;height:18px;background:${count > 1 ? '#0284c7' : '#10b981'};color:white;border-radius:50%;font-size:9px;font-weight:700;flex-shrink:0;">
                                        ${alumni.no}
                                    </span>
                                    <span style="font-size:12px;font-weight:700;color:#111827;">${alumni.nama}</span>
                                </div>
                                <div style="font-size:10px;font-weight:600;color:${count > 1 ? '#0284c7' : '#059669'};margin-left:24px;margin-bottom:1px;">${alumni.instansi}</div>
                                <div style="font-size:9px;color:#6b7280;margin-left:24px;">${alumni.posisi} · ${alumni.prodi}</div>
                            </div>
                        `;
                    });

                    popupHtml += `</div>`;

                    const icon = L.divIcon({
                        html: markerHtml,
                        className: 'custom-leaflet-marker',
                        iconSize: markerSize,
                        iconAnchor: markerAnchor,
                    });

                    const marker = L.marker([group.lat, group.lng], { icon })
                        .bindPopup(popupHtml, { className: 'premium-leaflet-popup', maxWidth: 280 })
                        .addTo(map);

                    // Click to Zoom In smoothly
                    marker.on('click', function(e) {
                        map.flyTo(e.target.getLatLng(), 15, { duration: 1.2 });
                    });
                });

                // Map popup close event to Zoom Out
                map.on('popupclose', function(e) {
                    setTimeout(() => {
                        // Check if any popup is still open on the map (e.g. user clicked another marker)
                        let anyOpen = false;
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Popup && map.hasLayer(layer)) {
                                anyOpen = true;
                            }
                        });

                        if (!anyOpen && !map._popup) {
                            const views = {
                                lampung:   { center: [-5.40, 105.26], zoom: 8.5 },
                                indonesia: { center: [-2.50, 117.00], zoom: 5 },
                                dunia:     { center: [20.0, 0.0],     zoom: 2 }
                            };
                            const activeView = window.activeMapView || 'lampung';
                            const v = views[activeView];
                            map.flyTo(v.center, v.zoom, { duration: 1.2 });
                        }
                    }, 150);
                });

                // If no markers, show info
                if (mapRecordsData.length === 0) {
                    mapElement.innerHTML = `
                        <div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:8px;color:#9ca3af;font-family:sans-serif;">
                            <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p style="font-size:13px;font-weight:600;">Belum ada data koordinat alumni</p>
                            <p style="font-size:11px;">Jalankan: php artisan alumni:geocode</p>
                        </div>
                    `;
                }
            };
            document.head.appendChild(leafletScript);
        }

    });
    </script>
    @endpush
</x-layouts.app>
