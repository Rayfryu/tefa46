{{-- resources/views/siswa/portfolios/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Portfolio')

@section('header')
<div>
    <h1 class="text-2xl font-bold font-display text-slate-900">
        Detail Portfolio
    </h1>

    <p class="text-slate-500 text-sm mt-1">
        Detail isi portfolio kamu
    </p>
</div>
@endsection

@section('content')

<div class="space-y-6">

    {{-- Thumbnail --}}
    <div class="bg-slate-50 border border-slate-300 rounded-3xl overflow-hidden">

        @if ($portfolio->thumbnail)
            <img src="{{ asset('storage/' . $portfolio->thumbnail) }}"
                 class="w-full h-[260px] md:h-[360px] object-cover">
        @else
            <div class="w-full h-[260px] md:h-[360px]
                        bg-brand-500/10 flex items-center justify-center">

                <svg class="w-20 h-20 text-brand-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9.75 17L15 12l-5.25-5.25" />
                </svg>

            </div>
        @endif

    </div>

    {{-- Main --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Title --}}
            <div class="bg-slate-50 border border-slate-300 rounded-2xl p-6">

                <div class="flex flex-wrap items-center gap-2 mb-4">

                    <span class="text-xs font-medium px-2.5 py-1 rounded-full border
                        {{ $portfolio->is_published
                            ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                            : 'bg-amber-500/10 text-amber-400 border-amber-500/20' }}">
                        {{ $portfolio->is_published ? 'Published' : 'Draft' }}
                    </span>

                    @if ($portfolio->type)
                        <span class="text-xs text-slate-500">
                            {{ $portfolio->type }}
                        </span>
                    @endif

                </div>

                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $portfolio->title }}
                </h2>

                <div class="mt-5">
                    <h3 class="text-sm font-semibold text-slate-900 mb-2">
                        Deskripsi
                    </h3>

                    <p class="text-slate-500 leading-relaxed">
                        {{ $portfolio->description ?: 'Tidak ada deskripsi portfolio.' }}
                    </p>
                </div>

            </div>

            {{-- Skills --}}
            @if ($portfolio->skills && count($portfolio->skills))
            <div class="bg-slate-50 border border-slate-300 rounded-2xl p-6">

                <h3 class="text-sm font-semibold text-slate-900 mb-4">
                    Skills & Tools
                </h3>

                <div class="flex flex-wrap gap-2">

                    @foreach ($portfolio->skills as $skill)
                        <span class="px-3 py-1.5 rounded-xl
                                     bg-blue-500/10 border border-blue-500/20
                                     text-blue-700 text-sm font-medium">
                            {{ $skill }}
                        </span>
                    @endforeach

                </div>

            </div>
            @endif

        </div>

        {{-- RIGHT --}}
        <div class="space-y-6">

            {{-- Student --}}
            <div class="bg-slate-50 border border-slate-300 rounded-2xl p-6">

                <h3 class="text-sm font-semibold text-slate-900 mb-4">
                    Pembuat Portfolio
                </h3>

                <div class="flex items-center gap-3">

                    @if ($portfolio->student->image)
                        <img src="{{ asset('storage/' . $portfolio->student->image) }}"
                             class="w-14 h-14 rounded-full object-cover border border-surface-700">
                    @else
                        <div class="w-14 h-14 rounded-full
                                    bg-blue-100 border border-blue-500/20
                                    flex items-center justify-center
                                    text-blue-700 font-bold">
                            {{ strtoupper(substr($portfolio->student->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <p class="font-semibold text-slate-900">
                            {{ $portfolio->student->name }}
                        </p>
                        <p class="text-xs text-slate-500">
                            {{ $portfolio->student->email }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- Links --}}
            @php
                $links = is_array($portfolio->links)
                    ? $portfolio->links
                    : json_decode($portfolio->links, true);

                $links = array_filter($links ?? []);
            @endphp

            @if (!empty($links))
            <div class="bg-slate-50 border border-slate-300 rounded-2xl p-6">

                <h3 class="text-sm font-semibold text-slate-900 mb-4">
                    External Links
                </h3>

                <div class="space-y-3">

                    @foreach ($links as $platform => $link)
                        <a href="{{ $link }}" target="_blank"
                           class="flex items-center justify-between
                                  px-4 py-3 rounded-xl
                                  bg-slate-50 border border-slate-300
                                  hover:border-blue-500/40 hover:bg-blue-50
                                  transition-all group">

                            <div class="min-w-0">
                                <p class="text-xs text-slate-500 capitalize mb-1">
                                    {{ $platform }}
                                </p>
                                <p class="text-sm text-slate-900 truncate">
                                    {{ $link }}
                                </p>
                            </div>

                            <svg class="w-4 h-4 text-slate-500 group-hover:text-brand-400 transition-colors"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14 5h7m0 0v7m0-7L10 16" />
                            </svg>

                        </a>
                    @endforeach

                </div>

            </div>
            @endif

            {{-- Meta --}}
            <div class="bg-slate-50 border border-slate-300 rounded-2xl p-6">

                <h3 class="text-sm font-semibold text-slate-900 mb-4">
                    Informasi
                </h3>

                <div class="space-y-4 text-sm">

                    <div class="flex justify-between">
                        <span class="text-slate-500">Dibuat</span>
                        <span class="text-slate-900">
                            {{ $portfolio->created_at->format('d M Y') }}
                        </span>
                    </div>

                    @if ($portfolio->published_at)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dipublish</span>
                        <span class="text-slate-900">
                            {{ $portfolio->published_at->format('d M Y') }}
                        </span>
                    </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

</div>

@endsection