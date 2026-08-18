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
                <form method="POST" action="{{ route('admin.orders.reset') }}" class="w-full sm:w-auto" onsubmit="return confirm('⚠️ ARE YOU SURE YOU WANT TO RESET ALL ORDERS?\n\nThis will permanently delete all order history and set all machines to idle status.')">
                    @csrf
                    <button type="submit" class="w-full bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-2.5 py-1.5 rounded-lg text-[10px] font-bold transition whitespace-nowrap flex items-center justify-center h-full">
                        Reset All Orders
                    </button>
                </form>
                <a href="{{ route('laundry.create') }}" class="btn-primary text-[10px] py-1.5 px-2.5 whitespace-nowrap text-center col-span-2 sm:col-span-1 w-full sm:w-auto flex items-center justify-center">New Drop-Off Order</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif


        <div class="space-y-4 pb-64">
            @forelse($orders as $order)
                <div class="app-card p-5 space-y-4 shadow-sm hover:border-blue-600/30 transition">
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
                        <form method="POST" action="{{ route('admin.laundry.update', $order->id) }}" class="flex flex-wrap items-center gap-2">
                            @csrf
                            @method('PATCH')

                            <select name="status" class="max-w-[180px] sm:max-w-[220px] truncate py-1 px-2.5 text-xs rounded-lg font-bold">
                                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>1. Pending (Order Placed)</option>
                                <option value="out_for_pickup" {{ $order->order_status === 'out_for_pickup' ? 'selected' : '' }}>2. Out for Pickup</option>
                                <option value="received" {{ $order->order_status === 'received' ? 'selected' : '' }}>3. Store Received</option>
                                <option value="washing" {{ $order->order_status === 'washing' ? 'selected' : '' }}>4. Washing</option>
                                <option value="rinsing" {{ $order->order_status === 'rinsing' ? 'selected' : '' }}>5. Rinsing</option>
                                <option value="drying" {{ $order->order_status === 'drying' ? 'selected' : '' }}>6. Drying</option>
                                <option value="finish" {{ $order->order_status === 'finish' ? 'selected' : '' }}>7. Finish & Shelved</option>
                                <option value="out_for_delivery" {{ $order->order_status === 'out_for_delivery' ? 'selected' : '' }}>8. Out for Delivery</option>
                                <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>9. Completed</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>

                            <select name="machine_id" class="max-w-[220px] sm:max-w-[260px] truncate py-1 px-2.5 text-xs rounded-lg font-semibold bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100">
                                <option value="">-- Assign Machine --</option>
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
                                @endphp

                                @if(count($availableM) > 0)
                                    <optgroup label="AVAILABLE MACHINES">
                                        @foreach($availableM as $item)
                                            @php $m = $item['m']; @endphp
                                            <option value="{{ $m->id }}" {{ $order->machine_id == $m->id ? 'selected' : '' }}>
                                                {{ $m->machine_name }} ({{ $m->machine_code }}) [AVAILABLE]
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if(count($occupiedM) > 0)
                                    <optgroup label="BUSY / IN-USE MACHINES">
                                        @foreach($occupiedM as $item)
                                            @php $m = $item['m']; $mOrd = $item['mOrd']; @endphp
                                            <option value="{{ $m->id }}" {{ $order->machine_id == $m->id ? 'selected' : '' }} disabled>
                                                {{ $m->machine_name }} ({{ $m->machine_code }}) [BUSY - Order #{{ $mOrd->order_number ?? 'In Use' }}]
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>

                            <select name="payment_status" class="py-1 px-2.5 text-xs rounded-lg">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>

                            <button type="submit" class="btn-primary py-1 px-3 text-xs">
                                Update Order & Machine
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.laundry.extend', $order->id) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="delay_minutes" class="py-1 px-2 text-xs rounded-lg">
                                <option value="30">+30 mins delay</option>
                                <option value="60" selected>+60 mins delay</option>
                                <option value="120">+2 hours delay</option>
                                <option value="180">+3 hours delay</option>
                            </select>
                            <button type="submit" onclick="return confirm('Extend estimated completion time for Power Outage / Interruption?')" class="bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 hover:bg-amber-500/25 px-3 py-1.5 rounded-lg text-xs font-bold transition">
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

    <x-camera-qr-scanner />

</x-app-layout>
