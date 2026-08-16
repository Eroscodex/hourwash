<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="e.g. Maria Santos" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" placeholder="09123456789" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimum 8 characters" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
            <a class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:underline font-semibold" href="{{ route('login') }}">
                {{ __('Already registered? Log In') }}
            </a>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Register Account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
