{{-- atur ulang UI bawaan breeze --}}

<section>

    {{-- FORM VERIFIKASI --}}
    <form id="send-verification"
          method="post"
          action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- FORM UPDATE PROFILE --}}
    <form method="post"
          action="{{ route('profile.update') }}"
          class="space-y-6"
          enctype="multipart/form-data">

        @csrf
        @method('patch')

        {{-- Name --}}
        <div>
            <label for="name"
                   class="block text-sm font-medium text-slate-300 mb-2">
                Nama
            </label>

            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
                class="w-full rounded-xl border border-surface-700 bg-surface-900
                       text-white placeholder:text-slate-500
                       focus:border-brand-500 focus:ring-brand-500
                       transition-all duration-200">

            @error('name')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- FOTO PROFILE --}}
        <div class="mb-6">

            <label class="block text-sm font-medium text-slate-300 mb-2">
                Foto Profile
            </label>

            <div class="flex items-center gap-4">

                {{-- Preview --}}
                @if ($user->image)
                    <img
                        src="{{ asset('storage/' . $user->image) }}"
                        class="w-20 h-20 aspect-square rounded-full object-cover border border-surface-700">
                @else
                    <div
                        class="w-20 h-20 aspect-square rounded-full
                               bg-brand-500/20 border border-brand-500/30
                               flex items-center justify-center
                               text-brand-400 text-2xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                {{-- Input --}}
                <input
                    type="file"
                    name="image"
                    accept="image/*"
                    class="block w-full text-sm text-slate-400
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-xl file:border-0
                           file:bg-brand-500 file:text-white
                           hover:file:bg-brand-600
                           cursor-pointer">
            </div>

            {{-- Tombol Hapus Foto --}}
            @if ($user->image)

                <button
                    type="submit"
                    form="delete-profile-image-form"
                    class="px-4 py-2 rounded-xl mt-4
                           bg-red-500/10 border border-red-500/20
                           text-red-400 text-sm font-medium
                           hover:bg-red-500/20
                           transition-all duration-200">

                    Hapus Foto Profile
                </button>

            @endif

            @error('image')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
            @enderror

        </div>

        {{-- Email --}}
        <div>
            <label for="email"
                   class="block text-sm font-medium text-slate-300 mb-2">
                Email
            </label>

            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                class="w-full rounded-xl border border-surface-700 bg-surface-900
                       text-white placeholder:text-slate-500
                       focus:border-brand-500 focus:ring-brand-500
                       transition-all duration-200">

            @error('email')
                <p class="mt-2 text-sm text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- SAVE BUTTON --}}
        <div class="flex items-center gap-4">

            <button
                type="submit"
                class="px-5 py-2.5 rounded-xl
                       bg-slate-700 hover:bg-slate-600
                       text-white text-sm font-medium
                       transition-all duration-200">

                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400">

                    Profile berhasil diperbarui.
                </p>
            @endif

        </div>

    </form>

    {{-- FORM KHUSUS DELETE IMAGE --}}
    <form
        id="delete-profile-image-form"
        action="{{ route('profile.image.destroy') }}"
        method="POST"
        class="hidden">

        @csrf
        @method('DELETE')
    </form>

</section>