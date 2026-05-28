<section class="space-y-6">

    {{-- DELETE BUTTON --}}
    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 rounded-xl
               bg-red-500 hover:bg-red-600
               text-white text-sm font-medium
               shadow-md shadow-red-500/20
               transition-all duration-200">
        Hapus Akun
    </button>

    {{-- MODAL --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>

        <form method="post" action="{{ route('profile.destroy') }}"
            class="p-6 bg-white border border-slate-200 rounded-2xl">

            @csrf
            @method('delete')

            {{-- TITLE --}}
            <h2 class="text-xl font-semibold text-slate-900">
                Konfirmasi Hapus Akun
            </h2>

            {{-- DESCRIPTION --}}
            <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan.
                Semua data akun akan dihapus permanen.
                Masukkan password Anda untuk melanjutkan.
            </p>

            {{-- PASSWORD --}}
            <div x-data="{ show: false }" class="mt-6 relative">

                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Password
                </label>

                <input name="password" :type="show ? 'text' : 'password'" placeholder="Masukkan password"
                    class="w-full rounded-xl border border-slate-200 bg-white
                           text-slate-900 placeholder:text-slate-400 pr-12
                           focus:border-blue-500 focus:ring-blue-500
                           transition-all duration-200">

                {{-- EYE TOGGLE --}}
                <button type="button" @click="show = !show"
                    class="absolute right-3 top-[42px]
                               text-slate-400 hover:text-slate-600">

                    {{-- eye open --}}
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7
                              c-1.274 4.057-5.064 7-9.542 7s-8.268-2.943-9.542-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>

                    {{-- eye off --}}
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7" />
                    </svg>

                </button>

                {{-- ERROR --}}
                @if ($errors->userDeletion->get('password'))
                    <p class="mt-2 text-sm text-red-500">
                        {{ $errors->userDeletion->first('password') }}
                    </p>
                @endif

            </div>

            {{-- ACTIONS --}}
            <div class="mt-6 flex justify-end gap-3">

                {{-- CANCEL --}}
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 rounded-xl
                               bg-slate-100 hover:bg-slate-200
                               text-slate-700 text-sm font-medium
                               transition-all duration-200">
                    Batal
                </button>

                {{-- DELETE --}}
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl
                               bg-red-500 hover:bg-red-600
                               text-white text-sm font-medium
                               shadow-md shadow-red-500/20
                               transition-all duration-200">
                    Ya, Hapus Akun
                </button>

            </div>

        </form>

    </x-modal>

</section>
