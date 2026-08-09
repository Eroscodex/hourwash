<x-app-layout>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">My Order History</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Review all your current and past laundry bookings and track status using QR code tags.</p>
        </div>
        <a href="{{ route('laundry.create') }}" class="btn-ios-primary w-fit">+ Book New Order</a>
    </div>

    @forelse($orders as $order)
        <div class="app-card p-5 space-y-4 shadow-sm hover:border-[#007AFF]/30 transition">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-black/10 dark:border-white/10 pb-3">
                <div>
                    <span class="px-2 py-0.5 rounded bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-[10px] font-bold uppercase tracking-wider">
                        LAUNDRY BAG ORDER
                    </span>
                    <h2 class="text-lg font-bold font-mono text-slate-900 dark:text-white mt-0.5">Order Code: #{{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $order->service->name ?? 'Wash & Dry' }}</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-emerald-600 dark:text-emerald-400 font-extrabold text-xl font-['Outfit']">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Laundry Weight</span>
                    <p class="text-slate-900 dark:text-slate-100 font-semibold">{{ $order->weight_kg }} kg</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Cleaning Stage</span>
                    <p class="text-[#007AFF] dark:text-[#0A84FF] capitalize font-bold">{{ str_replace('_', ' ', $order->order_status) }}</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Est. Completion</span>
                    <p class="text-slate-900 dark:text-slate-200 font-semibold">{{ $order->estimated_completion?->format('M d, Y h:i A') ?? 'TBD' }}</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">QR Code Tracking & Receipt</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <a href="{{ route('laundry.track', $order->qrCode->qr_token ?? $order->order_number) }}" class="inline-flex items-center gap-1 text-[#007AFF] dark:text-[#0A84FF] font-bold hover:underline">
                            <span>📱 Track</span>
                        </a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-300 font-bold hover:underline">
                            <span>🧾 Receipt</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="app-card p-8 text-center text-slate-500 text-xs">
            No order history found. Book your first laundry service today!
        </div>
    @endforelse

</div>

</x-app-layout>