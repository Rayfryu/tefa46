{{-- resources/views/client/projects/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Hasil Project')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Hasil Project</h1>
    <p class="text-slate-400 text-sm mt-1">Project yang sudah selesai dikerjakan</p>
</div>
@endsection

@section('content')
<div class="space-y-3">
    @forelse($projects as $project)
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5
                hover:border-surface-600 transition-all duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $project->status->badgeClass() }}">
                        {{ $project->status->label() }}
                    </span>
                </div>
                <h3 class="font-semibold text-white">{{ $project->title }}</h3>
                <p class="text-xs text-slate-500 mt-1">{{ $project->order->title ?? '—' }}</p>

                @if($project->status->value === 'completed')
                <p class="text-xs text-emerald-400 mt-1">
                    ✅ {{ $project->finalDeliverables->count() }} hasil tersedia untuk didownload
                </p>
                @endif
            </div>

            @if($project->status->value === 'completed')
            <a href="{{ route('client.projects.result', $project) }}"
               class="flex-shrink-0 px-4 py-2 bg-emerald-500 hover:bg-emerald-600
                      text-white text-sm font-semibold rounded-xl transition-colors">
                📥 Ambil Hasil →
            </a>
            @else
            <span class="flex-shrink-0 px-4 py-2 bg-surface-700 text-slate-500
                         text-sm rounded-xl cursor-not-allowed">
                Belum Selesai
            </span>
            @endif
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
</div>
@endsection