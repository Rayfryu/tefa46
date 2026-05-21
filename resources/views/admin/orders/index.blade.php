@extends('layouts.app')
@section('title', 'Manajemen Order')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Manajemen Order</h1>
    <p class="text-slate-400 text-sm mt-1">Review dan kelola semua order masuk</p>
</div>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @php
    $sc = [
        ['label' => 'Total Order',    'value' => $stats['all'],         'color' => 'text-slate-300'],
        ['label' => 'Menunggu',       'value' => $stats['pending'],     'color' => 'text-amber-300'],
        ['label' => 'Dikerjakan',     'value' => $stats['in_progress'], 'color' => 'text-brand-300'],
        ['label' => 'Selesai',        'value' => $stats['done'],        'color' => 'text-emerald-300'],
    ];
    @endphp
    @foreach($sc as $s)
    <div class="bg-surface-800 border border-surface-700/50 rounded-xl px-4 py-3 text-center">
        <p class="text-2xl font-bold font-display {{ $s['color'] }}">{{ $s['value'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-4 mb-4">
    <form method="GET" action="{{ route('admin.orders.index') }}"
          class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul atau nama client..."
                   class="w-full pl-9 pr-4 py-2.5 bg-surface-700 border border-surface-600
                          text-white text-sm rounded-xl placeholder-slate-500
                          focus:outline-none focus:border-brand-500 transition-colors">
        </div>
        <select name="status"
                class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                       text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            <option value="">Semua Status</option>
            <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Menunggu</option>
            <option value="approved"    {{ request('status') === 'approved'    ? 'selected' : '' }}>Disetujui</option>
            <option value="rejected"    {{ request('status') === 'rejected'    ? 'selected' : '' }}>Ditolak</option>
            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Dikerjakan</option>
            <option value="done"        {{ request('status') === 'done'        ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit"
                class="px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-colors">
            Filter
        </button>
        @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.orders.index') }}"
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
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Order</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Client</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Layanan</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-700/30">
                @forelse($orders as $order)
                <tr class="hover:bg-surface-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-medium text-white">{{ $order->title }}</p>
                        @if($order->budget)
                        <p class="text-xs text-slate-500 mt-0.5">Rp {{ number_format($order->budget, 0, ',', '.') }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 border border-amber-500/30
                                        flex items-center justify-center text-amber-400 text-xs font-bold">
                                {{ strtoupper(substr($order->client->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="text-slate-300 text-sm">{{ $order->client->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-400 text-xs">
                        {{ $order->service->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $order->status->badgeClass() }}">
                            {{ $order->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs">
                        {{ $order->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="p-2 rounded-lg text-slate-400 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"
                               title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            @if($order->status->value === 'pending')
                            <form method="POST" action="{{ route('admin.orders.approve', $order) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="p-2 rounded-lg text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10 transition-colors"
                                        title="Approve" onclick="return confirm('Setujui order ini?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-slate-500 text-sm">
                        Tidak ada order ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-surface-700/50">
        {{ $orders->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection