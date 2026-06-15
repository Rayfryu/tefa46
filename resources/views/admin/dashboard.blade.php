{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-slate-800">Dashboard</h1>
    <p class="text-slate-400 text-sm mt-1">Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
</div>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
    $cards = [
        ['label' => 'Total Pengguna', 'value' => $stats['total_users'],    'icon' => 'users',        'color' => 'brand'],
        ['label' => 'Total Order',    'value' => $stats['total_orders'],   'icon' => 'shopping-cart','color' => 'amber'],
        ['label' => 'Order Pending',  'value' => $stats['pending_orders'], 'icon' => 'bell',         'color' => 'red'],
        ['label' => 'Project Aktif',  'value' => $stats['active_projects'],'icon' => 'folder',       'color' => 'emerald'],
    ];
    $colorMap = [
        'brand'   => ['bg'=>'bg-brand-50',   'border'=>'border-brand-100',   'icon'=>'text-brand-500',   'val'=>'text-brand-600',   'label'=>'text-brand-400'],
        'amber'   => ['bg'=>'bg-amber-50',   'border'=>'border-amber-100',   'icon'=>'text-amber-500',   'val'=>'text-amber-600',   'label'=>'text-amber-400'],
        'red'     => ['bg'=>'bg-red-50',     'border'=>'border-red-100',     'icon'=>'text-red-500',     'val'=>'text-red-600',     'label'=>'text-red-400'],
        'emerald' => ['bg'=>'bg-emerald-50', 'border'=>'border-emerald-100', 'icon'=>'text-emerald-500', 'val'=>'text-emerald-600', 'label'=>'text-emerald-400'],
    ];
    @endphp

    @foreach($cards as $i => $card)
    @php $c = $colorMap[$card['color']]; @endphp
    <div class="bg-white border border-surface-200 rounded-2xl p-5
                hover:shadow-md hover:border-surface-300 transition-all duration-200 animate-fade-in"
         style="animation-delay: {{ $i * 80 }}ms">
        <div class="flex items-start justify-between mb-4">
            <div class="p-2.5 rounded-xl {{ $c['bg'] }} border {{ $c['border'] }} {{ $c['icon'] }}">
                @include('layouts.partials.icons', ['icon' => $card['icon'], 'active' => false])
            </div>
            <span class="text-[10px] font-semibold px-2 py-1 rounded-full {{ $c['bg'] }} {{ $c['label'] }}">
                {{ now()->format('Y') }}
            </span>
        </div>
        <p class="text-3xl font-bold font-display {{ $c['val'] }}">{{ number_format($card['value']) }}</p>
        <p class="text-slate-400 text-sm mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Recent Orders + Recent Projects --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Recent Orders --}}
    <div class="bg-white border border-surface-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
            <h3 class="font-semibold font-display text-slate-800">Order Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}"
               class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
               Lihat semua →
            </a>
        </div>
        <div class="divide-y divide-surface-100">
            @forelse($recent_orders as $order)
            <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-surface-50 transition-colors">
                <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center
                            text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($order->client->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ $order->title }}</p>
                    <p class="text-xs text-slate-400">{{ $order->client->name ?? '-' }}</p>
                </div>
                @php
                $statusColor = match($order->status->value) {
                    'pending'         => 'bg-amber-50 text-amber-600 border-amber-200',
                    'approved'        => 'bg-blue-50 text-blue-600 border-blue-200',
                    'waiting_payment' => 'bg-orange-50 text-orange-600 border-orange-200',
                    'in_progress'     => 'bg-brand-50 text-brand-600 border-brand-200',
                    'done'            => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                    'rejected'        => 'bg-red-50 text-red-600 border-red-200',
                    default           => 'bg-slate-50 text-slate-600 border-slate-200',
                };
                @endphp
                <span class="text-[10px] font-semibold px-2 py-1 rounded-full border
                             {{ $statusColor }} whitespace-nowrap">
                    {{ $order->status->label() }}
                </span>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
                <p class="text-slate-400 text-sm">Belum ada order</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Projects --}}
    <div class="bg-white border border-surface-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
            <h3 class="font-semibold font-display text-slate-800">Project Terbaru</h3>
            <a href="{{ route('admin.projects.index') }}"
               class="text-xs text-brand-500 hover:text-brand-600 font-medium transition-colors">
               Lihat semua →
            </a>
        </div>
        <div class="divide-y divide-surface-100">
            @forelse($recent_projects as $project)
            <div class="px-6 py-4 hover:bg-surface-50 transition-colors">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold text-slate-800 truncate flex-1 mr-3">
                        {{ $project->title }}
                    </p>
                    <span class="text-xs font-bold
                                 {{ $project->progress >= 100 ? 'text-emerald-600' : 'text-brand-600' }}">
                        {{ $project->progress }}%
                    </span>
                </div>
                {{-- Progress Bar --}}
                <div class="w-full bg-surface-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full transition-all duration-700
                                {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }}"
                         style="width: {{ $project->progress }}%">
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-2">
                    PIC: {{ $project->picGuru->name ?? 'Belum ditentukan' }}
                </p>
            </div>
            @empty
            <div class="px-6 py-10 text-center">
                <p class="text-slate-400 text-sm">Belum ada project</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection