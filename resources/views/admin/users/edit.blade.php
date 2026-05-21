{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('header')
<div class="flex items-center gap-3">
    <a href="{{ route('admin.users.index') }}"
       class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Edit Pengguna</h1>
        <p class="text-slate-400 text-sm mt-0.5">{{ $user->name }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.users.update', $user) }}"
          class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6 space-y-5">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl focus:outline-none focus:border-brand-500 transition-colors
                          @error('name') border-red-500 @enderror">
            @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl focus:outline-none focus:border-brand-500 transition-colors
                          @error('email') border-red-500 @enderror">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Role</label>
                <select name="role"
                        class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                               rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="admin"  {{ old('role', $user->role->value) === 'admin'  ? 'selected' : '' }}>Admin</option>
                    <option value="guru"   {{ old('role', $user->role->value) === 'guru'   ? 'selected' : '' }}>Guru</option>
                    <option value="siswa"  {{ old('role', $user->role->value) === 'siswa'  ? 'selected' : '' }}>Siswa</option>
                    <option value="client" {{ old('role', $user->role->value) === 'client' ? 'selected' : '' }}>Client</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1.5">Divisi</label>
                <select name="division_id"
                        class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                               rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="">— Tanpa Divisi —</option>
                    @foreach($divisions as $division)
                    <option value="{{ $division->id }}"
                            {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">No. HP</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                          rounded-xl focus:outline-none focus:border-brand-500 transition-colors"
                   placeholder="08xxxxxxxxxx">
        </div>

        <div class="flex items-center gap-3 pt-2 border-t border-surface-700/50">
            <button type="submit"
                    class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                           font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
                Update Pengguna
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