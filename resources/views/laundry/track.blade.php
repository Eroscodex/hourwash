<x-app-layout>

<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-1 sm:px-0">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-[10px] font-extrabold uppercase tracking-wider border border-blue-600/30">
                    LIVE TRACKER
                </span>
                <span class="text-xs text-slate-500 dark:text-slate-400 font-mono truncate max-w-[180px] sm:max-w-none">Order #{{ $order->order_number }}</span>
            </div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white mt-1">
                Order Tracking & Verification
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff()))
                <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="flex-1 sm:flex-none text-center px-3 py-2 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition shadow-sm">
                    Print Thermal Receipt
                </a>
            @else
                <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="flex-1 sm:flex-none text-center px-3 py-2 rounded-lg bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-xs font-bold hover:opacity-90 transition shadow-sm">
                    View Digital Receipt
                </a>
            @endif

            @auth
                @if(auth()->user()->isOwner() || auth()->user()->isStaff())
                    <a href="{{ route('admin.laundry.index') }}" class="flex-1 sm:flex-none text-center btn-secondary text-xs">Back to Orders</a>
                @else
                    <a href="{{ route('my.orders') }}" class="flex-1 sm:flex-none text-center btn-secondary text-xs">My Orders</a>
                @endif
            @else
                <a href="{{ route('welcome') }}" class="flex-1 sm:flex-none text-center btn-secondary text-xs">Home</a>
            @endauth
        </div>
    </div>

    <div class="app-card p-4 sm:p-7 space-y-5 sm:space-y-6 shadow-sm border-t-4 border-t-[#2563EB]">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 border-b border-slate-200 dark:dark:border-zinc-700 pb-4 sm:pb-5">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg sm:text-xl font-black font-mono text-slate-900 dark:text-white">
                        #{{ $order->order_number }}
                    </h2>
                    <button onclick="navigator.clipboard.writeText('{{ $order->order_number }}'); alert('Order ID copied to clipboard!')" class="px-2 py-0.5 rounded-md bg-slate-100 dark:dark:bg-zinc-800 text-slate-600 dark:text-slate-300 text-[10px] font-bold hover:bg-blue-600 hover:text-white transition">
                        Copy
                    </button>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Placed on {{ $order->created_at->format('M d, Y • h:i A') }}
                </p>
            </div>

            <div class="text-left sm:text-right space-y-1">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">DISPATCH & PAYMENT STATUS</span>
                @php
                    $statusBadge = match($order->order_status) {
                        'completed' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
                        'finish' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border-amber-500/30',
                        'cancelled' => 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border-rose-500/30',
                        default => 'bg-blue-600/15 text-blue-600 dark:text-blue-400 border-blue-600/30',
                    };
                @endphp
                <div class="flex items-center sm:justify-end gap-2">
                    <span class="inline-block px-3 py-1 rounded-md text-xs font-black uppercase tracking-wider border {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border-rose-500/30' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                    <span class="inline-block px-3 py-1 rounded-md text-xs font-black uppercase tracking-wider border {{ $statusBadge }}">
                        {{ $order->order_status === 'finish' ? 'FINISH & READY' : str_replace('_', ' ', $order->order_status) }}
                    </span>
                </div>
            </div>
        </div>

        @php
            $serviceType = $order->service?->service_type ?? '';
            $serviceName = strtolower($order->service?->name ?? '');

            $isWashOnly = str_contains($serviceName, 'wash only') || ($serviceType === 'wash') || (str_contains($serviceName, 'wash') && !str_contains($serviceName, 'dry') && !str_contains($serviceName, 'fold') && !str_contains($serviceName, 'full'));
            $isDryOnly = str_contains($serviceName, 'dry only') || ($serviceType === 'dry') || (str_contains($serviceName, 'dry') && !str_contains($serviceName, 'wash') && !str_contains($serviceName, 'full'));
            $isFoldOnly = str_contains($serviceName, 'fold only') || ($serviceType === 'fold') || (str_contains($serviceName, 'fold') && !str_contains($serviceName, 'wash') && !str_contains($serviceName, 'dry'));

            $isPickupDeliveryService = ($serviceType === 'pickup_delivery' || str_contains($serviceName, 'pickup'));
            $isPickupType = in_array($order->pickup_type, ['pickup', 'delivery', 'pickup_delivery']);
            $isWalkIn = (! $isPickupDeliveryService && ! $isPickupType);

            if ($isWashOnly) {
                if ($isWalkIn) {
                    $stages = [
                        'pending'   => ['step' => 1, 'label' => 'ORDER PLACED',    'pct' => 16],
                        'received'  => ['step' => 2, 'label' => 'STORE RECEIVED',  'pct' => 33],
                        'washing'   => ['step' => 3, 'label' => 'WASHING',         'pct' => 50],
                        'rinsing'   => ['step' => 4, 'label' => 'RINSING',         'pct' => 66],
                        'finish'    => ['step' => 5, 'label' => 'READY FOR PICKUP','pct' => 83],
                        'completed' => ['step' => 6, 'label' => 'COMPLETED',       'pct' => 100],
                    ];
                } else {
                    $stages = [
                        'pending'          => ['step' => 1, 'label' => 'ORDER PLACED',    'pct' => 14],
                        'out_for_pickup'   => ['step' => 2, 'label' => 'OUT FOR PICKUP',  'pct' => 28],
                        'received'         => ['step' => 3, 'label' => 'STORE RECEIVED',  'pct' => 42],
                        'washing'          => ['step' => 4, 'label' => 'WASHING',         'pct' => 57],
                        'rinsing'          => ['step' => 5, 'label' => 'RINSING',         'pct' => 71],
                        'finish'           => ['step' => 6, 'label' => 'READY FOR PICKUP','pct' => 82],
                        'out_for_delivery' => ['step' => 7, 'label' => 'OUT FOR DELIVERY','pct' => 91],
                        'completed'        => ['step' => 8, 'label' => 'COMPLETED',       'pct' => 100],
                    ];
                }
            } elseif ($isDryOnly) {
                if ($isWalkIn) {
                    $stages = [
                        'pending'   => ['step' => 1, 'label' => 'ORDER PLACED',    'pct' => 20],
                        'received'  => ['step' => 2, 'label' => 'STORE RECEIVED',  'pct' => 40],
                        'drying'    => ['step' => 3, 'label' => 'DRYING',          'pct' => 60],
                        'finish'    => ['step' => 4, 'label' => 'READY FOR PICKUP','pct' => 80],
                        'completed' => ['step' => 5, 'label' => 'COMPLETED',       'pct' => 100],
                    ];
                } else {
                    $stages = [
                        'pending'          => ['step' => 1, 'label' => 'ORDER PLACED',    'pct' => 16],
                        'out_for_pickup'   => ['step' => 2, 'label' => 'OUT FOR PICKUP',  'pct' => 33],
                        'received'         => ['step' => 3, 'label' => 'STORE RECEIVED',  'pct' => 50],
                        'drying'           => ['step' => 4, 'label' => 'DRYING',          'pct' => 66],
                        'finish'           => ['step' => 5, 'label' => 'READY FOR PICKUP','pct' => 80],
                        'out_for_delivery' => ['step' => 6, 'label' => 'OUT FOR DELIVERY','pct' => 90],
                        'completed'        => ['step' => 7, 'label' => 'COMPLETED',       'pct' => 100],
                    ];
                }
            } elseif ($isFoldOnly) {
                $stages = [
                    'pending'   => ['step' => 1, 'label' => 'ORDER PLACED',    'pct' => 25],
                    'received'  => ['step' => 2, 'label' => 'STORE RECEIVED',  'pct' => 50],
                    'finish'    => ['step' => 3, 'label' => 'FOLDING & READY', 'pct' => 75],
                    'completed' => ['step' => 4, 'label' => 'COMPLETED',       'pct' => 100],
                ];
            } else {
                if ($isWalkIn) {
                    $stages = [
                        'pending'          => ['step' => 1, 'label' => 'ORDER PLACED',      'pct' => 14],
                        'received'         => ['step' => 2, 'label' => 'STORE RECEIVED',    'pct' => 28],
                        'washing'          => ['step' => 3, 'label' => 'WASHING',           'pct' => 42],
                        'rinsing'          => ['step' => 4, 'label' => 'RINSING',           'pct' => 57],
                        'drying'           => ['step' => 5, 'label' => 'DRYING',            'pct' => 71],
                        'finish'           => ['step' => 6, 'label' => 'FOLDING & READY',   'pct' => 85],
                        'completed'        => ['step' => 7, 'label' => 'COMPLETED',         'pct' => 100],
                    ];
                } else {
                    $stages = [
                        'pending'          => ['step' => 1, 'label' => 'ORDER PLACED',      'pct' => 10],
                        'out_for_pickup'   => ['step' => 2, 'label' => 'OUT FOR PICKUP',    'pct' => 22],
                        'received'         => ['step' => 3, 'label' => 'STORE RECEIVED',    'pct' => 35],
                        'washing'          => ['step' => 4, 'label' => 'WASHING',           'pct' => 48],
                        'rinsing'          => ['step' => 5, 'label' => 'RINSING',           'pct' => 60],
                        'drying'           => ['step' => 6, 'label' => 'DRYING',            'pct' => 72],
                        'finish'           => ['step' => 7, 'label' => 'FOLDING & READY',   'pct' => 84],
                        'out_for_delivery' => ['step' => 8, 'label' => 'OUT FOR DELIVERY',  'pct' => 92],
                        'completed'        => ['step' => 9, 'label' => 'COMPLETED',         'pct' => 100],
                    ];
                }
            }

            $currentStatus = $order->order_status;
            $currentStageInfo = $stages[$currentStatus] ?? ['step' => 1, 'label' => 'Processing', 'pct' => 0];

            $statusKeys = array_keys($stages);
            $currentIndex = array_search($currentStatus, $statusKeys);
            if ($currentIndex === false) {
                $currentIndex = -1;
            }
            $totalSteps = count($stages);
        @endphp

        <div class="space-y-3 sm:space-y-5 bg-slate-50 dark:bg-[#141417] p-3 sm:p-5 rounded-lg sm:rounded-lg border border-black/5 dark:dark:border-zinc-700">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs">
                    Order Tracking Progress ({{ $isWalkIn ? 'Store Walk-in / Drop-off' : 'Pickup & Delivery' }})
                </span>
                <span class="font-extrabold text-blue-600 dark:text-blue-400 text-[11px] sm:text-xs">
                    {{ $currentStatus === 'completed' ? '100% Completed' : $currentStageInfo['pct'].'% Progress' }}
                </span>
            </div>

            <div class="relative w-full h-2 sm:h-3 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden p-0.5">
                <div class="h-full bg-blue-600 dark:bg-blue-600 rounded-full transition-all duration-700 shadow-sm"
                     style="width: {{ $currentStageInfo['pct'] }}%"></div>
            </div>

            @if($currentStatus === 'cancelled')
                <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 p-3 rounded-lg text-xs font-semibold text-center">
                    This order has been Cancelled.
                </div>
            @endif

            <!-- Clean Unified Responsive Stepper -->
            <div class="flex overflow-x-auto sm:grid gap-1.5 pb-2 sm:pb-0 text-center scrollbar-none snap-x snap-mandatory" style="grid-template-columns: repeat({{ $totalSteps }}, minmax(0, 1fr));">
                @foreach($stages as $key => $info)
                    @php
                        $stageIdx = array_search($key, $statusKeys);
                        $isActive = ($currentIndex >= $stageIdx && $currentStatus !== 'cancelled');
                        $isCurrent = ($currentStatus === $key);
                    @endphp
                    <div class="min-w-[110px] sm:min-w-0 flex-1 p-2 sm:p-1.5 rounded-lg border flex flex-col items-center justify-center transition-all duration-300 relative min-h-[48px] snap-start shrink-0 {{ $isCurrent ? 'bg-blue-600/15 border-blue-600 text-blue-600 dark:text-blue-400 font-black shadow-sm' : ($isActive ? 'border-emerald-500/40 text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 font-bold' : 'border-black/5 dark:border-white/5 text-slate-400 opacity-50 font-medium') }}">
                        <span class="text-[10px] md:text-[10.5px] uppercase leading-tight whitespace-normal break-words text-center w-full px-0.5">
                            {{ $info['step'] }}. {{ $info['label'] }}
                        </span>
                        @if($isCurrent)
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-ping absolute -top-0.5 -right-0.5"></span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Assigned Dispatch Rider Contact Box -->
        @if($isPickupDeliveryService || $isPickupType || in_array($order->order_status, ['out_for_pickup', 'out_for_delivery']))
            @php
                $riderObj = \App\Models\User::where('role', 'rider')->first();
                $riderName = $order->pickupDelivery?->rider_name ?? ($riderObj?->name ?? 'Hour Wash Rider');
                $riderPhone = $order->pickupDelivery?->rider_phone ?? ($riderObj?->phone ?? '09100317744');
            @endphp
            <div class="p-4 sm:p-5 rounded-lg bg-blue-600/10 dark:bg-blue-600/15 border border-blue-600/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-[10px] font-extrabold uppercase tracking-wider border border-blue-600/30">
                            ASSIGNED RIDER
                        </span>
                    </div>
                    <p class="text-sm font-extrabold text-slate-900 dark:text-white">
                        {{ $riderName }} — Hour Wash Dispatch Specialist
                    </p>
                    <p class="text-xs text-slate-600 dark:text-slate-300">
                        Contact rider directly for pickup or delivery inquiries: <span class="font-mono font-bold text-blue-600 dark:text-blue-400">{{ $riderPhone }}</span>
                    </p>
                </div>

                <div class="flex sm:hidden items-center gap-2">
                    <a href="tel:{{ $riderPhone }}" class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-[#0056b3] text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                        Call Rider ({{ $riderPhone }})
                    </a>
                    <a href="sms:{{ $riderPhone }}?body=Hi%20{{ urlencode($riderName) }},%20inquiring%20about%20Order%20%23{{ $order->order_number }}" class="px-4 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow transition flex items-center gap-1.5">
                        Text Rider
                    </a>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 sm:gap-6">

            <div class="md:col-span-7 space-y-4">
                <div class="p-3.5 sm:p-4 rounded-lg sm:rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:dark:border-zinc-700 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider border-b border-black/5 dark:dark:border-zinc-700 pb-2">
                        <span>Delivery & Pickup Address</span>
                    </div>

                    @php
                        $isAuthorizedViewer = Auth::check() && (Auth::id() === $order->customer_id || Auth::user()->isStaff() || Auth::user()->isAdmin() || Auth::user()->isOwner() || Auth::user()->isRider());

                        $rawPhone = $order->customer?->phone ?? '09171234567';
                        $maskedPhone = (strlen($rawPhone) >= 10) ? substr($rawPhone, 0, 4) . ' *** ' . substr($rawPhone, -4) : '09** *** ****';
                        $displayPhone = $isAuthorizedViewer ? $rawPhone : $maskedPhone;

                        $rawName = trim($order->customer?->name ?? 'Store Walk-in Customer');
                        $nameParts = explode(' ', $rawName);
                        $maskedName = $nameParts[0] . (isset($nameParts[1]) && strlen($nameParts[1]) > 0 ? ' ' . substr($nameParts[1], 0, 1) . '.' : '');
                        $displayName = $isAuthorizedViewer ? $rawName : $maskedName;

                        $rawAddress = $order->customer?->customerProfile?->full_address ?? 'Magallanes St., Orosite, Legazpi City, Albay';
                        $displayAddress = $isAuthorizedViewer ? $rawAddress : 'Orosite, Legazpi City (Privacy Protected)';
                    @endphp

                    <div class="space-y-1 text-xs">
                        <div class="font-extrabold text-slate-900 dark:text-white text-sm">
                            {{ $displayName }}
                        </div>
                        <div class="text-slate-600 dark:text-slate-300 font-mono">
                            {{ $displayPhone }}
                        </div>
                        <div class="text-slate-500 dark:text-slate-400 pt-1 leading-relaxed">
                            {{ $displayAddress }}
                        </div>
                    </div>

                    @if($order->pickupDelivery?->pickup_proof_image || $order->pickupDelivery?->delivery_proof_image)
                        <div class="pt-2 border-t border-black/5 dark:border-zinc-700 space-y-2">
                            <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block">Rider Photo Proof Evidence</span>
                            <div class="flex flex-wrap items-center gap-3">
                                @if($order->pickupDelivery?->pickup_proof_image)
                                    <button type="button" onclick="openImageModal('{{ asset($order->pickupDelivery->pickup_proof_image) }}', 'Proof of Pickup Photo Evidence')" class="flex items-center gap-2 p-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/30 hover:bg-emerald-500/20 transition cursor-pointer text-left">
                                        <img src="{{ asset($order->pickupDelivery->pickup_proof_image) }}" alt="Pickup Proof" class="w-10 h-10 rounded object-cover border border-emerald-500/40">
                                        <div class="text-left">
                                            <span class="text-[11px] font-bold text-emerald-700 dark:text-emerald-400 block">Pickup Proof</span>
                                            <span class="text-[9.5px] text-blue-600 dark:text-blue-400 underline font-semibold">View Full</span>
                                        </div>
                                    </button>
                                @endif
                                @if($order->pickupDelivery?->delivery_proof_image)
                                    <button type="button" onclick="openImageModal('{{ asset($order->pickupDelivery->delivery_proof_image) }}', 'Proof of Delivery Photo Evidence')" class="flex items-center gap-2 p-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/30 hover:bg-cyan-500/20 transition cursor-pointer text-left">
                                        <img src="{{ asset($order->pickupDelivery->delivery_proof_image) }}" alt="Delivery Proof" class="w-10 h-10 rounded object-cover border border-cyan-500/40">
                                        <div class="text-left">
                                            <span class="text-[11px] font-bold text-cyan-700 dark:text-cyan-400 block">Delivery Proof</span>
                                            <span class="text-[9.5px] text-blue-600 dark:text-blue-400 underline font-semibold">View Full</span>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="p-3.5 sm:p-4 rounded-lg sm:rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:dark:border-zinc-700 space-y-3">
                    <div class="flex items-center justify-between border-b border-black/5 dark:dark:border-zinc-700 pb-2">
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                            Order Items & Package Summary
                        </span>
                        <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400">
                            {{ $order->weight_kg }} kg Total Weight
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">
                                {{ $order->service->name ?? 'Standard Laundry Wash' }}
                            </h4>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                ₱{{ number_format($order->service->price ?? 0, 2) }} / {{ $order->service->price_unit ?? 'kg' }} • {{ $order->weight_kg }} kg
                            </span>
                        </div>
                        <span class="font-extrabold text-slate-900 dark:text-white">
                            ₱{{ number_format($order->subtotal ?? $order->total_amount, 2) }}
                        </span>
                    </div>

                    <div class="border-t border-black/5 dark:dark:border-zinc-700 pt-3 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                        </div>
                        @if(!$isWalkIn && $order->delivery_fee > 0)
                            <div class="flex justify-between">
                                <span>Store Delivery Fee</span>
                                <span class="text-emerald-600 dark:text-emerald-400 font-semibold">
                                    ₱{{ number_format($order->delivery_fee, 2) }}
                                </span>
                            </div>
                        @endif
                        @if(($order->discount ?? 0) > 0)
                            <div class="flex justify-between text-rose-500 font-semibold">
                                <span>Discounts Applied</span>
                                <span>-₱{{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-sm font-extrabold text-slate-900 dark:text-white pt-2 border-t border-slate-200 dark:dark:border-zinc-700">
                            <div class="flex items-center gap-2">
                                <span>Total Payment Amount</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </div>
                            <span class="text-lg font-black text-blue-600 dark:text-blue-400">
                                ₱{{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-5 space-y-4">

                <!-- Single Combined Live Machine & Completion Card -->
                <div class="p-4 rounded-lg bg-slate-900 text-white space-y-3 shadow-lg border border-slate-800">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-400">MACHINE & DISPATCH STATUS</span>
                        <span class="text-[10px] font-mono text-emerald-400 font-bold">● LIVE</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs items-stretch">
                        <div class="space-y-1 min-w-0 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-semibold block uppercase min-h-[30px]">Assigned Unit</span>
                            <div class="font-bold text-white font-mono text-xs sm:text-sm min-h-[40px] break-words">
                                {{ $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'Auto-Assign' }}
                            </div>
                        </div>
                        <div class="space-y-1 min-w-0 flex flex-col">
                            <span class="text-[10px] text-slate-400 font-semibold block uppercase min-h-[30px]">Est. Completion</span>
                            <div class="font-bold text-amber-400 text-xs sm:text-sm min-h-[40px] break-words">
                                @if(in_array($order->order_status, ['pending', 'out_for_pickup', 'received']))
                                    {{ $isDryOnly ? 'Pending Dry Start' : ($isFoldOnly ? 'Pending Fold Start' : 'Pending Wash Start') }}
                                @else
                                    {{ ($order->estimated_completion ?? $order->updated_at->addMinutes(30))->format('M d • h:i A') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                        $stageCycleMinutes = match($order->order_status) {
                            'out_for_pickup'   => 20,
                            'picked_up'        => 15,
                            'received'         => 10,
                            'washing'          => 35,
                            'rinsing'          => 15,
                            'drying'           => 40,
                            'finish'           => 15,
                            'out_for_delivery' => 20,
                            default            => 30,
                        };

                        $stageTimerLabel = match($order->order_status) {
                            'out_for_pickup'   => 'Pickup Dispatch Time:',
                            'picked_up'        => 'Transit to Store Time:',
                            'received'         => 'Store Preparation Time:',
                            'washing'          => 'Washing Cycle Remaining:',
                            'rinsing'          => 'Rinse Cycle Remaining:',
                            'drying'           => 'Dryer Cycle Remaining:',
                            'finish'           => 'Folding & Ready Time:',
                            'out_for_delivery' => 'Delivery Dispatch Time:',
                            default            => 'Stage Time Remaining:',
                        };

                        $stageHistory = $order->statusHistory?->where('status', $order->order_status)->last();
                        $stageStartTime = ($stageHistory && $stageHistory->created_at)
                            ? \Carbon\Carbon::parse($stageHistory->created_at)
                            : $order->updated_at;

                        $stageExpiryTimestamp = $stageStartTime->copy()->addMinutes($stageCycleMinutes)->timestamp;
                    @endphp

                    @if(in_array($order->order_status, ['out_for_pickup', 'picked_up', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery']))
                        <div class="p-2.5 rounded-lg bg-slate-800/90 border border-amber-400/40 flex items-center justify-between text-xs font-mono font-bold text-amber-300">
                            <span class="text-amber-300 font-bold opacity-100">{{ $stageTimerLabel }}</span>
                            <span id="order-countdown" data-expiry="{{ $stageExpiryTimestamp }}" class="text-amber-300 font-extrabold">Calculating...</span>
                        </div>
                    @endif
                </div>

                <!-- Scannable QR Laundry Tag Card -->
                <div class="p-4 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-black/5 dark:dark:border-zinc-700 flex items-center justify-center">
                    <div class="w-44 h-44 sm:w-48 sm:h-48 mx-auto bg-white p-2.5 rounded-xl shadow-sm border border-slate-200 flex items-center justify-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $order->qrCode?->qr_token ?? $order->order_number }}"
                             alt="QR Code Tag #{{ $order->order_number }}"
                             class="w-full h-full rounded-lg">
                    </div>
                </div>

            </div>

        </div>

        <div class="space-y-3 border-t border-slate-200 dark:dark:border-zinc-700 pt-4 sm:pt-5">
            <h3 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                Detailed History & Status Updates
            </h3>

            <div class="relative pl-5 sm:pl-6 space-y-4 border-l-2 border-slate-200 dark:border-slate-800 text-xs">
                @forelse(($order->statusHistory ?? collect())->sortByDesc('created_at') as $history)
                    @php
                        $formattedTitle = match($history->status) {
                            'pending' => 'Order Placed',
                            'out_for_pickup' => 'Out for Pickup',
                            'picked_up' => 'Laundry Pickup Successful (En Route to Store)',
                            'received' => 'Store Received Laundry',
                            'washing' => 'Washing Cycle Started',
                            'rinsing' => 'Rinsing Cycle Started',
                            'drying' => 'Drying Cycle Started',
                            'finish' => 'Folding & Ready (Please Claim Order)',
                            'out_for_delivery' => 'Out for Delivery',
                            'completed' => 'Order Completed',
                            'cancelled' => 'Order Cancelled',
                            default => 'Status Updated to ' . str_replace('_', ' ', $history->status),
                        };

                        $formattedNote = $history->notes;
                        if (in_array(strtolower(trim($history->notes ?? '')), ['status updated to pending', 'pending'])) {
                            $formattedNote = 'Order created and submitted successfully.';
                        }
                    @endphp
                    <div class="relative group">
                        <span class="absolute -left-[25px] sm:-left-[31px] top-0 w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-blue-600 border-2 border-white dark:border-[#1C1C1E]"></span>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="font-bold text-slate-900 dark:text-white">
                                {{ $formattedTitle }}
                            </span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                                {{ $history->created_at ? \Carbon\Carbon::parse($history->created_at)->format('M d, Y • h:i A') : $order->updated_at->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                        @if(!empty($formattedNote))
                            <p class="text-slate-600 dark:text-slate-400 text-[11px] mt-0.5 italic">
                                "{{ $formattedNote }}"
                            </p>
                        @endif
                    </div>
                @empty
                    @if($order->order_status !== 'pending')
                        <div class="relative group">
                            <span class="absolute -left-[25px] sm:-left-[31px] top-0 w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-blue-600 border-2 border-white dark:border-[#1C1C1E]"></span>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                <span class="font-bold text-slate-900 dark:text-white capitalize">
                                    Status Updated to {{ $order->order_status === 'finish' ? 'Folding & Ready (Please Claim Order)' : str_replace('_', ' ', $order->order_status) }}
                                </span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                                    {{ $order->updated_at->format('M d, Y • h:i A') }}
                                </span>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-[11px] mt-0.5">
                                Order #{{ $order->order_number }} is currently {{ $order->order_status === 'finish' ? 'finished and neatly folded. PLEASE CLAIM YOUR LAUNDRY ORDER AT OUR STORE COUNTER.' : str_replace('_', ' ', $order->order_status) }}
                            </p>
                        </div>
                    @endif

                    <div class="relative">
                        <span class="absolute -left-[25px] sm:-left-[31px] top-0 w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-slate-400 border-2 border-white dark:border-[#1C1C1E]"></span>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                            <span class="font-bold text-slate-900 dark:text-white">
                                Order Created & Submitted
                            </span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                                {{ $order->created_at->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-[11px] mt-0.5">
                            Order #{{ $order->order_number }} received at Hour Wash Laundry Shop System.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center text-[11px] text-slate-500 dark:text-slate-400 pt-2 border-t border-black/5 dark:border-white/5">
            Store Location: Magallanes St., Orosite, Legazpi City • Hour Wash System
        </div>

    </div>

</div>

@if(in_array($order->order_status, ['out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery']))
<script>
    function runOrderCountdown() {
        const countdownEl = document.getElementById('order-countdown');
        if (!countdownEl) return;

        const expiryAttr = countdownEl.getAttribute('data-expiry');
        if (!expiryAttr) return;

        const expiryTimestamp = parseInt(expiryAttr) * 1000;

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = expiryTimestamp - now;

            if (distance <= 0) {
                countdownEl.innerText = "0m 00s (Stage Finishing...)";
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            let timeString = "";
            if (hours > 0) {
                timeString += hours + "h ";
            }
            timeString += minutes + "m " + (seconds < 10 ? "0" : "") + seconds + "s";

            countdownEl.innerText = timeString;
        }

        updateCountdown();
        if (window.orderTimerInterval) clearInterval(window.orderTimerInterval);
        window.orderTimerInterval = setInterval(updateCountdown, 1000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runOrderCountdown);
    } else {
        runOrderCountdown();
    }
</script>
@endif

<script>
    // Seamless Real-Time Live Order Tracking AJAX Sync (NO Hard Page Reload!)
    setInterval(function() {
        if (document.hidden) return;
        fetch(window.location.href, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) { return res.text(); })
        .then(function(html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMain = doc.getElementById('main-content-area');
            const currentMain = document.getElementById('main-content-area');
            if (newMain && currentMain) {
                if (currentMain.innerHTML !== newMain.innerHTML) {
                    const scrollY = window.scrollY;
                    currentMain.innerHTML = newMain.innerHTML;
                    window.scrollTo(0, scrollY);
                    if (typeof runOrderCountdown === 'function') {
                        runOrderCountdown();
                    }
                }
            }
        })
        .catch(function(e) { /* Silent tracking sync check */ });
    }, 4000);
</script>

<!-- Universal Image Lightbox Modal with Theme-Aware Auto-Adjusting Frame -->
<div id="image-lightbox-modal" onclick="if(event.target === this) closeImageModal()" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 dark:bg-black/85 backdrop-blur-md p-4 transition-all duration-200">
    <div class="relative max-w-[92vw] max-h-[92vh] w-fit h-fit bg-white dark:bg-zinc-900 rounded-2xl border border-slate-200 dark:border-zinc-800 shadow-2xl p-3 sm:p-4 flex flex-col items-center justify-center space-y-3 transition-colors">
        <!-- Prominent ✕ Exit Button in top right -->
        <button type="button" onclick="closeImageModal()" class="absolute -top-3 -right-3 sm:-top-3 sm:-right-3 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-rose-600 hover:bg-rose-500 text-white font-black text-lg sm:text-xl flex items-center justify-center transition shadow-2xl border-2 border-white dark:border-zinc-900 cursor-pointer z-50">
            ✕
        </button>

        <div class="text-center w-full px-2 border-b border-slate-100 dark:border-zinc-800/80 pb-2">
            <h3 id="image-modal-title" class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white uppercase tracking-wider">Photo Evidence View</h3>
        </div>

        <div class="flex items-center justify-center overflow-hidden rounded-xl bg-slate-100 dark:bg-zinc-950/80 border border-slate-200 dark:border-zinc-800 p-1.5">
            <img id="image-modal-img" src="" alt="Proof Evidence" class="max-h-[78vh] max-w-[85vw] w-auto h-auto rounded-lg object-contain block shadow-lg">
        </div>
    </div>
</div>

<script>
    function openImageModal(imgUrl, title) {
        const modal = document.getElementById('image-lightbox-modal');
        const img = document.getElementById('image-modal-img');
        const titleEl = document.getElementById('image-modal-title');
        if (modal && img) {
            img.src = imgUrl;
            if (titleEl && title) titleEl.textContent = title;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeImageModal() {
        const modal = document.getElementById('image-lightbox-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeImageModal();
    });
</script>

</x-app-layout>
