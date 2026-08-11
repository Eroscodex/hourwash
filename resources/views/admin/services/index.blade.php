<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#007AFF] dark:text-[#0A84FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.11a2 2 0 01-1.183-1.845V7.4a2 2 0 011.183-1.845l2.4-1.2a6 6 0 013.86-.517l.318.158a6 6 0 003.86.517l2.387-.477a2 2 0 011.022.547l2.4 2.4a2 2 0 01.586 1.414v7.172a2 2 0 01-.586 1.414l-2.4 2.4z"/>
                    </svg>
                    Services & Pricing Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Configure laundry service packages, prices per kg/item, estimated completion times, and availability.</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="btn-ios-primary w-full sm:w-fit text-center flex items-center justify-center gap-1.5 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Service Package
            </a>
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
                            <th class="px-4 sm:px-6 py-3.5">Created Date</th>
                            <th class="px-4 sm:px-6 py-3.5">Service Package</th>
                            <th class="px-4 sm:px-6 py-3.5">Type</th>
                            <th class="px-4 sm:px-6 py-3.5">Price</th>
                            <th class="px-4 sm:px-6 py-3.5">Est. Duration</th>
                            <th class="px-4 sm:px-6 py-3.5">Status</th>
                            <th class="px-4 sm:px-6 py-3.5 text-right">Actions</th>
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
                                {{ $srv->created_at ? $srv->created_at->format('M d, Y') : 'Aug 09, 2026' }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $srv->name }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 max-w-xs truncate block">{{ $srv->description }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 capitalize text-slate-700 dark:text-slate-300">{{ str_replace('_', ' ', $srv->service_type) }}</td>
                            <td class="px-4 sm:px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400 font-mono">
                                ₱{{ number_format($srv->price, 2) }} <span class="text-[10px] text-slate-500 font-normal">/ {{ $srv->price_unit }}</span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-slate-700 dark:text-slate-300 font-semibold font-mono">
                                {{ $durationFormatted }}
                            </td>
                            <td class="px-4 sm:px-6 py-4">
                                @if($srv->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Active</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.services.edit', $srv) }}" class="p-1.5 text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="Edit Service">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $srv) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete this service package?')" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete Service">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
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
