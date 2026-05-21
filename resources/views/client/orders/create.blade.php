@extends('layouts.app')
@section('title', 'Buat Order')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('client.orders.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Buat Order Baru</h1>
        <p class="text-slate-400 text-sm mt-0.5">Isi detail kebutuhan jasa kamu</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('client.orders.store') }}"
          class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6 space-y-5">
        @csrf

        {{-- Judul --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
                Judul Order <span class="text-red-400">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                          @error('title') border-red-500 @enderror"
                   placeholder="Contoh: Desain Logo Perusahaan">
            @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Pilih Layanan --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
                Layanan <span class="text-slate-500">(opsional)</span>
            </label>
            <select name="service_id"
                    class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                           rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                <option value="">— Pilih layanan yang diinginkan —</option>
                @foreach($services->groupBy('division.name') as $divName => $svcs)
                <optgroup label="{{ $divName }}">
                    @foreach($svcs as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                        @if($service->price_start)
                        (Mulai Rp {{ number_format($service->price_start, 0, ',', '.') }})
                        @endif
                    </option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
                Deskripsi Kebutuhan <span class="text-red-400">*</span>
            </label>
            <textarea name="description" rows="4"
                      class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                             rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                             resize-none @error('description') border-red-500 @enderror"
                      placeholder="Jelaskan secara detail kebutuhan kamu...">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Requirements --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">
                Requirement Tambahan <span class="text-slate-500">(opsional)</span>
            </label>
            <textarea name="requirements" rows="3"
                      class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                             rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"
                      placeholder="Contoh: warna dominan biru, format PNG dan AI, dll...">{{ old('requirements') }}</textarea>
        </div>

        {{-- Budget & Deadline --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Budget (Rp) <span class="text-slate-500">(opsional)</span>
                </label>
                <input type="number" name="budget" value="{{ old('budget') }}" min="0"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                       placeholder="500000">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">
                    Deadline <span class="text-slate-500">(opsional)</span>
                </label>
                <input type="date" name="deadline_requested" value="{{ old('deadline_requested') }}"
                       min="{{ now()->addDay()->format('Y-m-d') }}"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                              rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            </div>
        </div>

        {{-- Info Box --}}
        <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-brand-500/10 border border-brand-500/20">
            <svg class="w-5 h-5 text-brand-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-brand-300 text-xs leading-relaxed">
                Order kamu akan direview oleh admin terlebih dahulu. Kamu akan mendapat notifikasi setelah order diproses.
                Proses review biasanya memakan waktu 1-2 hari kerja.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2 border-t border-surface-700/50">
            <button type="submit"
                    class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                           font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
                Kirim Order
            </button>
            <a href="{{ route('client.orders.index') }}"
               class="px-6 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300
                      text-sm font-medium rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection