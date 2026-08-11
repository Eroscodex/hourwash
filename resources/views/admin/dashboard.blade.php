<x-app-layout>
    <div class="space-y-6 sm:space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white font-['Outfit'] flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#007AFF] dark:text-[#0A84FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Store Operations Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Real-time laundry POS queue, machine fleet monitoring, and system metrics.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <a href="{{ route('admin.laundry.index') }}" class="btn-ios-secondary text-center">
                    View Orders POS
                </a>
                <a href="{{ route('admin.machines.create') }}" class="btn-ios-primary text-center">
                    + Add New Machine
                </a>
            </div>
        </div>

        <!-- 4 Top Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <!-- Today's Orders -->
            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">TODAY'S ORDERS</span>
                    <div class="w-8 h-8 rounded-xl bg-[#007AFF]/10 text-[#007AFF] dark:text-[#0A84FF] border border-[#007AFF]/20 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $totalToday ?? 28 }}</div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">↑ +12% <span class="text-slate-500 dark:text-slate-400 font-normal">vs yesterday</span></span>
                </div>
            </div>

            <!-- In Progress -->
            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">IN PROCESSING</span>
                    <div class="w-8 h-8 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 border border-teal-500/20 flex items-center justify-center font-bold">
                        <img src="{{ asset('hourwash.ico') }}" alt="HourWash" class="w-5 h-5 rounded-full object-cover">
                    </div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $inProgress ?? 16 }}</div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Active machine cycles</span>
                </div>
            </div>

            <!-- Ready For Pickup -->
            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">READY FOR PICKUP</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $readyPickup ?? 8 }}</div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Awaiting customer collection</span>
                </div>
            </div>

            <!-- Completed Today -->
            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">COMPLETED TODAY</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $completedToday ?? 24 }}</div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">↑ +20% <span class="text-slate-500 dark:text-slate-400 font-normal">vs last week</span></span>
                </div>
            </div>
        </div>

        <!-- Middle Section: Machine Status Grid & QR Code Inspector (12 cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Machine Status Monitoring Grid (7 cols) -->
            <div class="lg:col-span-7 app-card p-4 sm:p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Machine Fleet Status</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Real-time status of commercial washers & dryers</p>
                    </div>
                    <a href="{{ route('admin.machines.index') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">Manage Machines →</a>
                </div>

                <!-- Machines Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                    @forelse($machines as $machine)
                        <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-2 hover:border-[#007AFF]/40 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate">{{ $machine->machine_name }}</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex-shrink-0">{{ $machine->machine_code }}</span>
                            </div>
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-bold
                                @if($machine->status === 'washing') bg-teal-500/15 text-teal-700 dark:text-teal-300
                                @elseif($machine->status === 'rinsing') bg-sky-500/15 text-sky-700 dark:text-sky-300
                                @elseif($machine->status === 'drying') bg-indigo-500/15 text-indigo-700 dark:text-indigo-300
                                @elseif($machine->status === 'idle') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300
                                @else bg-amber-500/15 text-amber-700 dark:text-amber-300 @endif">
                                <img src="{{ asset('hourwash.ico') }}" alt="HourWash" class="w-4 h-4 rounded-full object-cover">
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider block
                                    @if($machine->status === 'washing') text-teal-600 dark:text-teal-400
                                    @elseif($machine->status === 'rinsing') text-sky-600 dark:text-sky-400
                                    @elseif($machine->status === 'drying') text-indigo-600 dark:text-indigo-400
                                    @elseif($machine->status === 'idle') text-emerald-600 dark:text-emerald-400
                                    @else text-amber-600 dark:text-amber-400 @endif">
                                    {{ strtoupper($machine->status) }}
                                </span>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400">
                                    @if($machine->remaining_minutes)
                                        {{ $machine->remaining_minutes }} min remaining
                                    @else
                                        Available
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-xs text-slate-500">No machines configured.</div>
                    @endforelse
                </div>

                <!-- Status Legend -->
                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[11px] text-slate-500 dark:text-slate-400 border-t border-black/5 dark:border-white/10 pt-4">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span> Washing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Rinsing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Drying</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Idle</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Maintenance</span>
                </div>
            </div>

            <!-- QR Code Inspector Card (5 cols) -->
            <div class="lg:col-span-5 app-card p-4 sm:p-6 space-y-6">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">QR Verification Terminal</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Instant order verification & laundry tracking</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    <div class="sm:col-span-5 bg-white p-3 rounded-xl flex items-center justify-center border border-black/10 shadow-sm">
                        <svg class="w-28 h-28 sm:w-32 sm:h-32" viewBox="0 0 100 100" fill="none">
                            <rect width="100" height="100" fill="white"/>
                            <path d="M10 10h30v30H10V10zm6 6v18h18V16H16z" fill="#0F172A"/>
                            <path d="M22 22h6v6h-6v-6z" fill="#0F172A"/>
                            <path d="M60 10h30v30H60V10zm6 6v18h18V16H66z" fill="#0F172A"/>
                            <path d="M72 22h6v6h-6v-6z" fill="#0F172A"/>
                            <path d="M10 60h30v30H10V60zm6 6v18h18V66H16z" fill="#0F172A"/>
                            <path d="M22 72h6v6h-6v-6z" fill="#0F172A"/>
                            <path d="M50 50h10v10H50V50zm20 0h10v10H70V50zm10 20h10v10H80V70zm-20 10h10v10H60V80zm10 0h10v10H70V80z" fill="#0F172A"/>
                        </svg>
                    </div>

                    <div class="sm:col-span-7 space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900 dark:text-white">Order #HW-884210</span>
                            <span class="px-2 py-0.5 rounded bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] font-bold text-[10px]">WASHING</span>
                        </div>
                        <div class="space-y-1.5 text-slate-700 dark:text-slate-300">
                            <p><span class="text-slate-500 dark:text-slate-400">Customer:</span> <strong class="text-slate-900 dark:text-white">Maria Santos</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Service:</span> <strong class="text-slate-900 dark:text-white">Wash & Dry (6.5kg)</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Received:</span> <strong class="text-slate-900 dark:text-white">Today 10:15 AM</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Est. Finish:</span> <strong class="text-slate-900 dark:text-white">Today 11:45 AM</strong></p>
                            <p><span class="text-slate-500 dark:text-slate-400">Status:</span> <strong class="text-[#007AFF] dark:text-[#0A84FF]">In Machine #2</strong></p>
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-black/5 dark:border-white/10">
                    <div class="flex flex-wrap items-center justify-between gap-1 text-[10px] font-bold text-slate-500 dark:text-slate-400">
                        <span class="text-emerald-600 dark:text-emerald-400">RECEIVED</span>
                        <span class="text-[#007AFF] dark:text-[#0A84FF]">● WASHING</span>
                        <span>○ RINSING</span>
                        <span>○ DRYING</span>
                        <span>○ READY</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders POS Table (12 cols) -->
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Recent POS Laundry Orders</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Live feed of active store and online orders</p>
                </div>
                <a href="{{ route('admin.laundry.index') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">View All Orders →</a>
            </div>

            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[600px]">
                    <thead class="bg-black/5 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/5 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-3">Order Code</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Total Amount</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3 font-medium">{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $order->service->name ?? 'Standard Wash' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $order->weight_kg }} kg</td>
                                <td class="px-4 py-3 font-bold text-slate-900 dark:text-white">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($order->order_status === 'completed') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30
                                        @elseif($order->order_status === 'ready') bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30
                                        @elseif($order->order_status === 'pending') bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300
                                        @else bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30 @endif">
                                        {{ str_replace('_', ' ', $order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.laundry.index') }}" class="text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">Manage</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-500">No recent orders recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer Feedback Reviews Feed -->
        <div class="app-card p-4 sm:p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Customer Ratings & Reviews</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Live feedback submitted by Legazpi store customers</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-amber-500/15 text-amber-600 dark:text-amber-400 text-xs font-bold">
                    {{ count($feedbacks ?? []) }} Reviews
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($feedbacks ?? [] as $fb)
                    <div class="p-4 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#007AFF] text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($fb->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white">{{ $fb->user->name ?? 'Customer' }}</h4>
                                    <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ $fb->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center text-amber-400 gap-0.5">
                                @for($i = 0; $i < ($fb->rating ?? 5); $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 italic">"{{ $fb->comment }}"</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-xs text-slate-500">No customer reviews submitted yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>