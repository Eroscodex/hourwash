<x-app-layout>

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Page Header -->
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Account & Profile Settings
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-1">
                Manage your account credentials, personal information, contact address, and security credentials.
            </p>
        </div>

        <!-- User Profile Summary Header Card -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-600/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 flex items-center justify-center font-extrabold text-xl border border-blue-600/20 shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 border border-blue-500/20">
                            {{ ucfirst(Auth::user()->role ?? 'user') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="text-xs text-slate-400 dark:text-zinc-500 font-mono sm:text-right">
                <span>Member Since: {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : '2026' }}</span>
            </div>
        </div>

        <!-- Section 1: Profile Information -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/30 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Personal Information & Address</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Update your display name, contact phone number, and delivery address details.</p>
                </div>
                <span class="text-blue-600 dark:text-blue-400 font-bold text-[11px] uppercase tracking-wider hidden sm:inline">Profile Data</span>
            </div>

            <div class="p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Section 2: Security & Password -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4.5 border-b border-slate-200 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-900/30 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Security & Password</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5">Ensure your account is protected with a long, random password.</p>
                </div>
                <span class="text-blue-600 dark:text-blue-400 font-bold text-[11px] uppercase tracking-wider hidden sm:inline">Security</span>
            </div>

            <div class="p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Section 3: Danger Zone -->
        <div class="bg-white dark:bg-[#141417] border border-rose-500/30 dark:border-rose-500/20 rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4.5 border-b border-rose-500/20 bg-rose-500/5 dark:bg-rose-500/10 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-rose-600 dark:text-rose-400">Danger Zone</h3>
                    <p class="text-xs text-rose-600/80 dark:text-rose-300/80 mt-0.5">Permanently remove your account and all associated order history.</p>
                </div>
                <span class="text-rose-600 dark:text-rose-400 font-bold text-[11px] uppercase tracking-wider hidden sm:inline">Permanent Action</span>
            </div>

            <div class="p-6 sm:p-8">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
