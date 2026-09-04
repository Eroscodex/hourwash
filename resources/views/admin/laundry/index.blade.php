<x-app-layout>

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Store Orders & Cashier Queue</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Manage active laundry stages, process cashier payments, and print store receipts.</p>
            </div>
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                <button type="button" onclick="openAdminCameraScanner()" class="btn-secondary text-[10px] py-1.5 px-2.5 whitespace-nowrap flex items-center justify-center gap-1 w-full sm:w-auto">
                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <circle cx="12" cy="13" r="3"/>
                    </svg>
                    <span>Scan Order QR</span>
                </button>
                <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-reset-all-orders')" class="w-full bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition whitespace-nowrap flex items-center justify-center h-full">
                    Reset All Orders
                </button>

                <x-modal name="confirm-reset-all-orders" maxWidth="md">
                    <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg">
                        <div class="flex items-center gap-3 text-rose-600 dark:text-rose-400">
                            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <h2 class="text-base font-bold">Reset All Orders?</h2>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            This action will permanently purge all order history and set all commercial machines to idle status.
                        </p>
                        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                            <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('admin.orders.reset') }}">
                                @csrf
                                <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                    Yes, Reset All Orders
                                </button>
                            </form>
                        </div>
                    </div>
                </x-modal>
                <a href="{{ route('laundry.create') }}" class="btn-primary text-[10px] py-1.5 px-2.5 whitespace-nowrap text-center col-span-2 sm:col-span-1 w-full sm:w-auto flex items-center justify-center">New Drop-Off Order</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Tabs for Admin History Management -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 dark:border-zinc-800 pb-3" x-data="{ adminTab: 'all' }">
            <button type="button" @click="adminTab = 'all'; filterAdminOrders('all')" :class="adminTab === 'all' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
                All Orders ({{ $orders->count() }})
            </button>
            <button type="button" @click="adminTab = 'active'; filterAdminOrders('active')" :class="adminTab === 'active' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
                Active Queue ({{ $orders->whereNotIn('order_status', ['completed', 'cancelled'])->count() }})
            </button>
            <button type="button" @click="adminTab = 'completed'; filterAdminOrders('completed')" :class="adminTab === 'completed' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
                Completed History Log ({{ $orders->where('order_status', 'completed')->count() }})
            </button>
            <button type="button" @click="adminTab = 'cancelled'; filterAdminOrders('cancelled')" :class="adminTab === 'cancelled' ? 'bg-rose-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
                Cancelled History Log ({{ $orders->where('order_status', 'cancelled')->count() }})
            </button>
        </div>

        <div class="space-y-4 pb-64">
            @forelse($orders as $order)
                <div data-status="{{ $order->order_status }}" class="app-card p-5 space-y-4 shadow-sm hover:border-blue-600/30 transition admin-order-card">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:dark:border-zinc-700 pb-3">
                        <div class="flex items-center gap-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $order->qrCode->qr_token ?? $order->order_number }}"
                                 alt="QR Tag #{{ $order->order_number }}"
                                 class="w-14 h-14 bg-white p-1 rounded-lg border border-slate-200 shadow-sm flex-shrink-0">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-bold font-mono text-blue-600 dark:text-blue-400">#{{ $order->order_number }}</h3>
                                    @if($order->machine)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30">
                                            {{ $order->machine->machine_name }} ({{ $order->machine->machine_code }})
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                            No Machine Assigned
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Customer: <strong class="text-slate-900 dark:text-white">{{ $order->customer->name ?? 'Walk-in' }}</strong> ({{ $order->customer->phone ?? 'N/A' }})</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('laundry.track', $order->qrCode->qr_token ?? $order->order_number) }}" class="btn-secondary text-xs">
                                Status Update
                            </a>
                            <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-3 py-2 rounded-lg text-xs font-bold hover:opacity-90 transition flex items-center gap-1.5 shadow-sm">
                                Receipt
                            </a>
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                                {{ strtoupper($order->payment_status) }}
                            </span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold text-xl">₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Service Package</span>
                            <span class="font-bold text-slate-900 dark:text-slate-100">{{ $order->service->name ?? 'Standard Wash' }} (₱{{ number_format($order->service->price ?? 0, 2) }}/{{ $order->service->price_unit ?? 'kg' }})</span>
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
                            <span class="font-bold uppercase text-blue-600 dark:text-blue-400">{{ $order->order_status === 'finish' ? 'Finish' : str_replace('_', ' ', $order->order_status) }}</span>
                        </div>
                    </div>

                    @if(!empty($order->notes))
                        <div class="p-3 bg-slate-50 dark:bg-black/20 rounded-lg border border-black/5 dark:border-white/5 text-xs">
                            <span class="text-slate-500 dark:text-slate-400 text-[10px] uppercase font-bold block mb-1">Customer Remarks / Special Instructions</span>
                            <p class="text-slate-800 dark:text-slate-200 font-medium italic">"{{ $order->notes }}"</p>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-black/5 dark:border-white/5">
                        <form method="POST" action="{{ route('admin.laundry.update', $order->id) }}" class="flex flex-wrap items-center gap-2" x-data="{
                            statusOpen: false,
                            statusVal: '{{ $order->order_status }}',

                            machineOpen: false,
                            machineVal: '{{ $order->machine_id ?? '' }}',

                            paymentOpen: false,
                            paymentVal: '{{ $order->payment_status }}'
                        }">
                            @csrf
                            @method('PATCH')

                            <!-- Hidden inputs for backend form submission -->
                            <input type="hidden" name="status" :value="statusVal">
                            <input type="hidden" name="machine_id" :value="machineVal">
                            <input type="hidden" name="payment_status" :value="paymentVal">

                             @php
                                $serviceType = $order->service?->service_type ?? '';
                                $serviceName = strtolower($order->service?->name ?? '');

                                $isWashOnly = str_contains($serviceName, 'wash only') || ($serviceType === 'wash') || (str_contains($serviceName, 'wash') && !str_contains($serviceName, 'dry') && !str_contains($serviceName, 'fold') && !str_contains($serviceName, 'full'));
                                $isDryOnly = str_contains($serviceName, 'dry only') || ($serviceType === 'dry') || (str_contains($serviceName, 'dry') && !str_contains($serviceName, 'wash') && !str_contains($serviceName, 'full'));
                                $isFoldOnly = str_contains($serviceName, 'fold only') || ($serviceType === 'fold') || (str_contains($serviceName, 'fold') && !str_contains($serviceName, 'wash') && !str_contains($serviceName, 'dry'));
                                $isSelfService = str_contains($serviceName, 'self') || ($serviceType === 'wash_dry');

                                $isPickupDeliveryService = ($serviceType === 'pickup_delivery' || str_contains($serviceName, 'pickup'));
                                $isPickupType = in_array($order->pickup_type, ['pickup', 'delivery', 'pickup_delivery']);
                                $isDeliveryOrder = ($isPickupDeliveryService || $isPickupType);

                                $orderStatusOptions = [];
                                $stepNum = 1;

                                $orderStatusOptions['pending'] = $stepNum++ . '. Pending (Order Placed)';

                                if ($isDeliveryOrder) {
                                    $orderStatusOptions['out_for_pickup'] = $stepNum++ . '. Out for Pickup';
                                }

                                $orderStatusOptions['received'] = $stepNum++ . '. Store Received';

                                if ($isWashOnly || $isSelfService || (!$isDryOnly && !$isFoldOnly)) {
                                    $orderStatusOptions['washing'] = $stepNum++ . '. Washing';
                                    $orderStatusOptions['rinsing'] = $stepNum++ . '. Rinsing';
                                }

                                if ($isDryOnly || $isSelfService || (!$isWashOnly && !$isFoldOnly)) {
                                    $orderStatusOptions['drying'] = $stepNum++ . '. Drying';
                                }

                                if ($isWashOnly) {
                                    $finishLabel = 'FINISH (Washing Done - Ready for Claim)';
                                } elseif ($isDryOnly) {
                                    $finishLabel = 'FINISH (Drying Done - Ready for Claim)';
                                } elseif ($isFoldOnly) {
                                    $finishLabel = 'FINISH (Folding Done - Ready for Claim)';
                                } else {
                                    $finishLabel = 'FINISH (Folding & Ready - Ready for Claim)';
                                }
                                $orderStatusOptions['finish'] = $stepNum++ . '. ' . $finishLabel;

                                if ($isDeliveryOrder) {
                                    $orderStatusOptions['out_for_delivery'] = $stepNum++ . '. Out for Delivery';
                                }

                                $orderStatusOptions['completed'] = $stepNum++ . '. Completed';
                                $orderStatusOptions['cancelled'] = 'Cancelled';
                            @endphp

                            <!-- 1. Order Status Dropdown -->
                            <div class="relative inline-block" @click.outside="statusOpen = false">
                                <button type="button" @click="statusOpen = !statusOpen; machineOpen = false; paymentOpen = false;" class="h-9 min-w-[170px] py-1 px-3 text-xs rounded-lg font-bold bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 flex items-center justify-between gap-2 shadow-sm">
                                    <span class="truncate" x-text="({{ json_encode($orderStatusOptions) }}[statusVal] || statusVal)"></span>
                                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0 transition-transform duration-200" :class="statusOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="statusOpen" x-cloak x-transition class="absolute z-50 top-full left-0 mt-1 min-w-[220px] w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl max-h-60 overflow-y-auto py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                                    @foreach($orderStatusOptions as $sKey => $sVal)
                                        <button type="button" @click="statusVal = '{{ $sKey }}'; statusOpen = false;" class="w-full text-left px-3.5 py-2 text-xs font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="statusVal == '{{ $sKey }}' ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                                            <span>{{ $sVal }}</span>
                                            <span x-show="statusVal == '{{ $sKey }}'" class="font-bold shrink-0 ml-2">✓</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- 2. Machine Dropdown -->
                            @php
                                $availableM = [];
                                $occupiedM = [];
                                foreach($machines ?? [] as $m) {
                                    $mOrd = $m->displayOrder;
                                    $isOccupied = ($m->id != $order->machine_id) && ($m->status !== 'idle' || $m->current_order_id !== null || ($mOrd && $mOrd->id !== $order->id));
                                    if ($isOccupied) {
                                        $occupiedM[] = ['m' => $m, 'mOrd' => $mOrd];
                                    } else {
                                        $availableM[] = ['m' => $m, 'mOrd' => $mOrd];
                                    }
                                }
                                $allMachineMap = [];
                                foreach($machines ?? [] as $m) {
                                    $allMachineMap[$m->id] = $m->machine_name . " (" . $m->machine_code . ")";
                                }
                            @endphp

                            <div class="relative inline-block" @click.outside="machineOpen = false">
                                <button type="button" @click="machineOpen = !machineOpen; statusOpen = false; paymentOpen = false;" class="h-9 min-w-[210px] py-1 px-3 text-xs rounded-lg font-medium bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 flex items-center justify-between gap-2 shadow-sm">
                                    <span class="truncate" x-text="machineVal ? ({{ json_encode($allMachineMap) }}[machineVal] || '-- Assign Machine --') : '-- Assign Machine --'"></span>
                                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0 transition-transform duration-200" :class="machineOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="machineOpen" x-cloak x-transition class="absolute z-50 top-full left-0 mt-1 min-w-[230px] w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl max-h-60 overflow-y-auto py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                                    <button type="button" @click="machineVal = ''; machineOpen = false;" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-zinc-800">
                                        -- No Machine Assigned --
                                    </button>

                                    @if(count($availableM) > 0)
                                        <div class="px-3 py-1 text-[10px] font-extrabold text-emerald-600 dark:text-emerald-400 uppercase bg-emerald-50 dark:bg-emerald-950/40">Available Machines</div>
                                        @foreach($availableM as $item)
                                            @php $m = $item['m']; @endphp
                                            <button type="button" @click="machineVal = '{{ $m->id }}'; machineOpen = false;" class="w-full text-left px-3.5 py-2 text-xs font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="machineVal == '{{ $m->id }}' ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                                                <span>{{ $m->machine_name }} ({{ $m->machine_code }}) [AVAILABLE]</span>
                                                <span x-show="machineVal == '{{ $m->id }}'" class="font-bold shrink-0 ml-2">✓</span>
                                            </button>
                                        @endforeach
                                    @endif

                                    @if(count($occupiedM) > 0)
                                        <div class="px-3 py-1 text-[10px] font-extrabold text-amber-600 dark:text-amber-400 uppercase bg-amber-50 dark:bg-amber-950/40">Busy / In Use Machines</div>
                                        @foreach($occupiedM as $item)
                                            @php $m = $item['m']; $mOrd = $item['mOrd']; @endphp
                                            <button type="button" disabled class="w-full text-left px-3.5 py-2 text-xs font-medium text-slate-400 dark:text-slate-600 opacity-60 cursor-not-allowed">
                                                {{ $m->machine_name }} ({{ $m->machine_code }}) [BUSY - Order #{{ $mOrd->order_number ?? 'In Use' }}]
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- 3. Payment Status Dropdown -->
                            <div class="relative inline-block" @click.outside="paymentOpen = false">
                                <button type="button" @click="paymentOpen = !paymentOpen; statusOpen = false; machineOpen = false;" class="h-9 min-w-[95px] py-1 px-3 text-xs rounded-lg font-bold bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 flex items-center justify-between gap-2 shadow-sm">
                                    <span class="capitalize" x-text="paymentVal"></span>
                                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0 transition-transform duration-200" :class="paymentOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>

                                <div x-show="paymentOpen" x-cloak x-transition class="absolute z-50 top-full left-0 mt-1 min-w-[110px] w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                                    <button type="button" @click="paymentVal = 'unpaid'; paymentOpen = false;" class="w-full text-left px-3.5 py-2 text-xs font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="paymentVal == 'unpaid' ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                                        <span>Unpaid</span>
                                        <span x-show="paymentVal == 'unpaid'" class="font-bold shrink-0 ml-2">✓</span>
                                    </button>
                                    <button type="button" @click="paymentVal = 'paid'; paymentOpen = false;" class="w-full text-left px-3.5 py-2 text-xs font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="paymentVal == 'paid' ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                                        <span>Paid</span>
                                        <span x-show="paymentVal == 'paid'" class="font-bold shrink-0 ml-2">✓</span>
                                    </button>
                                </div>
                            </div>

                            <!-- 4. Update Button -->
                            <button type="submit" class="btn-primary h-9 py-1 px-3 text-xs whitespace-nowrap">
                                Update Order & Machine
                            </button>
                        </form>

                        <form method="POST" action="{{ route('laundry.auto-assign-rider', $order->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 border border-indigo-500/30 hover:bg-indigo-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1 cursor-pointer" title="Auto-assign on-duty rider">
                                ⚡ Auto-Assign Rider
                            </button>
                        </form>

                        <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'power-outage-{{ $order->id }}')" class="bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 hover:bg-amber-500/25 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                            Power Outage Extension
                        </button>

                        <form method="POST" action="{{ route('laundry.destroy', $order->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete Order #{{ $order->order_number }} permanently? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-rose-500/15 text-rose-700 dark:text-rose-400 border border-rose-500/30 hover:bg-rose-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer" title="Delete Order One-by-One">
                                🗑️ Delete
                            </button>
                        </form>

                        <x-modal name="power-outage-{{ $order->id }}" maxWidth="sm">
                            <form method="POST" action="{{ route('admin.laundry.extend', $order->id) }}" class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                @csrf
                                <h2 class="text-base font-bold text-amber-600 dark:text-amber-400">Power Outage Extension?</h2>
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                    Select estimated brownout delay time for order <strong>#{{ $order->order_number }}</strong>:
                                </p>
                                <div>
                                    <select name="delay_minutes" class="w-full py-2 px-3 text-xs rounded-md bg-slate-50 dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700">
                                        <option value="30">+30 mins delay</option>
                                        <option value="60" selected>+60 mins delay</option>
                                        <option value="120">+2 hours delay</option>
                                        <option value="180">+3 hours delay</option>
                                    </select>
                                </div>
                                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn-primary text-xs py-1.5 px-3">
                                        Apply Delay & Notify Customer
                                    </button>
                                </div>
                            </form>
                        </x-modal>
                    </div>
                </div>
            @empty
                <div class="app-card p-8 text-center text-slate-500 text-xs">
                    No laundry orders registered in the store system yet.
                </div>
            @endforelse
        </div>
    </div>

    <x-camera-qr-scanner />

<script>
    function filterAdminOrders(type) {
        const cards = document.querySelectorAll('.admin-order-card');
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            if (type === 'all') {
                card.style.display = '';
            } else if (type === 'active') {
                card.style.display = (status !== 'completed' && status !== 'cancelled') ? '' : 'none';
            } else if (type === 'completed') {
                card.style.display = status === 'completed' ? '' : 'none';
            } else if (type === 'cancelled') {
                card.style.display = status === 'cancelled' ? '' : 'none';
            }
        });
    }
</script>

</x-app-layout>
