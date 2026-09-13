<x-app-layout>

    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header Title -->
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Edit Machine <span class="font-mono text-blue-600 dark:text-blue-400">#{{ $machine->machine_code }}</span>
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 mt-1">Update unit specifications, label, and live operational status.</p>
        </div>

        <!-- Form Card Container -->
        <div class="bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800 rounded-lg shadow-sm p-6 sm:p-8 space-y-6">
            <form method="POST" action="{{ route('admin.machines.update', $machine) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Machine Name -->
                <div>
                    <label for="machine_name" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                        Machine Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="machine_name" 
                           name="machine_name" 
                           value="{{ old('machine_name', $machine->machine_name) }}" 
                           class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                           required>
                    @error('machine_name')
                        <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Machine Code -->
                <div>
                    <label for="machine_code" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                        Machine Tag Code <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="machine_code" 
                           name="machine_code" 
                           value="{{ old('machine_code', $machine->machine_code) }}" 
                           class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 font-mono uppercase focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-sm" 
                           required>
                    @error('machine_code')
                        <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Machine Type -->
                    <div>
                        <label for="machine_type" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Machine Type <span class="text-rose-500">*</span>
                        </label>
                        <select id="machine_type" 
                                name="machine_type" 
                                class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 transition shadow-sm">
                            <option value="washer" {{ old('machine_type', $machine->machine_type) === 'washer' ? 'selected' : '' }}>Washer</option>
                            <option value="dryer" {{ old('machine_type', $machine->machine_type) === 'dryer' ? 'selected' : '' }}>Dryer</option>
                            <option value="washer_dryer" {{ old('machine_type', $machine->machine_type) === 'washer_dryer' ? 'selected' : '' }}>Washer & Dryer Combo Unit</option>
                        </select>
                        @error('machine_type')
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 uppercase tracking-wider mb-2">
                            Current Operational Status <span class="text-rose-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full bg-slate-50 dark:bg-zinc-800/80 border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2.5 text-xs sm:text-sm text-slate-900 dark:text-zinc-100 focus:outline-none focus:border-blue-600 transition shadow-sm">
                            <option value="idle" {{ old('status', $machine->status) === 'idle' ? 'selected' : '' }}>Idle (Available for Laundry)</option>
                            <option value="washing" {{ old('status', $machine->status) === 'washing' ? 'selected' : '' }}>Washing</option>
                            <option value="rinsing" {{ old('status', $machine->status) === 'rinsing' ? 'selected' : '' }}>Rinsing</option>
                            <option value="drying" {{ old('status', $machine->status) === 'drying' ? 'selected' : '' }}>Drying</option>
                            <option value="maintenance" {{ old('status', $machine->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="offline" {{ old('status', $machine->status) === 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                        @error('status')
                            <p class="text-rose-500 text-xs font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-zinc-800">
                    <a href="{{ route('admin.machines.index') }}" class="btn-secondary py-2.5 px-5 text-xs font-bold">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary py-2.5 px-6 text-xs font-bold shadow-sm">
                        Update Machine Unit
                    </button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>