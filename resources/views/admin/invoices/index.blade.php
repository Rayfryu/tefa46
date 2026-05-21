@extends('layouts.app')
@section('title', 'Manajemen Invoice')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Manajemen Invoice</h1>
    <p class="text-slate-400 text-sm mt-1">Kelola semua invoice dan pembayaran</p>
</div>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label' => 'Total',              'val' => $stats['total'],                'color' => 'text-slate-300'],
        ['label' => 'Belum Dibayar',      'val' => $stats['unpaid'],               'color' => 'text-red-300'],
        ['label' => 'Menunggu Konfirmasi','val' => $stats['pending_confirmation'],  'color' => 'text-amber-300'],
        ['label' => 'Lunas',              'val' => $stats['paid'],                 'color' => 'text-emerald-300'],
    ] as $s)
    <div class="bg-surface-800 border border-surface-700/50 rounded-xl px-4 py-3 text-center">
        <p class="text-2xl font-bold font-display {{ $s['color'] }}">{{ $s['val'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-4 mb-4">
    <form method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nomor invoice atau nama client..."
                   class="w-full pl-9 pr-4 py-2.5 bg-surface-700 border border-surface-600
                          text-white text-sm rounded-xl placeholder-slate-500
                          focus:outline-none focus:border-brand-500 transition-colors">
        </div>
        <select name="status"
                class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                       text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            <option value="">Semua Status</option>
            <option value="unpaid"               {{ request('status')==='unpaid'               ?'selected':'' }}>Belum Dibayar</option>
            <option value="pending_confirmation" {{ request('status')==='pending_confirmation' ?'selected':'' }}>Menunggu Konfirmasi</option>
            <option value="paid"                 {{ request('status')==='paid'                 ?'selected':'' }}>Lunas</option>
            <option value="cancelled"            {{ request('status')==='cancelled'            ?'selected':'' }}>Dibatalkan</option>
        </select>
        <button type="submit"
                class="px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.invoices.index') }}"
           class="px-4 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300 text-sm font-medium rounded-xl transition-colors">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-700/50">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Invoice</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Project</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-700/30">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-surface-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-mono text-brand-400 text-sm font-medium">{{ $invoice->invoice_number }}</p>
                        <p class="text-xs text-slate-500">{{ $invoice->created_at->format('d M Y') }}</p>
                    </td>
                    <td class="px-6 py-4 text-slate-300 text-sm">
                        {{ $invoice->order->client->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-slate-300 text-sm">
                        {{ $invoice->project->title ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-semibold text-white">Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                        @if($invoice->tax > 0)
                        <p class="text-xs text-slate-500">+ PPN {{ $invoice->tax }}%</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm {{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'text-red-400 font-semibold' : 'text-slate-300' }}">
                            {{ $invoice->due_date->format('d M Y') }}
                        </p>
                        @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                        <p class="text-xs text-red-400">Jatuh tempo!</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $invoice->statusBadge() }}">
                            {{ $invoice->statusLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.invoices.show', $invoice) }}"
                           class="p-2 inline-flex rounded-lg text-slate-400 hover:text-brand-400
                                  hover:bg-brand-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center text-slate-500 text-sm">
                        Belum ada invoice
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-surface-700/50">
        {{ $invoices->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection