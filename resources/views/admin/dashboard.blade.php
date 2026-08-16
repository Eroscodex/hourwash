<x-app-layout>
    <div class="space-y-6 sm:space-y-8" x-data="{ mode: 'overall' }">

        <!-- Mode Switcher Tab Bar -->
        <div class="flex items-center gap-2 p-1.5 bg-slate-200 dark:bg-[#1C1C1E] rounded-2xl border border-black/5 dark:border-white/10 w-fit">
            <button @click="mode = 'overall'" 
                    :class="mode === 'overall' ? 'bg-[#007AFF] text-white shadow-sm font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                    class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2">
                <span>📊</span> Overall System Dashboard
            </button>
            <button @click="mode = 'rider'" 
                    :class="mode === 'rider' ? 'bg-[#007AFF] text-white shadow-sm font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                    class="px-4 py-2 rounded-xl text-xs transition flex items-center gap-2 relative">
                <span>🛵</span> Rider Dispatch View
                @if(($outForPickup + $outForDelivery) > 0)
                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-amber-500 text-white font-extrabold">
                        {{ $outForPickup + $outForDelivery }}
                    </span>
                @endif
            </button>
        </div>

        <!-- OVERALL DASHBOARD CONTAINER -->
        <div x-show="mode === 'overall'" class="space-y-6 sm:space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white font-['Outfit']">
                    Overall Reports & System Dashboard
                </h1>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Central operational hub, system analytics, and quick navigation shortcuts.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <form method="POST" action="{{ route('admin.orders.reset') }}" class="inline" onsubmit="return confirm('⚠️ ARE YOU SURE YOU WANT TO RESET ALL ORDERS?\n\nThis will permanently delete all order history and set all machines to idle status.')">
                    @csrf
                    <button type="submit" class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-3 py-2 rounded-xl text-xs font-bold transition w-full sm:w-auto">
                        Reset All Orders
                    </button>
                </form>

                <a href="{{ route('admin.laundry.index') }}"
                   class="btn-ios-secondary text-center">
                    View Orders
                </a>

                <a href="{{ route('admin.machines.create') }}"
                   class="btn-ios-primary text-center">
                    + Add New Machine
                </a>
            </div>
        </div>

        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Quick Navbar Selection Shortcuts
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">

                <a href="{{ route('admin.laundry.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-[#007AFF]/15 text-[#007AFF] flex items-center justify-center text-sm font-bold">
                            🧺
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Laundry Orders
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            {{ $totalLaundry ?? 0 }} total orders
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.machines.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-teal-500/15 text-teal-600 flex items-center justify-center text-sm font-bold">
                            ⚙️
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Machine Monitor
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            {{ $totalMachines ?? 20 }} machines
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.services.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-500/15 text-indigo-600 flex items-center justify-center text-sm font-bold">
                            🏷️
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Services & Pricing
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            Manage rates
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-sky-500/15 text-sky-600 flex items-center justify-center text-sm font-bold">
                            👥
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Staff & Customers
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            {{ $totalUsers ?? 0 }} accounts
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.inventory.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-amber-500/15 text-amber-600 flex items-center justify-center text-sm font-bold">
                            📦
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Store Inventory
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            Stock levels
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.sms.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-500/15 text-emerald-600 flex items-center justify-center text-sm font-bold">
                            📱
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Live SMS Outbox
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            {{ $smsCount ?? 0 }} sent
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.emails.index') }}" 
                   class="app-card p-3.5 flex flex-col justify-between hover:border-[#007AFF] hover:shadow-md hover:scale-[1.02] active:scale-95 transition-all group">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-purple-500/15 text-purple-600 flex items-center justify-center text-sm font-bold">
                            ✉️
                        </span>
                        <span class="text-[9px] font-extrabold text-[#007AFF] dark:text-[#0A84FF]">GO</span>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-[#007AFF] transition block truncate">
                            Live Email Outbox
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                            {{ $emailCount ?? 0 }} sent
                        </span>
                    </div>
                </a>

            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        TODAY'S ORDERS
                    </span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        {{ $totalToday ?? 0 }}
                    </div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                        ↑ Active store queue
                    </span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        IN PROCESSING
                    </span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        {{ $inProgress ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">
                        Active machine cycles
                    </span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">STAFF COUNT</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        {{ $staffCount ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Active staff members</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">REGISTERED CUSTOMERS</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        {{ $customerCount ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Total registered accounts</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">PROFIT (PAID)</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        ₱{{ number_format($profitTotal ?? 0, 2) }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Total revenue from paid orders</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">TOTAL MACHINES</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        {{ $totalMachines ?? 20 }}
                    </div>
                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                        {{ $availableMachines ?? 0 }} Available (Idle)
                    </span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">TOTAL SYSTEM ORDERS</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-[#007AFF] dark:text-[#0A84FF]">
                        {{ $totalLaundry ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Lifetime processed orders</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">COMPLETED TODAY</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                        {{ $completedToday ?? 0 }}
                    </div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                        ↑ Completed today
                    </span>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 gap-6">
            <div class="app-card p-4 sm:p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            Machine Status Monitor
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Real-time status of commercial washers & dryers
                        </p>
                    </div>
                    <a href="{{ route('admin.machines.index') }}"
                       class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">
                        Manage Machines
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
                    @forelse($machines as $machine)
                        @php
                            $statusClass = match($machine->status) {
                                'washing' => 'bg-teal-500/15 text-teal-700 dark:text-teal-300',
                                'rinsing' => 'bg-sky-500/15 text-sky-700 dark:text-sky-300',
                                'drying' => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-300',
                                'idle' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300',
                                default => 'bg-amber-500/15 text-amber-700 dark:text-amber-300',
                            };
                            $statusTextClass = match($machine->status) {
                                'washing' => 'text-teal-600 dark:text-teal-400',
                                'rinsing' => 'text-sky-600 dark:text-sky-400',
                                'drying' => 'text-indigo-600 dark:text-indigo-400',
                                'idle' => 'text-emerald-600 dark:text-emerald-400',
                                default => 'text-amber-600 dark:text-amber-400',
                            };
                        @endphp

                        @if($machine->currentOrder)
                            <a href="{{ route('laundry.track', $machine->currentOrder->order_number) }}" 
                               class="block p-3.5 rounded-xl bg-[#007AFF]/5 dark:bg-[#0A84FF]/10 border border-[#007AFF]/40 space-y-2 hover:border-[#007AFF] hover:shadow-lg hover:scale-[1.02] active:scale-95 transition-all cursor-pointer group" 
                               title="Click to view order #{{ $machine->currentOrder->order_number }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-[#007AFF] transition">
                                        {{ $machine->machine_name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex-shrink-0">
                                        {{ $machine->machine_code }}
                                    </span>
                                </div>

                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold {{ $statusClass }}">
                                    <img src="{{ asset('favicon.svg') }}" alt="HourWash" class="w-5 h-5 object-contain" />
                                </div>

                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider block {{ $statusTextClass }}">
                                        {{ strtoupper($machine->status) }}
                                    </span>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 space-y-0.5">
                                        @if(in_array($machine->status, ['washing', 'rinsing', 'drying']) && $machine->remaining_minutes)
                                            <span>⏱ {{ $machine->remaining_minutes }} mins remaining</span>
                                        @elseif($machine->currentOrder->order_status === 'finish')
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓ Finish & Shelved</span>
                                        @else
                                            <span class="text-amber-600 dark:text-amber-400 font-bold">⏳ Order Received</span>
                                        @endif
                                        <span class="block text-[9.5px] font-bold text-[#007AFF] dark:text-[#0A84FF] mt-1 group-hover:underline truncate">
                                            Order: #{{ $machine->currentOrder->order_number }}
                                        </span>
                                        <span class="block text-[9px] font-semibold text-slate-700 dark:text-slate-300 truncate">
                                            👤 {{ $machine->currentOrder->customer->name ?? 'Customer' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-2 opacity-85 select-none" 
                                 title="No active order for {{ $machine->machine_name }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate">
                                        {{ $machine->machine_name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex-shrink-0">
                                        {{ $machine->machine_code }}
                                    </span>
                                </div>

                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold {{ $statusClass }}">
                                    <img src="{{ asset('favicon.svg') }}" alt="HourWash" class="w-5 h-5 object-contain" />
                                </div>

                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider block {{ $statusTextClass }}">
                                        {{ strtoupper($machine->status) }}
                                    </span>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        @if(in_array($machine->status, ['washing', 'rinsing', 'drying']))
                                            <span>⏱ {{ $machine->remaining_minutes ?? 30 }} mins remaining</span>
                                            <span class="block text-[9px] text-[#007AFF] dark:text-[#0A84FF] font-bold mt-0.5">
                                                Est. Finish: {{ now()->addMinutes($machine->remaining_minutes ?? 30)->format('h:i A') }}
                                            </span>
                                        @elseif($machine->status === 'maintenance')
                                            <span class="text-amber-600 dark:text-amber-400 font-semibold">⚠ Maintenance</span>
                                        @elseif($machine->status === 'offline')
                                            <span class="text-rose-600 dark:text-rose-400 font-semibold">🚫 Offline</span>
                                        @else
                                            Available
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full text-center py-6 text-xs text-slate-500">
                            No machines configured.
                        </div>
                    @endforelse
                </div>

                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[11px] text-slate-500 dark:text-slate-400 border-t border-black/5 dark:border-white/10 pt-4">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>Washing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>Rinsing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>Drying</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Idle</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Maintenance</span>
                </div>
            </div>
        </div>

        <div class="app-card p-4 sm:p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        Overall System Reports & Order Stage Breakdown
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Comprehensive summary of order distribution across laundry lifecycle stages
                    </p>
                </div>
                <span class="px-3 py-1 rounded-full bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-xs font-bold">
                    {{ count($laundryStatus ?? []) }} Stages
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-black/5 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                        <tr>
                            <th class="text-left px-4 py-3">Stage Status</th>
                            <th class="text-left px-4 py-3">Total Orders Count</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                        @forelse($laundryStatus ?? [] as $status)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 text-slate-900 dark:text-slate-200 capitalize font-medium">
                                    {{ $status->status === 'finish' ? 'Finish' : str_replace('_', ' ', $status->status) }}
                                </td>
                                <td class="px-4 py-3 text-slate-900 dark:text-white font-bold">
                                    {{ $status->total }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-xs text-slate-500">No order stage data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <div class="lg:col-span-8 app-card p-4 sm:p-6 space-y-4 overflow-hidden flex flex-col">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            Recent Laundry Orders
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Live feed of active store and online orders
                        </p>
                    </div>
                    <a href="{{ route('admin.laundry.index') }}"
                       class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">
                        View All Orders
                    </a>
                </div>

                <div class="overflow-x-auto max-w-full flex-1 mt-4">
                    <table class="w-full text-left text-xs whitespace-nowrap min-w-[500px]">
                        <thead class="bg-black/5 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                            <tr>
                                <th class="px-4 py-3">Order Code</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                            @forelse($recentOrders->take(6) as $order)
                                <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                    <td class="px-4 py-3 font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="px-4 py-3 font-medium">
                                        {{ $order->customer->name ?? 'Walk-in Customer' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                        {{ $order->service->name ?? 'Standard Wash' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            @if($order->order_status === 'completed') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30
                                            @elseif($order->order_status === 'ready' || $order->order_status === 'finish') bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30
                                            @elseif($order->order_status === 'pending') bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300
                                            @else bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30 @endif">
                                            {{ $order->order_status === 'finish' ? 'Finish' : str_replace('_', ' ', $order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.laundry.index') }}"
                                           class="text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-slate-500">
                                        No recent orders recorded.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-4 app-card p-4 sm:p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            Customer Ratings & Reviews
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Live feedback submitted by Legazpi store customers
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 text-xs font-bold whitespace-nowrap">
                        {{ count($feedbacks ?? []) }} Reviews
                    </span>
                </div>

                <div class="space-y-3 overflow-y-auto max-h-[350px]">
                    @forelse($feedbacks as $fb)
                        <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $fb->user->name ?? 'Customer' }}
                                </span>
                                <div class="flex items-center text-amber-500 text-xs font-bold">
                                    <span>★ {{ $fb->rating }}.0</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 italic">
                                "{{ $fb->comment }}"
                            </p>
                            <span class="text-[9px] text-slate-500 dark:text-slate-400 block font-mono">
                                {{ $fb->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-xs text-slate-500">
                            No customer feedback submitted yet.
                        </div>
                    @endforelse
                </div>
            </div>

        </div><!-- End Overall Dashboard Container -->

        <!-- RIDER DISPATCH VIEW -->
        <div x-show="mode === 'rider'" class="space-y-6">
            <div class="app-card p-5 sm:p-6 bg-[#1C1C1E] border border-white/10 rounded-2xl space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
                    <div>
                        <h2 class="text-lg font-bold text-white flex items-center gap-2 font-['Outfit']">
                            <span>🛵</span> Rider Logistics & Dispatch Tasks (Excludes Walk-In Orders)
                        </h2>
                        <p class="text-xs text-slate-400 mt-1">
                            Real-time view for customer pickup requests and delivery dispatches. 1-click status updates automatically trigger SMS alerts to customers.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="px-3.5 py-2 rounded-xl bg-amber-500/15 border border-amber-500/30 text-amber-400 text-xs font-bold flex items-center gap-2">
                            <span>📦 Pickup Requests:</span>
                            <span class="font-mono text-sm font-extrabold">{{ $riderPickupRequests ?? 0 }}</span>
                        </div>
                        <div class="px-3.5 py-2 rounded-xl bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 text-xs font-bold flex items-center gap-2">
                            <span>🚚 Out for Delivery:</span>
                            <span class="font-mono text-sm font-extrabold">{{ $riderDeliveryCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Rider 5-Stage Logistics Analytics Grid (Excludes Walk-In Orders) -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-2">
                    <div class="p-3 rounded-2xl bg-black/40 border border-amber-500/30 text-center">
                        <span class="text-[9px] font-extrabold text-amber-400 uppercase tracking-wider block">1. Pickup Requests</span>
                        <span class="text-xl font-extrabold text-white font-mono">{{ $riderPickupRequests ?? 0 }}</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-black/40 border border-blue-500/30 text-center">
                        <span class="text-[9px] font-extrabold text-blue-400 uppercase tracking-wider block">2. In-Shop Received</span>
                        <span class="text-xl font-extrabold text-white font-mono">{{ $riderReceivedCount ?? 0 }}</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-black/40 border border-cyan-500/30 text-center">
                        <span class="text-[9px] font-extrabold text-cyan-400 uppercase tracking-wider block">3. Out For Delivery</span>
                        <span class="text-xl font-extrabold text-white font-mono">{{ $riderDeliveryCount ?? 0 }}</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-black/40 border border-emerald-500/30 text-center">
                        <span class="text-[9px] font-extrabold text-emerald-400 uppercase tracking-wider block">4. Completed / Delivered</span>
                        <span class="text-xl font-extrabold text-white font-mono">{{ $riderCompletedCount ?? 0 }}</span>
                    </div>
                    <div class="p-3 rounded-2xl bg-black/40 border border-rose-500/30 text-center col-span-2 sm:col-span-1">
                        <span class="text-[9px] font-extrabold text-rose-400 uppercase tracking-wider block">5. Cancelled</span>
                        <span class="text-xl font-extrabold text-white font-mono">{{ $riderCancelledCount ?? 0 }}</span>
                    </div>
                </div>

                <!-- Rider Task Cards -->
                <div class="space-y-4">
                    @forelse($riderOrders as $order)
                        <div class="p-4 rounded-xl bg-black/40 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-[#007AFF]/50 transition">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-sm text-[#0A84FF]">#{{ $order->order_number }}</span>
                                    <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                                        @if(in_array($order->order_status, ['out_for_pickup', 'pending'])) bg-amber-500/20 text-amber-300 border border-amber-500/40
                                        @else bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 @endif">
                                        {{ strtoupper(str_replace('_', ' ', $order->order_status === 'pending' ? 'out_for_pickup' : $order->order_status)) }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-300 space-y-1">
                                    <p>👤 <span class="font-bold text-white">{{ $order->customer->name ?? 'Customer' }}</span> — 📞 <a href="tel:{{ $order->customer->phone ?? '' }}" class="text-[#0A84FF] font-mono hover:underline">{{ $order->customer->phone ?? 'No phone listed' }}</a></p>
                                    <p>📍 <span class="text-slate-300 font-semibold">{{ $order->customer->customerProfile->address ?? 'Legazpi City Shop Pick-Up / Delivery' }}</span></p>
                                    <p>🧺 <span class="text-slate-400">Service: {{ $order->service->name ?? 'Laundry Service' }}</span> | <span class="text-emerald-400 font-bold">P{{ number_format($order->total_amount, 2) }}</span></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 self-start md:self-center">
                                @if(in_array($order->order_status, ['out_for_pickup', 'pending']))
                                    <form method="POST" action="{{ route('admin.laundry.update', $order->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="received">
                                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                                            <span>✓</span> Mark Laundry Received
                                        </button>
                                    </form>
                                @elseif($order->order_status === 'out_for_delivery')
                                    <form method="POST" action="{{ route('admin.laundry.update', $order->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                                            <span>✓</span> Mark Laundry Delivered
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('admin.laundry.index') }}" class="px-3 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-medium text-xs transition">
                                    View Order
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 space-y-2">
                            <div class="text-3xl">🛵</div>
                            <p class="text-sm font-bold text-white">No Active Rider Tasks Right Now</p>
                            <p class="text-xs text-slate-500">Orders marked as "Out for Pickup" or "Out for Delivery" will automatically appear here for rider routing.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>