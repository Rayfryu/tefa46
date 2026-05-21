@extends('layouts.app')
@section('title', 'Detail Order')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.orders.index') }}"
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

    {{-- Status + Actions --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-sm font-medium px-3 py-1.5 rounded-full border {{ $order->status->badgeClass() }}">
                {{ $order->status->label() }}
            </span>
            @if($order->approved_at)
            <span class="text-xs text-slate-500">
                Disetujui {{ $order->approved_at->format('d M Y') }} oleh {{ $order->approvedBy->name ?? '—' }}
            </span>
            @endif
        </div>

        @if($order->status->value === 'pending')
        <div class="flex items-center gap-2" x-data="{ showReject: false }">
            {{-- Approve --}}
            <form method="POST" action="{{ route('admin.orders.approve', $order) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        onclick="return confirm('Setujui order ini?')"
                        class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl transition-colors">
                    ✓ Setujui
                </button>
            </form>

            {{-- Reject Button --}}
            <button @click="showReject = !showReject"
                    class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 text-sm font-semibold rounded-xl transition-colors">
                ✕ Tolak
            </button>

            {{-- Reject Form --}}
            <div x-show="showReject"
                 x-transition
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-surface-800 border border-surface-700 rounded-2xl p-6 w-full max-w-md shadow-2xl">
                    <h3 class="font-semibold font-display text-white mb-4">Tolak Order</h3>
                    <form method="POST" action="{{ route('admin.orders.reject', $order) }}">
                        @csrf @method('PATCH')
                        <textarea name="rejection_reason" rows="3" required
                                  class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                         rounded-xl placeholder-slate-500 focus:outline-none focus:border-red-500 transition-colors resize-none mb-4"
                                  placeholder="Jelaskan alasan penolakan order ini..."></textarea>
                        @error('rejection_reason')
                        <p class="text-red-400 text-xs mb-3">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-3">
                            <button type="submit"
                                    class="flex-1 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl transition-colors">
                                Konfirmasi Tolak
                            </button>
                            <button type="button" @click="showReject = false"
                                    class="flex-1 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300 text-sm rounded-xl transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Detail --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6 space-y-5">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <p class="text-xs text-slate-500 mb-1">Client</p>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center text-amber-400 font-bold text-sm">
                        {{ strtoupper(substr($order->client->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-white text-sm font-medium">{{ $order->client->name ?? '—' }}</p>
                        <p class="text-slate-500 text-xs">{{ $order->client->email ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Layanan</p>
                <p class="text-white text-sm font-medium">{{ $order->service->name ?? 'Tidak dipilih' }}</p>
                @if($order->service)
                <p class="text-slate-500 text-xs">{{ $order->service->division->name ?? '' }}</p>
                @endif
            </div>
        </div>

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

        @if($order->rejection_reason)
        <div class="px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20">
            <p class="text-xs text-red-400 font-semibold mb-1">Alasan Penolakan</p>
            <p class="text-red-300 text-sm">{{ $order->rejection_reason }}</p>
        </div>
        @endif

        <div class="grid grid-cols-3 gap-4 pt-4 border-t border-surface-700/50">
            <div>
                <p class="text-xs text-slate-500 mb-1">Budget</p>
                <p class="text-white font-semibold text-sm">
                    {{ $order->budget ? 'Rp ' . number_format($order->budget, 0, ',', '.') : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Deadline</p>
                <p class="text-white font-semibold text-sm">
                    {{ $order->deadline_requested ? $order->deadline_requested->format('d M Y') : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-1">Masuk</p>
                <p class="text-white font-semibold text-sm">{{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection