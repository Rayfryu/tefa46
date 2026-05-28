{{-- resources/views/siswa/dashboard.blade.php --}}
@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('header')
    <div>
        <h1 class="text-2xl font-bold font-display text-slate-900">Dashboard Siswa</h1>
        <p class="text-slate-500 text-sm mt-1">Semangat kerja, {{ auth()->user()->name }} 💪</p>
    </div>
@endsection
@section('content')
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-3xl font-bold text-slate-900">
                {{ $stats['total_tasks'] }}
            </p>
            <p class="text-slate-500 text-sm mt-1">Total Task</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-3xl font-bold text-blue-600">
                {{ $stats['pending_tasks'] }}
            </p>
            <p class="text-slate-500 text-sm mt-1">Task Belum Selesai</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-3xl font-bold text-emerald-600">
                {{ $stats['done_tasks'] }}
            </p>
            <p class="text-slate-500 text-sm mt-1">Task Selesai</p>
        </div>

    </div>
@endsection
