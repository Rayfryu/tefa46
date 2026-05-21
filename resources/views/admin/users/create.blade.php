{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.users.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Tambah Pengguna</h1>
        <p class="text-slate-400 text-sm mt-0.5">Buat akun pengguna baru</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.users.store') }}"
          class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6 space-y-5">
        @csrf

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                          @error('name') border-red-500 @enderror"
                   placeholder="Nama lengkap pengguna">
            @error('name')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                          @error('email') border-red-500 @enderror"
                   placeholder="email@example.com">
            @error('email')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Role & Division (2 kolom) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Role</label>
                <select name="role"
                        class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                               rounded-xl focus:outline-none focus:border-brand-500 transition-colors
                               @error('role') border-red-500 @enderror">
                    <option value="">Pilih Role</option>
                    <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
                    <option value="guru"   {{ old('role') === 'guru'   ? 'selected' : '' }}>Guru</option>
                    <option value="siswa"  {{ old('role') === 'siswa'  ? 'selected' : '' }}>Siswa</option>
                    <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                </select>
                @error('role')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Divisi <span class="text-slate-500">(opsional)</span></label>
                <select name="division_id"
                        class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                               rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="">— Tanpa Divisi —</option>
                    @foreach($divisions as $division)
                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Phone --}}
        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">No. HP <span class="text-slate-500">(opsional)</span></label>
            <input type="text" name="phone" value="{{ old('phone') }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                   placeholder="08xxxxxxxxxx">
        </div>

        {{-- Password --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors
                              @error('password') border-red-500 @enderror"
                       placeholder="Min. 8 karakter">
                @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                              rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors"
                       placeholder="Ulangi password">
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-2 border-t border-surface-700/50">
            <button type="submit"
                    class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                           font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
                Simpan Pengguna
            </button>
            <a href="{{ route('admin.users.index') }}"
               class="px-6 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300
                      text-sm font-medium rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection