{{-- resources/views/client/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Client')
@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Dashboard Client</h1>
    <p class="text-slate-400 text-sm mt-1">Selamat datang, {{ auth()->user()->name }} 🙌</p>
</div>
@endsection
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-brand-300">{{ $stats['total_orders'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Total Order</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-amber-300">{{ $stats['pending_orders'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Menunggu</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-blue-300">{{ $stats['active_orders'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Sedang Dikerjakan</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-red-300">{{ $stats['unpaid_invoices'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Invoice Belum Bayar</p>
    </div>
</div>
@endsection