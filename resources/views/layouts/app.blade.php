<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — @yield('title', 'Dashboard')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main Wrapper --}}
    <div class="lg:ml-64 min-h-screen flex flex-col transition-all duration-300 bg-slate-50"
         id="main-content">

        {{-- Topbar --}}
        @include('layouts.partials.topbar')

        {{-- Page Content --}}
        <main class="flex-1 p-6 lg:p-8">

            {{-- Page Header --}}
            @hasSection('header')
            <div class="mb-6">
                @yield('header')
            </div>
            @endif

            {{-- Flash Messages --}}
            @include('layouts.partials.flash')

            {{-- Content --}}
            <div>
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="px-8 py-4 border-t border-slate-200 text-center text-xs text-slate-500 bg-white">
            © {{ date('Y') }}
            <span class="text-blue-600 font-semibold">TEFA SMK</span>
            — Teaching Factory Digital
        </footer>
    </div>

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-20 hidden lg:hidden"
         onclick="closeSidebar()">
    </div>

    @stack('scripts')
</body>
</html>