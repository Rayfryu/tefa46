{{-- resources/views/layouts/partials/topbar.blade.php --}}
<header class="sticky top-0 z-10 bg-white backdrop-blur-md border-b border-slate-200 px-6 py-3">
    <div class="flex items-center justify-between">

        {{-- Mobile: Hamburger + Page Title --}}
        <div class="flex items-center gap-4">
            <button onclick="toggleSidebar()"
                class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                <img src="{{ asset('images/logo_46.png') }}" alt="Menu" class="w-5 h-5 object-contain">
            </button>

            {{-- Breadcrumb / Page Title --}}
            <div class="hidden sm:block">
                <h2 class="text-sm font-semibold text-slate-900">
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
            <button
                class="relative p-2 rounded-lg text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                {{-- Badge notif --}}
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
            </button>

            {{-- Divider --}}
            <div class="w-px h-6 bg-slate-200 mx-1"></div>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-2.5 p-1.5 rounded-lg hover:bg-blue-50 transition-colors">

                    <div
                        class="w-8 h-8 rounded-full overflow-hidden border border-blue-200 bg-white flex items-center justify-center">

                        @if (auth()->user()->image)
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="Profile"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-blue-100 flex items-center justify-center
                                        text-blue-700 font-bold text-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif

                    </div>

                    <div class="hidden md:block text-left">
                        <p class="text-xs font-semibold text-slate-900 leading-tight">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] text-slate-500 leading-tight">
                            {{ auth()->user()->role->label() }}
                        </p>
                    </div>

                    <svg class="w-4 h-4 text-slate-500 hidden md:block transition-transform duration-200"
                        :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @click.outside="open = false"
                    class="absolute right-0 top-full mt-2 w-52 bg-white border border-slate-200
                           rounded-xl shadow-lg overflow-hidden z-50">

                    <div class="px-4 py-3 border-b border-slate-200">
                        <p class="text-xs font-semibold text-slate-900">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[11px] text-slate-500 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div class="py-1">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700
                                  hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profil Saya
                        </a>

                        <a href="#"
                            class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700
                                  hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.983 2.25a1 1 0 00-.966.74l-.38 1.5a7.972 7.972 0 00-1.9.783l-1.34-.8a1 1 0 00-1.3.2L5.1 5.82a1 1 0 00.2 1.3l.8 1.34a7.972 7.972 0 00-.783 1.9l-1.5.38a1 1 0 000 1.932l1.5.38a7.972 7.972 0 00.783 1.9l-.8 1.34a1 1 0 00-.2 1.3l1.03 1.03a1 1 0 001.3.2l1.34-.8c.6.35 1.24.62 1.9.783l.38 1.5a1 1 0 001.932 0l.38-1.5a7.972 7.972 0 001.9-.783l1.34.8a1 1 0 001.3-.2l1.03-1.03a1 1 0 00.2-1.3l-.8-1.34c.35-.6.62-1.24.783-1.9l1.5-.38a1 1 0 000-1.932l-1.5-.38a7.972 7.972 0 00-.783-1.9l.8-1.34a1 1 0 00-.2-1.3L18.18 5.1a1 1 0 00-1.3-.2l-1.34.8a7.972 7.972 0 00-1.9-.783l-.38-1.5a1 1 0 00-.966-.74z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15.5a3.5 3.5 0 110-7 3.5 3.5 0 010 7z" />
                            </svg>
                            Pengaturan
                        </a>
                    </div>

                    <div class="border-t border-slate-200 py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm
                                       text-red-500 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
