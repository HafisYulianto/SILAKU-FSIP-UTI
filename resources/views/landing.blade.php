<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAKU — FSIP Universitas Teknokrat Indonesia</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .text-gradient { background: linear-gradient(135deg, #34d399 0%, #059669 50%, #047857 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-size: 200% auto; animation: shimmer 3s ease-in-out infinite; }
        @keyframes shimmer { 0%,100%{background-position:0% center} 50%{background-position:100% center} }
        @keyframes float { 0%,100%{transform:translateY(0) rotate(0deg)} 33%{transform:translateY(-20px) rotate(1deg)} 66%{transform:translateY(10px) rotate(-1deg)} }
        @keyframes pulse-glow { 0%,100%{opacity:.4;transform:scale(1)} 50%{opacity:.8;transform:scale(1.05)} }
        @keyframes grid-move { 0%{transform:translate(0,0)} 100%{transform:translate(40px,40px)} }
        @keyframes count-fade { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .float-slow { animation: float 8s ease-in-out infinite; }
        .float-med { animation: float 6s ease-in-out infinite 1s; }
        .float-fast { animation: float 5s ease-in-out infinite 2s; }
        .glow-pulse { animation: pulse-glow 4s ease-in-out infinite; }
        .card-hover { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-hover:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 25px 50px -12px rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.4); }
        .hero-grid { background-image: linear-gradient(rgba(16,185,129,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,0.06) 1px, transparent 1px); background-size: 60px 60px; animation: grid-move 20s linear infinite; }
        .glass-stat { background: rgba(255,255,255,0.7); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.8); }
        [x-cloak] { display: none !important; }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Navigation -->
    <nav :class="{ 'bg-primary-900/95 shadow-2xl shadow-primary-900/20 py-2': scrolled, 'bg-primary-800 py-4': !scrolled }" class="fixed w-full z-50 transition-all duration-500 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/002-UTI.png') }}" alt="Logo UTI" :class="scrolled ? 'h-12' : 'h-16'" class="w-auto object-contain drop-shadow-lg transition-all duration-500">
                    <div>
                        <h1 class="text-xl font-bold text-white leading-tight tracking-tight">SILAKU</h1>
                        <p class="text-xs text-primary-200 font-medium">Sistem Pelaporan IKU</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#about" class="px-4 py-2 text-sm font-medium text-primary-100 hover:text-white hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_about') }}</a>
                    <a href="#vision" class="px-4 py-2 text-sm font-medium text-primary-100 hover:text-white hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_vision') }}</a>
                    <a href="#programs" class="px-4 py-2 text-sm font-medium text-primary-100 hover:text-white hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_programs') }}</a>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-1 px-3 py-1.5 text-primary-100 hover:text-white text-sm font-medium rounded-lg hover:bg-white/10 transition-all focus:outline-none">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                            {{ strtoupper(app()->getLocale()) }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-2xl border border-gray-100 py-1.5 z-50">
                            <a href="{{ route('lang.switch', 'id') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">🇮🇩 Indonesia</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors">🇬🇧 English</a>
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-primary-800 bg-white rounded-full hover:bg-primary-50 shadow-lg shadow-black/10 hover:shadow-xl transition-all hover:scale-105">
                            {{ __('landing.nav_dashboard') }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-primary-800 bg-white rounded-full hover:bg-primary-50 shadow-lg shadow-black/10 hover:shadow-xl transition-all hover:scale-105">
                            {{ __('landing.nav_login') }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-cover bg-center" style="background-image: url('{{ asset('images/hero-bg.jpg') }}')">
        <!-- Overlay mask to ensure content readability -->
        <div class="absolute inset-0 bg-gradient-to-br from-white/75 via-white/50 to-primary-50/35"></div>
        <!-- Animated grid background -->
        <div class="absolute inset-0 hero-grid opacity-30"></div>
        <!-- Floating gradient orbs -->
        <div class="absolute top-20 right-[10%] w-[500px] h-[500px] bg-gradient-to-br from-primary-400/20 to-teal-300/20 rounded-full blur-3xl float-slow glow-pulse"></div>
        <div class="absolute bottom-20 left-[5%] w-[400px] h-[400px] bg-gradient-to-tr from-emerald-400/15 to-cyan-300/15 rounded-full blur-3xl float-med"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-r from-primary-300/10 to-teal-200/10 rounded-full blur-3xl float-fast"></div>
        <!-- Geometric decorations -->
        <div class="absolute top-32 left-[15%] w-3 h-3 bg-primary-400 rounded-full float-fast opacity-40"></div>
        <div class="absolute top-48 right-[20%] w-2 h-2 bg-teal-400 rounded-full float-slow opacity-50"></div>
        <div class="absolute bottom-40 left-[25%] w-4 h-4 border-2 border-primary-300 rounded-full float-med opacity-30"></div>
        <div class="absolute top-60 right-[35%] w-6 h-6 border border-emerald-300 rotate-45 float-slow opacity-20"></div>
        <div class="absolute bottom-32 right-[15%] w-3 h-3 bg-emerald-400 rounded-full float-fast opacity-30"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-24 pb-12">
            <div class="text-center max-w-5xl mx-auto">
                <div class="inline-flex items-center px-5 py-2.5 rounded-full bg-white/80 backdrop-blur-sm border border-primary-100 text-primary-700 text-sm font-semibold mb-10 shadow-lg shadow-primary-500/5 fade-in">
                    <span class="relative flex h-2.5 w-2.5 mr-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                    </span>
                    {{ __('landing.hero_badge') }}
                </div>
                <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black tracking-tight text-gray-900 mb-8 fade-in leading-[0.9]" style="animation-delay: 100ms;">
                    {{ __('landing.hero_title_1') }} <br>
                    <span class="text-gradient">{{ __('landing.hero_title_2') }}</span>
                </h1>
                <p class="mt-8 text-lg md:text-xl text-gray-500 leading-relaxed mb-12 fade-in max-w-2xl mx-auto" style="animation-delay: 200ms;">
                    {{ __('landing.hero_desc') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 fade-in" style="animation-delay: 300ms;">
                    @auth
                        <a href="{{ route('dashboard') }}" class="group px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-primary-600 to-emerald-600 rounded-full hover:from-primary-700 hover:to-emerald-700 transition-all shadow-2xl shadow-primary-600/30 hover:shadow-primary-600/50 hover:scale-105 flex items-center justify-center gap-2">
                            {{ __('landing.hero_btn_dashboard') }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-primary-600 to-emerald-600 rounded-full hover:from-primary-700 hover:to-emerald-700 transition-all shadow-2xl shadow-primary-600/30 hover:shadow-primary-600/50 hover:scale-105 flex items-center justify-center gap-2">
                            {{ __('landing.hero_btn_login') }}
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth
                    <a href="#about" class="px-8 py-4 text-base font-semibold text-gray-600 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full hover:bg-white hover:border-primary-300 hover:text-primary-700 transition-all shadow-lg shadow-gray-200/50">
                        {{ __('landing.hero_btn_learn') }}
                    </a>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-20 max-w-3xl mx-auto fade-in" style="animation-delay: 500ms;">
                <div class="glass-stat rounded-2xl p-6 shadow-2xl shadow-gray-200/50">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 divide-x divide-gray-200/50">
                        <div class="text-center px-4">
                            <p class="text-3xl font-black text-primary-600">5</p>
                            <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Program Studi</p>
                        </div>
                        <div class="text-center px-4">
                            <p class="text-3xl font-black text-primary-600">2035</p>
                            <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Target Visi</p>
                        </div>
                        <div class="text-center px-4">
                            <p class="text-3xl font-black text-primary-600">OBE</p>
                            <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Kurikulum</p>
                        </div>
                        <div class="text-center px-4">
                            <p class="text-3xl font-black text-primary-600">A</p>
                            <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wider">Akreditasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 120L48 108C96 96 192 72 288 66C384 60 480 72 576 78C672 84 768 84 864 78C960 72 1056 60 1152 60C1248 60 1344 72 1392 78L1440 84V120H1392C1344 120 1248 120 1152 120C1056 120 960 120 864 120C768 120 672 120 576 120C480 120 384 120 288 120C192 120 96 120 48 120H0Z" fill="white"/></svg>
        </div>
    </section>

    <!-- Vision & Mission Section -->
    <section id="vision" class="py-28 bg-white relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-primary-400 to-emerald-400 rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-sm font-bold text-primary-600 uppercase tracking-[0.2em] mb-3">{{ __('landing.vision_badge') }}</h2>
                <h3 class="text-4xl font-black text-gray-900">{{ __('landing.vision_title') }}</h3>
            </div>
            <div class="grid lg:grid-cols-12 gap-8">
                <div class="lg:col-span-5 flex flex-col">
                    <div class="bg-gradient-to-br from-primary-900 via-primary-800 to-emerald-900 rounded-3xl p-10 text-white shadow-2xl shadow-primary-900/30 h-full relative overflow-hidden group">
                        <div class="absolute -right-20 -top-20 w-60 h-60 bg-white opacity-5 rounded-full blur-3xl group-hover:opacity-10 transition-opacity duration-700"></div>
                        <div class="absolute right-10 bottom-10 w-32 h-32 bg-primary-400 opacity-10 rounded-full blur-2xl group-hover:opacity-20 transition-opacity duration-700"></div>
                        <div class="absolute left-10 bottom-20 w-20 h-20 bg-emerald-400 opacity-10 rounded-full blur-xl"></div>
                        <div class="w-16 h-16 bg-white/10 rounded-2xl backdrop-blur-md flex items-center justify-center mb-8 border border-white/20 shadow-lg">
                            <svg class="w-8 h-8 text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <h4 class="text-2xl font-bold mb-6">{{ __('landing.vision_heading') }}</h4>
                        <p class="text-primary-100 leading-relaxed text-lg italic border-l-2 border-primary-400/40 pl-5">"{{ __('landing.vision_text') }}"</p>
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-10 border border-gray-100 h-full shadow-xl shadow-gray-100/50">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ __('landing.mission_heading') }}</h4>
                        </div>
                        <ul class="space-y-5">
                            @foreach(['mission_1','mission_2','mission_3','mission_4','mission_5'] as $i => $key)
                            <li class="flex gap-4 group">
                                <span class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-primary-500 to-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-primary-500/20 group-hover:scale-110 transition-transform">{{ $i+1 }}</span>
                                <p class="text-gray-600 leading-relaxed pt-1">{{ __('landing.'.$key) }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Faculty Goals -->
    <section class="py-28 bg-gradient-to-b from-slate-50 to-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-sm font-bold text-primary-600 uppercase tracking-[0.2em] mb-3">{{ __('landing.goals_badge') }}</h2>
                <h3 class="text-4xl font-black text-gray-900">{{ __('landing.goals_title') }}</h3>
            </div>
            @php $goalIcons = ['M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z','M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z','M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9','M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z']; $goalColors = ['from-blue-500 to-cyan-500','from-violet-500 to-purple-500','from-emerald-500 to-teal-500','from-amber-500 to-orange-500','from-rose-500 to-pink-500']; @endphp
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i = 0; $i < 5; $i++)
                <div class="group bg-white rounded-2xl p-8 border border-gray-100 shadow-lg shadow-gray-100/50 card-hover {{ $i >= 3 ? ($i == 3 ? 'md:col-span-1' : 'md:col-span-2 lg:col-span-2') : '' }}">
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $goalColors[$i] }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $goalIcons[$i] }}"/></svg>
                        </div>
                        <p class="text-gray-700 leading-relaxed font-medium">{{ __('landing.goal_'.($i+1)) }}</p>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="programs" class="py-28 bg-white relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-primary-400 to-emerald-400 rounded-full"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-sm font-bold text-primary-600 uppercase tracking-[0.2em] mb-3">{{ __('landing.programs_badge') }}</h2>
                <h3 class="text-4xl font-black text-gray-900">{{ __('landing.programs_title') }}</h3>
            </div>
            @php $progs = [
                ['key'=>'s1ss','color'=>'blue','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['key'=>'s1pbi','color'=>'emerald','icon'=>'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
                ['key'=>'s1po','color'=>'orange','icon'=>'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664zM21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['key'=>'s1pma','color'=>'indigo','icon'=>'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['key'=>'s2bi','color'=>'violet','icon'=>'M19 14l-7 7m0 0l-7-7m7 7V3'],
            ]; @endphp
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                @foreach($progs as $p)
                <div class="group border border-gray-100 rounded-3xl p-8 hover:border-{{ $p['color'] }}-300 hover:shadow-2xl hover:shadow-{{ $p['color'] }}-100/50 transition-all duration-500 hover:-translate-y-2 bg-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $p['color'] }}-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="w-16 h-16 bg-{{ $p['color'] }}-50 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-gradient-to-br group-hover:from-{{ $p['color'] }}-500 group-hover:to-{{ $p['color'] }}-600 transition-all duration-500 group-hover:shadow-xl group-hover:shadow-{{ $p['color'] }}-500/30 relative z-10 group-hover:scale-110">
                        <svg class="w-7 h-7 text-{{ $p['color'] }}-600 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p['icon'] }}"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 relative z-10 group-hover:text-{{ $p['color'] }}-900">{{ __('landing.prog_'.$p['key'].'_title') }}</h4>
                    <p class="text-gray-500 text-sm relative z-10">{{ __('landing.prog_'.$p['key'].'_desc') }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative bg-gradient-to-b from-gray-900 to-gray-950 pt-16 pb-8 text-white overflow-hidden">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-emerald-500 to-teal-500"></div>
        <div class="absolute top-20 right-10 w-64 h-64 bg-primary-600/5 rounded-full blur-3xl"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/002-UTI.png') }}" alt="Logo UTI" class="h-16 w-auto object-contain bg-white/10 backdrop-blur rounded-2xl p-2 border border-white/10">
                    <div>
                        <h2 class="text-xl font-bold text-white">SILAKU</h2>
                        <p class="text-gray-400 text-sm">{{ __('landing.footer_faculty') }}</p>
                    </div>
                </div>
                <div class="text-gray-400 text-sm text-center md:text-right space-y-1">
                    <p>&copy; {{ date('Y') }} Universitas Teknokrat Indonesia.</p>
                    <p class="text-gray-500">{{ __('landing.footer_rights') }}</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll reveal script -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.card-hover').forEach(el => {
            el.classList.add('reveal');
        });
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if(e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }});
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
    });
    </script>

</body>
</html>

