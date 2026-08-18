<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    User Accounts & Directory
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage store owners, administrators, staff specialists, dispatch riders, and customer accounts.
                </p>
            </div>

            <button onclick="document.getElementById('add-user-modal').classList.remove('hidden')" class="btn-primary w-full sm:w-fit text-center flex items-center justify-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add User Account
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <a href="{{ route('admin.users.index') }}" class="app-card p-4 flex flex-col justify-between hover:border-blue-600 transition">
                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Total Users</span>
                <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1">{{ $totalUsers }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="app-card p-4 flex flex-col justify-between border-rose-500/30 hover:border-rose-500 transition">
                <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">Owners & Admins</span>
                <span class="text-xl font-bold text-rose-700 dark:text-rose-300 font-mono mt-1">{{ $adminCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" class="app-card p-4 flex flex-col justify-between border-amber-500/30 hover:border-amber-500 transition">
                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">Staff Specialists</span>
                <span class="text-xl font-bold text-amber-700 dark:text-amber-300 font-mono mt-1">{{ $staffCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'rider']) }}" class="app-card p-4 flex flex-col justify-between border-cyan-500/30 hover:border-cyan-500 transition">
                <span class="text-[10px] font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-wider block">Riders</span>
                <span class="text-xl font-bold text-cyan-700 dark:text-cyan-300 font-mono mt-1">{{ $riderCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="app-card p-4 flex flex-col justify-between border-sky-500/30 hover:border-sky-500 transition col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold text-sky-600 dark:text-sky-400 uppercase tracking-wider block">Customers</span>
                <span class="text-xl font-bold text-sky-700 dark:text-sky-300 font-mono mt-1">{{ $customerCount }}</span>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="app-card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-1 items-center gap-2">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="relative flex-1 flex items-center">
                    <svg class="w-4 h-4 absolute left-3 text-slate-400 pointer-events-none shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone number..."
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white placeholder-slate-500 focus:border-blue-600 focus:outline-none">
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-lg bg-slate-900 text-white dark:bg-white dark:text-slate-900 font-bold text-xs transition">
                    Search
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-lg bg-rose-500/15 text-rose-600 dark:text-rose-400 font-bold text-xs transition">
                        Clear
                    </a>
                @endif
            </form>

            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-3 py-1.5 rounded-lg font-bold transition {{ !request('role') ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:dark:bg-zinc-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200' }}">
                    All
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'rider']) }}" 
                   class="px-3 py-1.5 rounded-lg font-bold transition {{ request('role') === 'rider' ? 'bg-cyan-600 text-white' : 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30' }}">
                    Riders ({{ $riderCount }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" 
                   class="px-3 py-1.5 rounded-lg font-bold transition {{ request('role') === 'staff' ? 'bg-amber-600 text-white' : 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30' }}">
                    Staff ({{ $staffCount }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" 
                   class="px-3 py-1.5 rounded-lg font-bold transition {{ request('role') === 'customer' ? 'bg-sky-600 text-white' : 'bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30' }}">
                    Customers ({{ $customerCount }})
                </a>
            </div>
        </div>

        <!-- Users Table -->
        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap min-w-[750px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:dark:border-zinc-700">
                        <tr>
                            <th class="px-6 py-3.5">User Name</th>
                            <th class="px-6 py-3.5">Email Address</th>
                            <th class="px-6 py-3.5">Phone Number</th>
                            <th class="px-6 py-3.5">Physical Address</th>
                            <th class="px-6 py-3.5">Role / Account</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-xs flex-shrink-0">
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
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @elseif($user->role === 'staff')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                        Staff Specialist
                                    </span>
                                @elseif($user->role === 'rider')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30">
                                        Rider
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase @if(($user->status ?? 'active') === 'active') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 @else bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 @endif">
                                    {{ strtoupper($user->status ?? 'active') }}
                                </span>
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
                                <div id="edit-user-modal-{{ $user->id }}" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden overflow-y-auto p-4 sm:p-6 text-left flex items-center justify-center">
                                    <div class="app-card max-w-md w-full p-5 sm:p-6 space-y-3.5 shadow-sm max-h-[85vh] overflow-y-auto my-auto">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Edit User: {{ $user->name }}</h3>
                                            <button type="button" onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4 text-xs">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                                                <input type="text" name="name" value="{{ $user->name }}" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                                                <input type="email" name="email" value="{{ $user->email }}" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                                                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="e.g. 09100317744" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Physical Address</label>
                                                <textarea name="address" rows="2" placeholder="e.g. Magallanes St., Orosite, Legazpi City" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">{{ $user->customerProfile->address ?? '' }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Account Role</label>
                                                    <select name="role" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                                                        <option value="rider" {{ $user->role === 'rider' ? 'selected' : '' }}>Rider</option>
                                                        <option value="customer" {{ in_array($user->role, ['customer', 'user']) ? 'selected' : '' }}>Customer</option>
                                                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff Specialist</option>
                                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Store Owner</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Account Status</label>
                                                    <select name="status" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                                        <option value="active" {{ ($user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ ($user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="blocked" {{ ($user->status ?? '') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Reset Password (Optional)</label>
                                                <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                            </div>

                                            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-200 dark:dark:border-zinc-700">
                                                <button type="button" onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.add('hidden')" class="btn-secondary text-xs">Cancel</button>
                                                <button type="submit" class="btn-primary text-xs">Update Account</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-slate-500">
                                No registered users found matching filter.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-200 dark:dark:border-zinc-700">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    <!-- Add User Modal -->
    <div id="add-user-modal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden overflow-y-auto p-4 sm:p-6 text-left flex items-center justify-center">
        <div class="app-card max-w-md w-full p-5 sm:p-6 space-y-3.5 shadow-sm max-h-[85vh] overflow-y-auto my-auto">
            <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Register New User Account</h3>
                <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name</label>
                    <input type="text" name="name" placeholder="e.g. Anthony Cayme" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="e.g. caymeanthony1@gmail.com" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. 09100317744" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Physical Address</label>
                    <textarea name="address" rows="2" placeholder="e.g. Magallanes St., Orosite, Legazpi City" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Minimum 8 characters" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Account Role</label>
                    <select name="role" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                        <option value="rider">Rider</option>
                        <option value="customer" selected>Customer</option>
                        <option value="staff">Staff Specialist</option>
                        <option value="admin">Admin</option>
                        <option value="owner">Store Owner</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-200 dark:dark:border-zinc-700">
                    <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="btn-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn-primary text-xs">Save User Account</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>