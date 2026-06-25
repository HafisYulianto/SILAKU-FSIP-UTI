<x-layouts.guest :title="'Login'">
    <style>
        /* Custom dynamic transitions and floating shapes */
        @keyframes float-login {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .float-login-card {
            animation: float-login 6s ease-in-out infinite;
        }

        .input-premium {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .input-premium:focus {
            background: rgba(5, 150, 105, 0.25);
            border-color: #34d399;
            box-shadow: 0 0 20px rgba(52, 211, 153, 0.25), inset 0 0 8px rgba(52, 211, 153, 0.1);
            transform: translateY(-1px) scale(1.005);
        }

        /* Pulsing logo ring */
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
        .pulse-ring-element {
            animation: pulse-ring 4s ease-in-out infinite;
        }
    </style>

    <div class="relative float-login-card w-full">
        <!-- Ambient Glow Orbs directly behind the card -->
        <div class="absolute -top-10 -left-10 w-44 h-44 bg-emerald-400/25 rounded-full blur-3xl pointer-events-none pulse-ring-element"></div>
        <div class="absolute -bottom-10 -right-10 w-44 h-44 bg-teal-400/25 rounded-full blur-3xl pointer-events-none pulse-ring-element" style="animation-delay: 2s;"></div>

        <!-- Glassmorphic Login Card with organic, modern asymmetrical rounded corners -->
        <div class="relative bg-primary-950/80 backdrop-blur-xl rounded-tl-[3.5rem] rounded-br-[3.5rem] rounded-tr-[1.5rem] rounded-bl-[1.5rem] p-8 sm:p-10 shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] border border-white/15 transition-all duration-500 overflow-hidden w-full">
            <!-- Decorative corner accent -->
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-500/10 to-transparent rounded-bl-full pointer-events-none"></div>

            <!-- Brand logo & header -->
            <div class="text-center mb-8 relative z-10">
                <div class="relative inline-flex w-20 h-20 bg-white/10 border border-white/20 rounded-2xl items-center justify-center shadow-xl mb-4 group hover:scale-105 transition-transform duration-300">
                    <!-- Ring glow indicator -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-60 transition-opacity duration-300"></div>
                    <img src="{{ asset('images/Logo FSIP 1.png') }}" alt="Logo FSIP" class="h-14 w-auto object-contain drop-shadow-lg relative z-10">
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight leading-none">SILAKU</h2>
                <p class="text-xs text-emerald-300 font-bold tracking-wider uppercase mt-2.5">Sistem Pelaporan IKU</p>
                <p class="text-[10px] text-emerald-100/50 font-medium mt-1">FSIP Universitas Teknokrat Indonesia</p>
            </div>

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login.submit') }}" id="login-form" class="space-y-6 relative z-10">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-emerald-100 mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="w-full input-premium rounded-xl pl-12 pr-4 py-3.5 placeholder-emerald-100/20 focus:outline-none"
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
                    <label for="password" class="block text-sm font-semibold text-emerald-100 mb-2">Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-emerald-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input :type="show ? 'text' : 'password'" name="password" id="password" required
                               class="w-full input-premium rounded-xl pl-12 pr-12 py-3.5 placeholder-emerald-100/20 focus:outline-none"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-300 hover:text-white transition-colors p-1 rounded-md focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01-1.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-4.5 h-4.5 bg-white/5 border-white/20 rounded text-emerald-400 focus:ring-emerald-400/30 transition-all cursor-pointer">
                        <span class="ml-2 text-sm text-emerald-100/80 group-hover:text-white transition-colors">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="login-submit"
                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-500/20 hover:shadow-emerald-400/40 hover:scale-[1.02] active:scale-[0.98]">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Card footer -->
            <div class="pt-6 border-t border-white/10 mt-6 flex justify-between items-center text-xs text-emerald-300/60 relative z-10">
                <p>&copy; {{ date('Y') }} FSIP UTI</p>
                <a href="{{ url('/') }}" class="hover:text-white flex items-center gap-1 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-layouts.guest>
