<section>
    <form method="post" action="{{ route('password.change') }}" class="space-y-5">
        @csrf
        @method('put')

    <!-- Current Password -->
    <div>
        <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
            Current Password <span class="text-rose-500">*</span>
        </label>

        <div class="relative">
            <input type="password"
                id="update_password_current_password"
                name="current_password"
                class="w-full pe-16 bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm"
                autocomplete="current-password"
                required>

            <button type="button"
                    data-toggle-password="update_password_current_password"
                    class="absolute inset-y-0 end-0 px-3 text-xs text-slate-500">
                Show
            </button>
        </div>

        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-rose-500" />
    </div>

    <!-- New Password -->
    <div>
        <label for="update_password_password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
            New Password <span class="text-rose-500">*</span>
        </label>

        <div class="relative">
            <input type="password"
                id="update_password_password"
                name="password"
                class="w-full pe-16 bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm"
                autocomplete="new-password"
                required>

            <button type="button"
                    data-toggle-password="update_password_password"
                    class="absolute inset-y-0 end-0 px-3 text-xs text-slate-500">
                Show
            </button>
        </div>

        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-rose-500" />
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
            Confirm New Password <span class="text-rose-500">*</span>
        </label>

        <div class="relative">
            <input type="password"
                id="update_password_password_confirmation"
                name="password_confirmation"
                class="w-full pe-16 bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm"
                autocomplete="new-password"
                required>

            <button type="button"
                    data-toggle-password="update_password_password_confirmation"
                    class="absolute inset-y-0 end-0 px-3 text-xs text-slate-500">
                Show
            </button>
        </div>

        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-rose-500" />
    </div>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                const passwordInput = document.getElementById(this.dataset.togglePassword);
                const isHidden = passwordInput.type === 'password';

                passwordInput.type = isHidden ? 'text' : 'password';
                this.textContent = isHidden ? 'Hide' : 'Show';
            });
        });
    </script>

        <div class="flex items-center gap-4 pt-3 border-t border-slate-200 dark:border-zinc-800">
            <button type="submit" class="btn-primary py-2.5 px-6 text-xs font-bold shadow-sm">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                    ✓ Password updated successfully.
                </p>
            @endif
        </div>
    </form>
</section>
