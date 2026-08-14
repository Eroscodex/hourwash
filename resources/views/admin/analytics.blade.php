<x-app-layout>

<div class="space-y-8">

    <div>
        <h1 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">System Reports & Analytics</h1>
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Operational statistics, user metrics, and order stage breakdown.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="app-card p-5">
            <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Registered Users</div>
            <p class="text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $totalUsers }}</p>
        </div>

        <div class="app-card p-5">
            <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Total Machines</div>
            <p class="text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">{{ $totalMachines }}</p>
        </div>

        <div class="app-card p-5">
            <div class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-2">Available (Idle)</div>
            <p class="text-3xl font-bold font-['Outfit'] text-emerald-600 dark:text-emerald-400">{{ $availableMachines }}</p>
        </div>

        <div class="app-card p-5">
            <div class="text-[11px] font-bold text-[#007AFF] dark:text-[#0A84FF] uppercase tracking-wider mb-2">Total System Orders</div>
            <p class="text-3xl font-bold font-['Outfit'] text-[#007AFF] dark:text-[#0A84FF]">{{ $totalLaundry }}</p>
        </div>

    </div>

    <div class="app-card p-6 space-y-4">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Order Status Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-100 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                    <tr>
                        <th class="text-left px-4 py-3">Stage Status</th>
                        <th class="text-left px-4 py-3">Total Orders Count</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                @foreach($laundryStatus as $status)
                    <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                        <td class="px-4 py-3 text-slate-900 dark:text-slate-200 capitalize font-medium">{{ str_replace('_', ' ', $status->status) }}</td>
                        <td class="px-4 py-3 text-slate-900 dark:text-white font-bold">{{ $status->total }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-app-layout>