{{-- resources/views/siswa/projects/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Project')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('siswa.projects.index') }}"
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
            <p class="text-sm font-semibold text-white">Progress Project</p>
            <span class="text-brand-400 font-bold font-display">{{ $project->progress }}%</span>
        </div>
        <div class="w-full bg-surface-700 rounded-full h-2.5">
            <div class="h-2.5 rounded-full bg-brand-500 transition-all duration-700"
                 style="width: {{ $project->progress }}%"></div>
        </div>
        <div class="flex gap-4 mt-3 text-xs text-slate-500">
            <span>PIC: {{ $project->picGuru->name ?? '—' }}</span>
            @if($project->end_date)
            <span>Deadline: {{ $project->end_date->format('d M Y') }}</span>
            @endif
        </div>
    </div>

    {{-- Task Saya di Project Ini --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-700/50">
            <h3 class="font-semibold font-display text-white">Task Saya di Project Ini</h3>
        </div>
        <div class="divide-y divide-surface-700/30">
            @forelse($project->tasks as $task)
            <div class="px-5 py-4 flex items-center justify-between hover:bg-surface-700/30 transition-colors">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $task->status->badgeClass() }}">
                            {{ $task->status->label() }}
                        </span>
                    </div>
                    <p class="text-sm font-medium text-white">{{ $task->title }}</p>
                    @if($task->deadline)
                    <p class="text-xs text-slate-500 mt-0.5">⏰ {{ $task->deadline->format('d M Y') }}</p>
                    @endif
                </div>
                <a href="{{ route('siswa.tasks.show', $task) }}"
                   class="px-3 py-1.5 bg-surface-700 hover:bg-surface-600 text-slate-300 text-xs rounded-lg transition-colors">
                    Buka →
                </a>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-slate-500 text-sm">
                Belum ada task yang di-assign ke kamu di project ini
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection