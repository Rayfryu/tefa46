@extends('layouts.app')

@section('title', 'Profile')

@section('header')
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Profile Saya
        </h1>
        <p class="text-slate-500 text-sm mt-1">
            Kelola informasi akun dan keamanan akun Anda.
        </p>
    </div>
@endsection

@section('content')

    <div class="space-y-6">

        {{-- Update Profile --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900">
                    Informasi Profile
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Perbarui nama dan email akun Anda.
                </p>
            </div>

            @include('profile.partials.update-profile-information-form')

        </div>

        {{-- Update Password --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-slate-900">
                    Ubah Password
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Gunakan password yang kuat untuk menjaga keamanan akun.
                </p>
            </div>

            @include('profile.partials.update-password-form')

        </div>

        {{-- Delete Account --}}
        <div class="bg-white border border-red-200 rounded-2xl p-6">

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-red-500">
                    Hapus Akun
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Setelah akun dihapus, seluruh data akan hilang permanen.
                </p>
            </div>

            @include('profile.partials.delete-user-form')

        </div>

    </div>

@endsection
