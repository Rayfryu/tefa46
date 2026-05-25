{{-- resources/views/guru/projects/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Project')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('guru.projects.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-xl font-bold font-display text-white">{{ $project->title }}</h1>
        <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $project->status->badgeClass() }}">
            {{ $project->status->label() }}
        </span>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-4">

    {{-- Progress --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <div class="flex justify-between mb-2">
            <div>
                <p class="text-sm font-semibold text-white">Progress Keseluruhan</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $project->tasks->where('status', 'done')->count() }} /
                    {{ $project->tasks->count() }} task selesai
                </p>
            </div>
            <span class="text-3xl font-bold font-display
                         {{ $project->progress >= 100 ? 'text-emerald-400' : 'text-brand-400' }}">
                {{ $project->progress }}%
            </span>
        </div>
        <div class="w-full bg-surface-700 rounded-full h-2.5">
            <div class="h-2.5 rounded-full transition-all duration-700
                        {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }}"
                 style="width: {{ $project->progress }}%"></div>
        </div>
        @if($project->end_date)
        <p class="text-xs text-slate-500 mt-3">Deadline: {{ $project->end_date->format('d M Y') }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Task List --}}
        <div class="lg:col-span-2 bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-700/50 flex items-center justify-between">
                <h3 class="font-semibold font-display text-white">Semua Task</h3>
                <span class="text-xs text-slate-500">{{ $project->tasks->count() }} task</span>
            </div>
            <div class="divide-y divide-surface-700/30">
                @forelse($project->tasks as $task)
                <div class="px-5 py-4 hover:bg-surface-700/30 transition-colors">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $task->status->badgeClass() }}">
                                    {{ $task->status->label() }}
                                </span>
                                @if($task->latestProgress && !$task->latestProgress->review)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    Butuh Review
                                </span>
                                @endif
                            </div>
                            <p class="text-sm font-medium text-white">{{ $task->title }}</p>
                            <div class="flex gap-3 mt-1 text-xs text-slate-500">
                                <span>{{ $task->assignedTo->name ?? 'Belum di-assign' }}</span>
                                @if($task->deadline)
                                <span class="{{ $task->deadline->isPast() && $task->status->value !== 'done' ? 'text-red-400' : '' }}">
                                    ⏰ {{ $task->deadline->format('d M Y') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-5 py-10 text-center text-slate-500 text-sm">
                    Belum ada task di project ini
                </div>
                @endforelse
            </div>
        </div>

        {{-- Anggota --}}
        <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
            <h3 class="font-semibold font-display text-white mb-4">
                Anggota Tim
                <span class="text-slate-500 font-normal text-sm">({{ $project->members->count() }})</span>
            </h3>
            <div class="space-y-3">
                @forelse($project->members as $member)
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 border border-emerald-500/30
                                flex items-center justify-center text-emerald-400 font-bold text-xs">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                        @if($member->pivot->role_in_project)
                        <p class="text-xs text-slate-500">{{ $member->pivot->role_in_project }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-slate-500 text-sm text-center py-4">Belum ada anggota</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection