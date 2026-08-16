<x-app-layout>

<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full bg-[#EE4D2D]/15 text-[#EE4D2D] dark:text-[#FF6647] text-[10px] font-extrabold uppercase tracking-wider border border-[#EE4D2D]/30">
                    SHOPEE STYLE TRACKER
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Live Order #{{ $order->order_number }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold font-['Outfit'] text-slate-900 dark:text-white mt-1">
                Parcel & Order Tracking
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="px-3 py-2 rounded-xl bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-xs font-bold hover:opacity-90 transition shadow-sm flex items-center gap-1.5">
                🖨️ Print Receipt
            </a>

            @auth
                @if(auth()->user()->isOwner() || auth()->user()->isStaff())
                    <a href="{{ route('admin.laundry.index') }}" class="btn-ios-secondary text-xs">Back to Orders</a>
                @else
                    <a href="{{ route('my.orders') }}" class="btn-ios-secondary text-xs">My Orders</a>
                @endif
            @else
                <a href="{{ route('welcome') }}" class="btn-ios-secondary text-xs">Home</a>
            @endauth
        </div>
    </div>

    <div class="app-card p-5 sm:p-7 space-y-6 shadow-xl border-t-4 border-t-[#EE4D2D]">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-black/10 dark:border-white/10 pb-5">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg sm:text-xl font-black font-mono text-slate-900 dark:text-white">
                        #{{ $order->order_number }}
                    </h2>
                    <button onclick="navigator.clipboard.writeText('{{ $order->order_number }}'); alert('Order ID copied to clipboard!')" class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-white/10 text-slate-600 dark:text-slate-300 text-[10px] font-bold hover:bg-[#007AFF] hover:text-white transition">
                        📋 Copy
                    </button>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Placed on {{ $order->created_at->format('M d, Y • h:i A') }}
                </p>
            </div>

            <div class="text-left sm:text-right space-y-1">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">CURRENT LOGISTICS STATUS</span>
                @php
                    $statusBadge = match($order->order_status) {
                        'completed' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
                        'finish' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                        'cancelled' => 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/30',
                        default => 'bg-[#EE4D2D]/15 text-[#EE4D2D] dark:text-[#FF6647] border-[#EE4D2D]/30',
                    };
                @endphp
                <span class="inline-block px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider border {{ $statusBadge }}">
                    {{ $order->order_status === 'finish' ? 'FINISH & READY' : str_replace('_', ' ', $order->order_status) }}
                </span>
            </div>
        </div>

        @php
            $stages = [
                'pending'          => ['label' => 'Order Placed',      'icon' => '📦', 'pct' => 10],
                'out_for_pickup'   => ['label' => 'Out for Pickup',    'icon' => '🚚', 'pct' => 22],
                'received'         => ['label' => 'Store Received',    'icon' => '📥', 'pct' => 35],
                'washing'          => ['label' => 'Washing',           'icon' => '🧼', 'pct' => 48],
                'rinsing'          => ['label' => 'Rinsing',           'icon' => '💧', 'pct' => 60],
                'drying'           => ['label' => 'Drying',            'icon' => '💨', 'pct' => 72],
                'finish'           => ['label' => 'Finish & Shelved',  'icon' => '✨', 'pct' => 84],
                'out_for_delivery' => ['label' => 'Out for Delivery',  'icon' => '🛵', 'pct' => 92],
                'completed'        => ['label' => 'Completed',         'icon' => '🎉', 'pct' => 100],
            ];
            
            $currentStatus = $order->order_status;
            $currentStageInfo = $stages[$currentStatus] ?? ['label' => 'Processing', 'icon' => '⚙️', 'pct' => 0];
            
            $statusKeys = array_keys($stages);
            $currentIndex = array_search($currentStatus, $statusKeys);
            if ($currentIndex === false) {
                $currentIndex = -1;
            }
        @endphp

        <div class="space-y-6 bg-slate-50 dark:bg-[#1C1C1E] p-5 rounded-2xl border border-black/5 dark:border-white/10">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                    🚚 Shopee Parcel Tracking Progress
                </span>
                <span class="text-xs font-extrabold text-[#EE4D2D] dark:text-[#FF6647]">
                    {{ $currentStageInfo['pct'] }}% Completed
                </span>
            </div>

            <div class="relative w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden p-0.5">
                <div class="h-full bg-gradient-to-r from-[#EE4D2D] via-amber-500 to-emerald-500 rounded-full transition-all duration-700 shadow-sm"
                     style="width: {{ $currentStageInfo['pct'] }}%"></div>
            </div>

            @if($currentStatus === 'cancelled')
                <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 p-3.5 rounded-xl text-xs font-semibold text-center">
                    🚫 This order has been Cancelled.
                </div>
            @endif

            <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-9 gap-2 text-center text-[10px] font-extrabold">
                @foreach($stages as $key => $info)
                    @php
                        $stageIdx = array_search($key, $statusKeys);
                        $isActive = ($currentIndex >= $stageIdx && $currentStatus !== 'cancelled');
                        $isCurrent = ($currentStatus === $key);
                    @endphp
                    <div class="p-2 rounded-xl border flex flex-col items-center justify-between gap-1.5 transition-all duration-300 relative {{ $isCurrent ? 'bg-[#EE4D2D]/15 border-[#EE4D2D] text-[#EE4D2D] dark:text-[#FF6647] scale-105 shadow-md' : ($isActive ? 'border-emerald-500/40 text-emerald-600 dark:text-emerald-400 bg-emerald-500/10' : 'border-black/5 dark:border-white/5 text-slate-400 opacity-50') }}">
                        <span class="text-base">
                            {{ $info['icon'] }}
                        </span>
                        <span class="leading-tight block font-['Outfit']">
                            {{ $info['label'] }}
                        </span>
                        @if($isCurrent)
                            <span class="w-1.5 h-1.5 rounded-full bg-[#EE4D2D] animate-ping absolute -top-1 -right-1"></span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            
            <div class="md:col-span-7 space-y-4">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#EE4D2D] dark:text-[#FF6647] uppercase tracking-wider border-b border-black/5 dark:border-white/10 pb-2">
                        <span>📍 Delivery & Pickup Address</span>
                    </div>

                    <div class="space-y-1 text-xs">
                        <div class="font-extrabold text-slate-900 dark:text-white text-sm">
                            {{ $order->customer->name ?? 'Store Walk-in Customer' }}
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 font-mono">
                            📞 {{ $order->customer->phone ?? '+63 917 123 4567' }}
                        </div>
                        <div class="text-slate-500 dark:text-slate-400 pt-1 leading-relaxed">
                            🏠 {{ $order->customer->customerProfile->address ?? 'Magallanes St., Orosite, Legazpi City, Albay' }}
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-3">
                    <div class="flex items-center justify-between border-b border-black/5 dark:border-white/10 pb-2">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            📝 Order Items & Package Summary
                        </span>
                        <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400">
                            {{ $order->weight_kg }} kg Total Weight
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-[#EE4D2D]/10 text-[#EE4D2D] dark:text-[#FF6647] flex items-center justify-center text-lg font-bold">
                                🧺
                            </span>
                            <div>
                                <h4 class="font-bold text-slate-900 dark:text-white">
                                    {{ $order->service->name ?? 'Standard Laundry Wash' }}
                                </h4>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                    ₱{{ number_format($order->service->price ?? 0, 2) }} / {{ $order->service->price_unit ?? 'kg' }} • {{ $order->weight_kg }} kg
                                </span>
                            </div>
                        </div>
                        <span class="font-extrabold text-slate-900 dark:text-white">
                            ₱{{ number_format($order->subtotal ?? $order->total_amount, 2) }}
                        </span>
                    </div>

                    <div class="border-t border-black/5 dark:border-white/10 pt-3 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Store Delivery Fee</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">
                                {{ $order->delivery_fee > 0 ? '₱'.number_format($order->delivery_fee, 2) : 'FREE' }}
                            </span>
                        </div>
                        @if(($order->discount ?? 0) > 0)
                            <div class="flex justify-between text-rose-500 font-semibold">
                                <span>Discounts Applied</span>
                                <span>-₱{{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-sm font-extrabold text-slate-900 dark:text-white pt-2 border-t border-black/10 dark:border-white/10">
                            <span>Total Payment Amount</span>
                            <span class="text-lg font-black text-[#EE4D2D] dark:text-[#FF6647] font-['Outfit']">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-5 space-y-4">
                
                <div class="p-4 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white space-y-3 shadow-md">
                    <div class="flex items-center justify-between border-b border-white/10 pb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-400">ESTIMATED COMPLETION</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    </div>

                    <div>
                        <div class="text-lg font-bold font-['Outfit'] text-white">
                            {{ $order->estimated_completion?->format('M d, Y • h:i A') ?? 'In Processing' }}
                        </div>
                        @if(in_array($order->order_status, ['pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery']) && $order->estimated_completion && $order->estimated_completion->isFuture())
                            <div class="mt-2 p-2.5 rounded-xl bg-white/10 border border-white/10 flex items-center justify-between text-xs font-mono font-bold text-amber-300">
                                <span>⏳ Time Remaining:</span>
                                <span id="order-countdown" data-expiry="{{ $order->estimated_completion->timestamp }}">Calculating...</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 text-center space-y-3">
                    <span class="text-[10px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">
                        SCANNABLE QR LAUNDRY TAG
                    </span>

                    <div class="w-36 h-36 mx-auto bg-white p-2.5 rounded-2xl shadow-md border border-slate-200 flex items-center justify-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                             alt="QR Code Tag #{{ $order->order_number }}" 
                             class="w-full h-full rounded-xl">
                    </div>

                    <div class="text-xs">
                        <span class="font-mono text-slate-500 dark:text-slate-400 block text-[10px]">QR Token ID</span>
                        <span class="font-bold font-mono text-[#007AFF] dark:text-[#0A84FF] text-xs">
                            {{ $order->qrCode->qr_token ?? $order->order_number }}
                        </span>
                    </div>
                </div>

            </div>

        </div>

        <div class="space-y-3 border-t border-black/10 dark:border-white/10 pt-5">
            <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-2">
                <span>📋 Shopee Detailed Logistics History & Status Updates</span>
            </h3>

            <div class="relative pl-6 space-y-4 border-l-2 border-slate-200 dark:border-slate-800 text-xs">
                @forelse($order->statusHistory->sortByDesc('created_at') as $history)
                    <div class="relative group">
                        <span class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-[#EE4D2D] border-2 border-white dark:border-[#1C1C1E]"></span>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="font-bold text-slate-900 dark:text-white capitalize">
                                Status Updated to {{ $history->status === 'finish' ? 'Finish & Shelved' : str_replace('_', ' ', $history->status) }}
                            </span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                                {{ $history->created_at->format('M d, Y • h:i A') }} ({{ $history->created_at->diffForHumans() }})
                            </span>
                        </div>
                        @if($history->remarks)
                            <p class="text-slate-600 dark:text-slate-400 text-[11px] mt-0.5 italic">
                                "{{ $history->remarks }}"
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="relative">
                        <span class="absolute -left-[31px] top-0 w-4 h-4 rounded-full bg-[#EE4D2D] border-2 border-white dark:border-[#1C1C1E]"></span>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="font-bold text-slate-900 dark:text-white">
                                Order Created & Submitted
                            </span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                                {{ $order->created_at->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-[11px] mt-0.5">
                            Order #{{ $order->order_number }} received at Hour Wash Legazpi branch.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-black/5 dark:border-white/5">
            Store Location: Magallanes St., Orosite, Legazpi City • Hour Wash Logistics & Tracking
        </div>

    </div>

</div>

@if(in_array($order->order_status, ['pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery']) && $order->estimated_completion && $order->estimated_completion->isFuture())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const countdownEl = document.getElementById('order-countdown');
        if (countdownEl) {
            const expiryTimestamp = parseInt(countdownEl.getAttribute('data-expiry')) * 1000;
            
            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiryTimestamp - now;
                
                if (distance < 0) {
                    countdownEl.innerText = "Processing Completion...";
                    clearInterval(timerInterval);
                    return;
                }
                
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                let timeString = "";
                if (hours > 0) {
                    timeString += hours + "h ";
                }
                timeString += minutes + "m " + seconds + "s";
                
                countdownEl.innerText = timeString;
            }
            
            updateCountdown();
            const timerInterval = setInterval(updateCountdown, 1000);
        }
    });
</script>
@endif

</x-app-layout>