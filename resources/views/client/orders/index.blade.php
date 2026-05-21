@extends('layouts.app')
@section('title', 'Order Saya')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Order Saya</h1>
        <p class="text-slate-400 text-sm mt-1">Riwayat semua permintaan jasa kamu</p>
    </div>
    <a href="{{ route('client.orders.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600
              text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Order
    </a>
</div>
@endsection

@section('content')
<div class="space-y-3">
    @forelse($orders as $order)
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5
                hover:border-surface-600 transition-all duration-200">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $order->status->badgeClass() }}">
                        {{ $order->status->label() }}
                    </span>
                    @if($order->service)
                    <span class="text-xs text-slate-500">{{ $order->service->name }}</span>
                    @endif
                </div>
                <h3 class="font-semibold text-white text-base">{{ $order->title }}</h3>
                <p class="text-slate-400 text-sm mt-1 line-clamp-2">{{ $order->description }}</p>

                <div class="flex flex-wrap items-center gap-4 mt-3 text-xs text-slate-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $order->created_at->format('d M Y') }}
                    </span>
                    @if($order->budget)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Rp {{ number_format($order->budget, 0, ',', '.') }}
                    </span>
                    @endif
                    @if($order->deadline_requested)
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Deadline: {{ $order->deadline_requested->format('d M Y') }}
                    </span>
                    @endif
                </div>

                @if($order->status->value === 'rejected' && $order->rejection_reason)
                <div class="mt-3 px-3 py-2 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs">
                    <span class="font-semibold">Alasan ditolak:</span> {{ $order->rejection_reason }}
                </div>
                @endif
            </div>

            <a href="{{ route('client.orders.show', $order) }}"
               class="flex-shrink-0 px-4 py-2 bg-surface-700 hover:bg-surface-600
                      text-slate-300 text-sm rounded-xl transition-colors">
                Detail →
            </a>
        </div>
    </div>
    @empty
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl py-20 text-center">
        <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <p class="text-slate-400 font-medium">Belum ada order</p>
        <p class="text-slate-600 text-sm mt-1">Mulai buat order pertamamu!</p>
        <a href="{{ route('client.orders.create') }}"
           class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 bg-brand-500 hover:bg-brand-600
                  text-white text-sm font-semibold rounded-xl transition-colors">
            Buat Order Sekarang
        </a>
    </div>
    @endforelse

    @if($orders->hasPages())
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl px-6 py-4">
        {{ $orders->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection