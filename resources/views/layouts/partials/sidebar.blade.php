{{-- resources/views/layouts/partials/sidebar.blade.php --}}

@php
    $role = auth()->user()->role->value;
    $userName = auth()->user()->name;
    $userRole = auth()->user()->role->label();

    $menus = match ($role) {
        'admin' => [
            ['label' => 'Dashboard', 'icon' => 'grid', 'route' => 'admin.dashboard'],
            ['label' => 'Pengguna', 'icon' => 'users', 'route' => 'admin.users.index'],
            ['label' => 'Divisi', 'icon' => 'layers', 'route' => 'admin.divisions.index'],
            ['label' => 'Layanan', 'icon' => 'briefcase', 'route' => 'admin.services.index'],
            ['label' => 'Order', 'icon' => 'shopping-cart', 'route' => 'admin.orders.index'],
            ['label' => 'Project', 'icon' => 'folder', 'route' => 'admin.projects.index'],
            ['label' => 'Invoice', 'icon' => 'file-text', 'route' => 'admin.invoices.index'],
            ['label' => 'Pembayaran', 'icon' => 'credit-card', 'route' => 'admin.payments.index'],
            ['label' => 'Pengumuman', 'icon' => 'bell', 'route' => 'admin.announcements.index'],
            ['label' => 'Laporan', 'icon' => 'bar-chart-2', 'route' => 'admin.reports.index'],
        ],
        'guru' => [
            ['label' => 'Dashboard', 'icon' => 'grid', 'route' => 'guru.dashboard'],
            ['label' => 'Project Saya', 'icon' => 'folder', 'route' => 'guru.projects.index'],
            ['label' => 'Review', 'icon' => 'check-square', 'route' => 'guru.reviews.index'],
            ['label' => 'Siswa', 'icon' => 'users', 'route' => 'guru.students.index'],
        ],
        'siswa' => [
            ['label' => 'Dashboard', 'icon' => 'grid', 'route' => 'siswa.dashboard'],
            ['label' => 'Project Saya', 'icon' => 'folder', 'route' => 'siswa.projects.index'],
            ['label' => 'Task Saya', 'icon' => 'check-square', 'route' => 'siswa.tasks.index'],
            ['label' => 'Portfolio', 'icon' => 'award', 'route' => 'siswa.portfolio.index'],
        ],
        'client' => [
            ['label' => 'Dashboard', 'icon' => 'grid', 'route' => 'client.dashboard'],
            ['label' => 'Buat Order', 'icon' => 'plus-circle', 'route' => 'client.orders.create'],
            ['label' => 'Order Saya', 'icon' => 'shopping-cart', 'route' => 'client.orders.index'],
            ['label' => 'Invoice', 'icon' => 'file-text', 'route' => 'client.invoices.index'],
        ],
        default => []
    };

    $roleColor = match ($role) {
        'admin' => 'text-red-400 bg-red-400/10 border-red-400/20',
        'guru' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
        'siswa' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
        'client' => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
        default => 'text-slate-400 bg-slate-400/10 border-slate-400/20',
    };
@endphp

<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-surface-950 border-r border-surface-700/50
              flex flex-col z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-surface-700/50">
        <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center shadow-lg shadow-brand-500/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <div>
            <h1 class="font-display font-bold text-white text-sm leading-tight">TEFA SMK</h1>
            <p class="text-[10px] text-slate-500 leading-tight">Teaching Factory</p>
        </div>
    </div>

    {{-- User Info --}}
    <div class="px-4 py-4 border-b border-surface-700/50">
        <div class="flex items-center gap-3 p-3 rounded-xl bg-surface-800/60">
            <div class="w-9 h-9 rounded-xl bg-brand-500/20 border border-brand-500/30
                        flex items-center justify-center text-brand-400 font-bold text-sm font-display flex-shrink-0">
                {{ strtoupper(substr($userName, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ $userName }}</p>
                <span class="text-[10px] font-medium px-2 py-0.5 rounded-full border {{ $roleColor }}">
                    {{ $userRole }}
                </span>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-1 scrollbar-thin">
        <p class="text-[10px] font-semibold text-slate-600 uppercase tracking-widest px-3 mb-3">Menu Utama</p>

        @foreach($menus as $menu)
            @php
                $isActive = Route::is($menu['route']) || (isset($menu['routes']) && Route::is($menu['routes']));
                $routeExists = Route::has($menu['route']);
            @endphp

            @if($routeExists)
                <a href="{{ route($menu['route']) }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ $isActive
                    ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/25'
                    : 'text-slate-400 hover:text-white hover:bg-surface-800' }}">

                    {{-- Icon --}}
                    <span class="w-5 h-5 flex-shrink-0">
                        @include('layouts.partials.icons', ['icon' => $menu['icon'], 'active' => $isActive])
                    </span>

                    <span>{{ $menu['label'] }}</span>

                    @if($isActive)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white/60"></span>
                    @endif
                </a>
            @else
                <span class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                                 text-slate-600 cursor-not-allowed opacity-50">
                    <span class="w-5 h-5 flex-shrink-0">
                        @include('layouts.partials.icons', ['icon' => $menu['icon'], 'active' => false])
                    </span>
                    <span>{{ $menu['label'] }}</span>
                    <span class="ml-auto text-[9px] bg-surface-700 text-slate-500 px-1.5 py-0.5 rounded">soon</span>
                </span>
            @endif
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-surface-700/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
                           text-slate-400 hover:text-red-400 hover:bg-red-400/10 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>