<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage your laundry bookings, track live orders, and check machine availability.
                </p>
            </div>
        </div>

        <!-- Quick Navbar Selection Shortcuts -->
        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Quick Navbar Selection Shortcuts
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                <a href="{{ route('laundry.create') }}"
                   class="app-card p-3.5 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Book New Order
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            Schedule laundry wash
                        </span>
                    </div>
                </a>

                <a href="{{ route('my.orders') }}"
                   class="app-card p-3.5 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            My Laundry Orders
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            {{ $recentOrders->count() }} total bookings
                        </span>
                    </div>
                </a>

                <a href="{{ route('frequent_card.index') }}"
                   class="app-card p-3.5 flex flex-col justify-between hover:border-pink-500 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group border-l-4 border-l-pink-500">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-pink-600 transition block truncate">
                                Frequent User Card
                            </span>
                        </div>
                        <span class="text-[10px] text-pink-600 dark:text-pink-400 font-mono font-bold block mt-1">
                            {{ auth()->user()->stamps_count ?? 0 }}/12 Stamps
                            @if((auth()->user()->discount_rewards_available ?? 0) > 0)
                                <span class="text-[9px] bg-emerald-600 text-white px-1 rounded ml-1 font-bold">REWARD</span>
                            @endif
                        </span>
                    </div>
                </a>

                <a href="{{ route('welcome') }}"
                   class="app-card p-3.5 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
                    <div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 transition block truncate">
                            Home Dashboard
                        </span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono block mt-1">
                            View store info & services
                        </span>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="app-card p-3.5 flex flex-col justify-between hover:border-blue-600 hover:shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all group">
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

        @php
            $customerUnpaidOrders = $recentOrders->where('payment_status', 'unpaid');
            $customerUnpaidCount = $customerUnpaidOrders->count();
            $customerUnpaidTotal = $customerUnpaidOrders->sum('total_amount');

            $customerPaidOrders = $recentOrders->where('payment_status', 'paid');
            $customerPaidCount = $customerPaidOrders->count();
            $customerPaidTotal = $customerPaidOrders->sum('total_amount');
        @endphp

        <!-- Customer Payment Summary (Unpaid vs Paid) -->
        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                My Payment Summary & Invoices
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="app-card p-4 sm:p-5 border-l-4 border-l-rose-500 flex items-center justify-between shadow-sm">
                    <div>
                        <span class="text-[10px] font-extrabold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">
                            UNPAID ORDERS (NEED PAYMENT COLLECTION)
                        </span>
                        <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-mono mt-1">
                            ₱{{ number_format($customerUnpaidTotal, 2) }}
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            <span class="font-bold text-rose-600 dark:text-rose-400">{{ $customerUnpaidCount }}</span> order(s) pending cashier payment
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                        UNPAID
                    </span>
                </div>

                <div class="app-card p-4 sm:p-5 border-l-4 border-l-emerald-500 flex items-center justify-between shadow-sm">
                    <div>
                        <span class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">
                            PAID ORDERS (CLEARED INVOICES)
                        </span>
                        <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white font-mono mt-1">
                            ₱{{ number_format($customerPaidTotal, 2) }}
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $customerPaidCount }}</span> order(s) paid & cleared
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                        PAID
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-3">
            <div class="app-card p-3 sm:p-4">
                <div>
                    <h5 class="text-[10px] sm:text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold truncate">Washers</h5>
                    <p class="text-sm sm:text-base font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $idleWashers ?? 0 }} Idle</p>
                </div>
            </div>

            <div class="app-card p-3 sm:p-4">
                <div>
                    <h5 class="text-[10px] sm:text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold truncate">Dryers</h5>
                    <p class="text-sm sm:text-base font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $idleDryers ?? 0 }} Ready</p>
                </div>
            </div>

            <div class="app-card p-3 sm:p-4">
                <div>
                    <h5 class="text-[10px] sm:text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold truncate">My Orders</h5>
                    <p class="text-sm sm:text-base font-bold text-blue-600 dark:text-blue-400 mt-0.5">{{ $recentOrders->count() }} Orders</p>
                </div>
            </div>

            <div class="col-span-3 lg:col-span-1 app-card p-3.5 sm:p-4 flex flex-col justify-center">
                <div>
                    @if(($storeStatus ?? 'open') === 'open')
                        <span class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-emerald-600 dark:text-emerald-400 tracking-wide">
                            <span class="relative flex h-2 w-2 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            STORE OPEN TODAY
                        </span>
                        <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 mt-1">7:30 AM – 6:00 PM (Mon – Sun)</p>
                        <p class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 mt-0.5">⏱️ Same-Day Cut-Off: 4:30 PM</p>
                    @else
                        <span class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-rose-600 dark:text-rose-400 tracking-wide">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                            STORE CLOSED TODAY
                        </span>
                        <p class="text-xs sm:text-sm font-bold text-slate-500 dark:text-slate-400 mt-1">7:30 AM – 6:00 PM (Mon – Sun)</p>
                        <p class="text-[11px] font-semibold text-rose-500 dark:text-rose-400 mt-0.5">Closed All Day</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">

            <!-- 1. Live Store Machine Availability -->
            <div class="app-card p-4 sm:p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-700 pb-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            Live Store Machine Availability
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Real-time status of commercial washers & dryers at Hour Wash store.
                        </p>
                    </div>
                    @if(($storeStatus ?? 'open') === 'open')
                        <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-500/15 px-3 py-1.5 rounded-md border border-emerald-500/30 whitespace-nowrap shrink-0 self-start sm:self-auto">
                            <span class="relative flex h-2 w-2 shrink-0">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            {{ $availableMachinesCount ?? $machines->where('status', 'idle')->count() }} Available
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-extrabold text-rose-600 dark:text-rose-400 bg-rose-500/15 px-3 py-1.5 rounded-md border border-rose-500/30 whitespace-nowrap shrink-0 self-start sm:self-auto">
                            <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                            Store Closed
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-3.5">
                    @forelse($machines as $machine)
                        @php
                            $ord = $machine->displayOrder;
                            $targetOrder = ($ord && $ord->customer_id === auth()->id()) ? $ord : ((isset($activeOrder) && $activeOrder && $machine->id == $activeOrder->machine_id) ? $activeOrder : null);
                            $isMyOrder = auth()->check() && ($targetOrder !== null);

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
                        @endphp

                        @if($isMyOrder && $targetOrder)
                            <a href="{{ route('laundry.track', $targetOrder->order_number) }}"
                               class="block p-3.5 rounded-lg bg-white dark:bg-[#18181B] border-2 border-blue-600 space-y-2.5 shadow-xs hover:shadow-sm transition-all cursor-pointer relative group"
                               title="Click to view your order #{{ $targetOrder->order_number }}">
                                <span class="absolute -top-2.5 -right-1 bg-blue-600 text-white text-[8.5px] font-extrabold px-2 py-0.5 rounded-full shadow-xs uppercase tracking-wider">
                                    YOUR ORDER
                                </span>
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
                                            {{ strtoupper($machine->status === 'idle' ? str_replace('_', ' ', $targetOrder->order_status) : $machine->status) }}
                                        </span>
                                        @if(in_array($machine->status, ['washing', 'rinsing', 'drying']) && $machine->remaining_minutes)
                                            <span class="block text-[10px] text-slate-500 dark:text-slate-400 font-medium truncate">{{ $machine->remaining_minutes }}m remaining</span>
                                        @else
                                            <span class="block text-[10px] text-amber-600 dark:text-amber-400 font-bold truncate">Order {{ ucfirst(str_replace('_', ' ', $targetOrder->order_status)) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100 dark:border-zinc-800/80 text-[10px]">
                                    <span class="block font-bold text-blue-600 dark:text-blue-400 group-hover:underline truncate">
                                        Order: #{{ $targetOrder->order_number }}
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
                            No store machines available right now.
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

            <!-- 2. Active Order Tracker -->
            @if(isset($activeOrder) && $activeOrder)
                <div class="app-card p-5 sm:p-6 space-y-5 shadow-lg border-l-4 border-l-[#2563EB]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-black/5 dark:dark:border-zinc-700 pb-4">
                        <div class="flex items-center gap-3">
                            @if($activeOrder->qrCode)
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $activeOrder->qrCode->qr_token }}"
                                     alt="QR Code Tag"
                                     class="w-12 h-12 bg-white p-1 rounded-lg border border-slate-200 shadow-sm flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-blue-600/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm">
                                    QR
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                                        Active Order Tracker
                                    </h3>
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                        {{ $activeOrder->order_status === 'finish' ? 'Finish & Ready' : str_replace('_', ' ', $activeOrder->order_status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Live cleaning progress monitoring</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('laundry.track', $activeOrder->order_number) }}" class="btn-primary text-xs px-3 py-1.5">
                                Track Live Status
                            </a>
                            @if(in_array($activeOrder->order_status, ['pending', 'received']))
                                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'cancel-active-order-{{ $activeOrder->id }}')" class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-2.5 py-1 rounded-lg text-xs font-bold transition">
                                    Cancel
                                </button>

                                <x-modal name="cancel-active-order-{{ $activeOrder->id }}" maxWidth="sm">
                                    <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                        <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Cancel Order?</h2>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                            Are you sure you want to cancel pending order <strong>#{{ $activeOrder->order_number }}</strong>?
                                        </p>
                                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                                Keep Order
                                            </button>
                                            <form method="POST" action="{{ route('laundry.cancel', $activeOrder->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                                    Yes, Cancel Order
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </x-modal>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3 text-xs bg-slate-50 dark:bg-[#18181B] p-4 rounded-lg border border-slate-200 dark:border-zinc-700">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Order Code</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400 font-mono">#{{ $activeOrder->order_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Payment Status</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $activeOrder->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                {{ strtoupper($activeOrder->payment_status ?? 'UNPAID') }} (₱{{ number_format($activeOrder->total_amount, 2) }})
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Assigned Machine</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                {{ $activeOrder->machine ? $activeOrder->machine->machine_name . ' (' . $activeOrder->machine->machine_code . ')' : 'Auto-Assign on Wash' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Selected Service</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $activeOrder->service->name ?? 'Standard Laundry Wash' }} ({{ $activeOrder->weight_kg }} kg)</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Est. Completion</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $activeOrder->estimated_completion ? $activeOrder->estimated_completion->format('M d, Y • h:i A') : 'Processing' }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="app-card p-4 sm:p-6 text-center space-y-3 border-dashed border border-slate-300 dark:border-zinc-700 w-full">
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">No Active Laundry Order</h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto leading-relaxed">
                        You currently have no laundry orders in progress. Place a new drop-off or pickup order to track live cleaning progress!
                    </p>
                    <div class="pt-1">
                        @if(($storeStatus ?? 'open') === 'open' || (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff())))
                            <a href="{{ route('laundry.create') }}" class="btn-primary text-xs sm:text-sm py-2 px-6 inline-flex items-center justify-center shadow-sm">
                                Book New Laundry Order
                            </a>
                        @else
                            <button disabled class="opacity-65 bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 px-4 py-2 rounded-lg text-xs font-bold cursor-not-allowed inline-flex items-center justify-center gap-1.5">
                                Store Closed Today (Bookings Disabled)
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            <!-- 3. My Order History -->
            <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700 pb-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">My Order History</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Your recent laundry bookings, payment status, and invoices</p>
                    </div>
                    <a href="{{ route('my.orders') }}" class="btn-secondary py-1.5 px-3 text-xs">View All History</a>
                </div>

                <div class="overflow-x-auto max-w-full">
                    <table class="w-full text-left text-xs whitespace-nowrap min-w-[650px]">
                        <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-4 py-3">Order #</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Payment</th>
                                <th class="px-4 py-3">Stage Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-zinc-700 text-slate-900 dark:text-slate-200">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                    <td class="px-4 py-3 font-mono font-bold text-blue-600 dark:text-blue-400">
                                        <a href="{{ route('laundry.track', $order->order_number) }}" class="hover:underline">#{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-4 py-3 font-medium">{{ $order->service->name ?? 'Wash & Dry' }}</td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400 font-mono">{{ $order->created_at->format('M d, Y') }}</td>
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
                                         @else
                                             <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30">{{ str_replace('_', ' ', $order->order_status) }}</span>
                                         @endif
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-slate-500">No order history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>
</x-app-layout>
