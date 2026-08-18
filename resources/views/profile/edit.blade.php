<x-app-layout>

    <div class="space-y-6">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                Account & Profile Settings
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Manage your account information, security credentials, and account preferences.</p>
        </div>

        <div class="max-w-4xl space-y-6">

            
            <div class="app-card overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-200 dark:dark:border-zinc-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Profile Information</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Update your account's display name, email address, and contact number.</p>
                    </div>
                    <span class="text-blue-600 dark:text-blue-400 font-bold text-xs uppercase tracking-wider">Account Data</span>
                </div>

                <div class="p-6">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            
            <div class="app-card overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-200 dark:dark:border-zinc-700 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">
                            Change Password
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    <span class="text-blue-600 dark:text-blue-400 font-bold text-xs uppercase tracking-wider">Security</span>
                </div>

                <div class="p-6">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            
            <div class="app-card overflow-hidden border-rose-500/40 shadow-sm">
                <div class="p-5 border-b border-slate-200 dark:dark:border-zinc-700 bg-rose-500/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-rose-600 dark:text-rose-400">Danger Zone: Delete Account</h3>
                        <p class="text-xs text-rose-600/80 dark:text-rose-300/80">Permanently remove your account and all associated data.</p>
                    </div>
                    <span class="text-rose-600 dark:text-rose-400 font-bold text-xs uppercase tracking-wider">Permanent</span>
                </div>

                <div class="p-6">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>