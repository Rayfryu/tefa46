@extends('layouts.app')
@section('title', 'Buat Project')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.projects.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Buat Project Baru</h1>
        <p class="text-slate-400 text-sm mt-0.5">Buat project dari order yang disetujui</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.projects.store') }}"
          class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6 space-y-5">
        @csrf

        {{-- Link ke Order --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
                Berdasarkan Order <span class="text-slate-500">(opsional)</span>
            </label>
            <select name="order_id"
                    class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                           text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                <option value="">— Project mandiri (tanpa order) —</option>
                @foreach($orders as $order)
                <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                    {{ $order->title }} — {{ $order->client->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Judul --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
                Nama Project <span class="text-red-400">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                          @error('title') border-red-500 @enderror"
                   placeholder="Nama project">
            @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Deskripsi</label>
            <textarea name="description" rows="3"
                      class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                             rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                      placeholder="Deskripsi singkat project...">{{ old('description') }}</textarea>
        </div>

        {{-- Divisi & PIC Guru --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Divisi</label>
                <select name="division_id"
                        class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                               text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="">— Pilih Divisi —</option>
                    @foreach($divisions as $div)
                    <option value="{{ $div->id }}" {{ old('division_id') == $div->id ? 'selected' : '' }}>
                        {{ $div->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">PIC Guru</label>
                <select name="pic_guru_id"
                        class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                               text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="">— Pilih Guru —</option>
                    @foreach($gurus as $guru)
                    <option value="{{ $guru->id }}" {{ old('pic_guru_id') == $guru->id ? 'selected' : '' }}>
                        {{ $guru->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Start & End Date --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                              text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                              text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-surface-700/50">
            <button type="submit"
                    class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                           font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
                Buat Project
            </button>
            <a href="{{ route('admin.projects.index') }}"
               class="px-6 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300 text-sm font-medium rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection