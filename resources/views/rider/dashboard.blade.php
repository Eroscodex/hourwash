<x-app-layout>
    <div class="space-y-6 max-w-5xl mx-auto">

        <!-- Rider Header Banner -->
        <div class="app-card p-5 sm:p-6 bg-gradient-to-br from-[#1C1C1E] via-[#2C2C2E] to-[#1C1C1E] border border-white/10 rounded-3xl shadow-lg space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-[#007AFF]/20 text-[#007AFF] dark:text-[#0A84FF] border border-[#007AFF]/40 flex items-center justify-center text-2xl font-bold">
                        🛵
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-white font-['Outfit']">
                                Rider Portal & Logistics
                            </h1>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-[10px] font-extrabold uppercase tracking-wider border border-emerald-500/30">
                                ACTIVE RIDER
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Welcome back, <span class="font-bold text-white">{{ $user->name }}</span>! Real-time pickup & delivery routing.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="tel:09100317744" class="px-3.5 py-2 rounded-xl bg-[#007AFF] hover:bg-[#0056b3] text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                        <span>📞</span> Shop Dispatch (09100317744)
                    </a>
                </div>
            </div>

            <!-- Rider 5-Stage Logistics Analytics Grid (Excludes Walk-In Orders) -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5 pt-2 border-t border-white/10">
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
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-2 shadow-sm">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-400 text-xs font-bold flex items-center gap-2 shadow-sm">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        <!-- SECTION 1: PICKUP REQUESTS -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2 font-['Outfit']">
                    <span>📦</span> Customer Pickup Requests ({{ $pickupOrders->count() }})
                </h2>
                <span class="text-[11px] text-amber-400 font-semibold">Collect Laundry from Customer</span>
            </div>

            @forelse($pickupOrders as $order)
                <div class="app-card p-5 bg-[#1C1C1E] border border-amber-500/30 rounded-2xl space-y-4 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-white/10 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-extrabold text-base text-[#0A84FF]">#{{ $order->order_number }}</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/40">
                                OUT FOR PICKUP
                            </span>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">
                            Requested: {{ $order->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-black/40 border border-white/5 space-y-1.5">
                            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Customer Details</p>
                            <p class="font-bold text-white text-sm">{{ $order->customer->name ?? 'Customer' }}</p>
                            <p class="flex items-center gap-2 font-mono">
                                📞 <a href="tel:{{ $order->customer->phone ?? '' }}" class="text-[#0A84FF] font-bold hover:underline">{{ $order->customer->phone ?? 'No phone' }}</a>
                                @if($order->customer?->phone)
                                    <a href="sms:{{ $order->customer->phone }}" class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 text-[10px] font-extrabold">SMS</a>
                                @endif
                            </p>
                        </div>

                        <div class="p-3 rounded-xl bg-black/40 border border-white/5 space-y-1.5">
                            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Pickup Address & Info</p>
                            <p class="text-slate-200 font-semibold">📍 {{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                            <p class="text-slate-400">🧺 Service: <span class="text-white font-bold">{{ $order->service->name ?? 'Laundry Package' }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs font-bold text-emerald-400 font-mono">
                            Total: P{{ number_format($order->total_amount, 2) }} ({{ strtoupper($order->payment_status) }})
                        </span>

                        <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="received">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                                <span>✓</span> Mark Laundry Received & In Shop
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-400 rounded-2xl bg-[#1C1C1E]">
                    <p class="text-xs">No active pickup requests right now.</p>
                </div>
            @endforelse
        </div>

        <!-- SECTION 2: DELIVERY DISPATCHES -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2 font-['Outfit']">
                    <span>🚚</span> Clean Laundry Delivery Dispatches ({{ $deliveryOrders->count() }})
                </h2>
                <span class="text-[11px] text-cyan-400 font-semibold">Deliver Laundry to Customer</span>
            </div>

            @forelse($deliveryOrders as $order)
                <div class="app-card p-5 bg-[#1C1C1E] border border-cyan-500/30 rounded-2xl space-y-4 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-white/10 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-extrabold text-base text-[#0A84FF]">#{{ $order->order_number }}</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">
                                OUT FOR DELIVERY
                            </span>
                        </div>
                        <span class="text-xs text-slate-400 font-mono">
                            Dispatched: {{ $order->updated_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="p-3 rounded-xl bg-black/40 border border-white/5 space-y-1.5">
                            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Customer Details</p>
                            <p class="font-bold text-white text-sm">{{ $order->customer->name ?? 'Customer' }}</p>
                            <p class="flex items-center gap-2 font-mono">
                                📞 <a href="tel:{{ $order->customer->phone ?? '' }}" class="text-[#0A84FF] font-bold hover:underline">{{ $order->customer->phone ?? 'No phone' }}</a>
                                @if($order->customer?->phone)
                                    <a href="sms:{{ $order->customer->phone }}" class="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 text-[10px] font-extrabold">SMS</a>
                                @endif
                            </p>
                        </div>

                        <div class="p-3 rounded-xl bg-black/40 border border-white/5 space-y-1.5">
                            <p class="text-slate-400 font-semibold uppercase text-[10px] tracking-wider">Delivery Destination</p>
                            <p class="text-slate-200 font-semibold">📍 {{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                            <p class="text-slate-400">🧺 Service: <span class="text-white font-bold">{{ $order->service->name ?? 'Laundry Package' }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-xs font-bold text-emerald-400 font-mono">
                            Total: P{{ number_format($order->total_amount, 2) }} ({{ strtoupper($order->payment_status) }})
                        </span>

                        <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition flex items-center gap-1.5">
                                <span>✓</span> Mark Delivered & Completed
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-400 rounded-2xl bg-[#1C1C1E]">
                    <p class="text-xs">No active delivery dispatches right now.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
