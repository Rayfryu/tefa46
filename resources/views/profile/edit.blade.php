{{-- atur ulang UI bawaan breeze --}}

@extends('layouts.app')

@section('title', 'Profile')

@section('header')
    <div>
        <h1 class="text-2xl font-bold font-display text-white">
            Profile Saya
        </h1>
        <p class="text-slate-400 text-sm mt-1">
            Kelola informasi akun dan keamanan akun Anda.
        </p>
    </div>
@endsection

@section('content')

    <div class="space-y-6">

        {{-- Update Profile --}}
        <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold font-display text-white">
                    Informasi Profile
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Perbarui nama dan email akun Anda.
                </p>
            </div>

            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Update Password --}}
        <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold font-display text-white">
                    Ubah Password
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Gunakan password yang kuat untuk menjaga keamanan akun.
                </p>
            </div>

            @include('profile.partials.update-password-form')
        </div>

        {{-- Delete Account --}}
        <div class="bg-surface-800 border border-red-500/20 rounded-2xl p-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold font-display text-red-400">
                    Hapus Akun
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Setelah akun dihapus, seluruh data akan hilang permanen.
                </p>
            </div>

            @include('profile.partials.delete-user-form')
        </div>

    </div>

@endsection
