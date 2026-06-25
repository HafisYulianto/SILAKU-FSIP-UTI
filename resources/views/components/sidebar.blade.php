@php
    $entities = \App\Models\DynamicEntity::active()->with('children')->rootOnly()->orderBy('root_category')->orderBy('sort_order')->get();
    $dosenEntities = $entities->where('root_category', 'dosen');
    $mahasiswaEntities = $entities->where('root_category', 'mahasiswa');
    $alumniEntities = $entities->where('root_category', 'alumni');
    $pendingApprovalCount = \App\Models\DynamicEntity::pending()->count();
@endphp

<aside class="sidebar" :class="{ '-translate-x-full lg:translate-x-0': !mobileMenu, 'translate-x-0': mobileMenu }">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <img src="{{ asset('images/Logo FSIP 1.png') }}" alt="Logo FSIP" class="w-16 h-auto object-contain drop-shadow-md">
        <div>
            <h1 class="text-base font-bold text-white leading-tight">SILAKU</h1>
            <p class="text-[10px] text-primary-300 tracking-wide">Sistem Pelaporan IKU</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="mt-4 px-2 pb-4 overflow-y-auto h-[calc(100vh-88px)] space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        @hasanyrole('BAAK|Kaprodi')
        {{-- Entity Management --}}
        <div class="pt-4">
            <p class="sidebar-section-title">Manajemen Data</p>
        </div>

        <a href="{{ route('entities.index') }}"
           class="sidebar-link {{ request()->routeIs('entities.index') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <span>Semua Kategori</span>
        </a>

        <a href="{{ route('entities.create') }}"
           class="sidebar-link {{ request()->routeIs('entities.create') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Buat Kategori Baru</span>
        </a>
        @endhasanyrole

        {{-- Dosen Category --}}
        @if($dosenEntities->count() > 0)
        @php
            $dosenActive = request()->is('pimpinan/data/dosen') || request()->is('entities/dosen/*');
            foreach($dosenEntities as $entity) {
                if (request()->is('entities/' . $entity->id . '*')) {
                    $dosenActive = true;
                    break;
                }
            }
        @endphp
        <div class="pt-2" x-data="{ open: {{ $dosenActive ? 'true' : 'false' }} }">
            <div @click="open = !open" class="flex items-center justify-between px-4 py-2 cursor-pointer group select-none hover:bg-white/5 rounded-lg mx-2 transition-colors">
                <span class="text-xs font-bold uppercase tracking-wider text-primary-300/80 group-hover:text-white transition-colors flex items-center gap-1.5">
                    📚 Data Dosen
                </span>
                <span class="text-primary-300/80 group-hover:text-white transition-colors text-[10px]" x-text="open ? '▲' : '▼'"></span>
            </div>
            
            <div x-show="open" x-transition.opacity class="mt-1 space-y-1">
                @hasanyrole('Pimpinan|Wakil Dekan')
                <a href="{{ route('pimpinan.browse', 'dosen') }}"
                   class="sidebar-link {{ request()->is('pimpinan/data/dosen') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span class="truncate">Lihat Semua Data Dosen</span>
                </a>
                @endhasanyrole
                @foreach($dosenEntities as $entity)
                <a href="{{ route('entities.view', $entity) }}"
                   class="sidebar-link {{ request()->is('entities/' . $entity->id . '*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="truncate">{{ $entity->name }}</span>
                    <span class="ml-auto text-xs bg-white/10 rounded-full px-2 py-0.5">{{ $entity->records_count ?? $entity->records()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Mahasiswa Category --}}
        @if($mahasiswaEntities->count() > 0)
        @php
            $mahasiswaActive = request()->is('pimpinan/data/mahasiswa') || request()->is('entities/mahasiswa/*');
            foreach($mahasiswaEntities as $entity) {
                if (request()->is('entities/' . $entity->id . '*')) {
                    $mahasiswaActive = true;
                    break;
                }
            }
        @endphp
        <div class="pt-2" x-data="{ open: {{ $mahasiswaActive ? 'true' : 'false' }} }">
            <div @click="open = !open" class="flex items-center justify-between px-4 py-2 cursor-pointer group select-none hover:bg-white/5 rounded-lg mx-2 transition-colors">
                <span class="text-xs font-bold uppercase tracking-wider text-primary-300/80 group-hover:text-white transition-colors flex items-center gap-1.5">
                    🎓 Data Mahasiswa
                </span>
                <span class="text-primary-300/80 group-hover:text-white transition-colors text-[10px]" x-text="open ? '▲' : '▼'"></span>
            </div>
            
            <div x-show="open" x-transition.opacity class="mt-1 space-y-1">
                @hasanyrole('Pimpinan|Wakil Dekan')
                <a href="{{ route('pimpinan.browse', 'mahasiswa') }}"
                   class="sidebar-link {{ request()->is('pimpinan/data/mahasiswa') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span class="truncate">Lihat Semua Data Mahasiswa</span>
                </a>
                @endhasanyrole
                @foreach($mahasiswaEntities as $entity)
                <a href="{{ route('entities.view', $entity) }}"
                   class="sidebar-link {{ request()->is('entities/' . $entity->id . '*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="truncate">{{ $entity->name }}</span>
                    <span class="ml-auto text-xs bg-white/10 rounded-full px-2 py-0.5">{{ $entity->records_count ?? $entity->records()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Alumni Category --}}
        @if($alumniEntities->count() > 0)
        @php
            $alumniActive = request()->is('pimpinan/data/alumni') || request()->is('entities/alumni/*');
            foreach($alumniEntities as $entity) {
                if (request()->is('entities/' . $entity->id . '*')) {
                    $alumniActive = true;
                    break;
                }
            }
        @endphp
        <div class="pt-2" x-data="{ open: {{ $alumniActive ? 'true' : 'false' }} }">
            <div @click="open = !open" class="flex items-center justify-between px-4 py-2 cursor-pointer group select-none hover:bg-white/5 rounded-lg mx-2 transition-colors">
                <span class="text-xs font-bold uppercase tracking-wider text-primary-300/80 group-hover:text-white transition-colors flex items-center gap-1.5">
                    💼 Data Alumni
                </span>
                <span class="text-primary-300/80 group-hover:text-white transition-colors text-[10px]" x-text="open ? '▲' : '▼'"></span>
            </div>
            
            <div x-show="open" x-transition.opacity class="mt-1 space-y-1">
                @hasanyrole('Pimpinan|Wakil Dekan')
                <a href="{{ route('pimpinan.browse', 'alumni') }}"
                   class="sidebar-link {{ request()->is('pimpinan/data/alumni') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span class="truncate">Lihat Semua Data Alumni</span>
                </a>
                @endhasanyrole
                @foreach($alumniEntities as $entity)
                <a href="{{ route('entities.view', $entity) }}"
                   class="sidebar-link {{ request()->is('entities/' . $entity->id . '*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="truncate">{{ $entity->name }}</span>
                    <span class="ml-auto text-xs bg-white/10 rounded-full px-2 py-0.5">{{ $entity->records_count ?? $entity->records()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @hasanyrole('BAAK|Pimpinan|Wakil Dekan')
        {{-- Log Aktivitas --}}
        <div class="pt-4">
            <p class="sidebar-section-title">Aktivitas</p>
        </div>

        <a href="{{ route('activities.index') }}"
           class="sidebar-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Log Aktivitas</span>
        </a>
        @endhasanyrole

        @role('BAAK')
        {{-- User Management & Approval --}}
        <div class="pt-4">
            <p class="sidebar-section-title">Administrasi</p>
        </div>

        <a href="{{ route('approvals.index') }}"
           class="sidebar-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <span>Persetujuan Kategori</span>
            @if($pendingApprovalCount > 0)
            <span class="ml-auto text-xs bg-red-500 text-white rounded-full px-2 py-0.5 font-bold animate-pulse">{{ $pendingApprovalCount }}</span>
            @endif
        </a>

        <a href="{{ route('users.index') }}"
           class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Manajemen Pengguna</span>
        </a>
        @endrole
    </nav>
</aside>
