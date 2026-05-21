@extends('layouts.app')
@section('title', 'Konfirmasi Pembayaran')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Konfirmasi Pembayaran</h1>
    <p class="text-slate-400 text-sm mt-1">Verifikasi bukti pembayaran dari client</p>
</div>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    @foreach([
        ['label' => 'Menunggu',    'val' => $stats['pending'],   'color' => 'text-amber-300'],
        ['label' => 'Dikonfirmasi','val' => $stats['confirmed'], 'color' => 'text-emerald-300'],
        ['label' => 'Ditolak',     'val' => $stats['rejected'],  'color' => 'text-red-300'],
    ] as $s)
    <div class="bg-surface-800 border border-surface-700/50 rounded-xl px-4 py-3 text-center">
        <p class="text-2xl font-bold font-display {{ $s['color'] }}">{{ $s['val'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Payments --}}
<div class="space-y-3">
    @forelse($payments as $payment)
    <div class="bg-surface-800 border {{ $payment->status === 'pending' ? 'border-amber-500/20' : 'border-surface-700/50' }}
                rounded-2xl p-5">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $payment->statusBadge() }}">
                        {{ $payment->statusLabel() }}
                    </span>
                    <span class="font-mono text-brand-400 text-xs">{{ $payment->invoice->invoice_number }}</span>
                </div>
                <p class="font-semibold text-white">{{ $payment->client->name }}</p>
                <div class="flex flex-wrap gap-4 mt-1.5 text-xs text-slate-500">
                    <span>💳 {{ $payment->payment_method }}</span>
                    <span>📅 {{ $payment->payment_date->format('d M Y') }}</span>
                    <span class="text-white font-semibold">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</span>
                </div>

                @if($payment->proof_file)
                <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 mt-2 text-xs text-brand-400
                          hover:text-brand-300 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Lihat Bukti Pembayaran
                </a>
                @endif
            </div>

            @if($payment->status === 'pending')
            <div class="flex items-center gap-2 flex-shrink-0">
                <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                            onclick="return confirm('Konfirmasi pembayaran ini? Invoice akan ditandai lunas.')"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white
                                   text-sm font-semibold rounded-xl transition-colors">
                        ✓ Konfirmasi
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.payments.reject', $payment) }}"
                      x-data="{ reason: '' }" @submit.prevent="
                          reason = prompt('Alasan penolakan:');
                          if(reason) { $el.querySelector('[name=reason]').value = reason; $el.submit(); }
                      ">
                    @csrf @method('PATCH')
                    <input type="hidden" name="reason" value="">
                    <button type="submit"
                            class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400
                                   border border-red-500/20 text-sm font-semibold rounded-xl transition-colors">
                        ✕ Tolak
                    </button>
                </form>
            </div>
            @elseif($payment->status === 'confirmed')
            <div class="text-right text-xs text-slate-500">
                <p>Dikonfirmasi oleh</p>
                <p class="text-white font-medium">{{ $payment->confirmedBy->name ?? '—' }}</p>
                <p>{{ $payment->confirmed_at?->format('d M Y H:i') }}</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl py-20 text-center">
        <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-slate-400 font-medium">Tidak ada pembayaran menunggu konfirmasi</p>
    </div>
    @endforelse

    @if($payments->hasPages())
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl px-6 py-4">
        {{ $payments->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection