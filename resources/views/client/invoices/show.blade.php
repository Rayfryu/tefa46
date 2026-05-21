@extends('layouts.app')
@section('title', 'Detail Invoice')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('client.invoices.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">{{ $invoice->invoice_number }}</h1>
        <p class="text-slate-400 text-sm mt-0.5">Detail Invoice & Pembayaran</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Invoice Detail --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">

        <div class="px-6 py-5 border-b border-surface-700/50 flex items-start justify-between">
            <div>
                <p class="font-mono text-brand-400 font-medium">{{ $invoice->invoice_number }}</p>
                <p class="text-xs text-slate-500 mt-1">Diterbitkan: {{ $invoice->created_at->format('d M Y') }}</p>
                <p class="text-xs {{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-400' : 'text-slate-500' }}">
                    Jatuh tempo: {{ $invoice->due_date->format('d M Y') }}
                </p>
            </div>
            <span class="text-sm font-medium px-3 py-1.5 rounded-full border {{ $invoice->statusBadge() }}">
                {{ $invoice->statusLabel() }}
            </span>
        </div>

        <div class="px-6 py-5 border-b border-surface-700/50">
            <p class="text-xs text-slate-500 mb-1">Project</p>
            <p class="font-semibold text-white">{{ $invoice->project->title ?? '—' }}</p>
            <p class="text-slate-400 text-sm mt-0.5">{{ $invoice->order->title ?? '—' }}</p>
        </div>

        {{-- Total --}}
        <div class="px-6 py-5 border-b border-surface-700/50">
            <div class="flex justify-between items-center mb-2">
                <span class="text-slate-400 text-sm">Subtotal</span>
                <span class="text-white">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
            </div>
            @if($invoice->tax > 0)
            <div class="flex justify-between items-center mb-2">
                <span class="text-slate-400 text-sm">PPN {{ $invoice->tax }}%</span>
                <span class="text-white">Rp {{ number_format($invoice->total - $invoice->amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center pt-3 border-t border-surface-700/50">
                <span class="font-bold text-white">Total</span>
                <span class="text-2xl font-bold font-display text-brand-400">
                    Rp {{ number_format($invoice->total, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Upload Bukti Bayar --}}
    @if($invoice->status === 'unpaid')
    <div class="bg-surface-800 border border-brand-500/20 rounded-2xl p-5">
        <h3 class="font-semibold font-display text-white mb-4">💳 Upload Bukti Pembayaran</h3>

        <div class="mb-4 p-4 rounded-xl bg-brand-500/10 border border-brand-500/20 text-xs text-brand-300 space-y-1">
            <p class="font-semibold text-brand-400">Rekening Pembayaran:</p>
            <p>🏦 Bank BCA — 1234567890 — TEFA SMK</p>
            <p>🏦 Bank Mandiri — 0987654321 — TEFA SMK</p>
            <p>💚 GoPay/DANA — 081234567890</p>
        </div>

        <form method="POST" action="{{ route('client.invoices.pay', $invoice) }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        Metode Pembayaran <span class="text-red-400">*</span>
                    </label>
                    <select name="payment_method" required
                            class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                                   text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="">Pilih metode...</option>
                        <option value="Transfer BCA">Transfer BCA</option>
                        <option value="Transfer Mandiri">Transfer Mandiri</option>
                        <option value="GoPay">GoPay</option>
                        <option value="DANA">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="Tunai">Tunai</option>
                    </select>
                    @error('payment_method')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        Tanggal Bayar <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="payment_date"
                           value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}" required
                           class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                                  text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    @error('payment_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Bukti Transfer <span class="text-red-400">*</span>
                    <span class="text-slate-500 font-normal">(JPG, PNG, PDF — max 5MB)</span>
                </label>
                <input type="file" name="proof_file" required
                       accept=".jpg,.jpeg,.png,.pdf"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                              rounded-xl focus:outline-none focus:border-brand-500 transition-colors
                              file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                              file:bg-brand-500/20 file:text-brand-400 file:text-xs file:font-medium">
                @error('proof_file')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full py-3 bg-brand-500 hover:bg-brand-600 text-white font-semibold
                           rounded-xl transition-colors shadow-lg shadow-brand-500/25">
                📤 Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
    @endif

    {{-- Status Pembayaran --}}
    @if($invoice->status === 'pending_confirmation')
    <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5 text-center">
        <div class="w-12 h-12 rounded-full bg-amber-500/20 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="font-semibold text-amber-400">Pembayaran Sedang Dikonfirmasi</p>
        <p class="text-amber-300/70 text-sm mt-1">Admin sedang memverifikasi bukti pembayaran kamu.</p>
        <p class="text-amber-300/70 text-sm">Proses konfirmasi biasanya 1x24 jam.</p>
    </div>
    @endif

    @if($invoice->status === 'paid')
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-5 text-center">
        <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <p class="font-semibold text-emerald-400">Pembayaran Lunas!</p>
        <p class="text-emerald-300/70 text-sm mt-1">Terima kasih telah menggunakan layanan TEFA SMK.</p>
    </div>
    @endif

</div>
@endsection