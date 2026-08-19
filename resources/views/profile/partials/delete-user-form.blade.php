<section class="space-y-6">
    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="btn-danger text-xs py-2 px-4 shadow-sm"
    >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg">
            @csrf
            @method('delete')

            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-4">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 rounded-md text-xs focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 placeholder-slate-400 dark:placeholder-zinc-500"
                    placeholder="{{ __('Enter your password to confirm') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs text-rose-500" />
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="btn-secondary text-xs py-2 px-4 shadow-sm"
                >
                    {{ __('Cancel') }}
                </button>

                <button
                    type="submit"
                    class="btn-danger text-xs py-2 px-4 shadow-sm"
                >
                    {{ __('Permanently Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

