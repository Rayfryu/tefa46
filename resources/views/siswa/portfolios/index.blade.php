@extends('layouts.app')

@section('title', 'Portfolio Saya')

@section('header')
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Portfolio Saya
        </h1>

        <p class="text-slate-500 text-sm mt-1">
            Platform personal branding untuk menampilkan karya terbaikmu
        </p>
    </div>
@endsection

@section('content')
    <div class="space-y-3">

        {{-- Jika sudah ada portfolio --}}
        @if ($portfolios->count())

            {{-- Card Tambah Portfolio --}}
            <a href="{{ route('siswa.portfolios.create') }}"
                class="block bg-white border border-dashed border-slate-300
                   rounded-2xl p-5
                   hover:border-blue-400 hover:bg-blue-50/30
                   transition-all duration-200 group">

                <div class="flex items-center gap-4">

                    {{-- Icon --}}
                    <div
                        class="w-14 h-14 rounded-2xl
                           bg-blue-100 border border-blue-200
                           flex items-center justify-center
                           group-hover:scale-110 transition-transform duration-200">

                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>

                    {{-- Text --}}
                    <div>
                        <h3 class="text-slate-900 font-semibold group-hover:text-blue-600 transition-colors">
                            Tambah Portfolio Baru
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Upload project, karya, atau pengalaman baru kamu
                        </p>
                    </div>

                </div>
            </a>

            {{-- List Portfolio --}}
            @foreach ($portfolios as $p)
                <div
                    class="bg-white border border-slate-200 rounded-2xl p-5
                        hover:shadow-md transition-all duration-200">

                    <div class="flex flex-col lg:flex-row lg:items-center gap-4">

                        {{-- Thumbnail --}}
                        <div class="flex-shrink-0">

                            @if ($p->thumbnail)
                                <img src="{{ asset('storage/' . $p->thumbnail) }}" alt="{{ $p->title }}"
                                    class="w-full lg:w-44 h-40 object-cover rounded-xl border border-slate-200">
                            @else
                                <div
                                    class="w-full lg:w-44 h-40 rounded-xl
                                        bg-blue-50 border border-blue-100
                                        flex items-center justify-center">
                                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 17L15 12l-5.25-5" />
                                    </svg>
                                </div>
                            @endif

                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">

                            {{-- Status + Type --}}
                            <div class="flex items-center gap-2 mb-3">

                                <span
                                    class="text-xs font-medium px-2.5 py-1 rounded-full border
                                {{ $p->is_published
                                    ? 'bg-emerald-50 text-emerald-600 border-emerald-200'
                                    : 'bg-amber-50 text-amber-600 border-amber-200' }}">

                                    {{ $p->is_published ? 'Published' : 'Draft' }}
                                </span>

                                @if ($p->type)
                                    <span class="text-xs text-slate-500">
                                        {{ $p->type }}
                                    </span>
                                @endif

                            </div>

                            {{-- Title --}}
                            <h3 class="text-lg font-semibold text-slate-900">
                                {{ $p->title }}
                            </h3>

                            {{-- Description --}}
                            <p class="text-sm text-slate-500 mt-2 line-clamp-2">
                                {{ $p->description ?: 'Tidak ada deskripsi portfolio.' }}
                            </p>

                            {{-- Skills --}}
                            @if ($p->skills && count($p->skills))
                                <div class="flex flex-wrap gap-2 mt-4">

                                    @foreach ($p->skills as $skill)
                                        <span
                                            class="px-2.5 py-1 rounded-lg
                                                 bg-blue-50 border border-blue-200
                                                 text-blue-600 text-xs font-medium">
                                            {{ $skill }}
                                        </span>
                                    @endforeach

                                </div>
                            @endif

                            {{-- Footer --}}
                            <div class="flex items-center justify-between mt-5">

                                <p class="text-xs text-slate-400">
                                    Dibuat {{ $p->created_at->format('d M Y') }}
                                </p>

                                <div class="flex items-center gap-2 flex-wrap">

                                    {{-- Edit --}}
                                    <a href="{{ route('siswa.portfolios.edit', $p->id) }}"
                                        class="px-4 py-2 rounded-xl
                                          bg-blue-50 hover:bg-blue-100
                                          border border-blue-200
                                          text-blue-600 text-sm font-medium">

                                        Edit
                                    </a>

                                    {{-- Detail --}}
                                    <a href="{{ route('siswa.portfolios.show', $p->id) }}"
                                        class="px-4 py-2 rounded-xl
                                          bg-blue-600 hover:bg-blue-700
                                          text-white text-sm font-medium">

                                        Detail
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('siswa.portfolios.destroy', $p->id) }}" method="POST"
                                        x-data="{ open: false }" class="inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="button" @click="open = true"
                                            class="px-4 py-2 rounded-xl
                                               bg-red-50 hover:bg-red-100
                                               border border-red-200
                                               text-red-500 text-sm font-medium">

                                            Hapus
                                        </button>

                                        {{-- Modal --}}
                                        <div x-show="open" x-transition
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            style="display: none;">

                                            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>

                                            <div
                                                class="relative w-full max-w-md rounded-2xl
                                                    bg-white border border-slate-200 p-6 shadow-xl">

                                                <h3 class="text-lg font-semibold text-slate-900">
                                                    Hapus Portfolio?
                                                </h3>

                                                <p class="text-sm text-slate-500 mt-2">
                                                    Portfolio <span class="text-slate-900 font-medium">
                                                        "{{ $p->title }}"
                                                    </span> akan dihapus permanen.
                                                </p>

                                                <div class="flex justify-end gap-3 mt-6">

                                                    <button type="button" @click="open = false"
                                                        class="px-4 py-2 rounded-xl
                                                           bg-slate-100 hover:bg-slate-200
                                                           text-slate-700 text-sm">

                                                        Batal
                                                    </button>

                                                    <button type="submit"
                                                        class="px-4 py-2 rounded-xl
                                                           bg-red-500 hover:bg-red-600
                                                           text-white text-sm font-medium">

                                                        Ya, Hapus
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Empty State --}}
            <a href="{{ route('siswa.portfolios.create') }}"
                class="block bg-slate-100 border border-dashed border-slate-300
                   rounded-2xl py-16 px-6 text-center
                   hover:border-blue-400 hover:bg-blue-50/30
                   transition-all duration-200 group">

                <div
                    class="w-16 h-16 mx-auto rounded-2xl
                        bg-blue-100 border border-blue-200
                        flex items-center justify-center mb-5
                        group-hover:scale-110 transition-transform duration-200">

                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>

                <h3 class="text-lg font-semibold text-slate-900 group-hover:text-blue-600">
                    Buat Portfolio Pertama
                </h3>

                <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">
                    Mulai bangun personal branding kamu dengan menampilkan skill, project, dan karya terbaikmu.
                </p>

            </a>

        @endif

        {{-- Pagination --}}
        @if ($portfolios->hasPages())
            <div class="bg-white border border-slate-200 rounded-2xl px-6 py-4">
                {{ $portfolios->links('vendor.pagination.custom') }}
            </div>
        @endif

    </div>
@endsection
