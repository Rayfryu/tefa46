{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold font-display text-white">Manajemen Pengguna</h1>
        <p class="text-slate-400 text-sm mt-1">Kelola semua akun pengguna sistem TEFA</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-500 hover:bg-brand-600
              text-white text-sm font-semibold rounded-xl transition-colors shadow-lg shadow-brand-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Pengguna
    </a>
</div>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
    @php
    $statCards = [
        ['label' => 'Total',  'value' => $stats['total'],  'color' => 'slate'],
        ['label' => 'Admin',  'value' => $stats['admin'],  'color' => 'red'],
        ['label' => 'Guru',   'value' => $stats['guru'],   'color' => 'blue'],
        ['label' => 'Siswa',  'value' => $stats['siswa'],  'color' => 'emerald'],
        ['label' => 'Client', 'value' => $stats['client'], 'color' => 'amber'],
    ];
    $sc = [
        'slate'   => 'text-slate-300 bg-slate-500/10 border-slate-500/20',
        'red'     => 'text-red-300 bg-red-500/10 border-red-500/20',
        'blue'    => 'text-blue-300 bg-blue-500/10 border-blue-500/20',
        'emerald' => 'text-emerald-300 bg-emerald-500/10 border-emerald-500/20',
        'amber'   => 'text-amber-300 bg-amber-500/10 border-amber-500/20',
    ];
    @endphp

    @foreach($statCards as $s)
    <div class="bg-surface-800 border border-surface-700/50 rounded-xl px-4 py-3 text-center">
        <p class="text-2xl font-bold font-display {{ explode(' ', $sc[$s['color']])[0] }}">{{ $s['value'] }}</p>
        <p class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter & Search --}}
<div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-4 mb-4">
    <form method="GET" action="{{ route('admin.users.index') }}"
          class="flex flex-col sm:flex-row gap-3">

        <div class="flex-1 relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama atau email..."
                   class="w-full pl-9 pr-4 py-2.5 bg-surface-700 border border-surface-600
                          text-white text-sm rounded-xl placeholder-slate-500
                          focus:outline-none focus:border-brand-500 transition-colors">
        </div>

        <select name="role"
                class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                       text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            <option value="">Semua Role</option>
            <option value="admin"  {{ request('role') === 'admin'  ? 'selected' : '' }}>Admin</option>
            <option value="guru"   {{ request('role') === 'guru'   ? 'selected' : '' }}>Guru</option>
            <option value="siswa"  {{ request('role') === 'siswa'  ? 'selected' : '' }}>Siswa</option>
            <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Client</option>
        </select>

        <select name="status"
                class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                       text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <button type="submit"
                class="px-4 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                       font-semibold rounded-xl transition-colors">
            Filter
        </button>

        @if(request()->hasAny(['search', 'role', 'status']))
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2.5 bg-surface-700 hover:bg-surface-600 text-slate-300
                  text-sm font-medium rounded-xl transition-colors">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-700/50">
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">#</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Divisi</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Bergabung</th>
                    <th class="text-right px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-700/30">
                @forelse($users as $user)
                @php
                $roleColor = match($user->role->value) {
                    'admin'  => 'bg-red-500/10 text-red-400 border-red-500/20',
                    'guru'   => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                    'siswa'  => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'client' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                    default  => 'bg-slate-500/10 text-slate-400 border-slate-500/20',
                };
                @endphp
                <tr class="hover:bg-surface-700/30 transition-colors">
                    <td class="px-6 py-4 text-slate-500 text-xs">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-brand-500/20 border border-brand-500/30
                                        flex items-center justify-center text-brand-400 font-bold text-sm
                                        font-display flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $roleColor }}">
                            {{ $user->role->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-400 text-xs">
                        {{ $user->division->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1
                                     rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1
                                     rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            Nonaktif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-500 text-xs">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-1">
                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="p-2 rounded-lg text-slate-400 hover:text-brand-400
                                      hover:bg-brand-500/10 transition-colors"
                               title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            {{-- Toggle Status --}}
                            @if($user->id !== auth()->id())
                            <form method="POST"
                                  action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="p-2 rounded-lg transition-colors
                                               {{ $user->is_active
                                                   ? 'text-slate-400 hover:text-amber-400 hover:bg-amber-500/10'
                                                   : 'text-slate-400 hover:text-emerald-400 hover:bg-emerald-500/10' }}"
                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $user->is_active
                                                  ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'
                                                  : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                                    </svg>
                                </button>
                            </form>

                            {{-- Reset Password --}}
                            <form method="POST"
                                  action="{{ route('admin.users.reset-password', $user) }}"
                                  onsubmit="return confirm('Reset password ke \'password\'?')">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="p-2 rounded-lg text-slate-400 hover:text-blue-400
                                               hover:bg-blue-500/10 transition-colors"
                                        title="Reset Password">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                    </svg>
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form method="POST"
                                  action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus pengguna ini? Data tidak bisa dikembalikan.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="p-2 rounded-lg text-slate-400 hover:text-red-400
                                               hover:bg-red-500/10 transition-colors"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-slate-500 text-sm">Tidak ada pengguna ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-surface-700/50">
        {{ $users->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>

@endsection