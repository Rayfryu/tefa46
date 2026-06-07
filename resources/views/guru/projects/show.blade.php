{{-- resources/views/guru/projects/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Project')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('guru.projects.index') }}"
            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
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
                <span
                    class="text-3xl font-bold font-display
                         {{ $project->progress >= 100 ? 'text-emerald-400' : 'text-brand-400' }}">
                    {{ $project->progress }}%
                </span>
            </div>
            <div class="w-full bg-surface-700 rounded-full h-2.5">
                <div class="h-2.5 rounded-full transition-all duration-700
                        {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }}"
                    style="width: {{ $project->progress }}%"></div>
            </div>
            @if ($project->end_date)
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
                                        <span
                                            class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $task->status->badgeClass() }}">
                                            {{ $task->status->label() }}
                                        </span>
                                        @if ($task->latestProgress && !$task->latestProgress->review)
                                            <span
                                                class="text-xs px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                Butuh Review
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-white">{{ $task->title }}</p>
                                    <div class="flex gap-3 mt-1 text-xs text-slate-500">
                                        <span>{{ $task->assignedTo->name ?? 'Belum di-assign' }}</span>
                                        @if ($task->deadline)
                                            <span
                                                class="{{ $task->deadline->isPast() && $task->status->value !== 'done' ? 'text-red-400' : '' }}">
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

            {{-- Tambahkan di bawah section task list di guru/projects/show.blade.php --}}

            {{-- Upload Hasil Project --}}
            @if (in_array($project->status->value, ['active', 'revision']))
                <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5" x-data="{ open: false, type: 'file' }">

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold font-display text-white">Hasil Project</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Upload file, gambar, atau link hasil pengerjaan</p>
                        </div>
                        <button @click="open = !open"
                            class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm
                       font-semibold rounded-xl transition-colors">
                            + Upload Hasil
                        </button>
                    </div>

                    {{-- Form Upload --}}
                    <div x-show="open" x-transition class="mt-4 pt-4 border-t border-surface-700/50">
                        <form method="POST" action="{{ route('guru.projects.deliverables.store', $project) }}"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            {{-- Type Toggle --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Tipe Hasil</label>
                                <div class="flex gap-2">
                                    @foreach (['file' => '📦 File/ZIP', 'image' => '🖼️ Gambar', 'link' => '🔗 Link'] as $val => $lbl)
                                        <label class="flex-1">
                                            <input type="radio" name="type" value="{{ $val }}" x-model="type"
                                                class="sr-only">
                                            <div class="text-center py-2.5 px-3 rounded-xl border text-sm font-medium cursor-pointer transition-all"
                                                :class="type === '{{ $val }}'
                                                    ?
                                                    'bg-brand-500/20 border-brand-500/40 text-brand-400' :
                                                    'bg-surface-700 border-surface-600 text-slate-400 hover:text-white'">
                                                {{ $lbl }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">Judul</label>
                                <input type="text" name="title" required
                                    class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                                    placeholder="Contoh: Source Code Final, Desain Logo, Link Figma...">
                            </div>

                            {{-- File Input --}}
                            <div x-show="type !== 'link'">
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                                    File <span class="text-slate-500">(max 100MB)</span>
                                </label>
                                <input type="file" name="file" :accept="type === 'image' ? 'image/*' : '*'"
                                    class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                              rounded-xl focus:outline-none focus:border-brand-500 transition-colors
                              file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                              file:bg-brand-500/20 file:text-brand-400 file:text-xs file:font-medium">
                            </div>

                            {{-- Link Input --}}
                            <div x-show="type === 'link'">
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">URL</label>
                                <input type="url" name="link_url"
                                    class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                                    placeholder="https://figma.com/... atau https://github.com/...">
                            </div>

                            {{-- Description --}}
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                                    Keterangan <span class="text-slate-500">(opsional)</span>
                                </label>
                                <textarea name="description" rows="2"
                                    class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                 rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                                    placeholder="Jelaskan isi file/link ini..."></textarea>
                            </div>

                            {{-- Is Final --}}
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_final" value="1"
                                    class="w-4 h-4 rounded bg-surface-700 border-surface-600 text-brand-500
                              focus:ring-brand-500 focus:ring-offset-0">
                                <span class="text-sm text-slate-300">
                                    Tandai sebagai <span class="text-brand-400 font-semibold">hasil final</span>
                                    yang akan dikirim ke client
                                </span>
                            </label>

                            <div class="flex gap-3">
                                <button type="submit"
                                    class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                               font-semibold rounded-xl transition-colors">
                                    Upload
                                </button>
                                <button type="button" @click="open = false"
                                    class="px-5 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300
                               text-sm rounded-xl transition-colors">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- List Deliverables --}}
                    @if ($project->deliverables->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-surface-700/50 space-y-2">
                            @foreach ($project->deliverables as $item)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-700/50">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">{{ $item->icon }}</span>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium text-white">{{ $item->title }}</p>
                                                @if ($item->is_final)
                                                    <span
                                                        class="text-[10px] px-1.5 py-0.5 rounded-full bg-emerald-500/20
                                     text-emerald-400 border border-emerald-500/30 font-semibold">
                                                        FINAL
                                                    </span>
                                                @endif
                                            </div>
                                            @if ($item->description)
                                                <p class="text-xs text-slate-500">{{ $item->description }}</p>
                                            @endif
                                            @if ($item->file_size)
                                                <p class="text-xs text-slate-600">{{ $item->file_size_formatted }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $item->url }}" target="_blank"
                                            class="p-2 rounded-lg text-slate-400 hover:text-brand-400 hover:bg-brand-500/10 transition-colors"
                                            title="Buka">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('guru.deliverables.destroy', $item) }}"
                                            onsubmit="return confirm('Hapus file ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tombol Selesaikan Project --}}
                    @if ($project->deliverables()->where('is_final', true)->count() > 0)
                        <div class="mt-4 pt-4 border-t border-surface-700/50">
                            <div
                                class="flex items-start gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 mb-4">
                                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-emerald-300 text-sm">
                                    Kamu sudah mengupload
                                    <strong>{{ $project->deliverables()->where('is_final', true)->count() }} hasil
                                        final</strong>.
                                    Klik tombol di bawah untuk submit ke admin.
                                </p>
                            </div>
                            <form method="POST" action="{{ route('guru.projects.complete', $project) }}"
                                onsubmit="return confirm('Submit project ini ke admin untuk persetujuan?')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold
                           rounded-xl transition-colors shadow-lg shadow-emerald-500/25">
                                    🎉 Selesaikan & Submit ke Admin
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Status: Waiting Approval --}}
            @if ($project->status->value === 'waiting_approval')
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-amber-500/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="font-semibold text-amber-400">Menunggu Persetujuan Admin</p>
                    <p class="text-amber-300/70 text-sm mt-1">
                        Project sudah disubmit. Admin sedang mereview hasil pekerjaan.
                    </p>
                </div>
            @endif

            {{-- Status: Completed --}}
            @if ($project->status->value === 'completed')
                <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="font-semibold text-emerald-400">Project Selesai! 🎉</p>
                    <p class="text-emerald-300/70 text-sm mt-1">Hasil project sudah dikirim ke client.</p>
                </div>
            @endif

            {{-- Anggota --}}
            <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
                <h3 class="font-semibold font-display text-white mb-4">
                    Anggota Tim
                    <span class="text-slate-500 font-normal text-sm">({{ $project->members->count() }})</span>
                </h3>
                <div class="space-y-3">
                    @forelse($project->members as $member)
                        <div class="flex items-center gap-2.5">
                            <div
                                class="w-8 h-8 rounded-lg bg-emerald-500/20 border border-emerald-500/30
                                flex items-center justify-center text-emerald-400 font-bold text-xs">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                                @if ($member->pivot->role_in_project)
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
