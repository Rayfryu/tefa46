{{-- resources/views/layouts/partials/topbar.blade.php --}}
<header class="sticky top-0 z-10 bg-surface-900/80 backdrop-blur-md border-b border-surface-700/50 px-6 py-3">
    <div class="flex items-center justify-between">

        {{-- Mobile: Hamburger + Page Title --}}
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb / Page Title --}}
            <div class="hidden sm:block">
                <h2 class="text-sm font-semibold text-white font-display">
                    @yield('title', 'Dashboard')
                </h2>
                <p class="text-xs text-slate-500">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        {{-- Right Side --}}
        <div class="flex items-center gap-2">

            {{-- Notification Bell --}}
            <button class="relative p-2 rounded-lg text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                {{-- Badge notif --}}
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-brand-500 animate-pulse-slow"></span>
            </button>

            {{-- Divider --}}
            <div class="w-px h-6 bg-surface-700 mx-1"></div>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-surface-800 transition-colors">
                    <div class="w-8 h-8 rounded-lg bg-brand-500/20 border border-brand-500/30
                                flex items-center justify-center text-brand-400 font-bold text-sm font-display">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="hidden md:block text-left">
                        <p class="text-xs font-semibold text-white leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500 leading-tight">{{ auth()->user()->role->label() }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-500 hidden md:block transition-transform duration-200"
                         :class="{ 'rotate-180': open }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="open = false"
                     class="absolute right-0 top-full mt-2 w-52 bg-surface-800 border border-surface-700
                            rounded-xl shadow-xl shadow-black/40 overflow-hidden z-50">

                    <div class="px-4 py-3 border-b border-surface-700">
                        <p class="text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300
                                  hover:bg-surface-700 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <a href="#"
                           class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-300
                                  hover:bg-surface-700 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Pengaturan
                        </a>
                    </div>

                    <div class="border-t border-surface-700 py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm
                                           text-red-400 hover:bg-red-400/10 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>