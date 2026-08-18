<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Rider Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                        Rider Logistics & Dispatch Portal
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-md bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 text-[10px] font-extrabold uppercase tracking-wider border border-emerald-500/30">
                        ACTIVE RIDER
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Welcome back, <span class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</span>! Real-time pickup & delivery dispatches.
                </p>
            </div>

            <div class="w-full sm:w-auto">
                <a href="tel:09100317744" class="btn-primary text-xs flex items-center justify-center gap-1.5 shadow-sm w-full sm:w-auto py-2 px-4">
                    Shop Dispatch (09100317744)
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-lg bg-rose-500/15 border border-rose-500/30 text-rose-700 dark:text-rose-400 text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <!-- Rider 5-Stage Logistics Analytics KPI Grid -->
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
                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">4. Completed</span>
                <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderCompletedCount ?? 0 }}</span>
            </div>
            <div class="app-card p-4 text-center border-rose-500/30 col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">5. Cancelled</span>
                <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ $riderCancelledCount ?? 0 }}</span>
            </div>
        </div>

        <!-- SECTION 1: PICKUP REQUESTS -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                <h2 class="text-base font-bold text-slate-900 dark:text-white">
                    Customer Pickup Requests ({{ $pickupOrders->count() }})
                </h2>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Collect Laundry from Customer</span>
            </div>

            @forelse($pickupOrders as $order)
                <div class="app-card p-5 border-l-4 border-l-amber-500 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-base text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                OUT FOR PICKUP
                            </span>
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                            Requested: {{ $order->created_at->format('M d, Y • h:i A') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 space-y-1">
                            <p class="text-slate-500 dark:text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Customer Details</p>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $order->customer->name ?? 'Customer' }}</p>
                            <p class="flex items-center gap-2 font-mono">
                                <a href="tel:{{ $order->customer->phone ?? '' }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">{{ $order->customer->phone ?? 'No phone listed' }}</a>
                                @if($order->customer?->phone)
                                    <a href="sms:{{ $order->customer->phone }}" class="px-2 py-0.5 rounded bg-blue-500/15 text-blue-600 dark:text-blue-400 text-[10px] font-bold">SMS</a>
                                @endif
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 space-y-1">
                            <p class="text-slate-500 dark:text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Pickup Address & Package</p>
                            <p class="text-slate-900 dark:text-white font-semibold">📍 {{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                            <p class="text-slate-600 dark:text-slate-400">Service: <span class="text-slate-900 dark:text-white font-bold">{{ $order->service->name ?? 'Laundry Service' }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                            Total: ₱{{ number_format($order->total_amount, 2) }} ({{ strtoupper($order->payment_status) }})
                        </span>

                        <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="received">
                            <button type="submit" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                                <span>✓</span> Mark Laundry Received & In Shop
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-500 dark:text-slate-400">
                    <p class="text-xs">No active customer pickup requests right now.</p>
                </div>
            @endforelse
        </div>

        <!-- SECTION 2: DELIVERY DISPATCHES -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                <h2 class="text-base font-bold text-slate-900 dark:text-white">
                    Clean Laundry Delivery Dispatches ({{ $deliveryOrders->count() }})
                </h2>
                <span class="text-xs text-cyan-600 dark:text-cyan-400 font-semibold">Deliver Laundry to Customer</span>
            </div>

            @forelse($deliveryOrders as $order)
                <div class="app-card p-5 border-l-4 border-l-cyan-500 space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-base text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 border border-cyan-500/30">
                                OUT FOR DELIVERY
                            </span>
                            @if($order->machine)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30">
                                    Machine: {{ $order->machine->machine_name }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">
                            Dispatched: {{ $order->updated_at->format('M d, Y • h:i A') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 space-y-1">
                            <p class="text-slate-500 dark:text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Customer Details</p>
                            <p class="font-bold text-slate-900 dark:text-white text-sm">{{ $order->customer->name ?? 'Customer' }}</p>
                            <p class="flex items-center gap-2 font-mono">
                                <a href="tel:{{ $order->customer->phone ?? '' }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">{{ $order->customer->phone ?? 'No phone listed' }}</a>
                                @if($order->customer?->phone)
                                    <a href="sms:{{ $order->customer->phone }}" class="px-2 py-0.5 rounded bg-blue-500/15 text-blue-600 dark:text-blue-400 text-[10px] font-bold">SMS</a>
                                @endif
                            </p>
                        </div>

                        <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:border-white/5 space-y-1">
                            <p class="text-slate-500 dark:text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Delivery Destination</p>
                            <p class="text-slate-900 dark:text-white font-semibold">📍 {{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                            <p class="text-slate-600 dark:text-slate-400">Service: <span class="text-slate-900 dark:text-white font-bold">{{ $order->service->name ?? 'Laundry Service' }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                            Total: ₱{{ number_format($order->total_amount, 2) }} ({{ strtoupper($order->payment_status) }})
                        </span>

                        <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                                <span>✓</span> Mark Delivered & Completed
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-500 dark:text-slate-400">
                    <p class="text-xs">No active delivery dispatches right now.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
