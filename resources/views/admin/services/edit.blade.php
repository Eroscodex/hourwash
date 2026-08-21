<x-app-layout>

    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header Title -->
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Edit Service Package: <span class="text-blue-600 dark:text-blue-400">{{ $service->name }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-1">Update service package pricing, estimated completion duration, and options.</p>
        </div>

        <!-- Form Card Container -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-sm p-6 sm:p-8 space-y-6">
            <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Package Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                        Service Package Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $service->name) }}" 
                           placeholder="e.g. Wash & Dry Special" 
                           class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                           required>
                    @error('name') 
                        <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                        Service Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              placeholder="Brief details about what is included in this service package..." 
                              class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm">{{ old('description', $service->description) }}</textarea>
                    @error('description') 
                        <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <!-- Grid: Category Type & Price -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="service_type" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Category Type <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="service_type" 
                                    name="service_type" 
                                    class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm appearance-none pr-10" 
                                    required>
                                <option value="wash_dry" {{ old('service_type', $service->service_type) === 'wash_dry' ? 'selected' : '' }}>Wash & Dry</option>
                                <option value="wash" {{ old('service_type', $service->service_type) === 'wash' ? 'selected' : '' }}>Wash Only</option>
                                <option value="dry" {{ old('service_type', $service->service_type) === 'dry' ? 'selected' : '' }}>Dry Only</option>
                                <option value="fold" {{ old('service_type', $service->service_type) === 'fold' ? 'selected' : '' }}>Fold Only</option>
                                <option value="wash_dry_fold" {{ old('service_type', $service->service_type) === 'wash_dry_fold' ? 'selected' : '' }}>Wash, Dry & Fold</option>
                                <option value="blanket" {{ old('service_type', $service->service_type) === 'blanket' ? 'selected' : '' }}>Comforters & Blankets</option>
                                <option value="pickup_delivery" {{ old('service_type', $service->service_type) === 'pickup_delivery' ? 'selected' : '' }}>Pickup & Delivery</option>
                                <option value="other" {{ old('service_type', $service->service_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('service_type') 
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="price" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Price Amount (₱) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               id="price" 
                               step="0.01" 
                               name="price" 
                               value="{{ old('price', $service->price) }}" 
                               placeholder="120.00" 
                               class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                               required>
                        @error('price') 
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Grid: Unit, Duration, Availability -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="price_unit" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Pricing Unit <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="price_unit" 
                                    name="price_unit" 
                                    class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm appearance-none pr-10" 
                                    required>
                                <option value="load" {{ old('price_unit', $service->price_unit) === 'load' ? 'selected' : '' }}>Per Load</option>
                                <option value="kg" {{ old('price_unit', $service->price_unit) === 'kg' ? 'selected' : '' }}>Per Kilogram (kg)</option>
                                <option value="item" {{ old('price_unit', $service->price_unit) === 'item' ? 'selected' : '' }}>Per Item</option>
                                <option value="service" {{ old('price_unit', $service->price_unit) === 'service' ? 'selected' : '' }}>Per Service</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('price_unit') 
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_minutes" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Est. Duration (Mins) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" 
                               id="estimated_minutes" 
                               name="estimated_minutes" 
                               value="{{ old('estimated_minutes', $service->estimated_minutes) }}" 
                               placeholder="120" 
                               class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                               required>
                        @error('estimated_minutes') 
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Availability <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="status" 
                                    name="status" 
                                    class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm appearance-none pr-10" 
                                    required>
                                <option value="active" {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>Active (Available)</option>
                                <option value="inactive" {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>Inactive (Disabled)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('status') 
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p> 
                        @enderror
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-zinc-800">
                    <a href="{{ route('admin.services.index') }}" class="btn-secondary py-2.5 px-5 text-xs font-bold">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary py-2.5 px-6 text-xs font-bold shadow-sm">
                        Update Service Package
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>
