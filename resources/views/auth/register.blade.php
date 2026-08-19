<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        
        <!-- Full Name -->
        <div>
            <x-input-label for="name" :value="__('Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="e.g. Maria Santos" minlength="3" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email & Phone Number Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required placeholder="09123456789" />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
        </div>

        <!-- House No / Street Name & Barangay Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <x-input-label for="address" :value="__('House No. / Street Name')" />
                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required placeholder="e.g. #123 Magallanes St." />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="barangay" :value="__('Barangay')" />
                <x-text-input id="barangay" class="block mt-1 w-full" type="text" name="barangay" :value="old('barangay')" required placeholder="e.g. Brgy. Orosite" />
                <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
            </div>
        </div>

        <!-- City & Province Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <x-input-label for="city" :value="__('City / Municipality')" />
                <x-text-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required placeholder="e.g. Legazpi City" />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="province" :value="__('Province')" />
                <x-text-input id="province" class="block mt-1 w-full" type="text" name="province" :value="old('province')" required placeholder="e.g. Albay" />
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>
        </div>

        <!-- Password & Confirm Password Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 chars with symbols" minlength="8" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
            <a class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-semibold" href="{{ route('login') }}">
                {{ __('Already registered? Log In') }}
            </a>

            <x-primary-button class="w-full sm:w-auto">
                {{ __('Register Account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
