@extends('layouts.app')
@section('title', 'Detail Invoice')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.invoices.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">{{ $invoice->invoice_number }}</h1>
        <p class="text-slate-400 text-sm mt-0.5">Detail Invoice</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Invoice Card --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">

        {{-- Header Invoice --}}
        <div class="px-6 py-5 border-b border-surface-700/50 flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold font-display text-white text-lg">INVOICE</h2>
                        <p class="font-mono text-brand-400 text-sm">{{ $invoice->invoice_number }}</p>
                    </div>
                </div>
                <p class="text-xs text-slate-500 mt-2">Diterbitkan: {{ $invoice->created_at->format('d M Y') }}</p>
                <p class="text-xs text-slate-500">Jatuh tempo:
                    <span class="{{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-400 font-medium' : '' }}">
                        {{ $invoice->due_date->format('d M Y') }}
                    </span>
                </p>
            </div>
            <span class="text-sm font-medium px-3 py-1.5 rounded-full border {{ $invoice->statusBadge() }}">
                {{ $invoice->statusLabel() }}
            </span>
        </div>

        {{-- Client & Project Info --}}
        <div class="grid grid-cols-2 gap-6 px-6 py-5 border-b border-surface-700/50">
            <div>
                <p class="text-xs text-slate-500 mb-2 font-semibold uppercase tracking-wider">Tagihan Kepada</p>
                <p class="text-white font-semibold">{{ $invoice->order->client->name ?? '—' }}</p>
                <p class="text-slate-400 text-sm">{{ $invoice->order->client->email ?? '—' }}</p>
                <p class="text-slate-400 text-sm">{{ $invoice->order->client->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 mb-2 font-semibold uppercase tracking-wider">Detail Project</p>
                <p class="text-white font-semibold">{{ $invoice->project->title ?? '—' }}</p>
                <p class="text-slate-400 text-sm">{{ $invoice->order->title ?? '—' }}</p>
                <p class="text-slate-400 text-sm">Diterbitkan oleh: {{ $invoice->issuedBy->name ?? '—' }}</p>
            </div>
        </div>

        {{-- Amount Breakdown --}}
        <div class="px-6 py-5 border-b border-surface-700/50">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-700/50">
                        <th class="text-left py-2 text-xs text-slate-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="text-right py-2 text-xs text-slate-500 uppercase tracking-wider">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-3 text-slate-300">Jasa {{ $invoice->project->title ?? 'Project' }}</td>
                        <td class="py-3 text-right text-white font-medium">
                            Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($invoice->tax > 0)
                    <tr>
                        <td class="py-2 text-slate-400">PPN {{ $invoice->tax }}%</td>
                        <td class="py-2 text-right text-slate-400">
                            Rp {{ number_format($invoice->total - $invoice->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="border-t border-surface-700/50">
                        <td class="pt-4 font-bold text-white">TOTAL</td>
                        <td class="pt-4 text-right text-2xl font-bold font-display text-brand-400">
                            Rp {{ number_format($invoice->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Payment History --}}
        @if($invoice->payments->isNotEmpty())
        <div class="px-6 py-5">
            <h3 class="font-semibold text-white mb-4">Riwayat Pembayaran</h3>
            <div class="space-y-3">
                @foreach($invoice->payments as $payment)
                <div class="flex items-center justify-between p-4 rounded-xl bg-surface-700/50">
                    <div>
                        <p class="text-sm font-medium text-white">{{ $payment->client->name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $payment->payment_method }} •
                            {{ $payment->payment_date->format('d M Y') }}
                        </p>
                        @if($payment->proof_file)
                        <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank"
                           class="text-xs text-brand-400 hover:text-brand-300 transition-colors mt-1 inline-block">
                            📎 Lihat Bukti Bayar
                        </a>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-white">Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}</p>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $payment->statusBadge() }}">
                            {{ $payment->statusLabel() }}
                        </span>

                        {{-- Confirm / Reject buttons --}}
                        @if($payment->status === 'pending')
                        <div class="flex gap-2 mt-2 justify-end">
                            <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        onclick="return confirm('Konfirmasi pembayaran ini?')"
                                        class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white
                                               text-xs font-semibold rounded-lg transition-colors">
                                    ✓ Konfirmasi
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="reason" value="Bukti pembayaran tidak valid.">
                                <button type="submit"
                                        onclick="return confirm('Tolak pembayaran ini?')"
                                        class="px-3 py-1 bg-red-500/10 hover:bg-red-500/20 text-red-400
                                               border border-red-500/20 text-xs font-semibold rounded-lg transition-colors">
                                    ✕ Tolak
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection