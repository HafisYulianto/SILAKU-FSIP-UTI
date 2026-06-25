<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAKU — FSIP Universitas Teknokrat Indonesia</title>
    <meta name="description" content="Sistem Pelaporan IKU - Platform digital terpadu untuk pelaporan dan manajemen data FSIP Universitas Teknokrat Indonesia.">
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --emerald-glow: rgba(16,185,129,0.15);
        }

        /* ═══ Text Gradient Shimmer ═══ */
        .text-gradient {
            background: linear-gradient(135deg, #34d399 0%, #059669 40%, #047857 70%, #10b981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 300% auto;
            animation: shimmer 4s ease-in-out infinite;
        }
        @keyframes shimmer {
            0%,100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }

        /* ═══ Floating animations ═══ */
        @keyframes float { 0%,100%{transform:translateY(0) rotate(0deg)} 33%{transform:translateY(-25px) rotate(1deg)} 66%{transform:translateY(12px) rotate(-1deg)} }
        @keyframes float-reverse { 0%,100%{transform:translateY(0) rotate(0deg)} 33%{transform:translateY(15px) rotate(-1deg)} 66%{transform:translateY(-20px) rotate(1deg)} }
        @keyframes pulse-glow { 0%,100%{opacity:.3;transform:scale(1)} 50%{opacity:.7;transform:scale(1.08)} }
        @keyframes grid-scroll { 0%{transform:translate(0,0)} 100%{transform:translate(60px,60px)} }
        @keyframes spin-slow { 0%{transform:rotate(0deg)} 100%{transform:rotate(360deg)} }
        @keyframes morph { 0%,100%{border-radius:42% 58% 70% 30% / 45% 45% 55% 55%} 34%{border-radius:70% 30% 46% 54% / 30% 29% 71% 70%} 67%{border-radius:28% 72% 44% 56% / 49% 40% 60% 51%} }
        @keyframes draw-line { 0%{stroke-dashoffset:1000} 100%{stroke-dashoffset:0} }
        @keyframes count-up { 0%{opacity:0;transform:translateY(25px)} 100%{opacity:1;transform:translateY(0)} }
        @keyframes slide-up-hero { 0%{opacity:0;transform:translateY(40px)} 100%{opacity:1;transform:translateY(0)} }
        @keyframes fade-in-scale { 0%{opacity:0;transform:scale(0.95)} 100%{opacity:1;transform:scale(1)} }

        .float-slow { animation: float 8s ease-in-out infinite; }
        .float-med { animation: float 6s ease-in-out infinite 1s; }
        .float-rev { animation: float-reverse 7s ease-in-out infinite 0.5s; }
        .glow-pulse { animation: pulse-glow 4s ease-in-out infinite; }
        .morph-blob { animation: morph 12s ease-in-out infinite; }
        .spin-slow { animation: spin-slow 30s linear infinite; }

        /* ═══ Hero grid ═══ */
        .hero-grid {
            background-image:
                linear-gradient(rgba(16,185,129,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16,185,129,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: grid-scroll 25s linear infinite;
        }

        /* ═══ Glass effects ═══ */
        .glass {
            background: rgba(255,255,255,0.65);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.8);
        }
        .glass-dark {
            background: rgba(0,0,0,0.25);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255,255,255,0.12);
        }

        /* ═══ Card interactions ═══ */
        .card-3d {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .card-3d:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 30px 60px -15px rgba(16,185,129,0.15), 0 0 0 1px rgba(16,185,129,0.08);
        }

        /* ═══ Scroll reveal ═══ */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.9s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        /* ═══ Hero animation stages ═══ */
        .hero-animate-1 { animation: slide-up-hero 0.8s cubic-bezier(0.16,1,0.3,1) 0.1s both; }
        .hero-animate-2 { animation: slide-up-hero 0.8s cubic-bezier(0.16,1,0.3,1) 0.25s both; }
        .hero-animate-3 { animation: slide-up-hero 0.8s cubic-bezier(0.16,1,0.3,1) 0.4s both; }
        .hero-animate-4 { animation: fade-in-scale 0.8s cubic-bezier(0.16,1,0.3,1) 0.55s both; }
        .hero-animate-5 { animation: slide-up-hero 0.8s cubic-bezier(0.16,1,0.3,1) 0.7s both; }

        /* ═══ Particle canvas ═══ */
        #particles-canvas { position: absolute; inset: 0; z-index: 1; pointer-events: none; }

        /* ═══ Gradient mesh footer ═══ */
        .gradient-mesh {
            background:
                radial-gradient(ellipse at 20% 50%, rgba(16,185,129,0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(6,78,59,0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(5,150,105,0.08) 0%, transparent 50%);
        }

        /* ═══ Section divider ═══ */
        .section-accent::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 6rem;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg, #34d399, #059669, #047857);
        }

        /* ═══ Misc ═══ */
        [x-cloak] { display: none !important; }

        /* Smooth nav link underline */
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #fff;
            border-radius: 999px;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 60%;
        }

        /* Counter number */
        .counter-num {
            font-variant-numeric: tabular-nums;
        }

        /* ═══ Moving Wave Divider ═══ */
        .waves {
            position: relative;
            width: 100%;
            height: 120px;
            margin-bottom: -7px; /* Fixes tiny white gaps at bottom in some browsers */
            min-height: 80px;
            max-height: 150px;
        }

        .parallax > use {
            animation: move-forever 25s cubic-bezier(.55,.5,.45,.5) infinite;
        }
        .parallax > use:nth-child(1) {
            animation-delay: -2s;
            animation-duration: 8s;
        }
        .parallax > use:nth-child(2) {
            animation-delay: -3s;
            animation-duration: 12s;
        }
        .parallax > use:nth-child(3) {
            animation-delay: -4s;
            animation-duration: 16s;
        }
        .parallax > use:nth-child(4) {
            animation-delay: -5s;
            animation-duration: 22s;
        }
        @keyframes move-forever {
            0% {
                transform: translate3d(-90px,0,0);
            }
            100% {
                transform: translate3d(85px,0,0);
            }
        }
        @media (max-width: 768px) {
            .waves {
                height: 60px;
                min-height: 60px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-white overflow-x-hidden" x-data="{ scrolled: false, mobileNav: false }" @scroll.window="scrolled = (window.pageYOffset > 30)">

    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 pointer-events-none"
         :class="scrolled ? 'py-4' : 'py-5'">
        <div class="mx-auto transition-all duration-500 pointer-events-auto"
             :class="scrolled
                ? (mobileNav ? 'max-w-4xl w-[92%] bg-primary-950/95 backdrop-blur-xl border border-white/15 rounded-3xl py-3 px-6 shadow-2xl shadow-black/40' : 'max-w-4xl w-[92%] bg-primary-950/85 backdrop-blur-xl border border-white/15 rounded-full py-2.5 px-6 shadow-2xl shadow-black/40')
                : 'max-w-7xl w-full px-4 sm:px-6 lg:px-8 bg-transparent py-2 border-b border-white/5 backdrop-blur-none'">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3 group">
                    <div class="relative">
                        <img src="{{ asset('images/002-UTI.png') }}" alt="Logo UTI"
                             :class="scrolled ? 'h-8' : 'h-12'"
                             class="w-auto object-contain drop-shadow-lg transition-all duration-500 group-hover:scale-105">
                    </div>
                    <div class="transition-all duration-500">
                        <h1 :class="scrolled ? 'text-lg' : 'text-xl'" class="font-black text-white leading-tight tracking-tight transition-all duration-500">SILAKU</h1>
                        <p :class="scrolled ? 'text-[9px]' : 'text-[11px]'" class="text-primary-200 font-medium tracking-wide transition-all duration-500">Sistem Pelaporan IKU</p>
                    </div>
                </a>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="#about" class="nav-link px-4 py-2 text-sm font-medium text-white/70 hover:text-white transition-all">{{ __('landing.nav_about') }}</a>
                    <a href="#vision" class="nav-link px-4 py-2 text-sm font-medium text-white/70 hover:text-white transition-all">{{ __('landing.nav_vision') }}</a>
                    <a href="#programs" class="nav-link px-4 py-2 text-sm font-medium text-white/70 hover:text-white transition-all">{{ __('landing.nav_programs') }}</a>
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-3">
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-white/60 hover:text-white text-sm font-medium rounded-lg hover:bg-white/10 transition-all focus:outline-none">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                            {{ strtoupper(app()->getLocale()) }}
                            <svg class="w-3 h-3 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
                             class="absolute right-0 mt-2 w-36 bg-white backdrop-blur-xl rounded-xl shadow-2xl border border-gray-100 py-1.5 z-50">
                            <a href="{{ route('lang.switch', 'id') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors rounded-lg mx-1">🇮🇩 Indonesia</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors rounded-lg mx-1">🇬🇧 English</a>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-primary-800 bg-white rounded-full hover:bg-primary-50 shadow-lg shadow-black/10 hover:shadow-xl transition-all hover:scale-105 active:scale-95">
                            {{ __('landing.nav_dashboard') }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-primary-800 bg-white rounded-full hover:bg-primary-50 shadow-lg shadow-black/10 hover:shadow-xl transition-all hover:scale-105 active:scale-95">
                            {{ __('landing.nav_login') }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    @endauth

                    <!-- Mobile menu button -->
                    <button @click="mobileNav = !mobileNav" class="md:hidden p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-all">
                        <svg x-show="!mobileNav" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileNav" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileNav" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 class="md:hidden mt-4 pb-4 border-t border-white/10 pt-4 space-y-2">
                <a href="#about" @click="mobileNav = false" class="block px-4 py-2.5 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_about') }}</a>
                <a href="#vision" @click="mobileNav = false" class="block px-4 py-2.5 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_vision') }}</a>
                <a href="#programs" @click="mobileNav = false" class="block px-4 py-2.5 text-sm font-medium text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_programs') }}</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm font-bold text-primary-200 hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_dashboard') }} →</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2.5 text-sm font-bold text-primary-200 hover:bg-white/10 rounded-lg transition-all">{{ __('landing.nav_login') }} →</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ═══════════════════════════════════════════ -->
    <!-- HERO SECTION                                -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-primary-900 via-primary-800 to-emerald-900">
        <!-- Particle canvas -->
        <canvas id="particles-canvas"></canvas>

        <!-- Animated grid -->
        <div class="absolute inset-0 hero-grid opacity-30"></div>

        <!-- Gradient orbs -->
        <div class="absolute top-10 right-[5%] w-[600px] h-[600px] bg-gradient-to-br from-white/10 to-teal-300/10 rounded-full blur-[100px] float-slow glow-pulse"></div>
        <div class="absolute bottom-10 left-[5%] w-[500px] h-[500px] bg-gradient-to-tr from-emerald-300/10 to-cyan-300/8 rounded-full blur-[80px] float-rev"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-br from-white/5 to-transparent rounded-full blur-[120px]"></div>

        <!-- Morphing decorative blob -->
        <div class="absolute top-20 left-[15%] w-48 h-48 bg-white/5 morph-blob float-med hidden lg:block"></div>

        <!-- Spinning ring decoration -->
        <div class="absolute bottom-32 right-[10%] w-64 h-64 hidden lg:block">
            <div class="w-full h-full border border-white/10 rounded-full spin-slow"></div>
            <div class="absolute inset-4 border border-dashed border-white/5 rounded-full spin-slow" style="animation-direction: reverse;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-32 pb-20 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">

                <!-- Left: Content -->
                <div class="lg:col-span-7 text-left space-y-8">
                    <!-- Title -->
                    <h1 class="hero-animate-2 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black tracking-tight text-white leading-[1.05]">
                        {{ __('landing.hero_title_1') }} <br>
                        <span class="text-gradient">{{ __('landing.hero_title_2') }}</span>
                    </h1>

                    <!-- Description -->
                    <p class="hero-animate-3 text-lg md:text-xl text-primary-100/80 leading-relaxed max-w-2xl">
                        {{ __('landing.hero_desc') }}
                    </p>

                    <!-- CTA Buttons -->
                    <div class="hero-animate-3 flex flex-col sm:flex-row gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="group px-8 py-4 text-base font-bold text-primary-800 bg-white rounded-full hover:bg-primary-50 transition-all shadow-2xl shadow-black/15 hover:shadow-black/25 hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                                {{ __('landing.hero_btn_dashboard') }}
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group px-8 py-4 text-base font-bold text-primary-800 bg-white rounded-full hover:bg-primary-50 transition-all shadow-2xl shadow-black/15 hover:shadow-black/25 hover:scale-105 active:scale-95 flex items-center justify-center gap-2">
                                {{ __('landing.hero_btn_login') }}
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        @endauth
                        <a href="#about" class="group px-8 py-4 text-base font-semibold text-white/90 bg-white/10 border border-white/20 rounded-full hover:bg-white/15 hover:border-white/30 hover:text-white transition-all flex items-center justify-center gap-2 backdrop-blur-sm">
                            {{ __('landing.hero_btn_learn') }}
                            <svg class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Right: Image showcase -->
                <div class="lg:col-span-5 relative hero-animate-4">
                    <!-- Outer glow ring -->
                    <div class="absolute -inset-6 bg-gradient-to-tr from-white/15 via-primary-300/10 to-teal-300/15 rounded-[3rem] blur-2xl glow-pulse"></div>

                    <!-- Main image container -->
                    <div class="relative bg-white/15 p-3 rounded-[2.5rem] shadow-2xl shadow-black/20 border border-white/20 backdrop-blur-sm group">
                        <div class="rounded-[2rem] overflow-hidden aspect-[4/3] relative">
                            <img src="{{ asset('images/hero-bg.jpg') }}" alt="Gedung FSIP UTI" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <!-- Dark gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>

                            <!-- Glass info tag -->
                            <div class="absolute bottom-4 left-4 right-4 glass-dark rounded-2xl p-4 flex items-center justify-between">
                                <div>
                                    <h4 class="text-sm font-bold text-white">Gedung FSIP</h4>
                                    <p class="text-xs text-primary-200">Universitas Teknokrat Indonesia</p>
                                </div>
                                <span class="text-xs bg-white text-primary-800 font-bold px-3.5 py-1.5 rounded-full shadow-lg">Teknokrat</span>
                            </div>
                        </div>

                        <!-- Floating mini badge -->
                        <div class="absolute -top-3 -right-3 w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-xl shadow-primary-900/20 rotate-12 group-hover:rotate-0 transition-transform duration-500">
                            <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave divider -->
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden pointer-events-none">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18v44h-352z" />
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0" fill="rgba(255, 255, 255, 0.7)" />
                    <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255, 255, 255, 0.5)" />
                    <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255, 255, 255, 0.3)" />
                    <use xlink:href="#gentle-wave" x="48" y="7" fill="#ffffff" />
                </g>
            </svg>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════ -->
    <!-- ABOUT / VISION & MISSION                    -->
    <!-- ═══════════════════════════════════════════ -->
    <section id="about"></section>
    <section id="vision" class="py-28 bg-white relative section-accent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section header -->
            <div class="text-center mb-20 reveal">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-[0.2em] mb-4 border border-emerald-100">{{ __('landing.vision_badge') ?? 'Visi & Misi' }}</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">{{ __('landing.vision_title') }}</h2>
            </div>

            <div class="grid lg:grid-cols-12 gap-8">
                <!-- Vision Card -->
                <div class="lg:col-span-5 flex flex-col reveal-left">
                    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-emerald-950 rounded-3xl p-10 text-white shadow-2xl shadow-gray-900/30 h-full relative overflow-hidden group">
                        <!-- Decorative glows -->
                        <div class="absolute -right-20 -top-20 w-60 h-60 bg-emerald-500 opacity-[0.07] rounded-full blur-3xl group-hover:opacity-[0.12] transition-opacity duration-700"></div>
                        <div class="absolute right-10 bottom-10 w-32 h-32 bg-emerald-400 opacity-[0.08] rounded-full blur-2xl group-hover:opacity-[0.15] transition-opacity duration-700"></div>
                        <div class="absolute left-10 bottom-20 w-20 h-20 bg-teal-400 opacity-10 rounded-full blur-xl"></div>

                        <div class="w-16 h-16 bg-white/10 rounded-2xl backdrop-blur-md flex items-center justify-center mb-8 border border-white/20 shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <h4 class="text-2xl font-bold mb-6">{{ __('landing.vision_heading') }}</h4>
                        <p class="text-emerald-100/90 leading-relaxed text-lg italic border-l-3 border-emerald-400/40 pl-6" style="border-left-width: 3px;">{{ __('landing.vision_text') }}</p>
                    </div>
                </div>

                <!-- Mission Card -->
                <div class="lg:col-span-7 reveal-right">
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-10 border border-gray-100 h-full shadow-xl shadow-gray-100/50">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ __('landing.mission_heading') }}</h4>
                        </div>
                        <ul class="space-y-5">
                            @foreach(['mission_1','mission_2','mission_3','mission_4','mission_5'] as $i => $key)
                            <li class="flex gap-4 group">
                                <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-emerald-500/20 group-hover:scale-110 group-hover:rotate-6 transition-all">{{ $i+1 }}</span>
                                <p class="text-gray-600 leading-relaxed pt-1.5">{{ __('landing.'.$key) }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════ -->
    <!-- FACULTY GOALS                               -->
    <!-- ═══════════════════════════════════════════ -->
    <section class="py-28 bg-gradient-to-b from-gray-50 to-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-100/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-teal-100/20 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20 reveal">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-[0.2em] mb-4 border border-emerald-100">{{ __('landing.goals_badge') }}</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">{{ __('landing.goals_title') }}</h2>
            </div>

            @php
                $goalIcons = [
                    'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                    'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                    'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
                    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z'
                ];
                $goalColors = [
                    ['from-blue-500','to-cyan-500','shadow-blue-500/20','bg-blue-50','text-blue-600','border-blue-100'],
                    ['from-violet-500','to-purple-500','shadow-violet-500/20','bg-violet-50','text-violet-600','border-violet-100'],
                    ['from-emerald-500','to-teal-500','shadow-emerald-500/20','bg-emerald-50','text-emerald-600','border-emerald-100'],
                    ['from-amber-500','to-orange-500','shadow-amber-500/20','bg-amber-50','text-amber-600','border-amber-100'],
                    ['from-rose-500','to-pink-500','shadow-rose-500/20','bg-rose-50','text-rose-600','border-rose-100'],
                ];
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i = 0; $i < 5; $i++)
                <div class="card-3d bg-white rounded-2xl p-8 border border-gray-100 shadow-lg shadow-gray-100/50 reveal {{ $i >= 3 ? ($i == 3 ? 'md:col-span-1' : 'md:col-span-2 lg:col-span-2') : '' }}"
                     style="transition-delay: {{ $i * 80 }}ms">
                    <div class="flex items-start gap-5">
                        <div class="w-14 h-14 bg-gradient-to-br {{ $goalColors[$i][0] }} {{ $goalColors[$i][1] }} rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg {{ $goalColors[$i][2] }}">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $goalIcons[$i] }}"/></svg>
                        </div>
                        <div>
                            <span class="inline-block text-xs font-bold {{ $goalColors[$i][4] }} {{ $goalColors[$i][3] }} px-2.5 py-1 rounded-lg mb-3 border {{ $goalColors[$i][5] }}">Tujuan {{ $i+1 }}</span>
                            <p class="text-gray-700 leading-relaxed">{{ __('landing.goal_'.($i+1)) }}</p>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════ -->
    <!-- PROGRAMS SECTION                            -->
    <!-- ═══════════════════════════════════════════ -->
    <section id="programs" class="py-28 bg-white relative section-accent overflow-hidden">
        <div class="absolute bottom-0 right-0 w-80 h-80 bg-emerald-50/50 rounded-full blur-3xl translate-y-1/2 translate-x-1/4"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20 reveal">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-[0.2em] mb-4 border border-emerald-100">{{ __('landing.programs_badge') }}</span>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">{{ __('landing.programs_title') }}</h2>
            </div>

            @php $progs = [
                ['key'=>'s1ss','from'=>'from-blue-500','to'=>'to-cyan-500','bg'=>'bg-blue-50','text'=>'text-blue-600','border'=>'border-blue-200','shadow'=>'shadow-blue-100/50','icon'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['key'=>'s1pbi','from'=>'from-emerald-500','to'=>'to-teal-500','bg'=>'bg-emerald-50','text'=>'text-emerald-600','border'=>'border-emerald-200','shadow'=>'shadow-emerald-100/50','icon'=>'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
                ['key'=>'s1po','from'=>'from-orange-500','to'=>'to-amber-500','bg'=>'bg-orange-50','text'=>'text-orange-600','border'=>'border-orange-200','shadow'=>'shadow-orange-100/50','icon'=>'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664zM21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['key'=>'s1pma','from'=>'from-indigo-500','to'=>'to-violet-500','bg'=>'bg-indigo-50','text'=>'text-indigo-600','border'=>'border-indigo-200','shadow'=>'shadow-indigo-100/50','icon'=>'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['key'=>'s2bi','from'=>'from-violet-500','to'=>'to-purple-500','bg'=>'bg-violet-50','text'=>'text-violet-600','border'=>'border-violet-200','shadow'=>'shadow-violet-100/50','icon'=>'M19 14l-7 7m0 0l-7-7m7 7V3'],
            ]; @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                @foreach($progs as $idx => $p)
                <div class="group bg-white border border-gray-100 rounded-3xl p-8 hover:border-transparent hover:shadow-2xl {{ $p['shadow'] }} transition-all duration-500 hover:-translate-y-3 relative overflow-hidden card-3d reveal"
                     style="transition-delay: {{ $idx * 100 }}ms">
                    <!-- Hover gradient bg -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $p['from'] }}/5 {{ $p['to'] }}/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-3xl"></div>

                    <!-- Icon -->
                    <div class="w-16 h-16 {{ $p['bg'] }} rounded-2xl flex items-center justify-center mb-6 group-hover:bg-gradient-to-br group-hover:{{ $p['from'] }} group-hover:{{ $p['to'] }} transition-all duration-500 group-hover:shadow-xl group-hover:scale-110 relative z-10">
                        <svg class="w-7 h-7 {{ $p['text'] }} group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $p['icon'] }}"/></svg>
                    </div>

                    <!-- Text -->
                    <h4 class="text-lg font-bold text-gray-900 mb-2 relative z-10">{{ __('landing.prog_'.$p['key'].'_title') }}</h4>
                    <p class="text-gray-500 text-sm relative z-10 leading-relaxed">{{ __('landing.prog_'.$p['key'].'_desc') }}</p>

                    <!-- Arrow indicator -->
                    <div class="mt-5 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 relative z-10">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold {{ $p['text'] }}">
                            Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════ -->
    <!-- FOOTER                                      -->
    <!-- ═══════════════════════════════════════════ -->
    <footer class="relative bg-gray-950 pt-20 pb-8 text-white overflow-hidden">
        <!-- Top gradient line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>

        <!-- Gradient mesh bg -->
        <div class="absolute inset-0 gradient-mesh"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center mb-12">
                <!-- Left: Brand -->
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 bg-white/8 backdrop-blur rounded-2xl p-2.5 border border-white/10 flex items-center justify-center">
                        <img src="{{ asset('images/002-UTI.png') }}" alt="Logo UTI" class="h-full w-auto object-contain">
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-tight">SILAKU</h2>
                        <p class="text-emerald-400/80 text-sm font-medium">{{ __('landing.footer_faculty') }}</p>
                        <p class="text-gray-500 text-xs mt-1">Universitas Teknokrat Indonesia</p>
                    </div>
                </div>

                <!-- Right: Links & Contact -->
                <div class="flex flex-wrap gap-8 md:justify-end">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.15em] mb-3">Navigasi</h3>
                        <ul class="space-y-2">
                            <li><a href="#about" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">{{ __('landing.nav_about') }}</a></li>
                            <li><a href="#vision" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">{{ __('landing.nav_vision') }}</a></li>
                            <li><a href="#programs" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">{{ __('landing.nav_programs') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[0.15em] mb-3">Akses</h3>
                        <ul class="space-y-2">
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-emerald-400 transition-colors">Login</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Bottom bar -->
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Universitas Teknokrat Indonesia. {{ __('landing.footer_rights') }}</p>
                <p class="text-gray-600 text-xs">SILAKU v1.0 — Sistem Pelaporan IKU FSIP</p>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════ -->
    <!-- SCRIPTS                                     -->
    <!-- ═══════════════════════════════════════════ -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {

        /* ─── Scroll Reveal Observer ─── */
        const revealClasses = ['.reveal', '.reveal-left', '.reveal-right', '.reveal-scale'];
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

        revealClasses.forEach(cls => {
            document.querySelectorAll(cls).forEach(el => observer.observe(el));
        });

        /* ─── Particle System ─── */
        const canvas = document.getElementById('particles-canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let particles = [];
        const PARTICLE_COUNT = 60;

        function resize() {
            canvas.width = canvas.offsetWidth;
            canvas.height = canvas.offsetHeight;
        }
        resize();
        window.addEventListener('resize', resize);

        class Particle {
            constructor() { this.reset(); }
            reset() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2 + 0.5;
                this.speedX = (Math.random() - 0.5) * 0.4;
                this.speedY = (Math.random() - 0.5) * 0.4;
                this.opacity = Math.random() * 0.4 + 0.1;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x < 0 || this.x > canvas.width) this.speedX *= -1;
                if (this.y < 0 || this.y > canvas.height) this.speedY *= -1;
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
                ctx.fill();
            }
        }

        for (let i = 0; i < PARTICLE_COUNT; i++) {
            particles.push(new Particle());
        }

        function drawLines() {
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 120) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(255, 255, 255, ${0.06 * (1 - dist / 120)})`;
                        ctx.lineWidth = 0.5;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => { p.update(); p.draw(); });
            drawLines();
            requestAnimationFrame(animate);
        }
        animate();

        /* ─── Smooth anchor scroll ─── */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
    </script>

</body>
</html>
