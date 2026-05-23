{{-- atur ulang UI bawaan breeze + tambahin fitur mata password atau apalah itu --}}

<section class="space-y-6">

    {{-- Delete Button --}}
    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 rounded-xl
               bg-red-500 hover:bg-red-600
               text-white text-sm font-medium
               shadow-lg shadow-red-500/20
               transition-all duration-200">
        Hapus Akun
    </button>

    {{-- Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>

        <form method="post" action="{{ route('profile.destroy') }}"
            class="p-6 bg-surface-800 border border-surface-700 rounded-2xl">

            @csrf
            @method('delete')

            {{-- Title --}}
            <h2 class="text-xl font-semibold font-display text-white">
                Konfirmasi Hapus Akun
            </h2>

            {{-- Description --}}
            <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan.
                Semua data akun akan dihapus permanen.
                Masukkan password Anda untuk melanjutkan.
            </p>

            {{-- Password --}}
            <div x-data="{ show: false }" class="mt-6 relative">

                <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                    Password
                </label>

                <input id="password" name="password" :type="show ? 'text' : 'password'" placeholder="Masukkan password"
                    class="w-full rounded-xl border border-surface-700 bg-surface-900
               text-white placeholder:text-slate-500 pr-12
               focus:border-red-500 focus:ring-red-500
               transition-all duration-200">

                {{-- Eye Toggle --}}
                <button type="button" @click="show = !show"
                    class="absolute right-3 top-[42px]
               text-slate-400 hover:text-white
               transition-colors">

                    {{-- Eye Open --}}
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                     c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7
                     -4.477 0-8.268-2.943-9.542-7z" />
                    </svg>

                    {{-- Eye Closed --}}
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                     c-4.478 0-8.268-2.943-9.542-7
                     a9.956 9.956 0 012.293-3.95" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.88 9.88a3 3 0 104.24 4.24" />

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6.1 6.1L3 3m0 0l18 18" />
                    </svg>

                </button>

                {{-- Error Message --}}
                @if ($errors->userDeletion->get('password'))
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="mt-2 text-sm text-red-400">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif

            </div>

            {{-- Actions --}}
            <div class="mt-6 flex justify-end gap-3">

                {{-- Cancel --}}
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 rounded-xl
                           bg-surface-700 hover:bg-surface-600
                           text-slate-300 text-sm font-medium
                           transition-all duration-200">
                    Batal
                </button>

                {{-- Delete --}}
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl
                           bg-red-500 hover:bg-red-600
                           text-white text-sm font-medium
                           shadow-lg shadow-red-500/20
                           transition-all duration-200">
                    Ya, Hapus Akun
                </button>

            </div>

        </form>

    </x-modal>

</section>
