{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Dashboard</h1>
    <p class="text-slate-400 text-sm mt-1">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
</div>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
    $cards = [
        ['label' => 'Total Pengguna',    'value' => $stats['total_users'],    'icon' => 'users',        'color' => 'brand'],
        ['label' => 'Total Order',       'value' => $stats['total_orders'],   'icon' => 'shopping-cart','color' => 'amber'],
        ['label' => 'Order Pending',     'value' => $stats['pending_orders'], 'icon' => 'bell',         'color' => 'red'],
        ['label' => 'Project Aktif',     'value' => $stats['active_projects'],'icon' => 'folder',       'color' => 'emerald'],
    ];
    $colorMap = [
        'brand'   => ['bg' => 'bg-brand-500/10',   'border' => 'border-brand-500/20',   'text' => 'text-brand-400',   'val' => 'text-brand-300'],
        'amber'   => ['bg' => 'bg-amber-500/10',   'border' => 'border-amber-500/20',   'text' => 'text-amber-400',   'val' => 'text-amber-300'],
        'red'     => ['bg' => 'bg-red-500/10',     'border' => 'border-red-500/20',     'text' => 'text-red-400',     'val' => 'text-red-300'],
        'emerald' => ['bg' => 'bg-emerald-500/10', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-400', 'val' => 'text-emerald-300'],
    ];
    @endphp

    @foreach($cards as $i => $card)
    @php $c = $colorMap[$card['color']]; @endphp
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5
                hover:border-surface-600 transition-all duration-200 animate-fade-in"
         style="animation-delay: {{ $i * 80 }}ms">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 rounded-xl {{ $c['bg'] }} border {{ $c['border'] }}">
                @include('layouts.partials.icons', ['icon' => $card['icon'], 'active' => false])
            </div>
        </div>
        <p class="text-3xl font-bold font-display {{ $c['val'] }}">{{ number_format($card['value']) }}</p>
        <p class="text-slate-400 text-sm mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Recent Orders + Recent Projects --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Orders --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-surface-700/50 flex items-center justify-between">
            <h3 class="font-semibold font-display text-white">Order Terbaru</h3>
            <a href="#" class="text-xs text-brand-400 hover:text-brand-300 transition-colors">Lihat semua →</a>
        </div>
        <div class="divide-y divide-surface-700/30">
            @forelse($recent_orders as $order)
            <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-surface-700/30 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-brand-500/20 border border-brand-500/30
                            flex items-center justify-center text-brand-400 text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($order->client->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $order->title }}</p>
                    <p class="text-xs text-slate-500">{{ $order->client->name ?? '-' }}</p>
                </div>
                @php
                $statusColor = match($order->status->value) {
                    'pending'     => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    'approved'    => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                    'in_progress' => 'bg-brand-500/10 text-brand-400 border-brand-500/20',
                    'done'        => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'rejected'    => 'bg-red-500/10 text-red-400 border-red-500/20',
                    default       => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                };
                @endphp
                <span class="text-[10px] font-medium px-2 py-1 rounded-full border {{ $statusColor }} whitespace-nowrap">
                    {{ $order->status->label() }}
                </span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada order</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Projects --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-surface-700/50 flex items-center justify-between">
            <h3 class="font-semibold font-display text-white">Project Terbaru</h3>
            <a href="#" class="text-xs text-brand-400 hover:text-brand-300 transition-colors">Lihat semua →</a>
        </div>
        <div class="divide-y divide-surface-700/30">
            @forelse($recent_projects as $project)
            <div class="px-6 py-3.5 hover:bg-surface-700/30 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-medium text-white truncate flex-1 mr-2">{{ $project->title }}</p>
                    <span class="text-[10px] text-slate-500 whitespace-nowrap">{{ $project->progress }}%</span>
                </div>
                {{-- Progress Bar --}}
                <div class="w-full bg-surface-700 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full transition-all duration-500
                                {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }}"
                         style="width: {{ $project->progress }}%">
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-1.5">PIC: {{ $project->picGuru->name ?? 'Belum ditentukan' }}</p>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada project</div>
            @endforelse
        </div>
    </div>

</div>

@endsection