<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 font-medium">
                    Manage active washing, drying, and shelving pipeline for customer orders.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                <form method="POST" action="{{ route('admin.store-status.toggle') }}" class="w-full sm:w-auto">
                    @csrf
                    @if(($storeStatus ?? 'open') === 'open')
                        <button type="submit" title="Click to Mark Store Closed Today" class="w-full px-2.5 py-1.5 rounded-lg bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 font-extrabold text-[10px] whitespace-nowrap hover:bg-emerald-500/25 transition flex items-center justify-center gap-1.5 h-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span>STORE OPEN TODAY</span>
                            <span class="hidden xl:inline-block text-[9px] text-slate-500 dark:text-slate-400 font-normal pl-1 border-l border-emerald-500/30">(Click to Close)</span>
                        </button>
                    @else
                        <button type="submit" title="Click to Re-open Store Today" class="w-full px-2.5 py-1.5 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 font-extrabold text-[10px] whitespace-nowrap hover:bg-rose-500/25 transition flex items-center justify-center gap-1.5 h-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                            <span>STORE CLOSED TODAY</span>
                            <span class="hidden xl:inline-block text-[9px] text-slate-500 dark:text-slate-400 font-normal pl-1 border-l border-rose-500/30">(Click to Open)</span>
                        </button>
                    @endif
                </form>

                <button type="button" onclick="openAdminCameraScanner()" class="btn-secondary text-[10px] py-1.5 px-2.5 whitespace-nowrap flex items-center justify-center gap-1 w-full sm:w-auto h-full">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <span>Scan QR</span>
                </button>

                <a href="{{ route('staff.laundry.index') }}" class="btn-secondary text-[10px] py-1.5 px-2.5 whitespace-nowrap text-center w-full sm:w-auto flex items-center justify-center h-full">
                    Orders Queue
                </a>

                <a href="{{ route('laundry.create') }}" class="btn-primary text-[10px] py-1.5 px-2.5 whitespace-nowrap text-center w-full sm:w-auto flex items-center justify-center h-full">
                    New Order
                </a>
            </div>
        </div>

        @php
            $unpaidOrders = $orders->where('payment_status', 'unpaid');
            $unpaidCount = $unpaidOrders->count();
            $unpaidTotal = $unpaidOrders->sum('total_amount');

            $paidOrders = $orders->where('payment_status', 'paid');
            $paidCount = $paidOrders->count();
            $paidTotal = $paidOrders->sum('total_amount');

            $pendingCount = $orders->where('order_status', 'pending')->count();
            $pickupCount = $orders->where('order_status', 'out_for_pickup')->count();
            $receivedCount = $orders->where('order_status', 'received')->count();
            $washRinseCount = $orders->whereIn('order_status', ['washing', 'rinsing'])->count();
            $dryingCount = $orders->where('order_status', 'drying')->count();
            $finishCount = $orders->where('order_status', 'finish')->count();
            $deliveryCount = $orders->where('order_status', 'out_for_delivery')->count();
            $completedCount = $orders->where('order_status', 'completed')->count();
        @endphp

        <!-- Quick Terminal Shortcuts -->
        <div>
            <h2 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3">
                Quick Terminal Shortcuts
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                <a href="{{ route('staff.laundry.index') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        Manage Laundry Orders
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        {{ $totalOrders ?? 0 }} total orders
                    </span>
                </a>

                <a href="{{ route('staff.machines.index') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        Machine Monitor
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        {{ count($machines ?? []) }} commercial units
                    </span>
                </a>

                <a href="{{ route('laundry.create') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        New Walk-in Order
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Book customer wash
                    </span>
                </a>

                <a href="{{ route('admin.qr_scan_logs.index') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        QR Scan Logs Outbox
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Scan audit history
                    </span>
                </a>

                <a href="{{ route('welcome') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        Home Dashboard
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Public storefront
                    </span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        Account Settings
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Staff Profile & Security
                    </span>
                </a>
            </div>
        </div>

        <!-- Payment & Financial Status Summary (Pera / Payment Collection) -->
        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Payment Collection & Cashier Summary
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="metric-pill-card-rose">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center font-extrabold text-base shrink-0 border border-rose-500/30">
                            ₱
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                Unpaid Orders Collection
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                {{ $unpaidCount }} order(s) pending cashier payment
                            </span>
                        </div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold text-rose-600 dark:text-rose-400 font-mono shrink-0">
                        ₱{{ number_format($unpaidTotal, 2) }}
                    </span>
                </div>

                <div class="metric-pill-card-emerald">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-extrabold text-base shrink-0 border border-emerald-500/30">
                            ✓
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                Total Collected Revenue
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                {{ $paidCount }} order(s) cleared & paid
                            </span>
                        </div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono shrink-0">
                        ₱{{ number_format($paidTotal, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 8-Stage Order Status Pipeline Breakdown -->
        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Live Order Stage Pipeline Breakdown
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider block truncate">1. PENDING</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $pendingCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Order Placed</p>
                </div>

                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-sky-600 dark:text-sky-400 uppercase tracking-wider block truncate">2. PICKUP</span>
                    <span class="text-xl font-bold text-sky-600 dark:text-sky-400 mt-1">{{ $pickupCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Out for Pickup</p>
                </div>

                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider block truncate">3. RECEIVED</span>
                    <span class="text-xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $receivedCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Store Received</p>
                </div>

                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-wider block truncate">4. WASHING</span>
                    <span class="text-xl font-bold text-teal-600 dark:text-teal-400 mt-1">{{ $washRinseCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Wash & Rinse</p>
                </div>

                <div class="card-accent-purple p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block truncate">5. DRYING</span>
                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $dryingCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Dryer Units</p>
                </div>

                <div class="card-accent-amber p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-amber-600 dark:text-amber-400 uppercase tracking-wider block truncate">6. FINISH</span>
                    <span class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $finishCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Folding & Ready</p>
                </div>

                <div class="card-accent-purple p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-purple-600 dark:text-purple-400 uppercase tracking-wider block truncate">7. DELIVERY</span>
                    <span class="text-xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $deliveryCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Out for Delivery</p>
                </div>

                <div class="card-accent-emerald p-3 flex flex-col justify-between shadow-sm">
                    <span class="text-[9.5px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block truncate">8. COMPLETED</span>
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $completedCount }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Fulfilled & Done</p>
                </div>
            </div>
        </div>

        <!-- Machine Status Monitor Grid -->
        <div class="app-card p-4 sm:p-6 space-y-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        Machine Status Monitor
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">
                        Real-time status of commercial washers & dryers. Click any active order to view details.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.machines.create') }}" class="btn-primary py-1.5 px-3 text-xs">
                        + Add Machine
                    </a>
                    <a href="{{ route('staff.machines.index') }}" class="btn-secondary py-1.5 px-3 text-xs">
                        Manage Machines
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-3.5">
                @forelse($machines as $machine)
                    @php
                        $statusBadgeClass = match($machine->status) {
                            'washing' => 'bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800/60',
                            'rinsing' => 'bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800/60',
                            'drying' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60',
                            'idle' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60',
                            default => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60',
                        };
                        $dotClass = match($machine->status) {
                            'washing' => 'bg-teal-500 animate-pulse',
                            'rinsing' => 'bg-sky-500 animate-pulse',
                            'drying' => 'bg-amber-500 animate-pulse',
                            'idle' => 'bg-emerald-500',
                            default => 'bg-rose-500',
                        };
                        $ord = $machine->displayOrder;
                    @endphp

                    @if($ord)
                        <a href="{{ route('laundry.track', $ord->order_number) }}"
                           class="block p-3.5 rounded-lg bg-white dark:bg-[#18181B] border-2 border-blue-600 space-y-2.5 shadow-sm hover:shadow-sm transition-all cursor-pointer group"
                           title="Click to view order #{{ $ord->order_number }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600 transition">
                                    {{ $machine->machine_name }}
                                </span>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono font-semibold shrink-0">
                                    {{ $machine->machine_code }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $statusBadgeClass }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="truncate">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-800 dark:text-zinc-200">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ strtoupper($machine->status === 'idle' ? str_replace('_', ' ', $ord->order_status) : $machine->status) }}
                                    </span>
                                    @if(in_array($machine->status, ['washing', 'rinsing', 'drying']) && $machine->remaining_minutes)
                                        <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">{{ $machine->remaining_minutes }}m remaining</span>
                                    @else
                                        <span class="block text-[10px] text-amber-600 dark:text-amber-400 font-bold truncate">Order {{ ucfirst(str_replace('_', ' ', $ord->order_status)) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 dark:border-zinc-800/80 text-[10px] space-y-0.5">
                                <span class="block font-bold text-blue-600 dark:text-blue-400 group-hover:underline truncate">
                                    #{{ $ord->order_number }}
                                </span>
                                <span class="block text-slate-600 dark:text-slate-400 font-medium truncate">
                                    {{ $ord->customer->name ?? 'Customer' }}
                                </span>
                            </div>
                        </a>
                    @else
                        <div class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 space-y-2.5 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate">
                                    {{ $machine->machine_name }}
                                </span>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-mono font-semibold shrink-0">
                                    {{ $machine->machine_code }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs shrink-0 {{ $statusBadgeClass }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="truncate">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-700 dark:text-zinc-300">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ strtoupper($machine->status) }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400 dark:text-zinc-500 font-medium truncate">
                                        {{ $machine->status === 'idle' ? 'Available' : ($machine->remaining_minutes ? $machine->remaining_minutes.'m remaining' : 'In Service') }}
                                    </span>
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

            <div class="flex flex-wrap items-center gap-4 text-[11px] font-medium text-slate-600 dark:text-slate-400 border-t border-slate-200/80 dark:border-zinc-800 pt-3.5">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-teal-500"></span> Washing</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Rinsing</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Drying</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Idle / Available</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Maintenance</span>
            </div>
        </div>

                <!-- Active Processing Pipeline -->
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Active Processing Pipeline</h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Update cleaning status stages as laundry moves through machines</p>
                </div>
                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('laundry.create') }}" class="btn-primary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">
                        + New Order
                    </a>
                    <a href="{{ route('staff.laundry.index') }}" class="btn-secondary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">Full Orders Queue</a>
                </div>
            </div>

            <div class="overflow-x-auto max-w-full border border-slate-200 dark:border-zinc-800 rounded-lg">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[650px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Order Tag</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Current Stage</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-700 text-slate-900 dark:text-slate-200">
                        @forelse($orders->take(10) as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $order->customer->name ?? 'Walk-in' }}</div>
                                    @if(!empty($order->notes))
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 italic max-w-xs truncate" title="{{ $order->notes }}">
                                            {{ $order->notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $order->service->name ?? 'Standard Wash' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-mono">{{ $order->weight_kg }} kg</td>
                                <td class="px-4 py-3 font-mono">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                        {{ strtoupper($order->payment_status) }} (₱{{ number_format($order->total_amount, 2) }})
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                     @if($order->order_status === 'completed')
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Completed</span>
                                     @elseif($order->order_status === 'finish')
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">Finish</span>
                                     @elseif($order->order_status === 'cancelled')
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Cancelled</span>
                                     @else
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30">{{ str_replace('_', ' ', $order->order_status) }}</span>
                                     @endif
                                 </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('staff.laundry.index') }}" class="btn-secondary py-1 px-3 text-[11px]">Manage</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-500">No active processing orders.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-camera-qr-scanner />

</x-app-layout>
