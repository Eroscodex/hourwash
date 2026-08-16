<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            
            <div>
                <x-input-label for="email" :value="__('Email Address')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-xs mt-2 text-slate-600 dark:text-slate-300">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 rounded-md focus:outline-none">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-xs text-emerald-600 dark:text-emerald-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="09123456789" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        
        <div>
            <x-input-label for="address" :value="__('Street Address / Barangay')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->customerProfile->address ?? '')" placeholder="e.g. Magallanes St., Orosite" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <div>
                <x-input-label for="city" :value="__('City / Municipality')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->customerProfile->city ?? 'Legazpi City')" placeholder="e.g. Legazpi City" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            
            <div>
                <x-input-label for="province" :value="__('Province')" />
                <x-text-input id="province" name="province" type="text" class="mt-1 block w-full" :value="old('province', $user->customerProfile->province ?? 'Albay')" placeholder="e.g. Albay" />
                <x-input-error class="mt-2" :messages="$errors->get('province')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Save Profile Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold"
                >{{ __('Profile updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
