<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white flex items-center gap-2">
                    <span>👥</span> User Accounts & Roles Directory
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage store owners, system administrators, staff specialists, dispatch riders, and customer profiles.
                </p>
            </div>

            <button onclick="document.getElementById('add-user-modal').classList.remove('hidden')" class="btn-ios-primary w-full sm:w-fit text-center flex items-center justify-center gap-2 shadow-md">
                <span>+</span> Add New Account
            </button>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <a href="{{ route('admin.users.index') }}" class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] transition">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Total Users</span>
                <span class="text-xl font-extrabold text-white font-mono mt-1">{{ $totalUsers }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="app-card p-3.5 flex flex-col justify-between border-rose-500/30 hover:border-rose-500 transition">
                <span class="text-[10px] font-extrabold text-rose-400 uppercase tracking-wider block">Owners & Admins</span>
                <span class="text-xl font-extrabold text-rose-300 font-mono mt-1">{{ $adminCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" class="app-card p-3.5 flex flex-col justify-between border-amber-500/30 hover:border-amber-500 transition">
                <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-wider block">Staff Specialists</span>
                <span class="text-xl font-extrabold text-amber-300 font-mono mt-1">{{ $staffCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'rider']) }}" class="app-card p-3.5 flex flex-col justify-between border-cyan-500/30 hover:border-cyan-500 transition">
                <span class="text-[10px] font-extrabold text-cyan-400 uppercase tracking-wider block">🛵 Riders</span>
                <span class="text-xl font-extrabold text-cyan-300 font-mono mt-1">{{ $riderCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="app-card p-3.5 flex flex-col justify-between border-sky-500/30 hover:border-sky-500 transition col-span-2 sm:col-span-1">
                <span class="text-[10px] font-extrabold text-sky-400 uppercase tracking-wider block">Customers</span>
                <span class="text-xl font-extrabold text-sky-300 font-mono mt-1">{{ $customerCount }}</span>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="app-card p-4 bg-[#1C1C1E] border border-white/10 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-1 items-center gap-2">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone number..."
                           class="w-full pl-9 pr-4 py-2 text-xs rounded-xl bg-black/50 border border-white/10 text-white placeholder-slate-500 focus:border-[#007AFF] focus:outline-none">
                    <span class="absolute left-3 top-2.5 text-slate-500 text-xs">🔍</span>
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                    Search
                </button>
                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 rounded-xl bg-rose-500/20 text-rose-300 hover:bg-rose-500/30 font-bold text-xs transition">
                        Clear
                    </a>
                @endif
            </form>

            <div class="flex flex-wrap items-center gap-1.5 text-xs">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-3 py-1.5 rounded-xl font-bold transition {{ !request('role') ? 'bg-[#007AFF] text-white' : 'bg-white/10 text-slate-300 hover:bg-white/20' }}">
                    All
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'rider']) }}" 
                   class="px-3 py-1.5 rounded-xl font-bold transition {{ request('role') === 'rider' ? 'bg-cyan-600 text-white' : 'bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 hover:bg-cyan-500/25' }}">
                    🛵 Riders ({{ $riderCount }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" 
                   class="px-3 py-1.5 rounded-xl font-bold transition {{ request('role') === 'staff' ? 'bg-amber-600 text-white' : 'bg-amber-500/15 text-amber-300 border border-amber-500/30 hover:bg-amber-500/25' }}">
                    🧼 Staff ({{ $staffCount }})
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" 
                   class="px-3 py-1.5 rounded-xl font-bold transition {{ request('role') === 'customer' ? 'bg-sky-600 text-white' : 'bg-sky-500/15 text-sky-300 border border-sky-500/30 hover:bg-sky-500/25' }}">
                    👤 Customers ({{ $customerCount }})
                </a>
            </div>
        </div>

        <!-- Users Table -->
        <div class="app-card overflow-hidden bg-[#1C1C1E] border border-white/10 rounded-2xl">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[800px]">
                    <thead class="bg-black/50 text-slate-400 uppercase text-[10px] tracking-wider border-b border-white/10">
                        <tr>
                            <th class="px-6 py-3.5">User Account</th>
                            <th class="px-6 py-3.5">Email Address</th>
                            <th class="px-6 py-3.5">Phone Number</th>
                            <th class="px-6 py-3.5">Physical Address</th>
                            <th class="px-6 py-3.5">Role / Type</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 font-bold text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-[#007AFF]/20 text-[#0A84FF] border border-[#007AFF]/30 font-extrabold flex items-center justify-center text-xs flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-white font-bold text-sm block">{{ $user->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-mono">ID #{{ $user->id }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-300 font-mono text-xs">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-300 font-mono text-xs">
                                {{ $user->phone ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs max-w-[220px] truncate" title="{{ $user->customerProfile->address ?? '' }}">
                                {{ $user->customerProfile->address ?? 'Legazpi City Pick-Up / Shop' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role === 'owner' || $user->role === 'admin')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-500/20 text-rose-300 border border-rose-500/40">
                                        👑 {{ strtoupper($user->role) }}
                                    </span>
                                @elseif($user->role === 'staff')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                        🧼 STAFF
                                    </span>
                                @elseif($user->role === 'rider')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">
                                        🛵 RIDER
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-sky-500/20 text-sky-300 border border-sky-500/40">
                                        👤 CUSTOMER
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase
                                    @if(($user->status ?? 'active') === 'active') bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                    @else bg-rose-500/20 text-rose-400 border border-rose-500/30 @endif">
                                    {{ strtoupper($user->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.remove('hidden')" class="px-3 py-1.5 bg-[#007AFF]/20 hover:bg-[#007AFF]/30 text-[#0A84FF] border border-[#007AFF]/40 rounded-xl font-bold text-xs transition" title="Edit User">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete {{ $user->name }} permanently?')" class="px-3 py-1.5 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40 rounded-xl font-bold text-xs transition" title="Delete User">
                                            Delete
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div id="edit-user-modal-{{ $user->id }}" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden flex items-center justify-center p-4 text-left">
                                    <div class="app-card max-w-md w-full p-6 space-y-4 bg-[#1C1C1E] border border-white/10 rounded-2xl shadow-2xl">
                                        <div class="flex items-center justify-between border-b border-white/10 pb-3">
                                            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                                <span>✏️</span> Edit User: {{ $user->name }}
                                            </h3>
                                            <button type="button" onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.add('hidden')" class="text-slate-400 hover:text-white font-bold">✕</button>
                                        </div>

                                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-4 text-xs">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block font-bold text-slate-300 mb-1">Full Name</label>
                                                <input type="text" name="name" value="{{ $user->name }}" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-300 mb-1">Email Address</label>
                                                <input type="email" name="email" value="{{ $user->email }}" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-300 mb-1">Phone Number</label>
                                                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="e.g. 09100317744" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-300 mb-1">Physical Address</label>
                                                <textarea name="address" rows="2" placeholder="e.g. Magallanes St., Orosite, Legazpi City" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">{{ $user->customerProfile->address ?? '' }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-bold text-slate-300 mb-1">Account Role</label>
                                                    <select name="role" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                                                        <option value="rider" {{ $user->role === 'rider' ? 'selected' : '' }}>🛵 Rider Logistics</option>
                                                        <option value="customer" {{ in_array($user->role, ['customer', 'user']) ? 'selected' : '' }}>👤 Customer</option>
                                                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>🧼 Staff Specialist</option>
                                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>🛡️ Admin</option>
                                                        <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>👑 Store Owner</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block font-bold text-slate-300 mb-1">Account Status</label>
                                                    <select name="status" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">
                                                        <option value="active" {{ ($user->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ ($user->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        <option value="blocked" {{ ($user->status ?? '') === 'blocked' ? 'selected' : '' }}>Blocked</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block font-bold text-slate-300 mb-1">Reset Password (Optional)</label>
                                                <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">
                                            </div>

                                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-white/10">
                                                <button type="button" onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">Cancel</button>
                                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#007AFF] hover:bg-[#0056b3] text-white font-bold text-xs shadow-md transition">Update Account</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                No registered user accounts found matching filter.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-white/10">
                {{ $users->links() }}
            </div>
        </div>

    </div>

    <!-- Add User Modal -->
    <div id="add-user-modal" class="fixed inset-0 bg-black/70 backdrop-blur-md z-50 hidden flex items-center justify-center p-4 text-left">
        <div class="app-card max-w-md w-full p-6 space-y-4 bg-[#1C1C1E] border border-white/10 rounded-2xl shadow-2xl">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>👤</span> Register New User Account
                </h3>
                <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-white font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Full Name</label>
                    <input type="text" name="name" placeholder="e.g. Anthony Cayme" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="e.g. caymeanthony1@gmail.com" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. 09100317744" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none">
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Physical Address</label>
                    <textarea name="address" rows="2" placeholder="e.g. Magallanes St., Orosite, Legazpi City" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" placeholder="Minimum 8 characters" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                </div>

                <div>
                    <label class="block font-bold text-slate-300 mb-1">Account Role</label>
                    <select name="role" class="w-full p-2.5 rounded-xl bg-black/50 border border-white/10 text-white focus:border-[#007AFF] focus:outline-none" required>
                        <option value="rider">🛵 Rider Logistics</option>
                        <option value="customer" selected>👤 Customer</option>
                        <option value="staff">🧼 Staff Specialist</option>
                        <option value="admin">🛡️ Admin</option>
                        <option value="owner">👑 Store Owner</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-white/10">
                    <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#007AFF] hover:bg-[#0056b3] text-white font-bold text-xs shadow-md transition">Save User Account</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>