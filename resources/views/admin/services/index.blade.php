<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    Services & Pricing Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Configure laundry service packages, prices per kg/item, estimated completion times, and availability.</p>
            </div>
            <button type="button" 
                    x-data="" 
                    x-on:click="$dispatch('open-modal', 'add-service-modal')" 
                    class="btn-primary w-full sm:w-fit text-center flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Service Package
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
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
                            $hrLabel = $hrs == 1 ? 'hr' : 'hrs';
                            $durationFormatted = $hrs > 0 ? ($remMins > 0 ? "{$hrs} {$hrLabel} {$remMins} mins" : "{$hrs} {$hrLabel}") : "{$mins} mins";
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
                                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'delete-service-{{ $srv->id }}')" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete Service">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>

                                    <x-modal name="delete-service-{{ $srv->id }}" maxWidth="sm">
                                        <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Delete Service Package?</h2>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                                Are you sure you want to delete service package <strong>{{ $srv->name }}</strong>?
                                            </p>
                                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                                    Cancel
                                                </button>
                                                <form action="{{ route('admin.services.destroy', $srv) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                                        Delete Package
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>
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

    <!-- Register New Service Modal -->
    <x-modal name="add-service-modal" maxWidth="lg">
        <div class="p-6 sm:p-7 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 rounded-lg text-left space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-zinc-800">
                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Register New Service Package</h2>
                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition text-base font-bold">✕</button>
            </div>

            <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Package Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Wash & Dry Special" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Description</label>
                    <textarea name="description" rows="2" placeholder="Brief details about what is included..." class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Category Type <span class="text-rose-500">*</span></label>
                        <select name="service_type" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                            <option value="wash_dry">Wash & Dry</option>
                            <option value="wash">Wash Only</option>
                            <option value="dry">Dry Only</option>
                            <option value="fold">Fold Only</option>
                            <option value="wash_dry_fold">Wash, Dry & Fold</option>
                            <option value="blanket">Comforters & Blankets</option>
                            <option value="pickup_delivery">Pickup & Delivery</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Price Amount (₱) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', '120.00') }}" placeholder="120.00" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Pricing Unit <span class="text-rose-500">*</span></label>
                        <select name="price_unit" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                            <option value="load">Per Load</option>
                            <option value="kg">Per Kilogram (kg)</option>
                            <option value="item">Per Item</option>
                            <option value="service">Per Service</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Est. Mins <span class="text-rose-500">*</span></label>
                        <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', '60') }}" placeholder="60" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                    <button type="button" x-on:click="$dispatch('close')" class="btn-secondary py-2 px-4 text-xs font-bold">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary py-2 px-5 text-xs font-bold shadow-sm">
                        Save Service Package
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

</x-app-layout>
