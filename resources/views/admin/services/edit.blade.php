<x-app-layout>

    <div class="max-w-2xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Edit Service Package: {{ $service->name }}</h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Update service package pricing, estimated completion duration, and options.</p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="btn-ios-secondary text-xs">← Back to Services</a>
        </div>

        <div class="app-card p-6 sm:p-8 space-y-6">
            <form action="{{ route('admin.services.update', $service) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Service Package Name</label>
                    <input type="text" name="name" value="{{ old('name', $service->name) }}" placeholder="e.g. Wash & Dry Special" class="w-full text-xs" required>
                    @error('name') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Service Description</label>
                    <textarea name="description" rows="3" placeholder="Brief details about what is included in this service..." class="w-full text-xs">{{ old('description', $service->description) }}</textarea>
                    @error('description') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Service Category Type</label>
                        <select name="service_type" class="w-full text-xs py-2" required>
                            <option value="wash_dry" {{ $service->service_type === 'wash_dry' ? 'selected' : '' }}>Wash & Dry</option>
                            <option value="wash" {{ $service->service_type === 'wash' ? 'selected' : '' }}>Wash Only</option>
                            <option value="dry" {{ $service->service_type === 'dry' ? 'selected' : '' }}>Dry Only</option>
                            <option value="wash_dry_fold" {{ $service->service_type === 'wash_dry_fold' ? 'selected' : '' }}>Wash, Dry & Fold</option>
                            <option value="blanket" {{ $service->service_type === 'blanket' ? 'selected' : '' }}>Comforters & Blankets</option>
                            <option value="pickup_delivery" {{ $service->service_type === 'pickup_delivery' ? 'selected' : '' }}>Pickup & Delivery</option>
                            <option value="other" {{ $service->service_type === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('service_type') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Price Amount (₱)</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $service->price) }}" placeholder="120.00" class="w-full text-xs" required>
                        @error('price') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Pricing Unit</label>
                        <select name="price_unit" class="w-full text-xs py-2" required>
                            <option value="kg" {{ $service->price_unit === 'kg' ? 'selected' : '' }}>Per Kilogram (kg)</option>
                            <option value="load" {{ $service->price_unit === 'load' ? 'selected' : '' }}>Per Load</option>
                            <option value="item" {{ $service->price_unit === 'item' ? 'selected' : '' }}>Per Item</option>
                            <option value="service" {{ $service->price_unit === 'service' ? 'selected' : '' }}>Per Service</option>
                        </select>
                        @error('price_unit') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Est. Duration (Minutes)</label>
                        <input type="number" name="estimated_minutes" value="{{ old('estimated_minutes', $service->estimated_minutes) }}" placeholder="120" class="w-full text-xs" required>
                        @error('estimated_minutes') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Package Availability</label>
                        <select name="status" class="w-full text-xs py-2" required>
                            <option value="active" {{ $service->status === 'active' ? 'selected' : '' }}>Active (Available)</option>
                            <option value="inactive" {{ $service->status === 'inactive' ? 'selected' : '' }}>Inactive (Disabled)</option>
                        </select>
                        @error('status') <span class="text-rose-500 text-[11px] font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-black/10 dark:border-white/10 flex justify-end gap-3">
                    <a href="{{ route('admin.services.index') }}" class="btn-ios-secondary text-xs">Cancel</a>
                    <button type="submit" class="btn-ios-primary text-xs">Update Service Package</button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>
