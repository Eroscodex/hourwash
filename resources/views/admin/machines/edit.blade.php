<x-app-layout>

    <div class="space-y-6">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Edit Machine #{{ $machine->machine_code }}</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Update specifications and live operational status.</p>
        </div>

        <div class="max-w-xl app-card p-4 sm:p-6 shadow-xl space-y-6">
            <form method="POST" action="{{ route('admin.machines.update', $machine) }}">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Machine Name</label>
                    <input type="text" name="machine_name" value="{{ old('machine_name', $machine->machine_name) }}" class="w-full" required>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Machine Code</label>
                    <input type="text" name="machine_code" value="{{ old('machine_code', $machine->machine_code) }}" class="w-full" required>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Machine Type</label>
                    <select name="machine_type" class="w-full">
                        <option value="washer" {{ $machine->machine_type === 'washer' ? 'selected' : '' }}>Washer</option>
                        <option value="dryer" {{ $machine->machine_type === 'dryer' ? 'selected' : '' }}>Dryer</option>
                        <option value="washer_dryer" {{ $machine->machine_type === 'washer_dryer' ? 'selected' : '' }}>Washer & Dryer</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Current Status</label>
                    <select name="status" class="w-full">
                        <option value="idle" {{ $machine->status === 'idle' ? 'selected' : '' }}>Idle (Available)</option>
                        <option value="washing" {{ $machine->status === 'washing' ? 'selected' : '' }}>Washing</option>
                        <option value="rinsing" {{ $machine->status === 'rinsing' ? 'selected' : '' }}>Rinsing</option>
                        <option value="drying" {{ $machine->status === 'drying' ? 'selected' : '' }}>Drying</option>
                        <option value="maintenance" {{ $machine->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="offline" {{ $machine->status === 'offline' ? 'selected' : '' }}>Offline</option>
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit" class="btn-primary w-full sm:w-auto text-center">Update Machine</button>
                    <a href="{{ route('admin.machines.index') }}" class="btn-secondary w-full sm:w-auto text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>