@extends('layouts.app')
@section('title', 'Detail Order')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('client.orders.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Detail Order</h1>
        <p class="text-slate-400 text-sm mt-0.5">{{ $order->title }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Status Banner --}}
    <div class="px-5 py-4 rounded-2xl border {{ $order->status->badgeClass() }} flex items-center gap-3">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="font-semibold text-sm">Status: {{ $order->status->label() }}</p>
            @if($order->status->value === 'rejected' && $order->rejection_reason)
            <p class="text-xs opacity-80 mt-0.5">Alasan: {{ $order->rejection_reason }}</p>
            @endif
            @if($order->approved_at)
            <p class="text-xs opacity-80 mt-0.5">Disetujui pada: {{ $order->approved_at->format('d M Y H:i') }}</p>
            @endif
        </div>
    </div>

    {{-- Detail Order --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6 space-y-5">
        <h3 class="font-semibold font-display text-white text-lg">{{ $order->title }}</h3>

        @if($order->service)
        <div class="flex items-center gap-2">
            <span class="text-xs text-slate-500">Layanan:</span>
            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-brand-500/10 text-brand-400 border border-brand-500/20">
                {{ $order->service->name }} — {{ $order->service->division->name ?? '' }}
            </span>
        </div>
        @endif

        <div>
            <p class="text-xs text-slate-500 mb-1.5">Deskripsi</p>
            <p class="text-slate-300 text-sm leading-relaxed">{{ $order->description }}</p>
        </div>

        @if($order->requirements)
        <div>
            <p class="text-xs text-slate-500 mb-1.5">Requirement</p>
            <p class="text-slate-300 text-sm leading-relaxed">{{ $order->requirements }}</p>
        </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-surface-700/50">
            <div>
                <p class="text-xs text-slate-500 mb-1">Budget</p>
                <p class="text-white font-semibold text-sm">
                    {{ $order->budget ? 'Rp ' . number_format($order->budget, 0, ',', '.') : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Deadline Diinginkan</p>
                <p class="text-white font-semibold text-sm">
                    {{ $order->deadline_requested ? $order->deadline_requested->format('d M Y') : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Tanggal Order</p>
                <p class="text-white font-semibold text-sm">{{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Project Info (kalau sudah ada) --}}
    @if($order->project)
    <div class="bg-surface-800 border border-brand-500/20 rounded-2xl p-6">
        <h3 class="font-semibold font-display text-white mb-4">Project Terkait</h3>
        <p class="text-white font-medium">{{ $order->project->title }}</p>
        <div class="mt-3">
            <div class="flex justify-between text-xs text-slate-500 mb-1">
                <span>Progress</span>
                <span>{{ $order->project->progress }}%</span>
            </div>
            <div class="w-full bg-surface-700 rounded-full h-2">
                <div class="h-2 rounded-full bg-brand-500 transition-all"
                     style="width: {{ $order->project->progress }}%"></div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection