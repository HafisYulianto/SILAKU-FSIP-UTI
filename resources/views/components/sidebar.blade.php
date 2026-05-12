@php
    $entities = \App\Models\DynamicEntity::active()->with('children')->rootOnly()->orderBy('root_category')->orderBy('sort_order')->get();
    $dosenEntities = $entities->where('root_category', 'dosen');
    $mahasiswaEntities = $entities->where('root_category', 'mahasiswa');
@endphp

<aside class="sidebar" :class="{ '-translate-x-full lg:translate-x-0': !mobileMenu, 'translate-x-0': mobileMenu }">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <img src="{{ asset('images/002-UTI.png') }}" alt="Logo UTI" class="w-16 h-auto object-contain drop-shadow-md">
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
        <div class="pt-4">
            <p class="sidebar-section-title">📚 Data Dosen</p>
        </div>
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
        @endif

        {{-- Mahasiswa Category --}}
        @if($mahasiswaEntities->count() > 0)
        <div class="pt-4">
            <p class="sidebar-section-title">🎓 Data Mahasiswa</p>
        </div>
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
        @endif

        @role('BAAK')
        {{-- User Management --}}
        <div class="pt-4">
            <p class="sidebar-section-title">Administrasi</p>
        </div>

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
