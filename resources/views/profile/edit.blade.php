<x-app-layout>

    <div class="space-y-6">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-[#007AFF] dark:text-[#0A84FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                Account & Profile Settings
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Manage your account information, security credentials, and account preferences.</p>
        </div>

        <div class="max-w-4xl space-y-6">

            <!-- Profile Information -->
            <div class="app-card overflow-hidden shadow-xl">
                <div class="p-5 border-b border-black/10 dark:border-white/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">Profile Information</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Update your account's display name, email address, and contact number.</p>
                    </div>
                    <span class="text-[#007AFF] dark:text-[#0A84FF] font-bold text-xs uppercase tracking-wider">Account Data</span>
                </div>

                <div class="p-6">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Password Update -->
            <div class="app-card overflow-hidden shadow-xl">
                <div class="p-5 border-b border-black/10 dark:border-white/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#007AFF] dark:text-[#0A84FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Change Password
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    <span class="text-[#007AFF] dark:text-[#0A84FF] font-bold text-xs uppercase tracking-wider">Security</span>
                </div>

                <div class="p-6">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="app-card overflow-hidden border-rose-500/40 shadow-xl">
                <div class="p-5 border-b border-black/10 dark:border-white/10 bg-rose-500/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-rose-600 dark:text-rose-400 font-['Outfit']">Danger Zone: Delete Account</h3>
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