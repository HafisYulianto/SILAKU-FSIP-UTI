<header class="topbar">
    <div class="flex items-center gap-4">
        {{-- Mobile menu button --}}
        <button @click="mobileMenu = !mobileMenu" class="lg:hidden btn-icon" id="mobile-menu-toggle">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Breadcrumb / Page title --}}
        <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h2>
        </div>
    </div>

    <div class="flex items-center gap-3">
        {{-- Role Badge --}}
        @php $roleName = auth()->user()->roles->first()?->name ?? 'User'; @endphp
        <span class="badge-primary hidden sm:inline-flex">{{ $roleName }}</span>

        {{-- User dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors" id="user-menu-button">
                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-white">{{ auth()->user()->initials }}</span>
                </div>
                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->nip)
                    <p class="text-xs text-gray-400 mt-1">{{ auth()->user()->identifier_label }}: {{ auth()->user()->nip }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center gap-2" id="logout-button">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
