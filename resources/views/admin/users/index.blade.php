<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Manage System Users</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Overview of registered customers, staff members, and system administrators.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap min-w-[500px]">
                    <thead class="bg-slate-100 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                        <tr>
                            <th class="px-6 py-3.5">User Name</th>
                            <th class="px-6 py-3.5">Email Address</th>
                            <th class="px-6 py-3.5">Role</th>
                            <th class="px-6 py-3.5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="text-slate-900 dark:text-white">{{ $user->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @if($user->role === 'owner' || $user->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @elseif($user->role === 'staff')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                        Staff
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this user account permanently?')" class="text-rose-600 dark:text-rose-400 hover:underline text-xs font-semibold transition">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-slate-500">
                                No registered users found.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-black/10 dark:border-white/10">
                {{ $users->links() }}
            </div>
        </div>

    </div>

</x-app-layout>