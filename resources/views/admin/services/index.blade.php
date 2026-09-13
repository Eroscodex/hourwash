<x-app-layout>

    <div class="space-y-3.5">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">
                    Services & Pricing Management
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">Configure laundry service packages, prices per kg/item, estimated completion times, and availability.</p>
            </div>
            <button type="button" 
                    x-data="" 
                    x-on:click="$dispatch('open-modal', 'add-service-modal')" 
                    class="btn-primary w-full sm:w-fit text-center flex items-center justify-center gap-1.5 py-1.5 px-3 text-xs shadow-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Service Package
            </button>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-3.5 py-2 rounded-lg text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs whitespace-nowrap min-w-full">
                    <thead class="bg-slate-100 dark:bg-[#2C2C2E] text-slate-700 dark:text-slate-300 uppercase text-[9.5px] font-extrabold tracking-wider border-b border-black/10 dark:border-white/10">
                        <tr>
                            <th class="px-2.5 py-2">ID</th>
                            <th class="px-2.5 py-2">Created Date</th>
                            <th class="px-2.5 py-2">Service Package</th>
                            <th class="px-2.5 py-2">Type</th>
                            <th class="px-2.5 py-2">Price</th>
                            <th class="px-2.5 py-2">Est. Duration</th>
                            <th class="px-2.5 py-2">Status</th>
                            <th class="px-2.5 py-2 text-right">Actions</th>
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
                            <td class="px-2.5 py-1.5 font-mono text-slate-500 font-bold">#{{ $srv->id }}</td>
                            <td class="px-2.5 py-1.5 text-xs font-medium text-slate-600 dark:text-slate-400">
                                {{ $srv->created_at ? $srv->created_at->format('M d, Y') : 'Aug 09, 2026' }}
                            </td>
                            <td class="px-2.5 py-1.5">
                                <span class="font-bold text-slate-900 dark:text-white block text-xs">{{ $srv->name }}</span>
                                <span class="text-[10.5px] text-slate-500 dark:text-slate-400 max-w-[200px] truncate block" title="{{ $srv->description }}">{{ $srv->description }}</span>
                            </td>
                            <td class="px-2.5 py-1.5 capitalize text-slate-700 dark:text-slate-300 text-xs">{{ str_replace('_', ' ', $srv->service_type) }}</td>
                            <td class="px-2.5 py-1.5 font-bold text-emerald-600 dark:text-emerald-400 font-mono text-xs">
                                ₱{{ number_format($srv->price, 2) }} <span class="text-[10px] text-slate-500 font-normal">/ {{ $srv->price_unit }}</span>
                            </td>
                            <td class="px-2.5 py-1.5 text-slate-700 dark:text-slate-300 font-semibold font-mono text-xs">
                                {{ $durationFormatted }}
                            </td>
                            <td class="px-2.5 py-1.5">
                                @if($srv->status === 'active')
                                    <span class="px-2 py-0.5 rounded text-[9.5px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Active</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[9.5px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Inactive</span>
                                @endif
                            </td>
                            <td class="px-2.5 py-1.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'edit-service-{{ $srv->id }}')" class="p-1.5 bg-blue-500/10 hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 rounded-lg transition cursor-pointer" title="Edit Service Package {{ $srv->name }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>

                                    <x-modal name="edit-service-{{ $srv->id }}" maxWidth="lg">
                                        <div class="p-6 sm:p-7 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 rounded-lg text-left space-y-5">
                                            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-zinc-800">
                                                <h2 class="text-base sm:text-lg font-extrabold text-slate-900 dark:text-white">Edit Service Package: <span class="text-blue-600 dark:text-blue-400">{{ $srv->name }}</span></h2>
                                                <button type="button" x-on:click="$dispatch('close')" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition text-base font-bold">✕</button>
                                            </div>

                                            <form action="{{ route('admin.services.update', $srv) }}" method="POST" class="space-y-4">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Package Name <span class="text-rose-500">*</span></label>
                                                    <input type="text" name="name" value="{{ old('name', $srv->name) }}" placeholder="e.g. Wash & Dry Special" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Description</label>
                                                    <textarea name="description" rows="2" placeholder="Brief details about what is included..." class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600">{{ old('description', $srv->description) }}</textarea>
                                                </div>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Category Type <span class="text-rose-500">*</span></label>
                                                        <select name="service_type" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                                                            <option value="wash_dry" {{ old('service_type', $srv->service_type) === 'wash_dry' ? 'selected' : '' }}>Wash & Dry</option>
                                                            <option value="wash" {{ old('service_type', $srv->service_type) === 'wash' ? 'selected' : '' }}>Wash Only</option>
                                                            <option value="dry" {{ old('service_type', $srv->service_type) === 'dry' ? 'selected' : '' }}>Dry Only</option>
                                                            <option value="fold" {{ old('service_type', $srv->service_type) === 'fold' ? 'selected' : '' }}>Fold Only</option>
                                                            <option value="wash_dry_fold" {{ old('service_type', $srv->service_type) === 'wash_dry_fold' ? 'selected' : '' }}>Wash, Dry & Fold</option>
                                                            <option value="blanket" {{ old('service_type', $srv->service_type) === 'blanket' ? 'selected' : '' }}>Comforters & Blankets</option>
                                                            <option value="other" {{ old('service_type', $srv->service_type) === 'other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Price Amount (₱) <span class="text-rose-500">*</span></label>
                                                        <input type="number" step="0.01" name="price" value="{{ old('price', $srv->price) }}" placeholder="120.00" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Pricing Unit <span class="text-rose-500">*</span></label>
                                                        <select name="price_unit" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                                                            <option value="load" {{ old('price_unit', $srv->price_unit) === 'load' ? 'selected' : '' }}>Per Load</option>
                                                            <option value="kg" {{ old('price_unit', $srv->price_unit) === 'kg' ? 'selected' : '' }}>Per Kilogram (kg)</option>
                                                            <option value="item" {{ old('price_unit', $srv->price_unit) === 'item' ? 'selected' : '' }}>Per Item</option>
                                                            <option value="service" {{ old('price_unit', $srv->price_unit) === 'service' ? 'selected' : '' }}>Per Service</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Est. Mins <span class="text-rose-500">*</span></label>
                                                        <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', $srv->estimated_minutes) }}" placeholder="60" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                                                    </div>

                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-1.5">Status <span class="text-rose-500">*</span></label>
                                                        <select name="status" class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600" required>
                                                            <option value="active" {{ old('status', $srv->status) === 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ old('status', $srv->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
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

                                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'delete-service-{{ $srv->id }}')" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete Service">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
