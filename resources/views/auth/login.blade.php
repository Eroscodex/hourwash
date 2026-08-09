<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter registered email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="Enter password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-black/15 dark:border-white/15 text-[#007AFF] focus:ring-[#007AFF]" name="remember">
                <span class="ms-2 text-xs text-slate-700 dark:text-slate-300 font-medium">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-slate-500 dark:text-slate-400 hover:text-[#007AFF] transition" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
            <a class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:underline font-semibold" href="{{ route('register') }}">
                {{ __("Don't have an account? Register") }}
            </a>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Log In') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
