{{-- resources/views/siswa/portfolios/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Portfolio')

@section('header')
    <div>
        <h1 class="text-2xl font-bold font-display text-slate-900">
            Edit Portfolio
        </h1>

        <p class="text-slate-400 text-sm mt-1">
            Perbarui isi portfolio kamu
        </p>
    </div>
@endsection

@section('content')

    <form action="{{ route('siswa.portfolios.update', $portfolio) }}" method="POST" enctype="multipart/form-data"
        class="space-y-6">

        @csrf
        @method('PATCH')

        <div class="bg-slate-50 border border-slate-300 rounded-2xl p-6 space-y-6">

            {{-- Title --}}
            <div>
                <label class="block text-sm font-medium text-slate-900 mb-2">
                    Judul Portfolio
                </label>

                <input type="text" name="title" value="{{ old('title', $portfolio->title) }}"
                    placeholder="Contoh: Website Absensi Sekolah"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-slate-900 placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">

                @error('title')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-slate-900 mb-2">
                    Deskripsi
                </label>

                <textarea name="description" rows="5" placeholder="Jelaskan project atau karya kamu..."
                    class="w-full rounded-xl border border-slate-300 bg-slate-50
                             text-slate-900 placeholder:text-slate-500
                             focus:border-brand-500 focus:ring-brand-500">{{ old('description', $portfolio->description) }}</textarea>

                @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Thumbnail --}}
            <div>
                <label class="block text-sm font-medium text-slate-900 mb-2">
                    Thumbnail
                </label>

                @if ($portfolio->thumbnail)
                    <img src="{{ asset('storage/' . $portfolio->thumbnail) }}"
                        class="w-full max-w-xs rounded-xl border border-surface-700 mb-4">
                @endif

                <input type="file" name="thumbnail" accept="image/*"
                    class="block w-full text-sm text-slate-400
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-xl file:border-0
                          file:bg-brand-500 file:text-white
                          hover:file:bg-brand-600
                          cursor-pointer">

                @error('thumbnail')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Skills --}}
            @php
                $selectedSkills = old('skills', $portfolio->skills ?? []);

                $skillOptions = ['Laravel', 'PHP', 'JavaScript', 'Tailwind', 'MySQL', 'Flutter', 'Figma', 'UI/UX'];
            @endphp

            <div>
                <label class="block text-sm font-medium text-slate-900 mb-3">
                    Skills / Teknologi
                </label>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach ($skillOptions as $skill)
                        <label
                            class="relative flex items-center gap-3 px-4 py-3 rounded-xl cursor-pointer transition-all duration-200 border {{ in_array($skill, $selectedSkills) ? 'border-slate-300 bg-slate-50' : 'border-slate-300 bg-slate-50 hover:border-blue-500/40' }}">

                            <input type="checkbox" name="skills[]" value="{{ $skill }}" class="hidden"
                                {{ in_array($skill, $selectedSkills) ? 'checked' : '' }}>

                            {{-- Fake Checkbox --}}
                            <div
                                class="w-5 h-5 rounded-md border flex items-center justify-center {{ in_array($skill, $selectedSkills) ? 'bg-slate-50 border-slate-100' : 'border-slate-100 bg-slate-50' }}">

                                @if (in_array($skill, $selectedSkills))
                                    <svg class="w-3 h-3 text-slate-900" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif

                            </div>

                            <span class="text-sm text-slate900">
                                {{ $skill }}
                            </span>

                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Links --}}
            <div>
                <label class="block text-sm font-medium text-slate-900 mb-4">
                    Link Portfolio / Sosial Media
                </label>

                <div class="space-y-4">

                    {{-- GitHub --}}
                    <div>
                        <label class="block text-xs text-slate-900 mb-2">
                            GitHub
                        </label>

                        <input type="url" name="links[github]"
                            value="{{ old('links.github', $portfolio->links['github'] ?? '') }}"
                            placeholder="https://github.com/username"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-slate-900 placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
                    </div>

                    {{-- Demo --}}
                    <div>
                        <label class="block text-xs text-slate-900 mb-2">
                            Demo Website
                        </label>

                        <input type="url" name="links[demo]"
                            value="{{ old('links.demo', $portfolio->links['demo'] ?? '') }}"
                            placeholder="https://project-demo.com"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-slate-900 placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
                    </div>

                    {{-- Instagram --}}
                    <div>
                        <label class="block text-xs text-slate-900 mb-2">
                            Instagram
                        </label>

                        <input type="url" name="links[instagram]"
                            value="{{ old('links.instagram', $portfolio->links['instagram'] ?? '') }}"
                            placeholder="https://instagram.com/username"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-slate-900 placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
                    </div>

                    {{-- LinkedIn --}}
                    <div>
                        <label class="block text-xs text-slate-900 mb-2">
                            LinkedIn
                        </label>

                        <input type="url" name="links[linkedin]"
                            value="{{ old('links.linkedin', $portfolio->links['linkedin'] ?? '') }}"
                            placeholder="https://linkedin.com/in/username"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-slate-900 placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
                    </div>

                    {{-- YouTube --}}
                    <div>
                        <label class="block text-xs text-slate-900 mb-2">
                            YouTube / Behance / Dribbble
                        </label>

                        <input type="url" name="links[youtube]"
                            value="{{ old('links.youtube', $portfolio->links['youtube'] ?? '') }}"
                            placeholder="https://youtube.com/@username"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-slate-900 placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
                    </div>

                </div>

                @error('links')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Type --}}
            <div>
                <label class="block text-sm font-medium text-slate-900 mb-2">
                    Jenis Portfolio
                </label>

                <select name="type"
                    class="w-full rounded-xl border border-slate-300
               bg-slate-50 text-slate-900
               focus:border-brand-500 focus:ring-brand-500">

                    <option value="">Pilih Jenis</option>

                    @php
                        $types = [
                            'Website',
                            'Mobile App',
                            'UI/UX Design',
                            'Graphic Design',
                            'Video Editing',
                            'Photography',
                            'Multimedia',
                            'Lainnya',
                        ];
                    @endphp

                    @foreach ($types as $type)
                        <option value="{{ $type }}"
                            {{ old('type', $portfolio->type) == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach

                </select>

                @error('type')
                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Publish --}}
            <div class="flex items-center gap-3">

                <input type="checkbox" name="is_published" value="1"
                    {{ old('is_published', $portfolio->is_published) ? 'checked' : '' }}
                    class="rounded border-surface-600 bg-slate-50 text-brand-500">

                <label class="text-sm text-slat900">
                    Publish portfolio
                </label>

            </div>

        </div>

        {{-- Action --}}
        <div class="flex items-center justify-between">

            {{-- Delete Button --}}
            <button type="button" x-data @click="$dispatch('open-delete-modal')"
                class="px-5 py-3 rounded-xl
               bg-red-500/10 border border-red-500/20
               text-red-400 hover:bg-red-500/20
               text-sm font-medium transition-colors">

                Hapus Portfolio
            </button>

            {{-- Save --}}
            <button type="submit"
                class="px-6 py-3 rounded-xl
               bg-brand-500 hover:bg-brand-600
               text-white text-sm font-semibold
               transition-colors shadow-lg shadow-brand-500/20">

                Simpan Perubahan
            </button>

        </div>

    </form>

    {{-- Delete Modal --}}
    <div x-data="{ open: false }" @open-delete-modal.window="open = true">

        <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none;">

            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

            {{-- Modal --}}
            <div
                class="relative w-full max-w-md rounded-2xl
                    bg-surface-800 border border-surface-700 p-6">

                <h3 class="text-lg font-semibold text-white">
                    Hapus Portfolio?
                </h3>

                <p class="text-sm text-slate-400 mt-2">
                    Portfolio ini akan dihapus permanen.
                </p>

                <div class="flex justify-end gap-3 mt-6">

                    <button type="button" @click="open = false"
                        class="px-4 py-2 rounded-xl
                               bg-surface-700 hover:bg-surface-600
                               text-sla900 text-sm">

                        Batal
                    </button>

                    <form action="{{ route('siswa.portfolios.destroy', $portfolio) }}" method="POST">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-4 py-2 rounded-xl
                                   bg-red-500 hover:bg-red-600
                                   text-white text-sm font-medium">

                            Ya, Hapus
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
