<x-app-layout>
    <div class="space-y-6 sm:space-y-8">
        
        <!-- Staff Workstation Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                    Staff Processing Terminal
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage active washing, drying, and folding pipeline for customer orders.
                </p>
            </div>
            
            <a href="{{ route('admin.laundry.index') }}" class="btn-ios-primary text-center">
                + New Walk-in Order
            </a>
        </div>

        <!-- 4 Workstation Status Counters -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="app-card p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider block">RECEIVED</span>
                    <span class="text-2xl font-bold text-slate-900 dark:text-white font-['Outfit']">6</span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Awaiting washer loading</p>
                </div>
            </div>

            <div class="app-card p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider block">WASHING & RINSING</span>
                    <span class="text-2xl font-bold text-[#007AFF] dark:text-[#0A84FF] font-['Outfit']">8</span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Machines running</p>
                </div>
            </div>

            <div class="app-card p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider block">DRYING CYCLE</span>
                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-['Outfit']">4</span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">In dryer units</p>
                </div>
            </div>

            <div class="app-card p-4 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-extrabold text-slate-900 dark:text-slate-200 uppercase tracking-wider block">READY FOR PICKUP</span>
                    <span class="text-2xl font-bold text-amber-600 dark:text-amber-400 font-['Outfit']">12</span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Shelved & tagged</p>
                </div>
            </div>
        </div>

        <!-- Live Processing Pipeline Table -->
        <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Active Processing Pipeline</h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">Update cleaning status stages as laundry moves through machines</p>
                </div>
                <a href="{{ route('admin.laundry.index') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] font-semibold hover:opacity-80">Full Orders Queue →</a>
            </div>

            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-[600px]">
                    <thead class="bg-slate-100 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-3">Order Tag</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Service</th>
                            <th class="px-4 py-3">Weight</th>
                            <th class="px-4 py-3">Current Stage</th>
                            <th class="px-4 py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                <td class="px-4 py-3 font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">#{{ $order->order_number }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $order->customer->name ?? 'Walk-in' }}</div>
                                    @if(!empty($order->notes))
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400 italic max-w-xs truncate" title="{{ $order->notes }}">
                                            📝 {{ $order->notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $order->service->name ?? 'Standard Wash' }}</td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-300">{{ $order->weight_kg }} kg</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($order->order_status === 'completed') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30
                                        @elseif($order->order_status === 'done') bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30
                                        @elseif($order->order_status === 'cancelled') bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30
                                        @else bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30 @endif">
                                        {{ str_replace('_', ' ', $order->order_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.laundry.index') }}" class="btn-ios-secondary py-1 px-3 text-[11px]">Manage</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-500">No active processing orders.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
