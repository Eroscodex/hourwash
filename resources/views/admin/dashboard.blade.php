<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>

                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 font-medium">
                    Central operational hub, system analytics, and quick navigation shortcuts.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                <form method="POST" action="{{ route('admin.store-status.toggle') }}" class="w-full sm:w-auto">
                    @csrf
                    @if(($storeStatus ?? 'open') === 'open')
                        <button type="submit" title="Click to Mark Store Closed Today" class="w-full px-3 py-2 rounded-lg bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 font-extrabold text-xs whitespace-nowrap hover:bg-emerald-500/25 transition flex items-center justify-center gap-1.5 h-full">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span>STORE OPEN TODAY</span>
                            <span class="hidden xl:inline-block text-[10px] text-slate-500 dark:text-slate-400 font-normal pl-1 border-l border-emerald-500/30">(Click to Close)</span>
                        </button>
                    @else
                        <button type="submit" title="Click to Re-open Store Today" class="w-full px-3 py-2 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 font-extrabold text-xs whitespace-nowrap hover:bg-rose-500/25 transition flex items-center justify-center gap-1.5 h-full">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                            <span>STORE CLOSED TODAY</span>
                            <span class="hidden xl:inline-block text-[10px] text-slate-500 dark:text-slate-400 font-normal pl-1 border-l border-rose-500/30">(Click to Open)</span>
                        </button>
                    @endif
                </form>

                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-admin-reset-all-orders')" class="w-full bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-3 py-2 rounded-lg text-xs font-bold transition whitespace-nowrap flex items-center justify-center h-full">
                    Reset All Orders
                </button>

                <x-modal name="confirm-admin-reset-all-orders" maxWidth="md">
                    <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg">
                        <div class="flex items-center gap-3 text-rose-600 dark:text-rose-400">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <h2 class="text-base font-bold">Reset All Orders?</h2>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            This action will permanently purge all order history and set all commercial machines to idle status.
                        </p>
                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('admin.orders.reset') }}">
                                @csrf
                                <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                    Yes, Reset All Orders
                                </button>
                            </form>
                        </div>
                    </div>
                </x-modal>

                <a href="{{ route('admin.laundry.index') }}" class="btn-primary text-xs py-2 px-3 whitespace-nowrap text-center w-full sm:w-auto flex items-center justify-center h-full">
                    Manage Orders Queue
                </a>

                <a href="{{ route('admin.machines.create') }}" class="btn-secondary text-xs py-2 px-3 whitespace-nowrap text-center w-full sm:w-auto flex items-center justify-center h-full">
                    Add New Machine
                </a>
            </div>
        </div>

        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Quick Navbar Selection Shortcuts
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-5 lg:grid-cols-5 gap-3.5">

                <a href="{{ route('admin.laundry.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Laundry Orders
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $totalLaundry ?? 0 }} total
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.machines.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Machine Monitor
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $totalMachines ?? 20 }} units
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.services.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Services & Rates
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            Pricing
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            All Users
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $totalUsers ?? 0 }} accounts
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.qr_scan_logs.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            QR Scan Outbox
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $qrScanCount ?? 0 }} scans
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Reviews Outbox
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $reviewCount ?? 0 }} reviews
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.sms.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Live SMS Outbox
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $smsCount ?? 0 }} sent
                        </span>
                    </div>
                </a>

                <a href="{{ route('admin.emails.index') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Live Email Outbox
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $emailCount ?? 0 }} sent
                        </span>
                    </div>
                </a>

                <a href="{{ route('laundry.create') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            New Drop-Off
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            Book order
                        </span>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="app-card p-3 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Account Settings
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            Profile & Security
                        </span>
                    </div>
                </a>

            </div>
        </div>

        <!-- Payment & Financial Status Summary (Unpaid vs Paid) -->
        @php
            $adminUnpaidOrders = \App\Models\Order::where('payment_status', 'unpaid')->get();
            $adminUnpaidCount = $adminUnpaidOrders->count();
            $adminUnpaidTotal = $adminUnpaidOrders->sum('total_amount');

            $adminPaidOrders = \App\Models\Order::where('payment_status', 'paid')->get();
            $adminPaidCount = $adminPaidOrders->count();
            $adminPaidTotal = $adminPaidOrders->sum('total_amount');
        @endphp

        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Payment Collection & Cashier Summary
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="app-card p-4 flex items-center justify-between border-l-4 border-rose-500 shadow-sm">
                    <div>
                        <span class="text-[10px] font-extrabold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">
                            UNPAID ORDERS (NEED PAYMENT COLLECTION)
                        </span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white font-mono">
                            ₱{{ number_format($adminUnpaidTotal, 2) }}
                        </span>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            <span class="font-bold text-rose-600 dark:text-rose-400">{{ $adminUnpaidCount }} order(s)</span> pending cashier payment
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-md bg-rose-500/15 text-rose-700 dark:text-rose-300 font-extrabold text-xs uppercase border border-rose-500/30">
                        UNPAID
                    </span>
                </div>

                <div class="app-card p-4 flex items-center justify-between border-l-4 border-emerald-500 shadow-sm">
                    <div>
                        <span class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">
                            PAID ORDERS (COLLECTED REVENUE)
                        </span>
                        <span class="text-2xl font-extrabold text-slate-900 dark:text-white font-mono">
                            ₱{{ number_format($adminPaidTotal, 2) }}
                        </span>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $adminPaidCount }} order(s)</span> paid & cleared
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-md bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 font-extrabold text-xs uppercase border border-emerald-500/30">
                        PAID
                    </span>
                </div>
            </div>
        </div>

        <!-- 8-Stage Order Status Pipeline Breakdown -->
        @php
            $adminPendingCount = \App\Models\Order::where('order_status', 'pending')->count();
            $adminPickupCount = \App\Models\Order::where('order_status', 'out_for_pickup')->count();
            $adminReceivedCount = \App\Models\Order::where('order_status', 'received')->count();
            $adminWashRinseCount = \App\Models\Order::whereIn('order_status', ['washing', 'rinsing'])->count();
            $adminDryingCount = \App\Models\Order::where('order_status', 'drying')->count();
            $adminFinishCount = \App\Models\Order::where('order_status', 'finish')->count();
            $adminDeliveryCount = \App\Models\Order::where('order_status', 'out_for_delivery')->count();
            $adminCompletedCount = \App\Models\Order::where('order_status', 'completed')->count();
        @endphp

        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Live Order Stage Pipeline Breakdown
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider block truncate">1. PENDING</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $adminPendingCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Order Placed</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-sky-600 dark:text-sky-400 uppercase tracking-wider block truncate">2. PICKUP</span>
                    <span class="text-xl font-bold text-sky-600 dark:text-sky-400 mt-1">{{ $adminPickupCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Out for Pickup</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider block truncate">3. RECEIVED</span>
                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $adminReceivedCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Store Received</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-wider block truncate">4. WASHING</span>
                    <span class="text-xl font-bold text-teal-600 dark:text-teal-400 mt-1">{{ $adminWashRinseCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Wash & Rinse</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block truncate">5. DRYING</span>
                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $adminDryingCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Dryer Units</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-amber-600 dark:text-amber-400 uppercase tracking-wider block truncate">6. FINISH</span>
                    <span class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $adminFinishCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Shelved & Tagged</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-purple-600 dark:text-purple-400 uppercase tracking-wider block truncate">7. DELIVERY</span>
                    <span class="text-xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $adminDeliveryCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Out for Delivery</p>
                </div>

                <div class="app-card p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block truncate">8. COMPLETED</span>
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $adminCompletedCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Fulfilled & Done</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        TODAY'S ORDERS
                    </span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
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
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $inProgress ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">
                        Active machine cycles
                    </span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">COMPLETED TODAY</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $completedToday ?? 0 }}
                    </div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                        ↑ Completed today
                    </span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">STAFF COUNT</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $staffCount ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Active staff members</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">DISPATCH RIDERS</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
                        {{ $riderCount ?? 0 }}
                    </div>
                    <span class="text-[11px] text-cyan-600 dark:text-cyan-400 font-semibold">Active delivery riders</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div>
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">REGISTERED CUSTOMERS</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
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
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
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
                    <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
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
                    <div class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $totalLaundry ?? 0 }}
                    </div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Lifetime processed orders</span>
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
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.machines.create') }}" class="btn-primary py-1.5 px-3 text-xs">
                            + Add Machine
                        </a>
                        <a href="{{ route('admin.machines.index') }}" class="btn-secondary py-1.5 px-3 text-xs">
                            Manage Machines
                        </a>
                    </div>
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

                        @php
                            $ord = $machine->displayOrder;
                        @endphp

                        @if($ord)
                            <a href="{{ route('laundry.track', $ord->order_number) }}"
                               class="block p-3.5 rounded-lg bg-blue-600/5 dark:bg-blue-600/10 border border-blue-600/40 space-y-2 hover:border-blue-600 shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer group"
                               title="Click to view order #{{ $ord->order_number }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate group-hover:text-blue-600 transition">
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
                                        {{ strtoupper($machine->status === 'idle' ? str_replace('_', ' ', $ord->order_status) : $machine->status) }}
                                    </span>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 space-y-0.5">
                                        @if(in_array($machine->status, ['washing', 'rinsing', 'drying']) && $machine->remaining_minutes)
                                            <span>{{ $machine->remaining_minutes }} mins remaining</span>
                                        @elseif($ord->order_status === 'finish')
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓ Finish & Shelved</span>
                                        @else
                                            <span class="text-amber-600 dark:text-amber-400 font-bold">Order {{ ucfirst(str_replace('_', ' ', $ord->order_status)) }}</span>
                                        @endif
                                        <span class="block text-[9.5px] font-bold text-blue-600 dark:text-blue-400 mt-1 group-hover:underline truncate">
                                            Order: #{{ $ord->order_number }}
                                        </span>
                                        <span class="block text-[9px] font-semibold text-slate-700 dark:text-slate-300 truncate">
                                            Customer: {{ $ord->customer->name ?? 'Customer' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="p-3.5 rounded-lg bg-black/5 dark:bg-[#18181B] border border-black/5 dark:dark:border-zinc-700 space-y-2 opacity-85 select-none"
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
                                            <span>{{ $machine->remaining_minutes ?? 30 }} mins remaining</span>
                                            <span class="block text-[9px] text-blue-600 dark:text-blue-400 font-bold mt-0.5">
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

                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[11px] text-slate-500 dark:text-slate-400 border-t border-black/5 dark:dark:border-zinc-700 pt-4">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>Washing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>Rinsing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>Drying</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>Idle</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>Maintenance</span>
                </div>
            </div>
        </div>

        <!-- Recent Laundry Orders -->
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden flex flex-col shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        Recent Laundry Orders
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Live feed of active store and online orders
                    </p>
                </div>
                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('laundry.create') }}" class="btn-primary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">
                        + New Order
                    </a>
                    <a href="{{ route('admin.laundry.index') }}" class="btn-secondary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">
                        View All Orders Queue
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto max-w-full flex-1 mt-2 border border-slate-200 dark:border-zinc-800 rounded-lg">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[650px]">
                    <thead class="bg-black/5 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Order Code</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                        @forelse($recentOrders->take(6) as $order)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-blue-600 dark:text-blue-400">
                                    #{{ $order->order_number }}
                                </td>
                                <td class="px-4 py-3 font-medium">
                                    {{ $order->customer->name ?? 'Walk-in Customer' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">
                                    {{ $order->service->name ?? 'Standard Wash' }}
                                </td>
                                <td class="px-4 py-3 font-mono">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                        {{ strtoupper($order->payment_status) }} (₱{{ number_format($order->total_amount, 2) }})
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($order->order_status === 'completed')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Completed</span>
                                    @elseif($order->order_status === 'ready' || $order->order_status === 'finish')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">Finish</span>
                                    @elseif($order->order_status === 'pending')
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">Pending</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30">{{ str_replace('_', ' ', $order->order_status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.laundry.index') }}"
                                       class="btn-secondary py-1 px-3 text-[11px]">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-500">
                                    No recent orders recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RIDER LOGISTICS REAL-TIME ANALYTICS -->
        <div id="rider-dispatch-section" class="app-card p-4 sm:p-6 space-y-4 w-full">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-800 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        Rider Logistics Real-Time Analytics
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Live 5-stage pickup and delivery dispatch metrics for store dispatches.
                    </p>
                </div>

                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('laundry.create') }}" class="btn-primary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">
                        + New Order
                    </a>
                    <a href="{{ route('admin.laundry.index') }}" class="btn-secondary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">
                        View All Orders Queue
                    </a>
                </div>
            </div>

            <!-- Rider 5-Stage Logistics Analytics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="app-card p-4 text-center border-amber-500/30">
                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">1. Pickup Requests</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderPickupRequests ?? 0 }}</span>
                </div>
                <div class="app-card p-4 text-center border-blue-500/30">
                    <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">2. In-Shop Received</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderReceivedCount ?? 0 }}</span>
                </div>
                <div class="app-card p-4 text-center border-cyan-500/30">
                    <span class="text-[10px] font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-wider block">3. Out For Delivery</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderDeliveryCount ?? 0 }}</span>
                </div>
                <div class="app-card p-4 text-center border-emerald-500/30">
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">4. Completed / Delivered</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderCompletedCount ?? 0 }}</span>
                </div>
                <div class="app-card p-4 text-center border-rose-500/30 col-span-2 sm:col-span-1">
                    <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">5. Cancelled</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderCancelledCount ?? 0 }}</span>
                </div>
            </div>
        </div>

                <div class="app-card p-4 sm:p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-800 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        Overall System Reports & Order Stage Breakdown
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Comprehensive summary of order distribution across laundry lifecycle stages
                    </p>
                </div>
                <span class="px-3 py-1 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-xs font-bold shrink-0 w-fit">
                    {{ count($laundryStatus ?? []) }} Stage(s) Reported
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-black/5 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:dark:border-zinc-700">
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
    </div>
</x-app-layout>
