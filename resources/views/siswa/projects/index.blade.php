@extends('layouts.app')
@section('title', 'Project Saya')

@section('header')
<div>
    <h1 class="text-2xl font-bold text-slate-900">Project Saya</h1>
    <p class="text-slate-500 text-sm mt-1">Semua project yang kamu ikuti</p>
</div>
@endsection

@section('content')
<div class="space-y-3">

    @forelse($projects as $project)

    <div class="bg-white border border-slate-200 rounded-2xl p-5
                hover:shadow-md transition-all duration-200">

        <div class="flex flex-col lg:flex-row lg:items-center gap-4">

            <div class="flex-1 min-w-0">

                <div class="flex items-center gap-2 mb-2">

                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border
                        {{ $project->status->badgeClass() }}">
                        {{ $project->status->label() }}
                    </span>

                    @if($project->division)
                    <span class="text-xs text-slate-500">
                        {{ $project->division->name }}
                    </span>
                    @endif

                </div>

                <h3 class="font-semibold text-slate-900">
                    {{ $project->title }}
                </h3>

                <div class="flex flex-wrap gap-4 mt-1.5 text-xs text-slate-500">
                    <span>PIC: {{ $project->picGuru->name ?? '—' }}</span>
                    <span>{{ $project->tasks->count() }} Task</span>

                    @if($project->end_date)
                    <span>
                        Deadline: {{ $project->end_date->format('d M Y') }}
                    </span>
                    @endif
                </div>

            </div>

            {{-- Progress --}}
            <div class="w-full lg:w-40">

                <div class="flex justify-between text-xs text-slate-500 mb-1.5">
                    <span>Progress</span>
                    <span class="font-semibold text-slate-900">
                        {{ $project->progress }}%
                    </span>
                </div>

                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500
                        {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-blue-600' }}"
                         style="width: {{ $project->progress }}%">
                    </div>
                </div>

            </div>

            {{-- Button --}}
            <a href="{{ route('siswa.projects.show', $project) }}"
               class="flex-shrink-0 px-4 py-2 bg-blue-600 hover:bg-blue-700
                      text-white text-sm rounded-xl transition-colors">
                Detail →
            </a>

        </div>
    </div>

    @empty

    <div class="bg-slate-100 border border-slate-300 rounded-2xl py-20 text-center">

        <svg class="w-14 h-14 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
        </svg>

        <p class="text-slate-500 font-medium">Belum ada project</p>
        <p class="text-slate-400 text-sm mt-1">
            Tunggu assignment dari admin atau guru
        </p>

    </div>

    @endforelse

    @if($projects->hasPages())
    <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4">
        {{ $projects->links('vendor.pagination.custom') }}
    </div>
    @endif

</div>
@endsection