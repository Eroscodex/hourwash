<x-app-layout>
    <div class="space-y-6 sm:space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white font-['Outfit']">
                    Store Operations Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Real-time laundry queue, machine monitoring, and system metrics.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <a href="{{ route('admin.laundry.index') }}" class="btn-ios-secondary text-center">
                    View Orders
                </a>
                <a href="{{ route('admin.machines.create') }}" class="btn-ios-primary text-center">
                    + Add New Machine
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">TODAY'S ORDERS</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $totalToday ?? 28 }}</div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">↑ +12% <span class="text-slate-500 dark:text-slate-400 font-normal">vs yesterday</span></span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">IN PROCESSING</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $inProgress ?? 16 }}</div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Active machine cycles</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">READY FOR PICKUP</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $readyPickup ?? 8 }}</div>
                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Awaiting customer collection</span>
                </div>
            </div>

            <div class="app-card p-4 sm:p-5 flex flex-col justify-between space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">COMPLETED TODAY</span>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $completedToday ?? 24 }}</div>
                    <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">↑ +20% <span class="text-slate-500 dark:text-slate-400 font-normal">vs last week</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-12 app-card p-4 sm:p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Machine Status Monitor</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Real-time status of commercial washers & dryers</p>
                    </div>
                    <a href="{{ route('admin.machines.index') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">Manage Machines →</a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @forelse($machines as $machine)
                        <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-2 hover:border-[#007AFF]/40 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 dark:text-slate-100 truncate">{{ $machine->machine_name }}</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 font-mono flex-shrink-0">{{ $machine->machine_code }}</span>
                            </div>
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold
                                @if($machine->status === 'washing') bg-teal-500/15 text-teal-700 dark:text-teal-300
                                @elseif($machine->status === 'rinsing') bg-sky-500/15 text-sky-700 dark:text-sky-300
                                @elseif($machine->status === 'drying') bg-indigo-500/15 text-indigo-700 dark:text-indigo-300
                                @elseif($machine->status === 'idle') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300
                                @else bg-amber-500/15 text-amber-700 dark:text-amber-300 @endif">
                                <svg class="w-4 h-4 stroke-current" fill="none" viewBox="0 0 24 24">
                                    <rect x="4" y="3" width="16" height="18" rx="2" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="4" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="1" stroke-width="2"/>
                                    <circle cx="8" cy="6" r="1" stroke-width="2"/>
                                    <circle cx="16" cy="6" r="1" stroke-width="2"/>
                                </svg>
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
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    @if(in_array($machine->status, ['washing', 'rinsing', 'drying']))
                                        <span>⏱ {{ $machine->remaining_minutes ?? 30 }} mins remaining</span>
                                        <span class="block text-[9px] text-[#007AFF] dark:text-[#0A84FF] font-bold mt-0.5">
                                            Est. Finish: {{ now()->addMinutes($machine->remaining_minutes ?? 30)->format('h:i A') }}
                                        </span>
                                    @elseif($machine->status === 'maintenance')
                                        <span class="text-amber-600 dark:text-amber-400 font-semibold">⚠ Maintenance</span>
                                    @elseif($machine->status === 'offline')
                                        <span class="text-rose-600 dark:text-rose-400 font-semibold">🚫 Offline</span>
                                    @else
                                        Available
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-6 text-xs text-slate-500">No machines configured.</div>
                    @endforelse
                </div>

                <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[11px] text-slate-500 dark:text-slate-400 border-t border-black/5 dark:border-white/10 pt-4">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span> Washing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Rinsing</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> Drying</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Idle</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Maintenance</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 app-card p-4 sm:p-6 space-y-4 overflow-hidden flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Recent Laundry Orders</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Live feed of active store and online orders</p>
                    </div>
                    <a href="{{ route('admin.laundry.index') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] hover:opacity-80 font-semibold">View All Orders →</a>
                </div>

                <div class="overflow-x-auto max-w-full flex-1 mt-4">
                    <table class="w-full text-left text-xs whitespace-nowrap min-w-[500px]">
                        <thead class="bg-black/5 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/5 dark:border-white/10">
                            <tr>
                                <th class="px-4 py-3">Order Code</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Service</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                            @forelse($recentOrders->take(6) as $order)
                                <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                    <td class="px-4 py-3 font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">#{{ $order->order_number }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                                    <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $order->service->name ?? 'Standard Wash' }}</td>
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
                                    <td colspan="5" class="text-center py-6 text-slate-500">No recent orders recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
                        <div class="flex items-center justify-between gap-4 pt-1">
                            <p class="text-xs text-slate-600 dark:text-slate-300 italic">"{{ $fb->comment }}"</p>
                            <form method="POST" action="{{ route('feedback.destroy', $fb->id) }}" onsubmit="return confirm('Delete this customer feedback?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-[10px] font-extrabold uppercase hover:underline transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-6 text-xs text-slate-500">No customer reviews submitted yet.</div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>