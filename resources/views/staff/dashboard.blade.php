<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 font-medium">
                    Manage active washing, drying, and shelving pipeline for customer orders.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                <form method="POST" action="{{ route('admin.store-status.toggle') }}" class="w-full sm:w-auto">
                    @csrf
                    @if(($storeStatus ?? 'open') === 'open')
                        <button type="submit" title="Click to Mark Store Closed Today" class="w-full px-2.5 py-1.5 rounded-lg bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 font-extrabold text-[10px] whitespace-nowrap hover:bg-emerald-500/25 transition flex items-center justify-center gap-1.5 h-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                            <span>STORE OPEN TODAY</span>
                            <span class="hidden xl:inline-block text-[9px] text-slate-500 dark:text-slate-400 font-normal pl-1 border-l border-emerald-500/30">(Click to Close)</span>
                        </button>
                    @else
                        <button type="submit" title="Click to Re-open Store Today" class="w-full px-2.5 py-1.5 rounded-lg bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 font-extrabold text-[10px] whitespace-nowrap hover:bg-rose-500/25 transition flex items-center justify-center gap-1.5 h-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                            <span>STORE CLOSED TODAY</span>
                            <span class="hidden xl:inline-block text-[9px] text-slate-500 dark:text-slate-400 font-normal pl-1 border-l border-rose-500/30">(Click to Open)</span>
                        </button>
                    @endif
                </form>

                <button type="button" onclick="openAdminCameraScanner()" class="btn-secondary text-[10px] py-1.5 px-2.5 whitespace-nowrap flex items-center justify-center gap-1 w-full sm:w-auto h-full">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <span>Scan QR</span>
                </button>

                <a href="{{ route('staff.laundry.index') }}" class="btn-secondary text-[10px] py-1.5 px-2.5 whitespace-nowrap text-center w-full sm:w-auto flex items-center justify-center h-full">
                    Orders Queue
                </a>

                <a href="{{ route('laundry.create') }}" class="btn-primary text-[10px] py-1.5 px-2.5 whitespace-nowrap text-center w-full sm:w-auto flex items-center justify-center h-full">
                    New Order
                </a>
            </div>
        </div>

        @php
            $unpaidOrders = $orders->where('payment_status', 'unpaid');
            $unpaidCount = $unpaidOrders->count();
            $unpaidTotal = $unpaidOrders->sum('total_amount');

            $paidOrders = $orders->where('payment_status', 'paid');
            $paidCount = $paidOrders->count();
            $paidTotal = $paidOrders->sum('total_amount');

            $pendingCount = $orders->where('order_status', 'pending')->count();
            $pickupCount = $orders->where('order_status', 'out_for_pickup')->count();
            $receivedCount = $orders->where('order_status', 'received')->count();
            $washRinseCount = $orders->whereIn('order_status', ['washing', 'rinsing'])->count();
            $dryingCount = $orders->where('order_status', 'drying')->count();
            $finishCount = $orders->where('order_status', 'finish')->count();
            $deliveryCount = $orders->where('order_status', 'out_for_delivery')->count();
            $completedCount = $orders->where('order_status', 'completed')->count();
        @endphp

        <!-- Quick Terminal Shortcuts -->
        <div>
            <h2 class="text-[11px] font-bold text-slate-400 dark:text-zinc-500 uppercase tracking-widest mb-3">
                Quick Terminal Shortcuts
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                <a href="{{ route('staff.laundry.index') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-4 border-l-blue-600 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group shadow-sm">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition block truncate">
                        Manage Laundry Orders
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        {{ $totalOrders ?? 0 }} total orders
                    </span>
                </a>

                <a href="{{ route('staff.machines.index') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-4 border-l-teal-500 hover:border-teal-500 dark:hover:border-teal-500 transition-colors group shadow-sm">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition block truncate">
                        Machine Monitor
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        {{ count($machines ?? []) }} commercial units
                    </span>
                </a>

                <a href="{{ route('laundry.create') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-4 border-l-emerald-500 hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors group shadow-sm">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition block truncate">
                        New Walk-in Order
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Book customer wash
                    </span>
                </a>

                <a href="{{ route('admin.qr_scan_logs.index') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-4 border-l-amber-500 hover:border-amber-500 dark:hover:border-amber-500 transition-colors group shadow-sm">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition block truncate">
                        QR Scan Logs Outbox
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Scan audit history
                    </span>
                </a>

                <a href="{{ route('welcome') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-4 border-l-cyan-500 hover:border-cyan-500 dark:hover:border-cyan-500 transition-colors group shadow-sm">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition block truncate">
                        Home Dashboard
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Public storefront
                    </span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="p-3.5 rounded-lg bg-white dark:bg-[#18181B] border border-slate-200/80 dark:border-zinc-800 border-l-4 border-l-purple-500 hover:border-purple-500 dark:hover:border-purple-500 transition-colors group shadow-sm">
                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-purple-600 dark:group-hover:text-purple-400 transition block truncate">
                        Account Settings
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-zinc-500 block mt-1">
                        Staff Profile & Security
                    </span>
                </a>
            </div>
        </div>

        <!-- Payment & Financial Status Summary (Pera / Payment Collection) -->
        <div>
            <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                Payment Collection & Cashier Summary
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="metric-pill-card-rose">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-rose-500/15 text-rose-600 dark:text-rose-400 flex items-center justify-center font-extrabold text-base shrink-0 border border-rose-500/30">
                            ₱
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                Unpaid Orders Collection
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                {{ $unpaidCount }} order(s) pending cashier payment
                            </span>
                        </div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold text-rose-600 dark:text-rose-400 font-mono shrink-0">
                        ₱{{ number_format($unpaidTotal, 2) }}
                    </span>
                </div>

                <div class="metric-pill-card-emerald">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-extrabold text-base shrink-0 border border-emerald-500/30">
                            ✓
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                                Total Collected Revenue
                            </span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                {{ $paidCount }} order(s) cleared & paid
                            </span>
                        </div>
                    </div>
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 font-mono shrink-0">
                        ₱{{ number_format($paidTotal, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Multi-Service Order Status Pipeline & Package Breakdown -->
        @php
            $allOrders = \App\Models\Order::with('service')->get();

            $washOnlyOrders = $allOrders->filter(fn($o) => str_contains(strtolower($o->service?->service_type ?? ''), 'wash') && !str_contains(strtolower($o->service?->service_type ?? ''), 'dry') && !str_contains(strtolower($o->service?->service_type ?? ''), 'pickup'));
            $dryOnlyOrders = $allOrders->filter(fn($o) => str_contains(strtolower($o->service?->service_type ?? ''), 'dry') && !str_contains(strtolower($o->service?->service_type ?? ''), 'wash') && !str_contains(strtolower($o->service?->service_type ?? ''), 'pickup'));
            $foldOnlyOrders = $allOrders->filter(fn($o) => str_contains(strtolower($o->service?->service_type ?? ''), 'fold') && !str_contains(strtolower($o->service?->service_type ?? ''), 'wash') && !str_contains(strtolower($o->service?->service_type ?? ''), 'dry'));
            $washDryOrders = $allOrders->filter(fn($o) => $o->service?->service_type === 'wash_dry' || (str_contains(strtolower($o->service?->name ?? ''), 'wash') && str_contains(strtolower($o->service?->name ?? ''), 'dry') && !str_contains(strtolower($o->service?->name ?? ''), 'fold') && !str_contains(strtolower($o->service?->name ?? ''), 'pickup')));
            $fullServiceOrders = $allOrders->filter(fn($o) => $o->service?->service_type === 'wash_dry_fold' || (str_contains(strtolower($o->service?->name ?? ''), 'fold') && str_contains(strtolower($o->service?->name ?? ''), 'wash') && !str_contains(strtolower($o->service?->name ?? ''), 'pickup')));
            $pickupDeliveryOrders = $allOrders->filter(fn($o) => $o->service?->service_type === 'pickup_delivery' || str_contains(strtolower($o->service?->name ?? ''), 'pickup') || str_contains(strtolower($o->service?->name ?? ''), 'delivery') || (in_array($o->pickup_type, ['pickup_delivery', 'pickup', 'delivery']) && !in_array($o->pickup_type, ['drop_off', 'walk_in'])));

            $pipelineDataStaff = [
                'all' => [
                    'pending' => $allOrders->where('order_status', 'pending')->count(),
                    'pickup' => $allOrders->where('order_status', 'out_for_pickup')->count(),
                    'received' => $allOrders->where('order_status', 'received')->count(),
                    'washing' => $allOrders->whereIn('order_status', ['washing', 'rinsing'])->count(),
                    'drying' => $allOrders->where('order_status', 'drying')->count(),
                    'finish' => $allOrders->where('order_status', 'finish')->count(),
                    'delivery' => $allOrders->where('order_status', 'out_for_delivery')->count(),
                    'completed' => $allOrders->where('order_status', 'completed')->count(),
                ],
                'wash' => [
                    'pending' => $washOnlyOrders->where('order_status', 'pending')->count(),
                    'pickup' => 0,
                    'received' => $washOnlyOrders->where('order_status', 'received')->count(),
                    'washing' => $washOnlyOrders->whereIn('order_status', ['washing', 'rinsing'])->count(),
                    'drying' => 0,
                    'finish' => $washOnlyOrders->where('order_status', 'finish')->count(),
                    'delivery' => 0,
                    'completed' => $washOnlyOrders->where('order_status', 'completed')->count(),
                ],
                'dry' => [
                    'pending' => $dryOnlyOrders->where('order_status', 'pending')->count(),
                    'pickup' => 0,
                    'received' => $dryOnlyOrders->where('order_status', 'received')->count(),
                    'washing' => 0,
                    'drying' => $dryOnlyOrders->where('order_status', 'drying')->count(),
                    'finish' => $dryOnlyOrders->where('order_status', 'finish')->count(),
                    'delivery' => 0,
                    'completed' => $dryOnlyOrders->where('order_status', 'completed')->count(),
                ],
                'fold' => [
                    'pending' => $foldOnlyOrders->where('order_status', 'pending')->count(),
                    'pickup' => 0,
                    'received' => $foldOnlyOrders->where('order_status', 'received')->count(),
                    'washing' => 0,
                    'drying' => 0,
                    'finish' => $foldOnlyOrders->where('order_status', 'finish')->count(),
                    'delivery' => 0,
                    'completed' => $foldOnlyOrders->where('order_status', 'completed')->count(),
                ],
                'self_service' => [
                    'pending' => $washDryOrders->where('order_status', 'pending')->count(),
                    'pickup' => 0,
                    'received' => $washDryOrders->where('order_status', 'received')->count(),
                    'washing' => $washDryOrders->whereIn('order_status', ['washing', 'rinsing'])->count(),
                    'drying' => $washDryOrders->where('order_status', 'drying')->count(),
                    'finish' => $washDryOrders->where('order_status', 'finish')->count(),
                    'delivery' => 0,
                    'completed' => $washDryOrders->where('order_status', 'completed')->count(),
                ],
                'full_service' => [
                    'pending' => $fullServiceOrders->where('order_status', 'pending')->count(),
                    'pickup' => 0,
                    'received' => $fullServiceOrders->where('order_status', 'received')->count(),
                    'washing' => $fullServiceOrders->whereIn('order_status', ['washing', 'rinsing'])->count(),
                    'drying' => $fullServiceOrders->where('order_status', 'drying')->count(),
                    'finish' => $fullServiceOrders->where('order_status', 'finish')->count(),
                    'delivery' => 0,
                    'completed' => $fullServiceOrders->where('order_status', 'completed')->count(),
                ],
                'pickup_delivery' => [
                    'pending' => $pickupDeliveryOrders->where('order_status', 'pending')->count(),
                    'pickup' => $pickupDeliveryOrders->where('order_status', 'out_for_pickup')->count(),
                    'received' => $pickupDeliveryOrders->where('order_status', 'received')->count(),
                    'washing' => $pickupDeliveryOrders->whereIn('order_status', ['washing', 'rinsing'])->count(),
                    'drying' => $pickupDeliveryOrders->where('order_status', 'drying')->count(),
                    'finish' => $pickupDeliveryOrders->where('order_status', 'finish')->count(),
                    'delivery' => $pickupDeliveryOrders->where('order_status', 'out_for_delivery')->count(),
                    'completed' => $pickupDeliveryOrders->where('order_status', 'completed')->count(),
                ],
            ];
        @endphp

        <div class="space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div>
                    <h2 class="text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Live Order Stage Pipeline Breakdown
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Filter 8-stage breakdown by service package (Wash Only, Dry Only, Fold Only, Self-Service, Full Service, Pickup & Delivery)
                    </p>
                </div>

                <!-- Service Package Filter Pill Tabs (7 Buttons in 1 Row) -->
                <div class="flex items-center justify-between gap-1 bg-slate-200/60 dark:bg-zinc-800/60 p-1 rounded-lg text-[11px] overflow-x-auto w-full md:w-auto">
                    <button type="button" id="staff-tab-btn-all" onclick="switchStaffPipelineService('all', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-bold transition bg-blue-600 text-white shadow-sm whitespace-nowrap flex-1 text-center">
                        All Services
                    </button>
                    <button type="button" id="staff-tab-btn-wash" onclick="switchStaffPipelineService('wash', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-zinc-700 transition whitespace-nowrap flex-1 text-center">
                        Wash Only
                    </button>
                    <button type="button" id="staff-tab-btn-dry" onclick="switchStaffPipelineService('dry', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-zinc-700 transition whitespace-nowrap flex-1 text-center">
                        Dry Only
                    </button>
                    <button type="button" id="staff-tab-btn-fold" onclick="switchStaffPipelineService('fold', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-zinc-700 transition whitespace-nowrap flex-1 text-center">
                        Fold Only
                    </button>
                    <button type="button" id="staff-tab-btn-self_service" onclick="switchStaffPipelineService('self_service', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-zinc-700 transition whitespace-nowrap flex-1 text-center">
                        Self-Service
                    </button>
                    <button type="button" id="staff-tab-btn-full_service" onclick="switchStaffPipelineService('full_service', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-zinc-700 transition whitespace-nowrap flex-1 text-center">
                        Full-Service
                    </button>
                    <button type="button" id="staff-tab-btn-pickup_delivery" onclick="switchStaffPipelineService('pickup_delivery', this)" class="staff-pipeline-tab-btn px-2 py-1 rounded-md font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-zinc-700 transition whitespace-nowrap flex-1 text-center">
                        Pickup & Delivery
                    </button>
                </div>
            </div>

            <!-- 8-Stage Pipeline Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3" id="staff-stage-cards-container">
                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-pending">
                    <span class="text-[9.5px] font-extrabold text-slate-500 dark:text-slate-400 uppercase tracking-wider block truncate">1. PENDING</span>
                    <span id="staff-stage-count-pending" class="text-xl font-bold text-slate-900 dark:text-white mt-1">{{ $pipelineDataStaff['all']['pending'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Order Placed</p>
                </div>

                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-pickup">
                    <span class="text-[9.5px] font-extrabold text-sky-600 dark:text-sky-400 uppercase tracking-wider block truncate">2. PICKUP</span>
                    <span id="staff-stage-count-pickup" class="text-xl font-bold text-sky-600 dark:text-sky-400 mt-1">{{ $pipelineDataStaff['all']['pickup'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Out for Pickup</p>
                </div>

                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-received">
                    <span class="text-[9.5px] font-extrabold text-blue-600 dark:text-blue-400 uppercase tracking-wider block truncate">3. RECEIVED</span>
                    <span id="staff-stage-count-received" class="text-xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $pipelineDataStaff['all']['received'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Store Received</p>
                </div>

                <div class="card-accent-blue p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-washing">
                    <span class="text-[9.5px] font-extrabold text-teal-600 dark:text-teal-400 uppercase tracking-wider block truncate">4. WASHING</span>
                    <span id="staff-stage-count-washing" class="text-xl font-bold text-teal-600 dark:text-teal-400 mt-1">{{ $pipelineDataStaff['all']['washing'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Wash & Rinse</p>
                </div>

                <div class="card-accent-purple p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-drying">
                    <span class="text-[9.5px] font-extrabold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block truncate">5. DRYING</span>
                    <span id="staff-stage-count-drying" class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $pipelineDataStaff['all']['drying'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Dryer Units</p>
                </div>

                <div class="card-accent-amber p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-finish">
                    <span class="text-[9.5px] font-extrabold text-amber-600 dark:text-amber-400 uppercase tracking-wider block truncate">6. FINISH</span>
                    <span id="staff-stage-count-finish" class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $pipelineDataStaff['all']['finish'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Folding & Ready</p>
                </div>

                <div class="card-accent-purple p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-delivery">
                    <span class="text-[9.5px] font-extrabold text-purple-600 dark:text-purple-400 uppercase tracking-wider block truncate">7. DELIVERY</span>
                    <span id="staff-stage-count-delivery" class="text-xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $pipelineDataStaff['all']['delivery'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Out for Delivery</p>
                </div>

                <div class="card-accent-emerald p-3 flex flex-col justify-between shadow-sm transition-all" id="staff-stage-card-completed">
                    <span class="text-[9.5px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider block truncate">8. COMPLETED</span>
                    <span id="staff-stage-count-completed" class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $pipelineDataStaff['all']['completed'] }}</span>
                    <p class="text-[9px] text-slate-500 dark:text-slate-400 truncate mt-0.5">Fulfilled & Done</p>
                </div>
            </div>

            <!-- Active Service Packages Breakdown Cards Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-1">
                <div onclick="switchStaffPipelineService('wash', document.getElementById('staff-tab-btn-wash'))" class="p-3 rounded-lg bg-blue-500/10 border border-blue-500/30 flex items-center justify-between cursor-pointer hover:bg-blue-500/20 transition">
                    <div>
                        <span class="text-[10px] font-extrabold text-blue-700 dark:text-blue-300 uppercase block">Wash Only</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">35m Wash Cycle</span>
                    </div>
                    <span class="text-lg font-black text-blue-600 dark:text-blue-400 font-mono">{{ $washOnlyOrders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }}</span>
                </div>

                <div onclick="switchStaffPipelineService('dry', document.getElementById('staff-tab-btn-dry'))" class="p-3 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-between cursor-pointer hover:bg-indigo-500/20 transition">
                    <div>
                        <span class="text-[10px] font-extrabold text-indigo-700 dark:text-indigo-300 uppercase block">Dry Only</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">40m Dryer Cycle</span>
                    </div>
                    <span class="text-lg font-black text-indigo-600 dark:text-indigo-400 font-mono">{{ $dryOnlyOrders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }}</span>
                </div>

                <div onclick="switchStaffPipelineService('fold', document.getElementById('staff-tab-btn-fold'))" class="p-3 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-between cursor-pointer hover:bg-amber-500/20 transition">
                    <div>
                        <span class="text-[10px] font-extrabold text-amber-700 dark:text-amber-300 uppercase block">Fold Only</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">15m Folding</span>
                    </div>
                    <span class="text-lg font-black text-amber-600 dark:text-amber-400 font-mono">{{ $foldOnlyOrders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }}</span>
                </div>

                <div onclick="switchStaffPipelineService('self_service', document.getElementById('staff-tab-btn-self_service'))" class="p-3 rounded-lg bg-teal-500/10 border border-teal-500/30 flex items-center justify-between cursor-pointer hover:bg-teal-500/20 transition">
                    <div>
                        <span class="text-[10px] font-extrabold text-teal-700 dark:text-teal-300 uppercase block">Self-Service</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">75m Wash + Dry</span>
                    </div>
                    <span class="text-lg font-black text-teal-600 dark:text-teal-400 font-mono">{{ $washDryOrders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }}</span>
                </div>

                <div onclick="switchStaffPipelineService('full_service', document.getElementById('staff-tab-btn-full_service'))" class="p-3 rounded-lg bg-purple-500/10 border border-purple-500/30 flex items-center justify-between cursor-pointer hover:bg-purple-500/20 transition">
                    <div>
                        <span class="text-[10px] font-extrabold text-purple-700 dark:text-purple-300 uppercase block">Full-Service</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">90m Wash Dry Fold</span>
                    </div>
                    <span class="text-lg font-black text-purple-600 dark:text-purple-400 font-mono">{{ $fullServiceOrders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }}</span>
                </div>

                <div onclick="switchStaffPipelineService('pickup_delivery', document.getElementById('staff-tab-btn-pickup_delivery'))" class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-between cursor-pointer hover:bg-emerald-500/20 transition">
                    <div>
                        <span class="text-[10px] font-extrabold text-emerald-700 dark:text-emerald-300 uppercase block">Pickup & Delivery</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">120m Doorstep</span>
                    </div>
                    <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $pickupDeliveryOrders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }}</span>
                </div>
            </div>
        </div>

        <script>
            const pipelineServiceDataStaff = @json($pipelineDataStaff);
            const staffServiceStageMap = {
                'all':             ['pending', 'pickup', 'received', 'washing', 'drying', 'finish', 'delivery', 'completed'],
                'wash':            ['pending', 'washing', 'finish', 'completed'],
                'dry':             ['pending', 'drying', 'finish', 'completed'],
                'fold':            ['pending', 'finish', 'completed'],
                'self_service':    ['pending', 'washing', 'drying', 'finish', 'completed'],
                'full_service':    ['pending', 'washing', 'drying', 'finish', 'completed'],
                'pickup_delivery': ['pending', 'pickup', 'received', 'washing', 'drying', 'finish', 'delivery', 'completed']
            };

            function switchStaffPipelineService(serviceKey, btnElement) {
                document.querySelectorAll('.staff-pipeline-tab-btn').forEach(btn => {
                    btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm', 'font-bold');
                    btn.classList.add('font-medium', 'text-slate-700', 'dark:text-slate-300');
                });
                if (btnElement) {
                    btnElement.classList.add('bg-blue-600', 'text-white', 'shadow-sm', 'font-bold');
                    btnElement.classList.remove('font-medium', 'text-slate-700', 'dark:text-slate-300');
                }

                const data = pipelineServiceDataStaff[serviceKey] || pipelineServiceDataStaff['all'];
                const activeStages = staffServiceStageMap[serviceKey] || staffServiceStageMap['all'];
                const allStageKeys = ['pending', 'pickup', 'received', 'washing', 'drying', 'finish', 'delivery', 'completed'];

                allStageKeys.forEach(stage => {
                    const countEl = document.getElementById('staff-stage-count-' + stage);
                    if (countEl) {
                        countEl.textContent = data[stage] !== undefined ? data[stage] : 0;
                    }

                    const cardEl = document.getElementById('staff-stage-card-' + stage);
                    if (cardEl) {
                        if (activeStages.includes(stage)) {
                            cardEl.style.display = '';
                        } else {
                            cardEl.style.display = 'none';
                        }
                    }
                });
            }
        </script>

        <!-- Machine Status Monitor Grid -->
        <div class="app-card p-4 sm:p-6 space-y-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                        Machine Status Monitor
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">
                        Real-time status of commercial washers & dryers. Click any active order to view details.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.machines.create') }}" class="btn-primary py-1.5 px-3 text-xs">
                        + Add Machine
                    </a>
                    <a href="{{ route('staff.machines.index') }}" class="btn-secondary py-1.5 px-3 text-xs">
                        Manage Machines
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-3.5">
                @forelse($machines as $machine)
                    @php
                        $isMaintenance = in_array($machine->status, ['maintenance', 'out_of_service', 'broken']);
                        $isBusy = in_array($machine->status, ['washing', 'rinsing', 'drying', 'in_use', 'busy']);
                        $ord = $machine->displayOrder;

                        $cardBorderClass = match(true) {
                            $isMaintenance => 'border-rose-500/40 dark:border-rose-900/50 bg-rose-500/5 dark:bg-rose-950/15',
                            $isBusy || $ord => 'border-2 border-blue-600 dark:border-blue-500 bg-blue-50/40 dark:bg-[#18181B] shadow-md shadow-blue-500/10',
                            default => 'border border-slate-200 dark:border-zinc-800 bg-white dark:bg-[#18181B] hover:border-emerald-500/60 dark:hover:border-emerald-500/50 hover:shadow-md'
                        };

                        $statusIconBg = match($machine->status) {
                            'washing' => 'bg-teal-500/15 text-teal-600 dark:text-teal-400 border border-teal-500/30',
                            'rinsing' => 'bg-sky-500/15 text-sky-600 dark:text-sky-400 border border-sky-500/30',
                            'drying' => 'bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30',
                            'idle' => 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30',
                            default => 'bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30',
                        };

                        $dotLed = match($machine->status) {
                            'washing' => 'bg-teal-500 shadow-[0_0_8px_rgba(20,184,166,0.8)] animate-pulse',
                            'rinsing' => 'bg-sky-500 shadow-[0_0_8px_rgba(14,165,233,0.8)] animate-pulse',
                            'drying' => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.8)] animate-pulse',
                            'idle' => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]',
                            default => 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.8)]',
                        };
                    @endphp

                    @if($ord)
                        <a href="{{ route('laundry.track', $ord->qrCode->qr_token ?? $ord->order_number) }}"
                           class="block p-4 rounded-xl {{ $cardBorderClass }} space-y-3 transition-all duration-200 cursor-pointer group hover:scale-[1.02]"
                           title="Click to track order #{{ $ord->order_number }}">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600 transition">
                                    {{ $machine->machine_name }}
                                </span>
                                <span class="text-[10px] bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 font-mono font-bold px-1.5 py-0.5 rounded border border-slate-200 dark:border-zinc-700 shrink-0">
                                    {{ $machine->machine_code }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs shrink-0 {{ $statusIconBg }}">
                                    @if($machine->status === 'washing')
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    @elseif($machine->status === 'drying')
                                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                                    @elseif($isMaintenance)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <div class="truncate">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-800 dark:text-zinc-200">
                                        <span class="w-2 h-2 rounded-full {{ $dotLed }}"></span>
                                        {{ strtoupper($machine->status === 'idle' ? str_replace('_', ' ', $ord->order_status) : $machine->status) }}
                                    </span>
                                    @if(in_array($machine->status, ['washing', 'rinsing', 'drying']) && $machine->remaining_minutes)
                                        <span class="block text-[10px] text-blue-600 dark:text-blue-400 font-bold font-mono truncate">{{ $machine->remaining_minutes }}m remaining</span>
                                    @else
                                        <span class="block text-[10px] text-amber-600 dark:text-amber-400 font-bold truncate">Order {{ ucfirst(str_replace('_', ' ', $ord->order_status)) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-100 dark:border-zinc-800 text-[10px] space-y-0.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-blue-600 dark:text-blue-400 group-hover:underline truncate">
                                        #{{ $ord->order_number }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 dark:text-zinc-500 uppercase font-semibold">Active</span>
                                </div>
                                <span class="block text-slate-600 dark:text-slate-400 font-medium truncate">
                                    {{ $ord->customer->name ?? 'Customer' }}
                                </span>
                            </div>
                        </a>
                    @else
                        <div class="p-4 rounded-xl {{ $cardBorderClass }} space-y-3 transition-all duration-200 hover:scale-[1.01]">
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate">
                                    {{ $machine->machine_name }}
                                </span>
                                <span class="text-[10px] bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 font-mono font-bold px-1.5 py-0.5 rounded border border-slate-200 dark:border-zinc-700 shrink-0">
                                    {{ $machine->machine_code }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs shrink-0 {{ $statusIconBg }}">
                                    @if($isMaintenance)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @endif
                                </div>
                                <div class="truncate">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-700 dark:text-zinc-300">
                                        <span class="w-2 h-2 rounded-full {{ $dotLed }}"></span>
                                        {{ strtoupper($machine->status) }}
                                    </span>
                                    <span class="block text-[10px] text-slate-500 dark:text-zinc-400 font-medium truncate">
                                        {{ $machine->status === 'idle' ? 'Available' : ($machine->remaining_minutes ? $machine->remaining_minutes.'m remaining' : 'In Service') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="col-span-full text-center py-6 text-xs text-slate-500">
                        No machines configured.
                    </div>
                @endforelse
            </div>

            <div class="flex flex-wrap items-center gap-4 text-[11px] font-medium text-slate-600 dark:text-slate-400 border-t border-slate-200/80 dark:border-zinc-800 pt-3.5">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-teal-500"></span> Washing</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Rinsing</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Drying</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Idle / Available</span>
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Maintenance</span>
            </div>
        </div>

                <!-- Active Processing Pipeline -->
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Active Processing Pipeline</h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Update cleaning status stages as laundry moves through machines</p>
                </div>
                <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('laundry.create') }}" class="btn-primary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">
                        + New Order
                    </a>
                    <a href="{{ route('staff.laundry.index') }}" class="btn-secondary py-1.5 px-3 text-xs w-full sm:w-auto text-center flex items-center justify-center">Full Orders Queue</a>
                </div>
            </div>

            <div class="overflow-x-auto max-w-full border border-slate-200 dark:border-zinc-800 rounded-lg">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[650px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Order Tag</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Current Stage</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-700 text-slate-900 dark:text-slate-200">
                        @forelse($orders->whereNotIn('order_status', ['completed', 'cancelled'])->take(10) as $order)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $order->customer->name ?? 'Walk-in' }}</div>
                                    @if(!empty($order->notes))
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 italic max-w-xs truncate" title="{{ $order->notes }}">
                                            {{ $order->notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $order->service->name ?? 'Standard Wash' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-mono">{{ $order->weight_kg }} kg</td>
                                <td class="px-4 py-3 font-mono">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                        {{ strtoupper($order->payment_status) }} (₱{{ number_format($order->total_amount, 2) }})
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                     @if($order->order_status === 'completed')
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Completed</span>
                                     @elseif($order->order_status === 'finish')
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">Finish</span>
                                     @elseif($order->order_status === 'cancelled')
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Cancelled</span>
                                     @else
                                         <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30">{{ str_replace('_', ' ', $order->order_status) }}</span>
                                     @endif
                                 </td>
                                <td class="px-4 py-3 text-center flex items-center justify-center gap-1.5">
                                    @php
                                        $isPickupDeliveryOrder = in_array($order->pickup_type, ['pickup_delivery', 'pickup', 'delivery']) || str_contains(strtolower($order->service?->service_type ?? ''), 'pickup') || str_contains(strtolower($order->service?->name ?? ''), 'pickup') || str_contains(strtolower($order->service?->name ?? ''), 'delivery');
                                    @endphp
                                    @if($isPickupDeliveryOrder)
                                        <form method="POST" action="{{ route('laundry.auto-assign-rider', $order->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 rounded bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30 hover:bg-indigo-600 hover:text-white text-[10px] font-bold transition cursor-pointer" title="Auto-assign on-duty rider">
                                                Auto-Rider
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('staff.laundry.index') }}" class="btn-secondary py-1 px-2.5 text-[11px]">Manage</a>
                                    <form method="POST" action="{{ route('laundry.destroy', $order->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete Order #{{ $order->order_number }} permanently? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30 hover:bg-rose-600 hover:text-white text-[10px] font-bold transition cursor-pointer" title="Delete Order">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-500">No active processing orders.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Completed Orders & Processing History Log -->
        @php
            $completedOrdersList = $orders->where('order_status', 'completed');
        @endphp
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden shadow-sm border-t-4 border-t-emerald-500">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Completed Orders History Log</h2>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                            {{ $completedOrdersList->count() }} Finished Orders
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Archived list of all completed wash, dry, and fold orders in shop</p>
                </div>
            </div>

            <div class="overflow-x-auto max-w-full border border-slate-200 dark:border-zinc-800 rounded-lg">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[650px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Order Tag</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Date Completed</th>
                            <th class="px-4 py-3 text-center">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-700 text-slate-900 dark:text-slate-200">
                        @forelse($completedOrdersList as $compOrder)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-emerald-600 dark:text-emerald-400">#{{ $compOrder->order_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $compOrder->customer->name ?? 'Walk-in' }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $compOrder->service->name ?? 'Standard Wash' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-mono">{{ $compOrder->weight_kg }} kg</td>
                                <td class="px-4 py-3 font-mono">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $compOrder->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                        {{ strtoupper($compOrder->payment_status) }} (₱{{ number_format($compOrder->total_amount, 2) }})
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-zinc-400 text-[11px]">
                                    {{ $compOrder->updated_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('laundry.receipt', $compOrder->id) }}" target="_blank" class="px-2.5 py-1 rounded bg-slate-900 text-white dark:bg-white dark:text-slate-900 text-[11px] font-bold hover:opacity-90 transition">
                                        View Receipt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-500">No completed orders history records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cancelled Orders History Log -->
        @php
            $cancelledOrdersList = $orders->where('order_status', 'cancelled');
        @endphp
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden shadow-sm border-t-4 border-t-rose-500">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-3">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Cancelled Orders History Log</h2>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                            {{ $cancelledOrdersList->count() }} Cancelled Orders
                        </span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Archived list of all cancelled orders in shop</p>
                </div>
            </div>

            <div class="overflow-x-auto max-w-full border border-slate-200 dark:border-zinc-800 rounded-lg">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[650px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:border-zinc-700">
                        <tr>
                            <th class="px-4 py-3">Order Tag</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Date Cancelled</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-zinc-700 text-slate-900 dark:text-slate-200">
                        @forelse($cancelledOrdersList as $cancOrder)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-rose-600 dark:text-rose-400">#{{ $cancOrder->order_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-slate-900 dark:text-slate-100">{{ $cancOrder->customer->name ?? 'Walk-in' }}</div>
                                </td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300">{{ $cancOrder->service->name ?? 'Standard Wash' }}</td>
                                <td class="px-4 py-3 text-slate-700 dark:text-slate-300 font-mono">{{ $cancOrder->weight_kg }} kg</td>
                                <td class="px-4 py-3 font-mono">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $cancOrder->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                        {{ strtoupper($cancOrder->payment_status) }} (₱{{ number_format($cancOrder->total_amount, 2) }})
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-zinc-400 text-[11px]">
                                    {{ $cancOrder->updated_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Cancelled</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-500">No cancelled orders history records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-camera-qr-scanner />

</x-app-layout>
