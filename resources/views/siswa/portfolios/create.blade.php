@extends('layouts.app')

@section('title', 'Buat Portfolio')

@section('header')
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Buat Portfolio
        </h1>

        <p class="text-slate-500 text-sm mt-1">
            Tampilkan karya terbaikmu untuk membangun personal branding.
        </p>
    </div>
@endsection

@section('content')

    <form action="{{ route('siswa.portfolios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

        @csrf

        {{-- MAIN CARD --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900">
                    Informasi Portfolio
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    Isi data portfolio dengan lengkap dan menarik.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- TITLE --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Judul Portfolio
                    </label>

                    <input type="text" name="title" value="{{ old('title') }}"
                        placeholder="Contoh: Website Absensi Sekolah"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50
                              text-slate-900 placeholder:text-slate-400
                              focus:border-blue-500 focus:ring-blue-500">

                    @error('title')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Jenis Portfolio
                    </label>

                    <select name="type"
                        class="w-full rounded-xl border border-slate-300 bg-slate-50
                               text-slate-900
                               focus:border-blue-500 focus:ring-blue-500">

                        <option value="">Pilih Jenis</option>

                        @foreach (['Website', 'Mobile App', 'UI/UX Design', 'Graphic Design', 'Video Editing', 'Photography', 'Multimedia', 'Lainnya'] as $type)
                            <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                    @error('type')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Status
                    </label>

                    <label
                        class="flex items-center gap-3 rounded-xl border border-slate-300
                              bg-slate-50 px-4 py-3 cursor-pointer">

                        <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                        <div>
                            <p class="text-sm text-slate-900 font-medium">
                                Publish Portfolio
                            </p>
                            <p class="text-xs text-slate-500">
                                Portfolio akan tampil ke guru
                            </p>
                        </div>

                    </label>
                </div>

                {{-- THUMBNAIL --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Thumbnail Project
                    </label>

                    <input type="file" name="thumbnail" accept="image/*"
                        class="block w-full text-sm text-slate-600
                              file:mr-4 file:py-2.5 file:px-4
                              file:rounded-xl file:border-0
                              file:bg-blue-600 file:text-white
                              hover:file:bg-blue-700">

                    <p class="text-xs text-slate-500 mt-2">
                        Upload screenshot, poster, atau preview project.
                    </p>

                    @error('thumbnail')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Deskripsi
                    </label>

                    <textarea name="description" rows="5" placeholder="Ceritakan tentang project kamu..."
                        class="w-full rounded-xl border border-slate-300 bg-slate-100
                                 text-slate-900 placeholder:text-slate-400
                                 focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- SKILLS --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <div class="mb-5">
                <h2 class="text-lg font-semibold text-slate-900">
                    Skills & Teknologi
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Pilih skill yang digunakan pada project ini.
                </p>
            </div>

            @php
                $skills = [
                    'Laravel',
                    'PHP',
                    'JavaScript',
                    'Tailwind',
                    'MySQL',
                    'UI/UX',
                    'Figma',
                    'Canva',
                    'CapCut',
                    'Premiere Pro',
                    'Photography',
                    'Videography',
                ];
                $selectedSkills = old('skills', []);
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">

                @foreach ($skills as $skill)
                    <label
                        class="flex items-center gap-3 px-4 py-3 rounded-xl
                              border border-slate-300 bg-slate-50
                              hover:border-blue-400 cursor-pointer transition">

                        <input type="checkbox" name="skills[]" value="{{ $skill }}"
                            {{ in_array($skill, $selectedSkills) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-slate-300 focus:ring-blue-500">

                        <span class="text-sm text-slate-700">
                            {{ $skill }}
                        </span>

                    </label>
                @endforeach

            </div>

        </div>

        {{-- Links --}}
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
                          text-white placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
            </div>

            {{-- Demo --}}
            <div>
                <label class="block text-xs text-slate-900 mb-2">
                    Demo Website
                </label>

                <input type="url" name="links[demo]" value="{{ old('links.demo', $portfolio->links['demo'] ?? '') }}"
                    placeholder="https://project-demo.com"
                    class="w-full rounded-xl border border-slate-300 bg-slate-50
                          text-white placeholder:text-slate-500
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
                          text-white placeholder:text-slate-500
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
                          text-white placeholder:text-slate-500
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
                          text-white placeholder:text-slate-500
                          focus:border-brand-500 focus:ring-brand-500">
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3">

            <a href="{{ route('siswa.portfolios.index') }}"
                class="px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200
                  text-slate-700 text-sm font-medium">

                Batal
            </a>

            <button type="submit"
                class="px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700
                       text-white text-sm font-semibold shadow-md">

                Publish Portfolio
            </button>

        </div>

    </form>

@endsection
