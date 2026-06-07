{{-- resources/views/client/projects/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Hasil Project')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('client.projects.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">{{ $project->title }}</h1>
        <p class="text-slate-400 text-sm mt-0.5">Hasil akhir project kamu</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl space-y-4">

    {{-- Congrats Banner --}}
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-5 text-center">
        <div class="text-4xl mb-2">🎉</div>
        <p class="font-bold font-display text-emerald-400 text-lg">Project Selesai!</p>
        <p class="text-emerald-300/70 text-sm mt-1">
            Terima kasih telah menggunakan layanan TEFA SMK.
            Berikut hasil project kamu yang sudah siap diunduh.
        </p>
    </div>

    {{-- Project Info --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <div class="flex justify-between text-sm mb-3">
            <span class="text-slate-500">Dikerjakan oleh</span>
            <span class="text-white font-medium">{{ $project->picGuru->name ?? '—' }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-slate-500">Order</span>
            <span class="text-white font-medium">{{ $project->order->title ?? '—' }}</span>
        </div>
    </div>

    {{-- Deliverables --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-700/50">
            <h3 class="font-semibold font-display text-white">
                📦 File & Hasil Project
                <span class="text-slate-500 font-normal text-sm">
                    ({{ $project->deliverables->count() }} item)
                </span>
            </h3>
        </div>
        <div class="divide-y divide-surface-700/30">
            @foreach($project->deliverables as $item)
            <div class="px-5 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                                {{ $item->is_final ? 'bg-emerald-500/20 border border-emerald-500/30' : 'bg-surface-700' }}">
                        {{ $item->icon }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-white truncate">{{ $item->title }}</p>
                            @if($item->is_final)
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-emerald-500/20
                                         text-emerald-400 border border-emerald-500/30 font-semibold flex-shrink-0">
                                FINAL
                            </span>
                            @endif
                        </div>
                        @if($item->description)
                        <p class="text-xs text-slate-500 truncate">{{ $item->description }}</p>
                        @endif
                        @if($item->file_size_formatted)
                        <p class="text-xs text-slate-600">{{ $item->file_size_formatted }}</p>
                        @endif
                        <p class="text-xs text-slate-600">
                            Diupload oleh {{ $item->uploadedBy->name }} •
                            {{ $item->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>

                {{-- Download / Open Button --}}
                @if($item->type === 'link')
                <a href="{{ $item->url }}" target="_blank"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2
                          bg-brand-500/10 hover:bg-brand-500/20 text-brand-400
                          border border-brand-500/20 text-sm font-medium rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Buka Link
                </a>
                @else
                <a href="{{ $item->url }}" download="{{ $item->original_name }}" target="_blank"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 px-4 py-2
                          bg-brand-500 hover:bg-brand-600 text-white
                          text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Feedback --}}
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
        <p class="text-sm font-semibold text-white mb-1">Ada pertanyaan?</p>
        <p class="text-slate-400 text-sm">
            Hubungi tim TEFA SMK jika ada kendala dengan hasil project kamu.
        </p>
    </div>

</div>
@endsection