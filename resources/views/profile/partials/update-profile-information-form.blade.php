<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    Full Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                       required 
                       autofocus 
                       autocomplete="name">
                <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('name')" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    Email Address <span class="text-rose-500">*</span>
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                       required 
                       autocomplete="username">
                <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-xs text-slate-600 dark:text-zinc-400">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-xs text-blue-600 dark:text-blue-400 hover:opacity-80 rounded-md focus:outline-none font-semibold">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-semibold text-xs text-emerald-600 dark:text-emerald-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Phone Number -->
        <div>
            <label for="phone" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                Phone Number
            </label>
            <input type="text" 
                   id="phone" 
                   name="phone" 
                   value="{{ old('phone', $user->phone) }}" 
                   placeholder="e.g. 09XXXXXXXXX" 
                   class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm">
            <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('phone')" />
        </div>

        <!-- House No. / Street Name -->
        <div>
            <label for="address" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                House No. / Street Name
            </label>
            <input type="text" 
                   id="address" 
                   name="address" 
                   value="{{ old('address', $user->customerProfile->address ?? '') }}" 
                   placeholder="e.g. #123 Magallanes St., Sampaguita Village" 
                   class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm">
            <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('address')" />
        </div>

        <!-- Grid: Barangay, City, Province -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Barangay -->
            <div>
                <label for="barangay" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    Barangay
                </label>
                <input type="text" 
                       id="barangay" 
                       name="barangay" 
                       value="{{ old('barangay', $user->customerProfile->barangay ?? '') }}" 
                       placeholder="e.g. Brgy. Orosite" 
                       class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm">
                <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('barangay')" />
            </div>

            <!-- City / Municipality -->
            <div>
                <label for="city" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    City / Municipality
                </label>
                <input type="text" 
                       id="city" 
                       name="city" 
                       value="{{ old('city', $user->customerProfile->city ?? 'Legazpi City') }}" 
                       placeholder="e.g. Legazpi City" 
                       class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm">
                <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('city')" />
            </div>

            <!-- Province -->
            <div>
                <label for="province" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                    Province
                </label>
                <input type="text" 
                       id="province" 
                       name="province" 
                       value="{{ old('province', $user->customerProfile->province ?? 'Albay') }}" 
                       placeholder="e.g. Albay" 
                       class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm">
                <x-input-error class="mt-1.5 text-xs text-rose-500" :messages="$errors->get('province')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-3 border-t border-slate-200 dark:border-zinc-800">
            <button type="submit" class="btn-primary py-2.5 px-6 text-xs font-bold shadow-sm">
                Save Profile Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                    ✓ Profile updated successfully.
                </p>
            @endif
        </div>
    </form>
</section>
