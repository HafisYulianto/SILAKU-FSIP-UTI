<x-layouts.guest :title="'Login'">
    <div class="max-w-5xl w-full mx-auto bg-primary-900/40 backdrop-blur-xl rounded-[2rem] overflow-hidden shadow-2xl border border-white/10 grid md:grid-cols-12 min-h-[600px] transition-all duration-500">
        
        <!-- Left: Form section -->
        <div class="md:col-span-7 p-8 sm:p-12 md:p-16 flex flex-col justify-between">
            <div>
                <!-- Brand logo & header -->
                <div class="flex items-center gap-3 mb-10">
                    <img src="{{ asset('images/002-UTI.png') }}" alt="Logo UTI" class="h-10 w-auto object-contain">
                    <div>
                        <h2 class="text-xl font-black text-white tracking-tight leading-none">SILAKU</h2>
                        <p class="text-[10px] text-primary-200 font-medium tracking-wide">FSIP Universitas Teknokrat Indonesia</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-white mb-2">Selamat Datang Kembali</h3>
                    <p class="text-sm text-primary-200/80">Silakan masuk menggunakan akun administrasi Anda.</p>
                </div>

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login.submit') }}" id="login-form" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-primary-100 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </span>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                                   class="w-full bg-white/5 hover:bg-white/10 focus:bg-primary-950/40 border border-white/10 focus:border-primary-400 rounded-xl pl-12 pr-4 py-3 text-white placeholder-primary-300/30 focus:outline-none focus:ring-2 focus:ring-primary-400/20 transition-all duration-300"
                                   placeholder="nama@teknokrat.ac.id">
                        </div>
                        @error('email')
                            <p class="text-rose-300 text-xs mt-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-primary-100 mb-2">Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                   class="w-full bg-white/5 hover:bg-white/10 focus:bg-primary-950/40 border border-white/10 focus:border-primary-400 rounded-xl pl-12 pr-12 py-3 text-white placeholder-primary-300/30 focus:outline-none focus:ring-2 focus:ring-primary-400/20 transition-all duration-300"
                                   placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-300 hover:text-white transition-colors p-1 rounded-md focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" id="remember"
                                   class="w-4.5 h-4.5 bg-white/10 border-white/20 rounded text-emerald-500 focus:ring-emerald-400/30 transition-all cursor-pointer">
                            <span class="ml-2 text-sm text-primary-200/90 group-hover:text-white transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="login-submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-400 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-400/40 active:scale-[0.98] mt-2">
                        Masuk ke Sistem
                    </button>
                </form>
            </div>

            <!-- Left section footer -->
            <div class="pt-10 border-t border-white/5 mt-8 flex justify-between items-center text-xs text-primary-400/60">
                <p>&copy; {{ date('Y') }} FSIP UTI</p>
                <a href="{{ url('/') }}" class="hover:text-white flex items-center gap-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Right: Photo/Visual section -->
        <div class="hidden md:col-span-5 relative overflow-hidden group">
            <!-- Background Image -->
            <img src="{{ asset('images/hero-bg.jpg') }}" alt="Gedung FSIP UTI" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[10s] group-hover:scale-105">
            <!-- Overlay gradients -->
            <div class="absolute inset-0 bg-gradient-to-t from-primary-950/90 via-primary-900/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-primary-950/50 to-transparent"></div>

            <!-- Content overlay -->
            <div class="absolute inset-0 p-12 flex flex-col justify-end text-white z-10">
                <span class="inline-flex w-fit items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-bold uppercase tracking-wider mb-5 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    SILAKU — FSIP
                </span>
                <h3 class="text-3xl font-black tracking-tight leading-tight mb-3">
                    Fakultas Sastra dan Ilmu Pendidikan
                </h3>
                <p class="text-sm text-primary-200 leading-relaxed font-light">
                    Mewujudkan fakultas yang unggul, berintegritas, dan inovatif melalui transformasi digital terpadu.
                </p>
                <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-primary-300">
                    <span>Universitas Teknokrat Indonesia</span>
                    <span class="font-bold">ASEAN\'s Best Private University</span>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
