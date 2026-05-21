{{-- resources/views/siswa/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Dashboard Siswa</h1>
    <p class="text-slate-400 text-sm mt-1">Semangat kerja, {{ auth()->user()->name }} 💪</p>
</div>
@endsection
@section('content')
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-brand-300">{{ $stats['total_tasks'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Total Task</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-amber-300">{{ $stats['pending_tasks'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Task Belum Selesai</p>
    </div>
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-3xl font-bold font-display text-emerald-300">{{ $stats['done_tasks'] }}</p>
        <p class="text-slate-400 text-sm mt-1">Task Selesai</p>
    </div>
</div>
@endsection