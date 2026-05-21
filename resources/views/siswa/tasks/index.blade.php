@extends('layouts.app')
@section('title', 'Task Saya')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Task Saya</h1>
    <p class="text-slate-400 text-sm mt-1">Daftar semua task yang di-assign ke kamu</p>
</div>
@endsection

@section('content')
<div class="space-y-3">
    @forelse($tasks as $task)
    @php
    $priorityColor = match($task->priority) {
        'high'   => 'text-red-400 bg-red-500/10 border-red-500/20',
        'medium' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
        'low'    => 'text-slate-400 bg-slate-500/10 border-slate-500/20',
    };
    @endphp
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5
                hover:border-surface-600 transition-all duration-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $task->status->badgeClass() }}">
                        {{ $task->status->label() }}
                    </span>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $priorityColor }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
                <h3 class="font-semibold text-white">{{ $task->title }}</h3>
                <div class="flex gap-4 mt-1.5 text-xs text-slate-500">
                    <span>📁 {{ $task->project->title }}</span>
                    @if($task->deadline)
                    <span class="{{ $task->deadline->isPast() && $task->status->value !== 'done' ? 'text-red-400 font-medium' : '' }}">
                        ⏰ {{ $task->deadline->format('d M Y') }}
                    </span>
                    @endif
                </div>
                @if($task->status->value === 'revision' && $task->latestProgress?->review)
                <div class="mt-2 px-3 py-2 rounded-lg bg-orange-500/10 border border-orange-500/20 text-orange-300 text-xs">
                    📝 Catatan guru: {{ $task->latestProgress->review->note ?? 'Silakan revisi.' }}
                </div>
                @endif
            </div>
            <a href="{{ route('siswa.tasks.show', $task) }}"
               class="flex-shrink-0 px-4 py-2 bg-surface-700 hover:bg-surface-600
                      text-slate-300 text-sm rounded-xl transition-colors">
                Buka →
            </a>
        </div>
    </div>
    @empty
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl py-20 text-center">
        <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="text-slate-400 font-medium">Belum ada task</p>
        <p class="text-slate-600 text-sm mt-1">Tunggu assignment dari guru atau admin</p>
    </div>
    @endforelse

    @if($tasks->hasPages())
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl px-6 py-4">
        {{ $tasks->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection