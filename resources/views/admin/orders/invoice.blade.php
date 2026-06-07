{{-- resources/views/admin/orders/invoice.blade.php --}}
@extends('layouts.app')
@section('title', 'Buat Invoice')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.orders.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Buat Invoice</h1>
        <p class="text-slate-400 text-sm mt-0.5">Order disetujui — kirim tagihan ke client</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl space-y-4">

    {{-- Order Summary --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-xs text-slate-500 uppercase tracking-wider font-semibold mb-3">Ringkasan Order</p>
        <h3 class="font-semibold text-white text-lg">{{ $order->title }}</h3>
        <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-400">
            <span>👤 {{ $order->client->name }}</span>
            <span>📧 {{ $order->client->email }}</span>
            @if($order->service)
            <span>🛠️ {{ $order->service->name }}</span>
            @endif
            @if($order->budget)
            <span>💰 Budget: Rp {{ number_format($order->budget, 0, ',', '.') }}</span>
            @endif
        </div>
        @if($order->description)
        <p class="text-slate-400 text-sm mt-3 leading-relaxed line-clamp-2">{{ $order->description }}</p>
        @endif
    </div>

    {{-- Invoice Form --}}
    <div class="bg-surface-800 border border-brand-500/20 rounded-2xl p-6">
        <h3 class="font-semibold font-display text-white mb-5">Detail Invoice</h3>

        <form method="POST" action="{{ route('admin.orders.invoice.store', $order) }}"
              class="space-y-5">
            @csrf

            {{-- Amount --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Jumlah Tagihan (Rp) <span class="text-red-400">*</span>
                </label>
                <input type="number" name="amount"
                       value="{{ old('amount', $order->budget ?? '') }}"
                       min="1000" required
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                              @error('amount') border-red-500 @enderror"
                       placeholder="500000">
                @error('amount')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tax & Due Date --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        PPN (%) <span class="text-slate-500">opsional</span>
                    </label>
                    <input type="number" name="tax" value="{{ old('tax', 0) }}"
                           min="0" max="100"
                           class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                  rounded-xl focus:outline-none focus:border-brand-500 transition-colors"
                           placeholder="0">
                    <p class="text-xs text-slate-500 mt-1">Isi 11 untuk PPN 11%</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">
                        Jatuh Tempo <span class="text-red-400">*</span>
                    </label>
                    <input type="date" name="due_date" required
                           value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                                  rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    @error('due_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Note --}}
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Catatan <span class="text-slate-500">opsional</span>
                </label>
                <textarea name="note" rows="2"
                          class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                 rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                          placeholder="Catatan tambahan untuk client...">{{ old('note') }}</textarea>
            </div>

            {{-- Preview Total --}}
            <div class="p-4 rounded-xl bg-brand-500/10 border border-brand-500/20"
                 x-data="{
                     amount: {{ old('amount', $order->budget ?? 0) }},
                     tax: {{ old('tax', 0) }},
                     get total() { return this.amount + (this.amount * this.tax / 100) }
                 }">
                <p class="text-xs text-brand-400 font-semibold mb-2">Preview Total Tagihan</p>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between text-slate-300">
                        <span>Subtotal</span>
                        <span x-text="'Rp ' + amount.toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between text-slate-300" x-show="tax > 0">
                        <span>PPN <span x-text="tax"></span>%</span>
                        <span x-text="'Rp ' + (amount * tax / 100).toLocaleString('id-ID')"></span>
                    </div>
                    <div class="flex justify-between font-bold text-white border-t border-brand-500/20 pt-2 mt-2">
                        <span>Total</span>
                        <span class="text-brand-400 text-lg font-display"
                              x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                    </div>
                </div>

                {{-- Update preview on input change --}}
                <input type="hidden"
                       x-effect="
                           let a = parseFloat(document.querySelector('[name=amount]').value) || 0;
                           let t = parseFloat(document.querySelector('[name=tax]').value) || 0;
                           amount = a; tax = t;
                       ">
            </div>

            {{-- Info --}}
            <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-amber-500/10 border border-amber-500/20">
                <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-amber-300 text-xs leading-relaxed">
                    Invoice akan langsung dikirim ke client. Project baru akan dibuat
                    <strong>setelah client melakukan pembayaran</strong> dan dikonfirmasi oleh admin.
                </p>
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-surface-700/50">
                <button type="submit"
                        class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                               font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
                    📤 Kirim Invoice ke Client
                </button>
                <a href="{{ route('admin.orders.index') }}"
                   class="px-6 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300
                          text-sm font-medium rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Alpine live preview script --}}
@push('scripts')
<script>
    // Update preview saat user mengetik
    document.querySelector('[name=amount]').addEventListener('input', function() {
        this.dispatchEvent(new Event('change'));
    });
    document.querySelector('[name=tax]').addEventListener('input', function() {
        this.dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection