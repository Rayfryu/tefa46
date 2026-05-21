{{-- resources/views/guru/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Dashboard Guru</h1>
    <p class="text-slate-400 text-sm mt-1">Halo, {{ auth()->user()->name }} 👋</p>
</div>
@endsection
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-brand-300">{{ $stats['total_projects'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Total Project</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-emerald-300">{{ $stats['active_projects'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Project Aktif</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-amber-300">{{ $stats['pending_reviews'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Menunggu Review</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-slate-300">{{ $stats['completed_projects'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Selesai</p>
    </div>
</div>
@endsection