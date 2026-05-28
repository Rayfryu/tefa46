<section>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- CURRENT PASSWORD --}}
        <div x-data="{ show: false }" class="relative">

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Password Saat Ini
            </label>

            <input name="current_password" :type="show ? 'text' : 'password'" autocomplete="current-password"
                class="w-full rounded-xl border border-slate-200 bg-white
                       text-slate-900 pr-12
                       focus:border-blue-500 focus:ring-blue-500
                       transition-all duration-200">

            <button type="button" @click="show = !show"
                class="absolute right-3 top-[42px] text-slate-400 hover:text-slate-700">

                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>

                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>

            </button>

            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-2 text-sm text-red-500">
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        {{-- NEW PASSWORD --}}
        <div x-data="{ show: false }" class="relative">

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Password Baru
            </label>

            <input name="password" :type="show ? 'text' : 'password'" autocomplete="new-password"
                class="w-full rounded-xl border border-slate-200 bg-white
                       text-slate-900 pr-12
                       focus:border-blue-500 focus:ring-blue-500
                       transition-all duration-200">

            <button type="button" @click="show = !show"
                class="absolute right-3 top-[42px] text-slate-400 hover:text-slate-700">

                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>

                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>

            </button>

            @if ($errors->updatePassword->get('password'))
                <p class="mt-2 text-sm text-red-500">
                    {{ $errors->updatePassword->first('password') }}
                </p>
            @endif
        </div>

        {{-- CONFIRM PASSWORD --}}
        <div x-data="{ show: false }" class="relative">

            <label class="block text-sm font-medium text-slate-700 mb-2">
                Konfirmasi Password
            </label>

            <input name="password_confirmation" :type="show ? 'text' : 'password'" autocomplete="new-password"
                class="w-full rounded-xl border border-slate-200 bg-white
                       text-slate-900 pr-12
                       focus:border-blue-500 focus:ring-blue-500
                       transition-all duration-200">

            <button type="button" @click="show = !show"
                class="absolute right-3 top-[42px] text-slate-400 hover:text-slate-700">

                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>

                <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>

            </button>

            @if ($errors->updatePassword->get('password_confirmation'))
                <p class="mt-2 text-sm text-red-500">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </p>
            @endif
        </div>

        {{-- BUTTON --}}
        <div class="flex items-center gap-4">

            <button type="submit"
                class="px-5 py-2.5 rounded-xl
                       bg-blue-600 hover:bg-blue-700
                       text-white text-sm font-medium transition-all">

                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600">

                    Password berhasil diperbarui.
                </p>
            @endif

        </div>

    </form>

</section>
