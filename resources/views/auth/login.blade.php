<x-layouts.guest :title="'Login'">
    <div class="fade-in">
        {{-- Logo & Title --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/002-UTI.png') }}" alt="Logo Universitas Teknokrat Indonesia" class="w-40 h-auto mx-auto mb-5 object-contain drop-shadow-lg">
            <h1 class="text-2xl font-bold text-white mb-1">SILAKU</h1>
            <p class="text-primary-200 text-sm">Sistem Pelaporan IKU</p>
            <p class="text-primary-300/60 text-xs mt-1">FSIP — Universitas Teknokrat Indonesia</p>
        </div>

        {{-- Login Form --}}
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 shadow-2xl border border-white/20">
            <form method="POST" action="{{ route('login.submit') }}" id="login-form">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-primary-100 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-primary-300/50 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all"
                           placeholder="nama@teknokrat.ac.id">
                    @error('email')
                        <p class="text-red-300 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-primary-100 mb-2">Password</label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" id="password" required
                               class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-primary-300/50 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition-all pr-12"
                               placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-300 hover:text-white transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 bg-white/10 border-white/30 rounded text-primary-500 focus:ring-primary-400">
                    <label for="remember" class="ml-2 text-sm text-primary-200">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit" id="login-submit"
                        class="w-full bg-primary-500 hover:bg-primary-400 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-primary-400/40 active:scale-[0.98]">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-primary-400/50 text-xs mt-6">
            &copy; {{ date('Y') }} FSIP Universitas Teknokrat Indonesia
        </p>
    </div>
</x-layouts.guest>
