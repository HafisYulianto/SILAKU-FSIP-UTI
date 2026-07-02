<x-layouts.app :title="'Peta Sebaran Alumni'">
    <div class="space-y-8 fade-in">
        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">📍 Peta Sebaran Geografis Alumni Bekerja</h1>
                <p class="page-subtitle">Pemetaan lokasi alumni FSIP Teknokrat berdasarkan data yang diinput</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-2.5 py-1 rounded-full font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Map
                </span>
            </div>
        </div>

        {{-- Map Card --}}
        <div class="card relative"
             x-data="{
                isLoading: true,
                mapView: 'lampung',
                searchQuery: '',
                selectedProdi: 'all',
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
                },
                filterMap() {
                    if (window.filterAlumniMap) {
                        window.filterAlumniMap(this.searchQuery, this.selectedProdi);
                    }
                }
             }"
             x-init="window.activeMapView = 'lampung'; setTimeout(() => isLoading = false, 800)">

            <!-- Skeleton overlay -->
            <div x-show="isLoading" class="absolute inset-0 bg-white dark:bg-gray-900 z-10 flex flex-col p-6 rounded-2xl">
                <div class="skeleton h-6 w-1/3 mb-6 animate-pulse"></div>
                <div class="skeleton h-[550px] w-full animate-pulse"></div>
            </div>

            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Peta Interaktif</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Navigasikan wilayah dan klik pin marker untuk informasi detail alumni</p>
                </div>
                <div class="flex items-center gap-3">
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
                </div>
            </div>

            {{-- Search and Filter Controls --}}
            <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/30 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 items-center justify-between">
                {{-- Search Box --}}
                <div class="relative w-full sm:w-80">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" 
                           x-model="searchQuery" 
                           @input="filterMap()"
                           placeholder="Cari nama alumni, instansi, atau lokasi..." 
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                </div>

                {{-- Filter Box --}}
                <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                    <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">Filter Prodi:</span>
                    <select x-model="selectedProdi" 
                            @change="filterMap()"
                            class="py-2 px-3 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all min-w-[160px]">
                        <option value="all">Semua Program Studi</option>
                        @foreach(\App\Models\ProgramStudi::where('is_active', true)->get() as $prodi)
                        <option value="{{ $prodi->name }}">{{ $prodi->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div :class="{ 'opacity-0': isLoading, 'opacity-100 transition-opacity duration-500': !isLoading }" class="p-6">
                {{-- Map Container --}}
                <div id="map-alumni-magang" class="h-[550px] rounded-xl border border-gray-200 dark:border-gray-800 z-0"></div>
                
                {{-- Alumni without coordinates notice --}}
                @php $withoutCoords = $mapRecords->whereNull('lat')->count(); @endphp
                @if($withoutCoords > 0)
                <p class="text-xs text-amber-600 dark:text-amber-400 mt-3 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    {{ $withoutCoords }} alumni belum memiliki koordinat geografis. Jalankan sinkronisasi koordinat atau hubungi administrator.
                </p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('alpine:init', () => {
        // Leaflet.js Geografis Map Initialization
        const mapElement = document.getElementById('map-alumni-magang');
        if (mapElement) {
            // Include Leaflet style dynamically
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

                window.alumniMap = map;

                L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(map);

                // Map data
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

                let currentMarkers = [];

                function renderMarkers(dataToRender) {
                    // Remove all old markers
                    currentMarkers.forEach(m => map.removeLayer(m));
                    currentMarkers = [];

                    // Group markers by coordinate
                    const locationGroups = {};
                    dataToRender.forEach(item => {
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
                            markerHtml = `
                                <div style="position:relative; display:flex; align-items:center; justify-content:center; width:28px; height:28px;">
                                    <span style="position:absolute; display:inline-flex; width:100%; height:100%; border-radius:50%; background:#10b981; opacity:0.4; animation:ping 1.5s cubic-bezier(0,0,0.2,1) infinite;"></span>
                                    <span style="position:relative; display:inline-flex; border-radius:50%; width:24px; height:24px; background:#10b981; color:white; font-size:10px; font-weight:700; font-family:sans-serif; align-items:center; justify-content:center; border:2px solid white; box-shadow:0 2px 6px rgba(0,0,0,0.25);">${primaryAlumni.no}</span>
                                </div>
                            `;
                        } else {
                            const numbersStr = group.alumni.map(a => a.no).join(', ');
                            markerSize = [44, 28];
                            markerAnchor = [22, 14];
                            markerHtml = `
                                <div style="position:relative; display:flex; align-items:center; justify-content:center; width:44px; height:28px;">
                                    <span style="position:absolute; display:inline-flex; width:100%; height:100%; border-radius:14px; background:#0284c7; opacity:0.4; animation:ping 1.5s cubic-bezier(0,0,0.2,1) infinite;"></span>
                                    <span style="position:relative; display:inline-flex; border-radius:14px; width:40px; height:24px; background:#0284c7; color:white; font-size:9px; font-weight:700; font-family:sans-serif; align-items:center; justify-content:center; border:2px solid white; box-shadow:0 2px 6px rgba(0,0,0,0.25); white-space:nowrap; padding:0 4px;">${numbersStr}</span>
                                </div>
                            `;
                        }

                        let popupHtml = `
                            <div style="padding:12px; min-width:210px; max-width:280px; font-family:sans-serif; max-height:240px; overflow-y:auto; scrollbar-width:thin;">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:8px; padding-bottom:6px; border-bottom:1px solid #e5e7eb; padding-right:20px;">
                                    <span style="font-size:11px; font-weight:700; color:#374151; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">📍 ${group.lokasi}</span>
                                    ${count > 1 ? `<span style="background:#e0f2fe; color:#0369a1; font-size:9px; font-weight:700; padding:2px 6px; border-radius:10px; flex-shrink:0;">${count} Alumni</span>` : ''}
                                </div>
                        `;

                        group.alumni.forEach((alumni, idx) => {
                            popupHtml += `
                                <div style="margin-bottom:${idx === count - 1 ? '0' : '8'}px; padding-bottom:${idx === count - 1 ? '0' : '8'}px; border-bottom:${idx === count - 1 ? 'none' : '1px dashed #f3f4f6'};">
                                    <div style="display:flex; align-items:center; gap:6px; margin-bottom:3px;">
                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; background:${count > 1 ? '#0284c7' : '#10b981'}; color:white; border-radius:50%; font-size:9px; font-weight:700; flex-shrink:0;">
                                            ${alumni.no}
                                        </span>
                                        <span style="font-size:12px; font-weight:700; color:#111827;">${alumni.nama}</span>
                                    </div>
                                    <div style="font-size:10px; font-weight:600; color:${count > 1 ? '#0284c7' : '#059669'}; margin-left:24px; margin-bottom:1px;">${alumni.instansi}</div>
                                    <div style="font-size:9px; color:#6b7280; margin-left:24px;">${alumni.posisi} · ${alumni.prodi}</div>
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

                        marker.on('click', function(e) {
                            map.flyTo(e.target.getLatLng(), 15, { duration: 1.2 });
                        });

                        currentMarkers.push(marker);
                    });

                    // Empty notice handling
                    const parent = mapElement.parentElement;
                    let notice = document.getElementById('map-empty-notice');
                    if (dataToRender.length === 0) {
                        if (!notice) {
                            notice = document.createElement('div');
                            notice.id = 'map-empty-notice';
                            notice.style.cssText = 'position:absolute; inset:0; background:rgba(255,255,255,0.8); z-index:400; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:8px; font-family:sans-serif; color:#9ca3af; border-radius: 12px;';
                            if (document.documentElement.classList.contains('dark')) {
                                notice.style.background = 'rgba(17, 24, 39, 0.8)';
                            }
                            notice.innerHTML = `
                                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p style="font-size:13px; font-weight:600;">Tidak ada alumni yang sesuai kriteria</p>
                            `;
                            mapElement.style.visibility = 'hidden';
                            mapElement.after(notice);
                        }
                    } else {
                        if (notice) {
                            notice.remove();
                            mapElement.style.visibility = 'visible';
                        }
                    }
                }

                // Initial render of markers
                renderMarkers(mapRecordsData);

                // Global filter function exposed to Alpine.js
                window.filterAlumniMap = function(query, prodi) {
                    const q = query.toLowerCase().trim();
                    const filtered = mapRecordsData.filter(item => {
                        const matchQuery = !q || 
                            item.nama.toLowerCase().includes(q) || 
                            item.lokasi.toLowerCase().includes(q) || 
                            item.instansi.toLowerCase().includes(q) || 
                            item.posisi.toLowerCase().includes(q);
                        const matchProdi = prodi === 'all' || item.prodi === prodi;
                        return matchQuery && matchProdi;
                    });

                    renderMarkers(filtered);

                    // If exact match (all coordinates in filtered map to 1 unique coordinate), fly to and zoom
                    if (filtered.length > 0) {
                        const uniqueCoords = new Set(filtered.map(x => `${x.lat.toFixed(4)}_${x.lng.toFixed(4)}`));
                        if (uniqueCoords.size === 1) {
                            const first = filtered[0];
                            const matchMarker = currentMarkers.find(m => {
                                const latLng = m.getLatLng();
                                return latLng.lat.toFixed(4) === first.lat.toFixed(4) && 
                                       latLng.lng.toFixed(4) === first.lng.toFixed(4);
                            });
                            if (matchMarker) {
                                map.flyTo(matchMarker.getLatLng(), 15, { duration: 1.2 });
                                setTimeout(() => matchMarker.openPopup(), 1200);
                            }
                        }
                    }
                };

                let popupCloseTimer;
                map.on('popupopen', function() {
                    if (popupCloseTimer) clearTimeout(popupCloseTimer);
                });

                map.on('popupclose', function(e) {
                    popupCloseTimer = setTimeout(() => {
                        const views = {
                            lampung:   { center: [-5.40, 105.26], zoom: 8.5 },
                            indonesia: { center: [-2.50, 117.00], zoom: 5 },
                            dunia:     { center: [20.0, 0.0],     zoom: 2 }
                        };
                        const activeView = window.activeMapView || 'lampung';
                        const v = views[activeView];
                        map.flyTo(v.center, v.zoom, { duration: 1.2 });
                    }, 300);
                });
            };
            document.head.appendChild(leafletScript);
        }
    });
    </script>
    @endpush
</x-layouts.app>
