<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf


        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter registered email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>


        <div>
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input
                    id="password"
                    class="block mt-1 w-full pe-16"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter password"
                />
                <button
                    type="button"
                    id="toggle-password"
                    class="absolute inset-y-0 end-0 px-3 text-sm text-slate-500"
                >
                    Show
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <script>
            document.getElementById('toggle-password').addEventListener('click', function () {
                const password = document.getElementById('password');
                const isHidden = password.type === 'password';

                password.type = isHidden ? 'text' : 'password';
                this.textContent = isHidden ? 'Hide' : 'Show';
            });
        </script>


        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-600" name="remember">
                <span class="ms-2 text-xs text-slate-700 dark:text-zinc-300 font-medium">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-slate-500 dark:text-zinc-400 hover:text-blue-600 transition" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
            <a class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold" href="{{ route('register') }}">
                {{ __("Don't have an account? Register") }}
            </a>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Log In') }}
            </x-primary-button>
        </div>

    </form>
</x-guest-layout>
