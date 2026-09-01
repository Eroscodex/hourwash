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

        <!-- Interactive Search Bar for Managing Multiple Dispatches -->
        <div class="app-card p-4 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-sm border-t-2 border-t-blue-500">
            <div class="relative w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="riderSearchInput" onkeyup="filterRiderOrders()" placeholder="Search by customer name, phone, order # (HW-...), or delivery address..." class="w-full text-xs text-slate-900 dark:text-white placeholder-slate-400 bg-slate-50 dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 rounded-lg py-2.5 pr-4 focus:ring-2 focus:ring-blue-500 focus:outline-none font-semibold" style="padding-left: 2.75rem !important;">
            </div>
            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-mono shrink-0 whitespace-nowrap">
                Showing <span id="visibleOrderCount" class="font-bold text-blue-600 dark:text-blue-400">{{ $totalActiveTasks }}</span> Active Dispatches
            </div>
        </div>

        <!-- SECTION 1: PICKUP REQUESTS -->
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span>Customer Pickup Requests</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30">{{ $pickupOrders->count() }}</span>
                </h2>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Collect Laundry from Customer</span>
            </div>

            @forelse($pickupOrders as $order)
                <div class="app-card p-5 border-l-4 border-l-amber-500 space-y-4 rider-order-card">
                    <!-- Top Order Card Header (QR Thumbnail + Order # + Machine Badge + Actions + Price) -->
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-700 pb-3">
                        <div class="flex items-center gap-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->qrCode?->qr_token ?? $order->order_number }}" alt="QR Code Tag #{{ $order->order_number }}" class="w-12 h-12 rounded-lg border border-slate-300 dark:border-zinc-700 bg-white p-0.5 shrink-0 shadow-sm">
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('laundry.track', $order->qrCode?->qr_token ?? $order->order_number) }}" target="_blank" class="font-mono font-bold text-base text-blue-600 dark:text-blue-400 hover:underline">
                                        #{{ $order->order_number }}
                                    </a>
                                    @if($order->machine)
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-purple-500/15 text-purple-700 dark:text-purple-300 border border-purple-500/30">
                                            Machine: {{ $order->machine->machine_name }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                            No Machine Assigned
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-0.5">
                                    Customer: <span class="font-bold text-slate-900 dark:text-white">{{ $order->customer->name ?? 'Store Walk-in Customer' }}</span>
                                    @if($order->customer?->phone)
                                        <span class="font-mono text-slate-500 dark:text-slate-400">({{ $order->customer->phone }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if($order->payment_status === 'paid')
                                <form method="POST" action="{{ route('rider.updatePaymentStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="unpaid">
                                    <button type="submit" title="Click to mark UNPAID" class="px-2.5 py-1 rounded text-xs font-extrabold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 hover:bg-rose-500 hover:text-white transition flex items-center gap-1 cursor-pointer shadow-sm">
                                        <span>✓ PAID IN FULL</span>
                                        <span class="text-[9px] font-mono opacity-80">(Mark Unpaid)</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('rider.updatePaymentStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" title="Click to mark PAID (Cash Collected)" class="px-2.5 py-1 rounded text-xs font-extrabold uppercase bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-emerald-600 hover:text-white transition flex items-center gap-1 cursor-pointer shadow-sm">
                                        <span>UNPAID</span>
                                        <span class="text-[9px] font-mono underline">(Mark Paid)</span>
                                    </button>
                                </form>
                            @endif
                            <span class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono pl-1">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- 4-Column Grid: Service Package, Weight, Payment Status, Current Stage -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 dark:bg-[#18181B] p-3 rounded-lg border border-slate-200 dark:border-zinc-800">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Service Package</span>
                            <span class="font-bold text-slate-900 dark:text-white block mt-0.5">{{ $order->service->name ?? 'Laundry Service' }}</span>
                            <span class="text-[10.5px] text-slate-500 dark:text-slate-400 font-mono">(₱{{ number_format($order->service->price ?? 250, 2) }}/load)</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Weight</span>
                            <span class="font-extrabold text-slate-900 dark:text-white font-mono block mt-0.5">{{ $order->weight_kg ? $order->weight_kg . ' kg' : '7 kg' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Payment Status</span>
                            <span class="font-extrabold uppercase block mt-0.5 {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ strtoupper($order->payment_status ?? 'UNPAID') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Current Stage</span>
                            <span class="font-extrabold text-blue-600 dark:text-blue-400 uppercase block mt-0.5">{{ strtoupper(str_replace('_', ' ', $order->order_status)) }}</span>
                        </div>
                    </div>

                    <!-- Prominent CUSTOMER REMARKS / SPECIAL INSTRUCTIONS Box -->
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-1">
                        <p class="text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">CUSTOMER REMARKS / SPECIAL INSTRUCTIONS</p>
                        <p class="text-xs font-semibold text-slate-900 dark:text-white italic">
                            "{{ $order->notes ?: '[Store Detergent & Softener]' }}"
                        </p>
                    </div>

                    <!-- Customer Address & Quick Call/SMS Actions -->
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Pickup Address</span>
                            <p class="text-slate-900 dark:text-white font-semibold leading-snug mt-0.5">{{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                        </div>
                        <div class="flex items-center gap-2 font-mono shrink-0">
                            <a href="tel:{{ $order->customer->phone ?? '' }}" class="px-2.5 py-1.5 rounded bg-blue-600 text-white font-bold text-[11px] hover:bg-blue-700 transition flex items-center gap-1">
                                Call {{ $order->customer->phone ?? 'Call' }}
                            </a>
                            @if($order->customer?->phone)
                                <a href="sms:{{ $order->customer->phone }}?body=Hi%20{{ urlencode($order->customer->name) }},%20I%20am%20your%20Hour%20Wash%20Rider%20regarding%20Order%20%23{{ $order->order_number }}" class="px-2.5 py-1.5 rounded bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700 transition flex items-center gap-1">
                                    Text SMS
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Laundry Progress Stepper -->
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-2">
                        <div class="flex items-center justify-between text-[10.5px] font-bold text-slate-600 dark:text-zinc-400">
                            <span>LAUNDRY PROGRESS TIMELINE</span>
                            <span class="text-amber-600 dark:text-amber-400 font-extrabold uppercase">{{ str_replace('_', ' ', $order->order_status) }}</span>
                        </div>
                        @php
                            $statusMap = ['pending' => 1, 'out_for_pickup' => 2, 'received' => 3, 'washing' => 4, 'rinsing' => 4, 'drying' => 4, 'finish' => 4, 'out_for_delivery' => 5, 'completed' => 6];
                            $currLvl = $statusMap[$order->order_status] ?? 1;
                        @endphp
                        <div class="grid grid-cols-5 gap-1.5 text-[9.5px] font-bold text-center">
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 1 ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">1. Requested</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 2 ? 'bg-amber-600 text-white font-black' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">2. Out Pickup</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 3 ? 'bg-blue-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">3. In Shop</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 4 ? 'bg-purple-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">4. Processing</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 6 ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">5. Done</div>
                        </div>
                    </div>

                    @if($order->pickupDelivery?->pickup_proof_image)
                        <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center gap-3">
                            <img src="{{ asset($order->pickupDelivery->pickup_proof_image) }}" alt="Pickup Proof" class="w-12 h-12 rounded object-cover border border-emerald-500/40">
                            <div>
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Proof of Pickup Photo Uploaded</p>
                                <a href="{{ asset($order->pickupDelivery->pickup_proof_image) }}" target="_blank" class="text-[11px] text-blue-600 dark:text-blue-400 underline font-bold">View Full Photo Evidence</a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-200 dark:border-zinc-800">
                        <div class="space-y-0.5">
                            <span class="text-xs font-black text-slate-900 dark:text-white font-mono block">
                                Total: ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                            <span class="text-[11px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $order->payment_status === 'paid' ? 'PAID IN FULL' : 'COD Cash to Collect: ₱' . number_format($order->total_amount, 2) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            @if($order->order_status === 'pending')
                                <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" class="inline-block flex-1 sm:flex-none">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="out_for_pickup">
                                    <button type="submit" class="w-full px-3 py-2 rounded-lg bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1">
                                        Out for Pickup
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto flex-1 sm:flex-none">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="received">

                                <select name="payment_status" class="px-2.5 py-2 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-200 transition shrink-0 w-full sm:w-auto">
                                    <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>COD: UNPAID</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>COD: PAID (Cash Collected)</option>
                                </select>

                                <label class="cursor-pointer px-2.5 py-2 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-200 transition flex items-center shrink-0 w-full sm:w-auto justify-center">
                                    <span>Camera Photo Proof</span>
                                    <input type="file" name="proof_image" accept="image/*" capture="environment" class="hidden" onchange="if(this.files[0]) this.previousElementSibling.textContent = '✓ ' + this.files[0].name.substring(0,10) + '...';">
                                </label>

                                <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1">
                                    Mark Received & In Shop
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-500 dark:text-slate-400">
                    <p class="text-xs">No active customer pickup requests right now.</p>
                </div>
            @endforelse
        </div>

        <!-- SECTION 2: IN-SHOP PROCESSING & SCHEDULED DELIVERIES -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span>In-Shop Laundry Processing & Delivery Schedule</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-blue-500/15 text-blue-600 dark:text-blue-400 border border-blue-500/30">{{ $inShopOrders->count() }}</span>
                    </h2>
                </div>
                <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">Prepped for Customer Delivery</span>
            </div>

            @forelse($inShopOrders as $order)
                <div class="app-card p-5 border-l-4 border-l-blue-500 space-y-4 rider-order-card">
                    <!-- Top Order Card Header (QR Thumbnail + Order # + Machine Badge + Actions + Price) -->
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-700 pb-3">
                        <div class="flex items-center gap-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->qrCode?->qr_token ?? $order->order_number }}" alt="QR Code Tag #{{ $order->order_number }}" class="w-12 h-12 rounded-lg border border-slate-300 dark:border-zinc-700 bg-white p-0.5 shrink-0 shadow-sm">
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('laundry.track', $order->qrCode?->qr_token ?? $order->order_number) }}" target="_blank" class="font-mono font-bold text-base text-blue-600 dark:text-blue-400 hover:underline">
                                        #{{ $order->order_number }}
                                    </a>
                                    @if($order->machine)
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-purple-500/15 text-purple-700 dark:text-purple-300 border border-purple-500/30">
                                            Machine: {{ $order->machine->machine_name }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                            No Machine Assigned
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-0.5">
                                    Customer: <span class="font-bold text-slate-900 dark:text-white">{{ $order->customer->name ?? 'Customer' }}</span>
                                    @if($order->customer?->phone)
                                        <span class="font-mono text-slate-500 dark:text-slate-400">({{ $order->customer->phone }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if($order->payment_status === 'paid')
                                <form method="POST" action="{{ route('rider.updatePaymentStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="unpaid">
                                    <button type="submit" title="Click to mark UNPAID" class="px-2.5 py-1 rounded text-xs font-extrabold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 hover:bg-rose-500 hover:text-white transition flex items-center gap-1 cursor-pointer shadow-sm">
                                        <span>✓ PAID IN FULL</span>
                                        <span class="text-[9px] font-mono opacity-80">(Mark Unpaid)</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('rider.updatePaymentStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" title="Click to mark PAID (Cash Collected)" class="px-2.5 py-1 rounded text-xs font-extrabold uppercase bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-emerald-600 hover:text-white transition flex items-center gap-1 cursor-pointer shadow-sm">
                                        <span>UNPAID</span>
                                        <span class="text-[9px] font-mono underline">(Mark Paid)</span>
                                    </button>
                                </form>
                            @endif
                            <span class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono pl-1">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- 4-Column Grid: Service Package, Weight, Payment Status, Current Stage -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 dark:bg-[#18181B] p-3 rounded-lg border border-slate-200 dark:border-zinc-800">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Service Package</span>
                            <span class="font-bold text-slate-900 dark:text-white block mt-0.5">{{ $order->service->name ?? 'Full Service with Pickup & Delivery' }}</span>
                            <span class="text-[10.5px] text-slate-500 dark:text-slate-400 font-mono">(₱{{ number_format($order->service->price ?? 250, 2) }}/load)</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Weight</span>
                            <span class="font-extrabold text-slate-900 dark:text-white font-mono block mt-0.5">{{ $order->weight_kg ? $order->weight_kg . ' kg' : '7 kg' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Payment Status</span>
                            <span class="font-extrabold uppercase block mt-0.5 {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ strtoupper($order->payment_status ?? 'UNPAID') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Current Stage</span>
                            <span class="font-extrabold text-blue-600 dark:text-blue-400 uppercase block mt-0.5">{{ strtoupper(str_replace('_', ' ', $order->order_status)) }}</span>
                        </div>
                    </div>

                    <!-- Prominent CUSTOMER REMARKS / SPECIAL INSTRUCTIONS Box -->
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-1">
                        <p class="text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">CUSTOMER REMARKS / SPECIAL INSTRUCTIONS</p>
                        <p class="text-xs font-semibold text-slate-900 dark:text-white italic">
                            "{{ $order->notes ?: '[Store Detergent & Softener]' }}"
                        </p>
                    </div>

                    <!-- Customer Address & Quick Call/SMS Actions -->
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Delivery Destination</span>
                            <p class="text-slate-900 dark:text-white font-semibold leading-snug mt-0.5">{{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                        </div>
                        <div class="flex items-center gap-2 font-mono shrink-0">
                            <a href="tel:{{ $order->customer->phone ?? '' }}" class="px-2.5 py-1.5 rounded bg-blue-600 text-white font-bold text-[11px] hover:bg-blue-700 transition flex items-center gap-1">
                                Call {{ $order->customer->phone ?? 'Call' }}
                            </a>
                            @if($order->customer?->phone)
                                <a href="sms:{{ $order->customer->phone }}?body=Hi%20{{ urlencode($order->customer->name) }},%20inquiring%20about%20Hour%20Wash%20Order%20%23{{ $order->order_number }}" class="px-2.5 py-1.5 rounded bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700 transition flex items-center gap-1">
                                    Text SMS
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Laundry Progress Stepper -->
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-2">
                        <div class="flex items-center justify-between text-[10.5px] font-bold text-slate-600 dark:text-zinc-400">
                            <span>IN-SHOP LAUNDRY PROGRESS & DISPATCH PREP</span>
                            <span class="text-blue-600 dark:text-blue-400 font-extrabold uppercase">{{ str_replace('_', ' ', $order->order_status) }}</span>
                        </div>
                        @php
                            $statusMap = ['pending' => 1, 'out_for_pickup' => 2, 'received' => 3, 'washing' => 4, 'rinsing' => 4, 'drying' => 4, 'finish' => 4, 'out_for_delivery' => 5, 'completed' => 6];
                            $currLvl = $statusMap[$order->order_status] ?? 3;
                        @endphp
                        <div class="grid grid-cols-5 gap-1.5 text-[9.5px] font-bold text-center">
                            <div class="py-1 px-0.5 rounded bg-amber-500 text-white">1. Requested</div>
                            <div class="py-1 px-0.5 rounded bg-amber-600 text-white">2. Out Pickup</div>
                            <div class="py-1 px-0.5 rounded bg-blue-600 text-white font-black">3. In Shop</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 4 ? 'bg-purple-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">4. Processing</div>
                            <div class="py-1 px-0.5 rounded {{ $currLvl >= 5 ? 'bg-cyan-600 text-white' : 'bg-slate-200 dark:bg-zinc-800 text-slate-400' }}">5. Out Delivery</div>
                        </div>
                    </div>

                    @if($order->pickupDelivery?->pickup_proof_image)
                        <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center gap-3">
                            <img src="{{ asset($order->pickupDelivery->pickup_proof_image) }}" alt="Pickup Proof" class="w-12 h-12 rounded object-cover border border-emerald-500/40">
                            <div>
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Proof of Pickup Photo Uploaded</p>
                                <a href="{{ asset($order->pickupDelivery->pickup_proof_image) }}" target="_blank" class="text-[11px] text-blue-600 dark:text-blue-400 underline font-bold">View Full Photo Evidence</a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-200 dark:border-zinc-800">
                        <div class="space-y-0.5">
                            <span class="text-xs font-black text-slate-900 dark:text-white font-mono block">
                                Total: ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                            <span class="text-[11px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $order->payment_status === 'paid' ? 'PAID IN FULL' : 'COD Cash to Collect: ₱' . number_format($order->total_amount, 2) }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($order->order_status === 'finish')
                                <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="out_for_delivery">
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs shadow transition flex items-center gap-1">
                                        Out for Delivery
                                    </button>
                                </form>
                            @else
                                <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                    Currently being washed/processed in shop (Ready for dispatch soon)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-500 dark:text-slate-400">
                    <p class="text-xs">No laundry currently processing in-shop right now.</p>
                </div>
            @endforelse
        </div>

        <!-- SECTION 3: DELIVERY DISPATCHES -->
        <div class="space-y-4 pt-2">
            <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span>Clean Laundry Delivery Dispatches</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-mono bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30">{{ $deliveryOrders->count() }}</span>
                </h2>
                <span class="text-xs text-cyan-600 dark:text-cyan-400 font-semibold">Deliver Laundry to Customer</span>
            </div>

            @forelse($deliveryOrders as $order)
                <div class="app-card p-5 border-l-4 border-l-cyan-500 space-y-4 rider-order-card">
                    <!-- Top Order Card Header (QR Thumbnail + Order # + Machine Badge + Actions + Price) -->
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-700 pb-3">
                        <div class="flex items-center gap-3">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $order->qrCode?->qr_token ?? $order->order_number }}" alt="QR Code Tag #{{ $order->order_number }}" class="w-12 h-12 rounded-lg border border-slate-300 dark:border-zinc-700 bg-white p-0.5 shrink-0 shadow-sm">
                            <div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('laundry.track', $order->qrCode?->qr_token ?? $order->order_number) }}" target="_blank" class="font-mono font-bold text-base text-blue-600 dark:text-blue-400 hover:underline">
                                        #{{ $order->order_number }}
                                    </a>
                                    @if($order->machine)
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-purple-500/15 text-purple-700 dark:text-purple-300 border border-purple-500/30">
                                            Machine: {{ $order->machine->machine_name }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                            No Machine Assigned
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mt-0.5">
                                    Customer: <span class="font-bold text-slate-900 dark:text-white">{{ $order->customer->name ?? 'Customer' }}</span>
                                    @if($order->customer?->phone)
                                        <span class="font-mono text-slate-500 dark:text-slate-400">({{ $order->customer->phone }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if($order->payment_status === 'paid')
                                <form method="POST" action="{{ route('rider.updatePaymentStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="unpaid">
                                    <button type="submit" title="Click to mark UNPAID" class="px-2.5 py-1 rounded text-xs font-extrabold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 hover:bg-rose-500 hover:text-white transition flex items-center gap-1 cursor-pointer shadow-sm">
                                        <span>✓ PAID IN FULL</span>
                                        <span class="text-[9px] font-mono opacity-80">(Mark Unpaid)</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('rider.updatePaymentStatus', $order->id) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" title="Click to mark PAID (Cash Collected)" class="px-2.5 py-1 rounded text-xs font-extrabold uppercase bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-emerald-600 hover:text-white transition flex items-center gap-1 cursor-pointer shadow-sm">
                                        <span>UNPAID</span>
                                        <span class="text-[9px] font-mono underline">(Mark Paid)</span>
                                    </button>
                                </form>
                            @endif
                            <span class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono pl-1">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- 4-Column Grid: Service Package, Weight, Payment Status, Current Stage -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 dark:bg-[#18181B] p-3 rounded-lg border border-slate-200 dark:border-zinc-800">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Service Package</span>
                            <span class="font-bold text-slate-900 dark:text-white block mt-0.5">{{ $order->service->name ?? 'Laundry Service' }}</span>
                            <span class="text-[10.5px] text-slate-500 dark:text-slate-400 font-mono">(₱{{ number_format($order->service->price ?? 250, 2) }}/load)</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Weight</span>
                            <span class="font-extrabold text-slate-900 dark:text-white font-mono block mt-0.5">{{ $order->weight_kg ? $order->weight_kg . ' kg' : '7 kg' }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Payment Status</span>
                            <span class="font-extrabold uppercase block mt-0.5 {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ strtoupper($order->payment_status ?? 'UNPAID') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Current Stage</span>
                            <span class="font-extrabold text-blue-600 dark:text-blue-400 uppercase block mt-0.5">{{ strtoupper(str_replace('_', ' ', $order->order_status)) }}</span>
                        </div>
                    </div>

                    <!-- Prominent CUSTOMER REMARKS / SPECIAL INSTRUCTIONS Box -->
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-1">
                        <p class="text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">CUSTOMER REMARKS / SPECIAL INSTRUCTIONS</p>
                        <p class="text-xs font-semibold text-slate-900 dark:text-white italic">
                            "{{ $order->notes ?: '[Store Detergent & Softener]' }}"
                        </p>
                    </div>

                    <!-- Customer Address & Quick Call/SMS Actions -->
                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                        <div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Delivery Destination</span>
                            <p class="text-slate-900 dark:text-white font-semibold leading-snug mt-0.5">{{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City' }}</p>
                        </div>
                        <div class="flex items-center gap-2 font-mono shrink-0">
                            <a href="tel:{{ $order->customer->phone ?? '' }}" class="px-2.5 py-1.5 rounded bg-blue-600 text-white font-bold text-[11px] hover:bg-blue-700 transition flex items-center gap-1">
                                Call {{ $order->customer->phone ?? 'Call' }}
                            </a>
                            @if($order->customer?->phone)
                                <a href="sms:{{ $order->customer->phone }}?body=Hi%20{{ urlencode($order->customer->name) }},%20I%20am%20delivering%20your%20Hour%20Wash%20Order%20%23{{ $order->order_number }}" class="px-2.5 py-1.5 rounded bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700 transition flex items-center gap-1">
                                    Text SMS
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Laundry Progress Stepper -->
                    <div class="p-3 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-2">
                        <div class="flex items-center justify-between text-[10.5px] font-bold text-slate-600 dark:text-zinc-400">
                            <span>LAUNDRY PROGRESS TIMELINE</span>
                            <span class="text-cyan-600 dark:text-cyan-400 font-extrabold uppercase">{{ str_replace('_', ' ', $order->order_status) }}</span>
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
                                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400">Proof of Delivery Photo Uploaded</p>
                                <a href="{{ asset($order->pickupDelivery->delivery_proof_image) }}" target="_blank" class="text-[11px] text-blue-600 dark:text-blue-400 underline font-bold">View Full Photo Evidence</a>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-200 dark:border-zinc-800">
                        <div class="space-y-0.5">
                            <span class="text-xs font-black text-slate-900 dark:text-white font-mono block">
                                Total: ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                            <span class="text-[11px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $order->payment_status === 'paid' ? 'PAID IN FULL' : 'COD Cash to Collect: ₱' . number_format($order->total_amount, 2) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            @if($order->order_status === 'finish')
                                <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" class="inline-block flex-1 sm:flex-none">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="out_for_delivery">
                                    <button type="submit" class="w-full px-3 py-2 rounded-lg bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1">
                                        Out for Delivery
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('rider.updateStatus', $order->id) }}" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto flex-1 sm:flex-none">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">

                                <select name="payment_status" class="px-2.5 py-2 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-200 transition shrink-0 w-full sm:w-auto">
                                    <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>COD: UNPAID</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>COD: PAID (Cash Collected)</option>
                                </select>

                                <label class="cursor-pointer px-2.5 py-2 rounded-lg bg-slate-100 dark:bg-zinc-800 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 text-xs font-semibold hover:bg-slate-200 transition flex items-center shrink-0 w-full sm:w-auto justify-center">
                                    <span>Camera Photo Proof</span>
                                    <input type="file" name="proof_image" accept="image/*" capture="environment" class="hidden" onchange="if(this.files[0]) this.previousElementSibling.textContent = '✓ ' + this.files[0].name.substring(0,10) + '...';">
                                </label>

                                <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-1">
                                    Mark Delivered & Completed
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="app-card p-6 text-center text-slate-500 dark:text-slate-400">
                    <p class="text-xs">No active delivery dispatches right now.</p>
                </div>
            @endforelse
        </div>

    </div>

    <script>
        function filterRiderOrders() {
            const input = document.getElementById('riderSearchInput');
            if (!input) return;
            const filter = input.value.toLowerCase().trim();
            const orderCards = document.querySelectorAll('.rider-order-card');
            let visibleCount = 0;

            orderCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (!filter || text.includes(filter)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            const countEl = document.getElementById('visibleOrderCount');
            if (countEl) {
                countEl.textContent = visibleCount;
            }
        }
    </script>
</x-app-layout>
