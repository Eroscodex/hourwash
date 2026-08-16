<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                    Manage System Users & Staff Accounts
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Complete directory of customers, staff specialists, store owners, and system administrators.
                </p>
            </div>
            
            <button onclick="document.getElementById('add-user-modal').classList.remove('hidden')" class="btn-ios-primary w-full sm:w-fit text-center flex items-center justify-center gap-1.5 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                + Add Staff / Customer Account
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap min-w-[750px]">
                    <thead class="bg-slate-100 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                        <tr>
                            <th class="px-6 py-3.5">User / Customer Name</th>
                            <th class="px-6 py-3.5">Email Address</th>
                            <th class="px-6 py-3.5">Phone Number</th>
                            <th class="px-6 py-3.5">Physical Address</th>
                            <th class="px-6 py-3.5">Role / Account</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] font-bold flex items-center justify-center text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="text-slate-900 dark:text-white font-bold">{{ $user->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-mono text-xs">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-700 dark:text-slate-300 font-mono text-xs">
                                {{ $user->phone ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-xs max-w-[220px] truncate" title="{{ $user->customerProfile->address ?? '' }}">
                                {{ $user->customerProfile->address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role === 'owner' || $user->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @elseif($user->role === 'staff')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                        Staff Specialist
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.remove('hidden')" class="p-1.5 text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="Edit User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete {{ $user->name }} permanently?')" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div id="edit-user-modal-{{ $user->id }}" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 text-left">
                                    <div class="app-card max-w-md w-full p-6 space-y-4 shadow-2xl">
                                        <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Edit User: {{ $user->name }}</h3>
                                            <button type="button" onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4 text-xs">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                                                <input type="text" name="name" value="{{ $user->name }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                                                <input type="email" name="email" value="{{ $user->email }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                                                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="e.g. 09171234567" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Physical Address</label>
                                                <textarea name="address" rows="2" placeholder="e.g. Magallanes St., Orosite, Legazpi City" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10">{{ $user->customerProfile->address ?? '' }}</textarea>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Account Role</label>
                                                <select name="role" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                    <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                                    <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff Specialist</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Store Owner</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10 dark:border-white/10">
                                                <button type="button" onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.add('hidden')" class="btn-ios-secondary text-xs">Cancel</button>
                                                <button type="submit" class="btn-ios-primary text-xs">Update Account</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-slate-500">
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

    <!-- Add User Modal -->
    <div id="add-user-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="app-card max-w-md w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Register New Staff or Customer Account</h3>
                <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                    <input type="text" name="name" placeholder="e.g. Maria Santos" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="e.g. maria@gmail.com" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. 09171234567" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Physical Address</label>
                    <textarea name="address" rows="2" placeholder="e.g. Magallanes St., Orosite, Legazpi City" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Minimum 8 characters" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Role</label>
                    <select name="role" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                        <option value="customer">Customer</option>
                        <option value="staff">Staff Specialist</option>
                        <option value="admin">Admin</option>
                        <option value="owner">Store Owner</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10 dark:border-white/10">
                    <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="btn-ios-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn-ios-primary text-xs">Save User Account</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>