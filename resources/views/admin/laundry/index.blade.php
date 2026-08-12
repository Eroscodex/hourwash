<x-app-layout>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Store Orders & Cashier Queue</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Manage active laundry stages, process cashier payments, award loyalty points, and print store receipts.</p>
            </div>
            <a href="{{ route('laundry.create') }}" class="btn-ios-primary w-full sm:w-fit text-center">+ New Drop-Off Order</a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Live Customer SMS Outbox & Gateway Terminal -->
        <div class="app-card p-5 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-black/10 dark:border-white/10 pb-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">
                        Live SMS Customer Outbox
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Automated SMS text messages generated & dispatched to customer phone numbers</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-xs font-bold">
                    {{ count($smsLogs ?? []) }} SMS Dispatched
                </span>
            </div>

            <div class="space-y-3">
                @forelse($smsLogs ?? [] as $sms)
                    <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-1">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 text-xs">
                            <span class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                Phone: <strong class="text-[#007AFF] dark:text-[#0A84FF] font-mono">{{ $sms->phone }}</strong>
                            </span>
                            <span class="text-[10px] text-slate-500 font-mono">{{ $sms->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p class="text-xs text-slate-700 dark:text-slate-300 font-mono bg-white dark:bg-black/30 p-2.5 rounded-lg border border-black/5 dark:border-white/5 break-all break-words">
                            {{ $sms->message }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-4 text-xs text-slate-500">No SMS text messages in outbox log yet.</div>
                @endforelse
            </div>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="app-card p-5 space-y-4 shadow-sm hover:border-[#007AFF]/30 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-black/10 dark:border-white/10 pb-3">
                        <div class="flex items-center gap-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                                 alt="QR Tag #{{ $order->order_number }}" 
                                 class="w-14 h-14 bg-white p-1 rounded-xl border border-slate-200 shadow-sm flex-shrink-0">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-bold font-mono text-[#007AFF] dark:text-[#0A84FF]">#{{ $order->order_number }}</h3>
                                    @php
                                        $pts = $order->customer->customerProfile->loyalty_points ?? 0;
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $pts >= 200 ? 'bg-amber-500/20 text-amber-600 border border-amber-500/30' : 'bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-slate-300' }}">
                                        {{ $pts >= 200 ? 'VIP MEMBER ('.$pts.' pts)' : $pts.' pts' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Customer: <strong class="text-slate-900 dark:text-white">{{ $order->customer->name ?? 'Walk-in' }}</strong> ({{ $order->customer->phone ?? 'N/A' }})</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('laundry.track', $order->qrCode->qr_token ?? $order->order_number) }}" class="btn-ios-secondary text-xs">
                                Status Update
                            </a>
                            <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-3 py-2 rounded-xl text-xs font-bold hover:opacity-90 transition flex items-center gap-1.5 shadow-sm">
                                Receipt
                            </a>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold text-xl font-['Outfit']">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Service Package</span>
                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ $order->service->name ?? 'Standard Wash' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Weight</span>
                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ $order->weight_kg }} kg</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Payment Status</span>
                            <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Current Stage</span>
                            <span class="font-bold uppercase text-[#007AFF] dark:text-[#0A84FF]">{{ str_replace('_', ' ', $order->order_status) }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-black/5 dark:border-white/5">
                        <form method="POST" action="{{ route('admin.laundry.update', $order->id) }}" class="flex flex-wrap items-center gap-2">
                            @csrf
                            @method('PATCH')
                            
                            <select name="status" class="py-1 px-2.5 text-xs rounded-xl">
                                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="received" {{ $order->order_status === 'received' ? 'selected' : '' }}>Received</option>
                                <option value="washing" {{ $order->order_status === 'washing' ? 'selected' : '' }}>Washing</option>
                                <option value="rinsing" {{ $order->order_status === 'rinsing' ? 'selected' : '' }}>Rinsing</option>
                                <option value="drying" {{ $order->order_status === 'drying' ? 'selected' : '' }}>Drying</option>
                                <option value="ready" {{ $order->order_status === 'ready' ? 'selected' : '' }}>Ready for Pickup</option>
                                <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>

                            <select name="payment_status" class="py-1 px-2.5 text-xs rounded-xl">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>

                            <button type="submit" class="btn-ios-primary py-1 px-3 text-xs">
                                Update Order & Award Points
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.laundry.extend', $order->id) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="delay_minutes" class="py-1 px-2 text-xs rounded-xl">
                                <option value="30">+30 mins delay</option>
                                <option value="60" selected>+60 mins delay</option>
                                <option value="120">+2 hours delay</option>
                                <option value="180">+3 hours delay</option>
                            </select>
                            <button type="submit" onclick="return confirm('Extend estimated completion time for Power Outage / Interruption?')" class="bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 hover:bg-amber-500/25 px-3 py-1.5 rounded-xl text-xs font-bold transition">
                                Power Outage Extension
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="app-card p-8 text-center text-slate-500 text-xs">
                    No laundry orders registered in the store system yet.
                </div>
            @endforelse
        </div>
    </div>

</x-app-layout>