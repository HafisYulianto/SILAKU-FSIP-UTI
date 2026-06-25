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
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title ?? 'Dashboard' }}</h2>
        </div>
    </div>

    <div class="flex items-center gap-3">
        {{-- Role Badge --}}
        @php $roleName = auth()->user()->roles->first()?->name ?? 'User'; @endphp
        <span class="badge-primary hidden sm:inline-flex">{{ $roleName }}</span>

        {{-- Theme Toggle --}}
        <button @click="darkMode = !darkMode" class="btn-icon" aria-label="Toggle Dark Mode" id="theme-toggle">
            {{-- Sun Icon --}}
            <svg x-show="!darkMode" class="w-5 h-5 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9h-1m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            {{-- Moon Icon --}}
            <svg x-show="darkMode" class="w-5 h-5 text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        {{-- Notification Dropdown --}}
        @php
            $recentActivities = \App\Models\ActivityLog::orderBy('created_at', 'desc')->take(5)->get();
        @endphp
        <div class="relative" x-data="{ 
            open: false, 
            unreadCount: {{ min(3, $recentActivities->count()) }}, 
            markAllRead() { 
                this.unreadCount = 0; 
            } 
        }">
            <button @click="open = !open" class="btn-icon relative" id="notification-button" aria-label="Notifications">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-1.5 right-1.5 bg-red-500 text-white text-[9px] font-bold rounded-full w-3.5 h-3.5 flex items-center justify-center animate-bounce"></span>
            </button>

            <div x-show="open" @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 py-2 z-50 overflow-hidden"
                 style="display: none;">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi Aktivitas</span>
                    <button @click="markAllRead()" class="text-xs text-primary-600 dark:text-primary-400 hover:underline">Tandai dibaca</button>
                </div>
                
                <div class="divide-y divide-gray-100 dark:divide-gray-800 max-h-64 overflow-y-auto">
                    @forelse($recentActivities as $activity)
                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-850/50 transition-colors">
                        <div class="flex items-start gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-950/40 text-primary-600 dark:text-primary-400 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                {{ substr($activity->actor_name ?? 'U', 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-700 dark:text-gray-300 leading-normal">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $activity->actor_name }}</span>
                                    ({{ $activity->actor_role }}):
                                    {{ $activity->description }}
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-4 py-8 text-center text-gray-400 dark:text-gray-500 text-xs">
                        Belum ada notifikasi aktivitas baru
                    </div>
                    @endforelse
                </div>
                
                @hasanyrole('BAAK|Pimpinan|Wakil Dekan')
                <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-950/30 text-center">
                    <a href="{{ route('activities.index') }}" class="text-xs text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 font-medium">Lihat Semua Aktivitas</a>
                </div>
                @endhasanyrole
            </div>
        </div>

        {{-- User dropdown --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" id="user-menu-button">
                <div class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-white">{{ auth()->user()->initials }}</span>
                </div>
                <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->name }}</span>
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
                 class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 py-2 z-50">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->nip)
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ auth()->user()->identifier_label }}: {{ auth()->user()->nip }}</p>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors flex items-center gap-2" id="logout-button">
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
