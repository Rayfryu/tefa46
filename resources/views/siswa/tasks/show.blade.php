@extends('layouts.app')
@section('title', 'Detail Task')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('siswa.tasks.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-xl font-bold font-display text-white">{{ $task->title }}</h1>
        <div class="flex items-center gap-2 mt-1">
            <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $task->status->badgeClass() }}">
                {{ $task->status->label() }}
            </span>
            <span class="text-xs text-slate-500">{{ $task->project->title }}</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl space-y-4">

    {{-- Task Info --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        @if($task->description)
        <p class="text-slate-300 text-sm leading-relaxed mb-4">{{ $task->description }}</p>
        @endif
        <div class="flex flex-wrap gap-4 text-xs text-slate-500">
            @if($task->deadline)
            <span class="{{ $task->deadline->isPast() && $task->status->value !== 'done' ? 'text-red-400' : '' }}">
                ⏰ Deadline: {{ $task->deadline->format('d M Y') }}
            </span>
            @endif
            <span>PIC: {{ $task->project->picGuru->name ?? '—' }}</span>
        </div>

        {{-- Start Task Button --}}
        @if($task->status->value === 'todo')
        <form method="POST" action="{{ route('siswa.tasks.start', $task) }}" class="mt-4">
            @csrf @method('PATCH')
            <button type="submit"
                    class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                           font-semibold rounded-xl transition-colors">
                🚀 Mulai Kerjakan
            </button>
        </form>
        @endif
    </div>

    {{-- Upload Progress --}}
    @if(in_array($task->status->value, ['in_progress', 'revision']))
    <div class="bg-surface-800 border border-brand-500/20 rounded-2xl p-5">
        <h3 class="font-semibold font-display text-white mb-4">Upload Progress</h3>
        <form method="POST" action="{{ route('siswa.tasks.progress.store', $task) }}"
              enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Deskripsi Progress <span class="text-red-400">*</span>
                </label>
                <textarea name="description" rows="3" required
                          class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                 rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                          placeholder="Jelaskan apa yang sudah kamu kerjakan...">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Status Update</label>
                <input type="text" name="status_update" value="{{ old('status_update') }}"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                       placeholder="Contoh: 70% selesai, halaman login sudah jalan">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    File Pendukung <span class="text-slate-500">(opsional, max 10MB)</span>
                </label>
                <input type="file" name="files[]" multiple
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                              rounded-xl focus:outline-none focus:border-brand-500 transition-colors
                              file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                              file:bg-brand-500/20 file:text-brand-400 file:text-xs file:font-medium">
            </div>
            <button type="submit"
                    class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                           font-semibold rounded-xl transition-colors">
                📤 Kirim Progress
            </button>
        </form>
    </div>
    @endif

    {{-- Progress History --}}
    @if($task->progresses->isNotEmpty())
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-700/50">
            <h3 class="font-semibold font-display text-white">Riwayat Progress</h3>
        </div>
        <div class="divide-y divide-surface-700/30">
            @foreach($task->progresses->sortByDesc('created_at') as $progress)
            <div class="px-5 py-4">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="text-sm font-medium text-white">{{ $progress->user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $progress->created_at->diffForHumans() }}</p>
                    </div>
                    @if($progress->review)
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border
                                 {{ $progress->review->status === 'approved'
                                    ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                                    : 'bg-orange-500/10 text-orange-400 border-orange-500/20' }}">
                        {{ $progress->review->status === 'approved' ? '✓ Disetujui' : '↩ Revisi' }}
                    </span>
                    @else
                    <span class="text-xs px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        Menunggu Review
                    </span>
                    @endif
                </div>
                <p class="text-slate-300 text-sm leading-relaxed">{{ $progress->description }}</p>
                @if($progress->status_update)
                <p class="text-slate-500 text-xs mt-1">📊 {{ $progress->status_update }}</p>
                @endif
                @if($progress->review?->note)
                <div class="mt-2 px-3 py-2 rounded-lg bg-surface-700 text-xs text-slate-300">
                    💬 Guru: {{ $progress->review->note }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection