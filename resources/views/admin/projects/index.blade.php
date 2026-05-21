@extends('layouts.app')
@section('title', 'Manajemen Project')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Manajemen Project</h1>
        <p class="text-slate-400 text-sm mt-1">Kelola semua project TEFA</p>
    </div>
    <a href="{{ route('admin.projects.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600
              text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Project
    </a>
</div>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label'=>'Total',     'val'=>$stats['total'],     'color'=>'text-slate-300'],
        ['label'=>'Aktif',     'val'=>$stats['active'],    'color'=>'text-brand-300'],
        ['label'=>'Revisi',    'val'=>$stats['revision'],  'color'=>'text-orange-300'],
        ['label'=>'Selesai',   'val'=>$stats['completed'], 'color'=>'text-emerald-300'],
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
                   placeholder="Cari nama project..."
                   class="w-full pl-9 pr-4 py-2.5 bg-surface-700 border border-surface-600 text-white
                          text-sm rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors">
        </div>
        <select name="status"
                class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                       text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            <option value="">Semua Status</option>
            <option value="active"    {{ request('status')==='active'    ?'selected':'' }}>Aktif</option>
            <option value="revision"  {{ request('status')==='revision'  ?'selected':'' }}>Revisi</option>
            <option value="completed" {{ request('status')==='completed' ?'selected':'' }}>Selesai</option>
            <option value="cancelled" {{ request('status')==='cancelled' ?'selected':'' }}>Dibatalkan</option>
        </select>
        <button type="submit"
                class="px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-colors">
            Filter
        </button>
    </form>
</div>

{{-- Project Cards --}}
<div class="space-y-3">
    @forelse($projects as $project)
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5
                hover:border-surface-600 transition-all duration-200">
        <div class="flex flex-col lg:flex-row lg:items-center gap-4">

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $project->status->badgeClass() }}">
                        {{ $project->status->label() }}
                    </span>
                    @if($project->division)
                    <span class="text-xs text-slate-500">{{ $project->division->name }}</span>
                    @endif
                </div>
                <h3 class="font-semibold text-white text-base">{{ $project->title }}</h3>
                <div class="flex flex-wrap gap-4 mt-2 text-xs text-slate-500">
                    <span>PIC: {{ $project->picGuru->name ?? '—' }}</span>
                    <span>{{ $project->members->count() }} Siswa</span>
                    <span>{{ $project->tasks->count() }} Task</span>
                    @if($project->end_date)
                    <span>Deadline: {{ $project->end_date->format('d M Y') }}</span>
                    @endif
                </div>
            </div>

            {{-- Progress --}}
            <div class="w-full lg:w-48">
                <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                    <span>Progress</span>
                    <span class="font-semibold {{ $project->progress >= 100 ? 'text-emerald-400' : 'text-white' }}">
                        {{ $project->progress }}%
                    </span>
                </div>
                <div class="w-full bg-surface-700 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500
                                {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }}"
                         style="width: {{ $project->progress }}%"></div>
                </div>
            </div>

            {{-- Action --}}
            <a href="{{ route('admin.projects.show', $project) }}"
               class="flex-shrink-0 px-4 py-2 bg-surface-700 hover:bg-surface-600
                      text-slate-300 text-sm rounded-xl transition-colors">
                Kelola →
            </a>
        </div>
    </div>
    @empty
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl py-20 text-center">
        <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
        </svg>
        <p class="text-slate-400 font-medium">Belum ada project</p>
    </div>
    @endforelse

    @if($projects->hasPages())
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl px-6 py-4">
        {{ $projects->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection