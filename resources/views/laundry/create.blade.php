<x-app-layout>

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">🧺 Book Laundry Order</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Select your service package, input laundry weight, and provide instructions.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 px-4 py-3 rounded-xl text-xs font-semibold">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="app-card p-4 sm:p-6 space-y-6">

        <form method="POST" action="{{ route('laundry.store') }}" onsubmit="const btn = this.querySelector('button[type=submit]'); if (btn) { btn.disabled = true; btn.innerText = 'Submitting Order...'; }">
            @csrf

            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Service Package</label>
                <select name="service_id" class="w-full">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} — ₱{{ number_format($service->price, 2) }}/{{ $service->price_unit }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Weight (kg)</label>
                <input type="number" name="weight_kg" value="{{ old('weight_kg', 1) }}" min="0.5" step="0.5" class="w-full">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Customer Remarks / Special Instructions</label>
                <textarea name="remarks" rows="4" class="w-full" placeholder="Example: Separate white clothes, use gentle detergent">{{ old('remarks') }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="btn-ios-primary w-full sm:w-auto text-center">Create Order</button>
                <a href="{{ route('my.orders') }}" class="btn-ios-secondary w-full sm:w-auto text-center">My Orders</a>
            </div>
        </form>

    </div>

</div>

</x-app-layout>