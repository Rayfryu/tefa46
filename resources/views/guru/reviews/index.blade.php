@extends('layouts.app')
@section('title', 'Review Progress')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-white">Review Progress</h1>
    <p class="text-slate-400 text-sm mt-1">Progress siswa yang menunggu review kamu</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    @forelse($pendingReviews as $progress)
    <div class="bg-surface-800 border border-amber-500/20 rounded-2xl p-5">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        Menunggu Review
                    </span>
                    <span class="text-xs text-slate-500">{{ $progress->created_at->diffForHumans() }}</span>
                </div>
                <h3 class="font-semibold text-white">{{ $progress->task->title }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $progress->task->project->title }} • oleh {{ $progress->user->name }}
                </p>
            </div>
        </div>

        {{-- Progress Content --}}
        <div class="bg-surface-700/50 rounded-xl px-4 py-3 mb-4">
            <p class="text-slate-300 text-sm leading-relaxed">{{ $progress->description }}</p>
            @if($progress->status_update)
            <p class="text-slate-500 text-xs mt-2">📊 {{ $progress->status_update }}</p>
            @endif
        </div>

        {{-- Review Form --}}
        <form method="POST" action="{{ route('guru.reviews.store', $progress) }}"
              class="space-y-3" x-data="{ status: '' }">
            @csrf
            <textarea name="note" rows="2"
                      class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                             rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                      placeholder="Catatan untuk siswa (opsional)..."></textarea>

            <div class="flex items-center gap-3">
                <button type="submit" name="status" value="approved"
                        class="flex-1 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm
                               font-semibold rounded-xl transition-colors">
                    ✓ Setujui Progress
                </button>
                <button type="submit" name="status" value="revision"
                        class="flex-1 py-2.5 bg-orange-500/10 hover:bg-orange-500/20 text-orange-400
                               border border-orange-500/20 text-sm font-semibold rounded-xl transition-colors">
                    ↩ Minta Revisi
                </button>
            </div>
        </form>
    </div>
    @empty
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl py-20 text-center">
        <svg class="w-14 h-14 text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-slate-400 font-medium">Semua progress sudah direview!</p>
        <p class="text-slate-600 text-sm mt-1">Tidak ada yang menunggu review saat ini</p>
    </div>
    @endforelse

    @if($pendingReviews->hasPages())
    <div class="bg-surface-800 border border-surface-700/50 rounded-2xl px-6 py-4">
        {{ $pendingReviews->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection