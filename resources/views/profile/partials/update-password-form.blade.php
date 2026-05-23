{{-- atur ulang UI bawaan breeze + tambahin fitur mata password atau apalah itu --}}

<section>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div x-data="{ show: false }" class="relative">

            <label for="update_password_current_password" class="block text-sm font-medium text-slate-300 mb-2">
                Password Saat Ini
            </label>

            <input id="update_password_current_password" name="current_password" :type="show ? 'text' : 'password'"
                autocomplete="current-password"
                class="w-full rounded-xl border border-surface-700 bg-surface-900
               text-white placeholder:text-slate-500 pr-12
               focus:border-brand-500 focus:ring-brand-500
               transition-all duration-200">

            {{-- Eye Toggle --}}
            <button type="button" @click="show = !show"
                class="absolute right-3 top-[42px] text-slate-400 hover:text-white transition-colors">

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.1 6.1L3 3m0 0l18 18" />
                </svg>

            </button>

            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-sm text-red-400">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif

        </div>

        {{-- New Password --}}
        <div x-data="{ show: false }" class="relative">

            <label for="update_password_password" class="block text-sm font-medium text-slate-300 mb-2">
                Password Baru
            </label>

            <input id="update_password_password" name="password" :type="show ? 'text' : 'password'"
                autocomplete="new-password"
                class="w-full rounded-xl border border-surface-700 bg-surface-900
               text-white placeholder:text-slate-500 pr-12
               focus:border-brand-500 focus:ring-brand-500
               transition-all duration-200">

            {{-- Eye Toggle --}}
            <button type="button" @click="show = !show"
                class="absolute right-3 top-[42px] text-slate-400 hover:text-white transition-colors">

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.1 6.1L3 3m0 0l18 18" />
                </svg>

            </button>

            @if ($errors->updatePassword->get('password'))
                <p class="mt-2 text-sm text-red-400">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif

        </div>

        {{-- Confirm Password --}}
        <div x-data="{ show: false }" class="relative">

            <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                Konfirmasi Password Baru
            </label>

            <input id="update_password_password_confirmation" name="password_confirmation"
                :type="show ? 'text' : 'password'" autocomplete="new-password"
                class="w-full rounded-xl border border-surface-700 bg-surface-900
               text-white placeholder:text-slate-500 pr-12
               focus:border-brand-500 focus:ring-brand-500
               transition-all duration-200">

            {{-- Eye Toggle --}}
            <button type="button" @click="show = !show"
                class="absolute right-3 top-[42px] text-slate-400 hover:text-white transition-colors">

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

            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="mt-2 text-sm text-red-400">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif

        </div>

        {{-- Submit Button --}}
        <div class="flex items-center gap-4">

            <button type="submit"
                class="px-5 py-2.5 rounded-xl
                     bg-slate-700 hover:bg-slate-600
                     text-white text-sm font-medium
                       transition-all duration-200">
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-400">
                    Password berhasil diperbarui.
                </p>
            @endif

        </div>

    </form>

</section>
