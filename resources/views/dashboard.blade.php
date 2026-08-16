<x-app-layout>
    <div class="space-y-6 sm:space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage your laundry bookings, track live orders, and check machine availability.
                </p>
            </div>
            
            <a href="{{ route('laundry.create') }}" class="btn-ios-primary flex items-center justify-center gap-2">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Book Laundry Order
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="app-card p-4">
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Washers Available</h5>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $idleWashers ?? 0 }} Idle</p>
                </div>
            </div>

            <div class="app-card p-4">
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Dryers Available</h5>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $idleDryers ?? 0 }} Ready</p>
                </div>
            </div>

            <div class="app-card p-4">
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">My Total Orders</h5>
                    <p class="text-sm font-bold text-[#007AFF] dark:text-[#0A84FF]">{{ $recentOrders->count() }} Orders</p>
                </div>
            </div>

            <div class="app-card p-4">
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Loyalty Balance</h5>
                    <p class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ $loyaltyPoints }} Points</p>
                </div>
            </div>

            <div class="col-span-2 lg:col-span-1 app-card p-4 flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        STORE OPEN
                    </span>
                    <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-0.5">7:00 AM - 6:00 PM</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            @if(isset($activeOrder) && $activeOrder)
                <div class="app-card p-5 sm:p-6 space-y-5 shadow-lg border-l-4 border-l-[#007AFF]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-black/5 dark:border-white/10 pb-4">
                        <div class="flex items-center gap-3">
                            @if($activeOrder->qrCode)
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $activeOrder->qrCode->qr_token }}" 
                                     alt="QR Code Tag" 
                                     class="w-12 h-12 bg-white p-1 rounded-xl border border-slate-200 shadow-sm flex-shrink-0">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-[#007AFF]/10 text-[#007AFF] dark:text-[#0A84FF] flex items-center justify-center font-bold text-sm">
                                    QR
                                </div>
                            @endif
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white font-['Outfit']">
                                        Active Order Tracker
                                    </h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                        {{ $activeOrder->order_status === 'finish' ? 'Finish & Ready' : str_replace('_', ' ', $activeOrder->order_status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Live cleaning progress monitoring</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('laundry.track', $activeOrder->order_number) }}" class="btn-ios-primary text-xs px-3 py-1.5">
                                Track Live Status
                            </a>
                            @if(in_array($activeOrder->order_status, ['pending', 'received']))
                                <form method="POST" action="{{ route('laundry.cancel', $activeOrder->id) }}" class="inline">
                                    @csrf
                                    <button onclick="return confirm('Are you sure you want to cancel this pending order?')" class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-2.5 py-1 rounded-lg text-xs font-bold transition">
                                        Cancel
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs bg-black/5 dark:bg-[#2C2C2E] p-4 rounded-xl border border-black/5 dark:border-white/10">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Order Code</span>
                            <span class="font-bold text-[#007AFF] dark:text-[#0A84FF] font-mono">#{{ $activeOrder->order_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Assigned Machine Unit</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                {{ $activeOrder->machine ? $activeOrder->machine->machine_name . ' (' . $activeOrder->machine->machine_code . ')' : 'Assigning Unit...' }}
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
                <div class="app-card p-6 text-center space-y-3 border-dashed border-2 border-slate-300 dark:border-slate-700">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#007AFF]/10 text-[#007AFF] dark:text-[#0A84FF] flex items-center justify-center text-xl">
                        🧺
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">No Active Laundry Order</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                        You currently have no laundry orders in progress. Place a new drop-off or pickup order to track live cleaning progress!
                    </p>
                    <div class="pt-1">
                        <a href="{{ route('laundry.create') }}" class="btn-ios-primary text-xs inline-block">
                            + Book New Laundry Order
                        </a>
                    </div>
                </div>
            @endif

            <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">My Order History</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Your recent laundry bookings and invoices</p>
                    </div>
                    <a href="{{ route('my.orders') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">View All History</a>
                </div>

                <div class="overflow-x-auto max-w-full">
                    <table class="w-full text-left text-xs whitespace-nowrap min-w-[500px]">
                        <thead class="bg-black/5 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/5 dark:border-white/10">
                            <tr>
                                <th class="px-4 py-3">Order #</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Amount</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                    <td class="px-4 py-3 font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">
                                        <a href="{{ route('laundry.track', $order->order_number) }}" class="hover:underline">#{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-4 py-3">{{ $order->service->name ?? 'Wash & Dry' }}</td>
                                    <td class="px-4 py-3 text-slate-500 dark:text-slate-400">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            @if($order->order_status === 'completed') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30
                                            @elseif($order->order_status === 'finish') bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30
                                            @else bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30 @endif">
                                            {{ $order->order_status === 'finish' ? 'Finish' : str_replace('_', ' ', $order->order_status) }}
                                        </span>
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

            <!-- Loyalty Rewards Section (Moved to Bottom) -->
            <div class="app-card p-4 sm:p-6 space-y-4 bg-gradient-to-r from-[#007AFF]/5 via-transparent to-amber-500/5 border border-amber-500/20">
                <div class="flex items-center justify-between border-b border-black/5 dark:border-white/10 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🎁</span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white font-['Outfit']">Loyalty Rewards Program</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Earn points on every wash and claim store discounts</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] font-bold text-xs">
                        CUSTOMER MEMBER
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white font-['Outfit']">{{ $loyaltyPoints }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Loyalty Wash Points</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 max-w-xl">
                            Earn 10 points for every ₱100 spent! Redeem your points for instant store discounts and free wash vouchers.
                        </p>
                    </div>

                    <form action="{{ route('loyalty.redeem') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <input type="hidden" name="points" value="100">
                        <button type="submit" class="btn-ios-primary text-xs py-2.5 px-5" @if($loyaltyPoints < 100) disabled @endif>
                            Redeem 100 Points for ₱20 Discount
                        </button>
                    </form>
                </div>
            </div>

            <!-- Live Store Machine Availability -->
            <div class="app-card p-4 sm:p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                            Live Store Machine Availability
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Real-time status of commercial washers & dryers at Hour Wash store.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Updates
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @forelse($machines as $machine)
                        @php
                            $isMyOrder = auth()->check() && $machine->currentOrder && ($machine->currentOrder->customer_id === auth()->id());
                            
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

                        @if($isMyOrder)
                            <a href="{{ route('laundry.track', $machine->currentOrder->order_number) }}" 
                               class="block p-3.5 rounded-xl bg-[#007AFF]/5 dark:bg-[#0A84FF]/10 border-2 border-[#007AFF] space-y-2 hover:shadow-lg hover:scale-[1.02] active:scale-95 transition-all cursor-pointer relative group" 
                               title="Click anywhere on box to view your order #{{ $machine->currentOrder->order_number }}">
                                <span class="absolute -top-2 -right-1 bg-[#007AFF] text-white text-[8px] font-extrabold px-1.5 py-0.5 rounded-md shadow uppercase tracking-wider">
                                    YOUR ORDER
                                </span>
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
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        <span>⏱ {{ $machine->remaining_minutes ?? 30 }} mins remaining</span>
                                        <span class="block text-[9px] font-bold text-[#007AFF] dark:text-[#0A84FF] mt-1 group-hover:text-blue-700">
                                            Order: {{ $machine->currentOrder->order_number }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-2 opacity-90">
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
                                            <span class="block text-[9px] text-slate-400 dark:text-slate-500 font-medium mt-0.5">
                                                In Use (Occupied)
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
            </div>

        </div>
    </div>
</x-app-layout>
