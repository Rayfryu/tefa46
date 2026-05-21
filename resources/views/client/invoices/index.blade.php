@extends('layouts.app')
@section('title', 'Invoice Saya')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Invoice Saya</h1>
    <p class="text-slate-400 text-sm mt-1">Tagihan dari TEFA SMK</p>
</div>
@endsection

@section('content')
<div class="space-y-3">
    @forelse($invoices as $invoice)
    <div class="bg-surface-800 border {{ $invoice->status === 'unpaid' ? 'border-red-500/20' : 'border-surface-700/50' }}
                rounded-2xl p-5 hover:border-surface-600 transition-all duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $invoice->statusBadge() }}">
                        {{ $invoice->statusLabel() }}
                    </span>
                    <span class="font-mono text-brand-400 text-xs">{{ $invoice->invoice_number }}</span>
                </div>
                <p class="font-semibold text-white">{{ $invoice->project->title ?? '—' }}</p>
                <div class="flex gap-4 mt-1.5 text-xs text-slate-500">
                    <span>Jatuh tempo:
                        <span class="{{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-400 font-medium' : '' }}">
                            {{ $invoice->due_date->format('d M Y') }}
                        </span>
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-xs text-slate-500">Total Tagihan</p>
                    <p class="font-bold font-display text-white text-lg">
                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                    </p>
                </div>
                <a href="{{ route('client.invoices.show', $invoice) }}"
                   class="px-4 py-2 bg-surface-700 hover:bg-surface-600
                          text-slate-300 text-sm rounded-xl transition-colors whitespace-nowrap">
                    {{ $invoice->status === 'unpaid' ? 'Bayar Sekarang →' : 'Detail →' }}
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl py-20 text-center">
        <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-slate-400 font-medium">Belum ada invoice</p>
    </div>
    @endforelse
</div>
@endsection