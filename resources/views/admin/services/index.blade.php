<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Services & Pricing Management</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Configure laundry service packages, prices per kg/item, estimated completion times, and availability.</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn-ios-primary w-full sm:w-fit text-center">+ Add New Service Package</a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap min-w-[750px]">
                    <thead class="bg-slate-100 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-black/10 dark:border-white/10">
                        <tr>
                            <th class="px-4 sm:px-6 py-3.5">ID</th>
                            <th class="px-4 sm:px-6 py-3.5">Created Date & Time</th>
                            <th class="px-4 sm:px-6 py-3.5">Service Package Name</th>
                            <th class="px-4 sm:px-6 py-3.5">Service Type</th>
                            <th class="px-4 sm:px-6 py-3.5">Price</th>
                            <th class="px-4 sm:px-6 py-3.5">Est. Duration (Hrs/Mins)</th>
                            <th class="px-4 sm:px-6 py-3.5">Status</th>
                            <th class="px-4 sm:px-6 py-3.5 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                    @forelse($services as $srv)
                        @php
                            $mins = $srv->estimated_minutes;
                            $hrs = floor($mins / 60);
                            $remMins = $mins % 60;
                            $durationFormatted = $hrs > 0 ? ($remMins > 0 ? "{$hrs} hrs {$remMins} mins" : "{$hrs} hrs") : "{$mins} mins";
                        @endphp
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <td class="px-4 sm:px-6 py-4 font-mono text-slate-500 font-bold">#{{ $srv->id }}</td>
                            <td class="px-4 sm:px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                                {{ $srv->created_at ? $srv->created_at->format('M d, Y h:i A') : 'Aug 09, 2026 12:31 PM' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $srv->name }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 max-w-xs truncate block">{{ $srv->description }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 capitalize text-slate-700 dark:text-slate-300">{{ str_replace('_', ' ', $srv->service_type) }}</td>
                            <td class="px-4 sm:px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                                ₱{{ number_format($srv->price, 2) }} <span class="text-[10px] text-slate-500 font-normal">/ {{ $srv->price_unit }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-700 dark:text-slate-300 font-semibold">
                                ⏱ {{ $durationFormatted }} <span class="text-[10px] text-slate-500">({{ $mins }} mins)</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                @if($srv->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center">
                                <a href="{{ route('admin.services.edit', $srv) }}" class="text-[#007AFF] dark:text-[#0A84FF] hover:underline mr-3 text-xs font-semibold transition inline-block">✏ Edit</a>
                                <form action="{{ route('admin.services.destroy', $srv) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Delete this service package?')" class="text-rose-600 dark:text-rose-400 hover:underline text-xs font-semibold transition">🗑 Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-slate-500">No service packages found in database.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>
