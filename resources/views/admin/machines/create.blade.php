<x-app-layout>

    <div class="space-y-6">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Add New Machine</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Register a new washer or dryer into the store monitor.</p>
        </div>

        <div class="max-w-xl app-card p-4 sm:p-6 shadow-xl space-y-6">
            <form method="POST" action="{{ route('admin.machines.store') }}">
                @csrf

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Machine Name</label>
                    <input type="text" name="machine_name" class="w-full" placeholder="Example: Commercial Washer #1" required>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Machine Code</label>
                    <input type="text" name="machine_code" class="w-full" placeholder="Example: WM-001" required>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Machine Type</label>
                    <select name="machine_type" class="w-full">
                        <option value="washer">Washer</option>
                        <option value="dryer">Dryer</option>
                        <option value="washer_dryer">Washer & Dryer</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Initial Status</label>
                    <select name="status" class="w-full">
                        <option value="idle">Idle (Available)</option>
                        <option value="washing">Washing</option>
                        <option value="rinsing">Rinsing</option>
                        <option value="drying">Drying</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="btn-primary w-full sm:w-auto text-center">Save Machine</button>
                    <a href="{{ route('admin.machines.index') }}" class="btn-secondary w-full sm:w-auto text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>