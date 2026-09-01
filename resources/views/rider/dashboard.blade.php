<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Rider Header Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                        Rider of Hour Wash Portal
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
            <div class="card-accent-amber p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10.5px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">1. Pickup</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Requests</span>
                </div>
                <span class="text-2xl font-black text-amber-600 dark:text-amber-400 font-mono">{{ $riderPickupRequests ?? 0 }}</span>
            </div>
            <div class="card-accent-blue p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10.5px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">2. In-Shop</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Received</span>
                </div>
                <span class="text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">{{ $riderReceivedCount ?? 0 }}</span>
            </div>
            <div class="card-accent-purple p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10.5px] font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider block">3. Delivery</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Out For Delivery</span>
                </div>
                <span class="text-2xl font-black text-purple-600 dark:text-purple-400 font-mono">{{ $riderDeliveryCount ?? 0 }}</span>
            </div>
            <div class="card-accent-emerald p-4 flex items-center justify-between shadow-sm">
                <div>
                    <span class="text-[10.5px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">4. Done</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Completed</span>
                </div>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $riderCompletedCount ?? 0 }}</span>
            </div>
            <div class="card-accent-rose p-4 flex items-center justify-between shadow-sm col-span-2 sm:col-span-1">
                <div>
                    <span class="text-[10.5px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider block">5. Cancelled</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400">Orders</span>
                </div>
                <span class="text-2xl font-black text-rose-600 dark:text-rose-400 font-mono">{{ $riderCancelledCount ?? 0 }}</span>
            </div>
        </div>

        <!-- Rider Financial & Earnings Summary Section -->
        <div class="app-card p-5 border-l-4 border-l-emerald-500 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Rider Earnings & COD Remittance Summary
                    </h2>
                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 uppercase">
                        TODAY'S FINANCIALS
                    </span>
                </div>
                <span class="text-xs font-mono text-slate-500 dark:text-slate-400 font-semibold">
                    {{ now()->format('F d, Y') }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Today's Completed</span>
                    <span class="text-xl font-black text-slate-900 dark:text-white font-mono block mt-1">{{ $completedTodayCount ?? 0 }} Dispatches</span>
                </div>

                <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800">
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block">Delivery Fee Earnings</span>
                    <span class="text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono block mt-1">₱{{ number_format($todayDeliveryFees ?? 0, 2) }}</span>
                </div>

                <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800">
                    <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block">COD Cash Collected</span>
                    <span class="text-xl font-black text-blue-600 dark:text-blue-400 font-mono block mt-1">₱{{ number_format($todayCodCollected ?? 0, 2) }}</span>
                </div>

                <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800">
                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider block">Pending COD to Collect</span>
                    <span class="text-xl font-black text-amber-600 dark:text-amber-400 font-mono block mt-1">₱{{ number_format($pendingCodToCollect ?? 0, 2) }}</span>
                </div>
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
                                {{ strtoupper(str_replace('_', ' ', $order->order_status)) }}
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

                    <!-- Laundry Progress Stepper -->
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-2">
                        <div class="flex items-center justify-between text-[10.5px] font-bold text-slate-600 dark:text-zinc-400">
                            <span>LAUNDRY PROGRESS TIMELINE</span>
                            <span class="text-blue-600 dark:text-blue-400 font-extrabold uppercase">{{ str_replace('_', ' ', $order->order_status) }}</span>
                        </div>
                        @php
                            $statusMap = ['pending' => 1, 'out_for_pickup' => 2, 'received' => 3, 'washing' => 4, 'rinsing' => 4, 'drying' => 4, 'finish' => 4, 'out_for_delivery' => 5, 'completed' => 6];
                            $currLvl = $statusMap[$order->order_status] ?? 1;
                        @endphp
                        <div class="grid grid-cols-5 gap-1.5 text-[9.5px] font-bold text-center">
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 1 ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">1. Requested</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 2 ? 'bg-amber-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">2. Out Pickup</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">3. In Shop</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 4 ? 'bg-purple-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">4. Processing</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 6 ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">5. Done</div>
                        </div>
                    </div>

                    @if($order->pickupDelivery?->pickup_proof_image)
                        <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center gap-3">
                            <img src="{{ asset($order->pickupDelivery->pickup_proof_image) }}" alt="Pickup Proof" class="w-12 h-12 rounded object-cover border border-emerald-500/40">
                            <div>
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">📷 Proof of Pickup Photo Uploaded</p>
                                <a href="{{ asset($order->pickupDelivery->pickup_proof_image) }}" target="_blank" class="text-[11px] text-blue-600 dark:text-blue-400 underline font-bold">View Full Photo Evidence</a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                            Total: ₱{{ number_format($order->total_amount, 2) }} ({{ strtoupper($order->payment_status) }})
                        </span>

                        <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="received">

                            <label class="cursor-pointer px-3 py-2 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-200 transition flex items-center gap-1.5 shrink-0 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>📷 Camera / Upload Proof</span>
                                <input type="file" name="proof_image" accept="image/*" capture="environment" class="hidden" onchange="if(this.files[0]) this.previousElementSibling.textContent = '✓ ' + this.files[0].name.substring(0,10) + '...';">
                            </label>

                            <button type="submit" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1.5 shrink-0 w-full sm:w-auto">
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

                    <!-- Laundry Progress Stepper -->
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-2">
                        <div class="flex items-center justify-between text-[10.5px] font-bold text-slate-600 dark:text-zinc-400">
                            <span>LAUNDRY PROGRESS TIMELINE</span>
                            <span class="text-cyan-600 dark:text-cyan-400 font-extrabold uppercase">OUT FOR DELIVERY</span>
                        </div>
                        <div class="grid grid-cols-5 gap-1.5 text-[9.5px] font-bold text-center">
                            <div class="py-1 px-0.5 rounded bg-amber-500 text-white">1. Requested</div>
                            <div class="py-1 px-0.5 rounded bg-amber-600 text-white">2. Out Pickup</div>
                            <div class="py-1 px-0.5 rounded bg-blue-600 text-white">3. In Shop</div>
                            <div class="py-1 px-0.5 rounded bg-purple-600 text-white">4. Processed</div>
                            <div class="py-1 px-0.5 rounded bg-cyan-600 text-white font-black">5. Out Delivery</div>
                        </div>
                    </div>

                    @if($order->pickupDelivery?->delivery_proof_image)
                        <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center gap-3">
                            <img src="{{ asset($order->pickupDelivery->delivery_proof_image) }}" alt="Delivery Proof" class="w-12 h-12 rounded object-cover border border-emerald-500/40">
                            <div>
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">📷 Proof of Delivery Photo Uploaded</p>
                                <a href="{{ asset($order->pickupDelivery->delivery_proof_image) }}" target="_blank" class="text-[11px] text-blue-600 dark:text-blue-400 underline font-bold">View Full Photo Evidence</a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                            Total: ₱{{ number_format($order->total_amount, 2) }} ({{ strtoupper($order->payment_status) }})
                        </span>

                        <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2.5 w-full sm:w-auto">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="completed">

                            <label class="cursor-pointer px-3 py-2 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-200 transition flex items-center gap-1.5 shrink-0 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>📷 Camera / Upload Proof</span>
                                <input type="file" name="proof_image" accept="image/*" capture="environment" class="hidden" onchange="if(this.files[0]) this.previousElementSibling.textContent = '✓ ' + this.files[0].name.substring(0,10) + '...';">
                            </label>

                            <button type="submit" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1.5 shrink-0 w-full sm:w-auto">
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
