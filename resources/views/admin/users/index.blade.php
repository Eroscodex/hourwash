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
            <a href="{{ route('admin.users.index') }}" class="card-accent-blue p-4 flex items-center justify-between shadow-sm hover:border-blue-600 transition">
                <div>
                    <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">Total Users</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">All Accounts</span>
                </div>
                <span class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ $totalUsers }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="card-accent-rose p-4 flex items-center justify-between shadow-sm hover:border-rose-600 transition">
                <div>
                    <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">Owners & Admins</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Management</span>
                </div>
                <span class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono">{{ $adminCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'staff']) }}" class="card-accent-amber p-4 flex items-center justify-between shadow-sm hover:border-amber-600 transition">
                <div>
                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">Staff Specialists</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Operators</span>
                </div>
                <span class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">{{ $staffCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'rider']) }}" class="card-accent-purple p-4 flex items-center justify-between shadow-sm hover:border-purple-600 transition">
                <div>
                    <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider block">Riders</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Dispatch</span>
                </div>
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400 font-mono">{{ $riderCount }}</span>
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="card-accent-emerald p-4 flex items-center justify-between shadow-sm hover:border-emerald-600 transition col-span-2 sm:col-span-1">
                <div>
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Customers</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Registered</span>
                </div>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $customerCount }}</span>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="app-card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-1 items-center gap-2">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <div class="relative flex-1 flex items-center">
                    <svg class="w-4 h-4 absolute left-3 text-slate-400 pointer-events-none shrink-0 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone number..."
                        class="w-full !pl-9 pr-4 py-2 text-xs rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 text-slate-900 dark:text-white placeholder-slate-500 focus:border-blue-600 focus:outline-none"
                        style="padding-left: 2.25rem !important;">
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
                <table class="w-full text-left text-[11px] whitespace-nowrap min-w-[750px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[9.5px] font-extrabold tracking-wider border-b border-slate-200 dark:dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-2.5">User Name</th>
                            <th class="px-4 py-2.5">Email Address</th>
                            <th class="px-4 py-2.5">Phone Number</th>
                            <th class="px-4 py-2.5">Physical Address</th>
                            <th class="px-4 py-2.5">Role / Account</th>
                            <th class="px-4 py-2.5">Status</th>
                            <th class="px-4 py-2.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <td class="px-4 py-2 font-bold text-slate-900 dark:text-white flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-[10.5px] shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="text-[11.5px] font-bold text-slate-900 dark:text-white">{{ $user->name }}</span>
                            </td>
                            <td class="px-4 py-2 text-slate-700 dark:text-slate-300 font-mono text-[11px]">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-slate-700 dark:text-slate-300 font-mono text-[11px]">
                                {{ $user->phone ?: 'N/A' }}
                            </td>
                            <td class="px-4 py-2 text-slate-600 dark:text-slate-400 text-[11px] max-w-[220px] truncate" title="{{ $user->customerProfile->address ?? '' }}">
                                {{ $user->customerProfile->address ?? 'N/A' }}
                            </td>
                            <td class="px-4 py-2">
                                @if($user->role === 'owner' || $user->role === 'admin')
                                    <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @elseif($user->role === 'staff')
                                    <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                        Staff Specialist
                                    </span>
                                @elseif($user->role === 'rider')
                                    <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold uppercase tracking-wider bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30">
                                        Rider
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md text-[9.5px] font-bold uppercase tracking-wider bg-sky-500/15 text-sky-700 dark:text-sky-300 border border-sky-500/30">
                                        Customer
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-0.5 rounded text-[9.5px] font-bold uppercase @if(($user->status ?? 'active') === 'active') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 @else bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 @endif">
                                    {{ strtoupper($user->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'manage-stamps-{{ $user->id }}')" class="p-1 text-pink-600 dark:text-pink-400 hover:bg-pink-500/10 rounded-lg transition" title="Manage Frequent User Stamp Card">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </button>

                                    <button onclick="document.getElementById('edit-user-modal-{{ $user->id }}').classList.remove('hidden')" class="p-1 text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="Edit User">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'delete-user-{{ $user->id }}')" class="p-1 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete User">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>

                                    <!-- Manage Stamp Card Modal -->
                                    <x-modal name="manage-stamps-{{ $user->id }}" maxWidth="md">
                                        <form method="POST" action="{{ route('admin.users.stamps.update', $user->id) }}" class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-3">
                                                <h2 class="text-sm font-bold text-pink-600 dark:text-pink-400 flex items-center gap-2">
                                                    Manage Loyalty Stamp Card ({{ $user->name }})
                                                </h2>
                                                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-slate-600">✕</button>
                                            </div>

                                            <div class="space-y-3 text-xs">
                                                <div>
                                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Active Stamp Count (0 to 12)</label>
                                                    <input type="number" name="stamps_count" value="{{ $user->stamps_count ?? 0 }}" min="0" max="12" class="w-full">
                                                    <p class="text-[10.5px] text-slate-500 dark:text-slate-400 mt-1">
                                                        Current progress on active Frequent User Card.
                                                    </p>
                                                </div>

                                                <div>
                                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Discount Rewards Available (₱50.00 OFF Tokens)</label>
                                                    <input type="number" name="discount_rewards_available" value="{{ $user->discount_rewards_available ?? 0 }}" min="0" max="99" class="w-full">
                                                    <p class="text-[10.5px] text-slate-500 dark:text-slate-400 mt-1">
                                                        Number of unlocked ₱50.00 OFF loyalty discount rewards available for redemption.
                                                    </p>
                                                </div>

                                                <div>
                                                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Completed Cards History (Total 12-Stamp Cards)</label>
                                                    <input type="number" name="completed_cards_count" value="{{ $user->completed_cards_count ?? 0 }}" min="0" max="999" class="w-full">
                                                    <p class="text-[10.5px] text-slate-500 dark:text-slate-400 mt-1">
                                                        Total lifetime completed 12-stamp cards earned by this customer.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn-primary text-xs py-1.5 px-3 bg-pink-600 hover:bg-pink-700">
                                                    Save Stamp Changes
                                                </button>
                                            </div>
                                        </form>
                                    </x-modal>

                                    <x-modal name="delete-user-{{ $user->id }}" maxWidth="sm">
                                        <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Delete User Account?</h2>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                                Are you sure you want to delete user <strong>{{ $user->name }}</strong> ({{ $user->email }}) permanently?
                                            </p>
                                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                                    Cancel
                                                </button>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                                        Delete User
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>
                                </div>

                                <!-- Edit Modal -->
                                <div id="edit-user-modal-{{ $user->id }}" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden overflow-y-auto p-4 sm:p-6 text-left flex items-center justify-center">
                                    <div class="app-card max-w-lg w-full p-5 sm:p-6 space-y-3.5 shadow-sm max-h-[85vh] overflow-y-auto my-auto">
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

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address</label>
                                                    <input type="email" name="email" value="{{ $user->email }}" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                                                    <input type="text" name="phone" value="{{ $user->phone }}" placeholder="e.g. 09XXXXXXXXX" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">House No. / Street Name</label>
                                                    <input type="text" name="address" value="{{ $user->customerProfile->address ?? '' }}" placeholder="e.g. #123 Magallanes St." class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Barangay</label>
                                                    <input type="text" name="barangay" value="{{ $user->customerProfile->barangay ?? '' }}" placeholder="e.g. Brgy. Orosite" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">City / Municipality</label>
                                                    <input type="text" name="city" value="{{ $user->customerProfile->city ?? '' }}" placeholder="e.g. Legazpi City" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                                </div>
                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Province</label>
                                                    <input type="text" name="province" value="{{ $user->customerProfile->province ?? '' }}" placeholder="e.g. Albay" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                                                </div>
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
    <div id="add-user-modal" onclick="if(event.target === this) this.classList.add('hidden')" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 {{ $errors->any() ? '' : 'hidden' }} overflow-y-auto p-4 sm:p-6 text-left flex items-center justify-center">
        <div class="app-card max-w-lg w-full p-5 sm:p-6 space-y-3.5 shadow-sm max-h-[85vh] overflow-y-auto my-auto relative">
            <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Register New User Account</h3>
                <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold p-1 rounded">✕</button>
            </div>

            @if($errors->any())
                <div class="p-3 rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-semibold space-y-1">
                    <p class="font-bold">Please fix the following validation errors:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Your Name" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required minlength="3">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. name@example.com" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. 09XXXXXXXXX" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">House No. / Street Name</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. #123 Magallanes St." class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Barangay</label>
                        <input type="text" name="barangay" value="{{ old('barangay') }}" placeholder="e.g. Brgy. Orosite" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">City / Municipality</label>
                        <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Legazpi City" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Province</label>
                        <input type="text" name="province" value="{{ old('province') }}" placeholder="e.g. Albay" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimum 8 characters" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required minlength="8">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Account Role <span class="text-rose-500">*</span></label>
                    <select name="role" class="w-full p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 text-slate-900 dark:text-white" required>
                        <option value="rider" {{ old('role') == 'rider' ? 'selected' : '' }}>Rider</option>
                        <option value="customer" {{ old('role', 'customer') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff Specialist</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Store Owner</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-200 dark:dark:border-zinc-700">
                    <button type="button" onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="btn-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn-primary text-xs">Save User Account</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const addModal = document.getElementById('add-user-modal');
                if (addModal && !addModal.classList.contains('hidden')) {
                    addModal.classList.add('hidden');
                }
            }
        });
    </script>

</x-app-layout>
