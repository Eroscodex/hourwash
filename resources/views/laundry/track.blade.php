<x-app-layout>

<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Live Order & QR Tracker</h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Public status verification for Order #{{ $order->order_number }}</p>
        </div>
        @auth
            @if(auth()->user()->isOwner())
                <a href="{{ route('admin.laundry.index') }}" class="btn-ios-secondary text-xs">Back</a>
            @elseif(auth()->user()->isStaff())
                <a href="{{ route('admin.laundry.index') }}" class="btn-ios-secondary text-xs">Back</a>
            @else
                <a href="{{ route('my.orders') }}" class="btn-ios-secondary text-xs">Back</a>
            @endif
        @else
            <a href="{{ route('welcome') }}" class="btn-ios-secondary text-xs">Back</a>
        @endauth
    </div>

    <!-- Main Order Details Card -->
    <div class="app-card p-6 sm:p-8 space-y-6 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-black/10 dark:border-white/10 pb-4">
            <div>
                <span class="px-2.5 py-0.5 rounded bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-[10px] font-bold uppercase tracking-wider">
                    QR VERIFIED ORDER
                </span>
                <h2 class="text-xl sm:text-2xl font-bold font-mono text-slate-900 dark:text-white mt-1">#{{ $order->order_number }}</h2>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-slate-500 dark:text-slate-400 block">Total Amount</span>
                <span class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-['Outfit']">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- 10-Stage Live Progress Bar -->
        @php
            $stages = [
                'pending' => ['label' => 'Pending', 'pct' => 10],
                'out_for_pickup' => ['label' => 'Out for Pickup', 'pct' => 20],
                'received' => ['label' => 'Received', 'pct' => 30],
                'washing' => ['label' => 'Washing', 'pct' => 40],
                'rinsing' => ['label' => 'Rinsing', 'pct' => 50],
                'drying' => ['label' => 'Drying', 'pct' => 60],
                'done' => ['label' => 'Done', 'pct' => 70],
                'out_for_delivery' => ['label' => 'Out for Delivery', 'pct' => 85],
                'completed' => ['label' => 'Completed', 'pct' => 100],
                'cancelled' => ['label' => 'Cancelled', 'pct' => 100]
            ];
            $currentStatus = $order->order_status;
            $currentStageInfo = $stages[$currentStatus] ?? ['label' => 'Unknown', 'pct' => 0];
            
            $statusKeys = ['pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'done', 'out_for_delivery', 'completed'];
            $currentIndex = array_search($currentStatus, $statusKeys);
            if ($currentIndex === false) {
                $currentIndex = -1;
            }
        @endphp

        <div class="space-y-4">
            <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-slate-500 dark:text-slate-400">Order Progress:</span>
                <span class="text-[#007AFF] dark:text-[#0A84FF] font-bold capitalize">{{ str_replace('_', ' ', $currentStatus) }}</span>
            </div>
            
            <div class="w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden p-0.5">
                <div class="h-full bg-[#007AFF] dark:bg-[#0A84FF] rounded-full transition-all duration-500" style="width: {{ $currentStageInfo['pct'] }}%"></div>
            </div>

            @if($currentStatus === 'cancelled')
                <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 p-3.5 rounded-xl text-xs font-semibold text-center">
                    🚫 This order has been Cancelled.
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-9 gap-2 text-center text-[10px] font-bold text-slate-500 dark:text-slate-400">
                @foreach($statusKeys as $index => $key)
                    @php
                        $isActive = ($currentIndex >= $index && $currentStatus !== 'cancelled');
                        $isCurrent = ($currentStatus === $key);
                    @endphp
                    <div class="p-2 rounded-xl border flex flex-col items-center justify-center gap-1 transition-all duration-300 {{ $isCurrent ? 'bg-[#007AFF]/10 border-[#007AFF] text-[#007AFF] scale-105' : ($isActive ? 'border-emerald-500/30 text-emerald-600 dark:text-emerald-400 bg-emerald-500/5' : 'border-black/5 dark:border-white/5 opacity-60') }}">
                        <span class="text-xs">
                            @if($isCurrent)
                                🔵
                            @elseif($isActive)
                                ✅
                            @else
                                ⚪
                            @endif
                        </span>
                        <span>{{ $stages[$key]['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Real Scannable QR Code Container -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 p-5 bg-slate-100 dark:bg-[#2C2C2E] rounded-2xl border border-black/5 dark:border-white/10">
            <div class="space-y-1 text-center sm:text-left">
                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider block">REAL SCANNABLE QR LAUNDRY TAG</span>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Scan with Smartphone Camera</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 max-w-sm">
                    Staff and customers can scan this physical QR tag attached to the laundry bag to open instant order status updates anytime.
                </p>
                <p class="text-xs text-[#007AFF] dark:text-[#0A84FF] font-mono font-semibold pt-1">
                    Token: {{ $order->qrCode->qr_token ?? $order->order_number }}
                </p>
            </div>

            <div class="p-3 bg-white rounded-2xl shadow-md border border-slate-200 flex-shrink-0">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $order->qrCode->qr_token ?? $order->order_number }}" 
                     alt="Real Order QR Code #{{ $order->order_number }}" 
                     class="w-36 h-36 rounded-xl">
            </div>
        </div>

        <!-- Specifications Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-slate-100 dark:bg-[#2C2C2E] p-4 rounded-xl border border-black/5 dark:border-white/10">
            <div>
                <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Customer Name</span>
                <p class="text-slate-900 dark:text-white font-bold text-sm">{{ $order->customer->name ?? 'Store Customer' }}</p>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Service Package</span>
                <p class="text-slate-900 dark:text-white font-bold text-sm">{{ $order->service->name ?? 'Standard Wash' }}</p>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Weight (kg)</span>
                <p class="text-slate-900 dark:text-white font-semibold">{{ $order->weight_kg }} kg</p>
            </div>
            <div>
                <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Estimated Completion Time</span>
                <p class="text-slate-900 dark:text-white font-semibold">{{ $order->estimated_completion?->format('M d, Y h:i A') ?? 'In Progress' }}</p>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500 dark:text-slate-400 pt-2">
            Store Location: Magallanes St., Orosite, Legazpi City • Hour Wash System
        </div>
    </div>

</div>

</x-app-layout>