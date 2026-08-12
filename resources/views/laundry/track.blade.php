<x-app-layout>

<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Live Order & QR Tracker</h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Public status verification for Order #{{ $order->order_number }}</p>
        </div>
        @auth
            @if(auth()->user()->isOwner())
                <a href="{{ route('admin.dashboard') }}" class="btn-ios-secondary text-xs">Back to Dashboard</a>
            @elseif(auth()->user()->isStaff())
                <a href="{{ route('staff.dashboard') }}" class="btn-ios-secondary text-xs">Back to Dashboard</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-ios-secondary text-xs">Back to Dashboard</a>
            @endif
        @else
            <a href="{{ route('welcome') }}" class="btn-ios-secondary text-xs">Back to Storefront</a>
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

        <!-- 5-Stage Live Progress Bar -->
        <div class="space-y-3">
            <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-slate-500 dark:text-slate-400">Cleaning Progress:</span>
                <span class="text-[#007AFF] dark:text-[#0A84FF] font-bold capitalize">{{ str_replace('_', ' ', $order->order_status) }}</span>
            </div>
            
            <div class="w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden flex p-0.5">
                <div class="h-full bg-[#007AFF] dark:bg-[#0A84FF] rounded-full transition-all duration-500
                    @if($order->order_status === 'pending' || $order->order_status === 'received') w-[20%]
                    @elseif($order->order_status === 'washing') w-[40%]
                    @elseif($order->order_status === 'rinsing') w-[60%]
                    @elseif($order->order_status === 'drying') w-[80%]
                    @elseif($order->order_status === 'ready' || $order->order_status === 'completed') w-[100%]
                    @else w-[10%] @endif"></div>
            </div>

            <div class="grid grid-cols-5 text-center text-[10px] font-bold text-slate-500 dark:text-slate-400 pt-1">
                <div class="@if(in_array($order->order_status, ['received','washing','rinsing','drying','ready','completed'])) text-emerald-600 dark:text-emerald-400 @endif">Received</div>
                <div class="@if(in_array($order->order_status, ['washing','rinsing','drying','ready','completed'])) text-[#007AFF] dark:text-[#0A84FF] @endif">● Washing</div>
                <div class="@if(in_array($order->order_status, ['rinsing','drying','ready','completed'])) text-indigo-600 dark:text-indigo-400 @endif">● Rinsing</div>
                <div class="@if(in_array($order->order_status, ['drying','ready','completed'])) text-amber-600 dark:text-amber-400 @endif">● Drying</div>
                <div class="@if(in_array($order->order_status, ['ready','completed'])) text-emerald-600 dark:text-emerald-400 @endif">Ready</div>
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