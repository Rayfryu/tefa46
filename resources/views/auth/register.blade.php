<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — TEFA SMK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white min-h-screen">

<div class="flex min-h-screen">

    {{-- KIRI — Hero --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-slate-900">
        <img src="/images/login-bg.jpg"
             alt="TEFA SMK"
             class="absolute inset-0 w-full h-full object-cover opacity-60"
             onerror="this.style.display='none'">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-700/80 via-brand-600/60 to-slate-900/80"></div>
        <div class="relative z-10 flex flex-col justify-between p-10 w-full">
            <div class="flex items-center gap-3">
                <img src="/images/logo_46.png" alt="Logo"
                     class="w-10 h-10 object-contain"
                     onerror="this.outerHTML='<div class=\'w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center\'><svg class=\'w-6 h-6 text-white\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16\'/></svg></div>'">
                <div>
                    <p class="font-display font-bold text-white text-base leading-tight">TEFA SMK Negeri 46 Jakarta</p>
                    <p class="text-white/60 text-xs leading-tight">Teaching Factory</p>
                </div>
            </div>
            <div>
                <h1 class="font-display font-bold text-white text-4xl leading-tight mb-4">
                    Bergabung<br>
                    Bersama<br>
                    Kami.
                </h1>
                <p class="text-white/70 text-sm leading-relaxed max-w-xs">
                    Daftarkan diri kamu sebagai client untuk memesan jasa dari tim siswa TEFA SMK.
                </p>
                <div class="flex flex-wrap gap-2 mt-6">
                    @foreach(['Order Jasa Digital', 'Track Progress', 'Lihat Invoice', 'Download Hasil'] as $feat)
                    <span class="text-xs font-medium px-3 py-1.5 rounded-full bg-white/15
                                 text-white/90 border border-white/20">
                        {{ $feat }}
                    </span>
                    @endforeach
                </div>
            </div>
            <p class="text-white/40 text-xs">© {{ date('Y') }} TEFA SMK. All rights reserved.</p>
        </div>
    </div>

    {{-- KANAN — Register Form --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 xl:px-24 overflow-y-auto">

        {{-- Mobile Logo --}}
        <div class="flex items-center gap-3 mb-8 lg:hidden">
            <img src="/images/logo.png" alt="Logo" class="w-9 h-9 object-contain"
                 onerror="this.style.display='none'">
            <span class="font-display font-bold text-slate-800 text-lg">TEFA SMK</span>
        </div>

        <div class="max-w-sm w-full mx-auto lg:mx-0">

            <div class="mb-8">
                <h2 class="font-display font-bold text-slate-900 text-3xl leading-tight">
                    Buat akun baru
                </h2>
                <p class="text-slate-400 text-sm mt-2">
                    Daftar sebagai client untuk memesan layanan TEFA
                </p>
            </div>

            {{-- Errors --}}
            @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <span class="w-1 h-1 rounded-full bg-red-400 flex-shrink-0"></span>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               required autofocus autocomplete="name"
                               placeholder="John Doe"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl
                                      text-slate-800 text-sm placeholder-slate-400
                                      focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-500/10
                                      transition-all @error('name') border-red-300 bg-red-50 @enderror">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autocomplete="username"
                               placeholder="nama@email.com"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl
                                      text-slate-800 text-sm placeholder-slate-400
                                      focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-500/10
                                      transition-all @error('email') border-red-300 bg-red-50 @enderror">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Password
                    </label>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" :type="show ? 'text' : 'password'"
                               name="password" required autocomplete="new-password"
                               placeholder="Min. 8 karakter"
                               class="w-full pl-10 pr-11 py-3 bg-slate-50 border border-slate-200 rounded-xl
                                      text-slate-800 text-sm placeholder-slate-400
                                      focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-500/10
                                      transition-all @error('password') border-red-300 bg-red-50 @enderror">
                        <button type="button" @click="show = !show"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2
                                       text-slate-400 hover:text-slate-600 transition-colors">
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation"
                           class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input id="password_confirmation"
                               type="password"
                               name="password_confirmation"
                               required autocomplete="new-password"
                               placeholder="Ulangi password"
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl
                                      text-slate-800 text-sm placeholder-slate-400
                                      focus:outline-none focus:border-brand-400 focus:ring-2 focus:ring-brand-500/10
                                      transition-all">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 px-6 bg-brand-500 hover:bg-brand-600 active:bg-brand-700
                               text-white font-semibold text-sm rounded-xl
                               transition-all duration-150 shadow-md shadow-brand-500/25
                               focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                    Buat Akun Sekarang
                </button>

                {{-- Login Link --}}
                <p class="text-center text-sm text-slate-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="text-brand-500 hover:text-brand-600 font-semibold transition-colors">
                        Masuk di sini
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>