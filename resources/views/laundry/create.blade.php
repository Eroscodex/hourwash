<x-app-layout>

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold font-['Outfit'] text-slate-900 dark:text-white">Book Laundry Order</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Select your service package, laundry supplies option, input weight, and choose an available machine.</p>
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

        <form id="laundry-order-form" method="POST" action="{{ route('laundry.store') }}" onsubmit="const btn = this.querySelector('button[type=submit]'); if (btn) { btn.disabled = true; btn.innerText = 'Submitting Order...'; }">
            @csrf

            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Service Package</label>
                <select id="service_id" name="service_id" class="w-full">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->price_unit }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} — ₱{{ number_format($service->price, 2) }}/{{ $service->price_unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Weight (kg)
                    </label>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF]">
                        1 Load = 7kg to 8kg Max
                    </span>
                </div>
                <input id="weight_kg" type="number" name="weight_kg" value="{{ old('weight_kg', 7) }}" min="0.5" step="0.5" class="w-full">
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5 font-medium">
                    💡 Standard commercial machine load is 7kg to 8kg max. (7kg–8kg = 1 Load).
                </p>
            </div>

            <!-- Detergent / Powder Supplies Tipid Discount Option -->
            <div class="mb-5 p-4 rounded-xl bg-[#007AFF]/5 dark:bg-[#0A84FF]/10 border border-[#007AFF]/20 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        🧼 Detergent & Supplies Option (Tipid Discount)
                    </label>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                        SAVE MONEY
                    </span>
                </div>
                <p class="text-[11.5px] text-slate-600 dark:text-slate-300">
                    Bring your own powder/detergent or fabric softener to get an instant discount on your order!
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#2C2C2E] cursor-pointer hover:border-[#007AFF] transition">
                        <input type="radio" name="supplies_option" value="store_provided" class="mt-0.5 text-[#007AFF]" {{ old('supplies_option', 'store_provided') === 'store_provided' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-slate-900 dark:text-white block">Store Detergent & Softener</span>
                            <span class="text-[10.5px] text-slate-500 dark:text-slate-400">Standard Service (No discount)</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#2C2C2E] cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="supplies_option" value="own_detergent" class="mt-0.5 text-emerald-500" {{ old('supplies_option') === 'own_detergent' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Bring Own Powder / Detergent</span>
                            <span class="text-[10.5px] font-bold text-emerald-500 block">-₱15.00 Discount</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#2C2C2E] cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="supplies_option" value="own_softener" class="mt-0.5 text-emerald-500" {{ old('supplies_option') === 'own_softener' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Bring Own Fabric Softener</span>
                            <span class="text-[10.5px] font-bold text-emerald-500 block">-₱10.00 Discount</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-[#2C2C2E] cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="supplies_option" value="own_both" class="mt-0.5 text-emerald-500" {{ old('supplies_option') === 'own_both' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Bring Own Powder & Softener</span>
                            <span class="text-[10.5px] font-bold text-emerald-500 block">-₱25.00 Tipid Combo Discount</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">
                    Select Machine (Available Idle Units: {{ count($availableMachines ?? []) }})
                </label>
                <select name="machine_id" class="w-full">
                    <option value="">-- Auto-Assign First Available Idle Machine --</option>
                    @foreach($availableMachines ?? [] as $mach)
                        <option value="{{ $mach->id }}" {{ old('machine_id') == $mach->id ? 'selected' : '' }}>
                            {{ $mach->machine_name }} ({{ $mach->machine_code }}) — {{ strtoupper(str_replace('_', ' ', $mach->machine_type)) }} [IDLE & READY]
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                    Select which washer/dryer unit your laundry load will use before creating your booking.
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Customer Remarks / Special Instructions</label>
                <textarea name="remarks" rows="3" class="w-full" placeholder="Example: Separate white clothes, use gentle cycle">{{ old('remarks') }}</textarea>
            </div>

            <!-- Price Breakdown Summary Box -->
            <div class="mb-6 p-4 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Service Subtotal:</span>
                    <span id="summary-subtotal" class="font-semibold text-slate-900 dark:text-white">₱0.00</span>
                </div>
                <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-semibold">
                    <span>Supplies Discount (Tipid Option):</span>
                    <span id="summary-discount">-₱0.00</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-slate-900 dark:text-white pt-2 border-t border-black/10 dark:border-white/10">
                    <span>Total Amount:</span>
                    <span id="summary-total" class="text-[#007AFF] dark:text-[#0A84FF]">₱0.00</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button type="submit" class="btn-ios-primary w-full sm:w-auto text-center">Create Order</button>
                <a href="{{ route('my.orders') }}" class="btn-ios-secondary w-full sm:w-auto text-center">My Orders</a>
            </div>
        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const weightInput = document.getElementById('weight_kg');
        const suppliesRadios = document.querySelectorAll('input[name="supplies_option"]');
        
        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryDiscount = document.getElementById('summary-discount');
        const summaryTotal = document.getElementById('summary-total');

        function updatePriceCalculation() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            if (!selectedOption) return;

            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const unit = selectedOption.getAttribute('data-unit') || 'kg';
            const weight = parseFloat(weightInput.value) || 1;

            let subtotal = price;
            if (unit === 'kg') {
                subtotal = price * weight;
            }

            let discount = 0;
            let selectedSupplies = 'store_provided';
            suppliesRadios.forEach(radio => {
                if (radio.checked) {
                    selectedSupplies = radio.value;
                }
            });

            if (selectedSupplies === 'own_detergent') {
                discount = 15.00;
            } else if (selectedSupplies === 'own_softener') {
                discount = 10.00;
            } else if (selectedSupplies === 'own_both') {
                discount = 25.00;
            }

            const total = Math.max(0, subtotal - discount);

            summarySubtotal.textContent = '₱' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            summaryDiscount.textContent = '-₱' + discount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            summaryTotal.textContent = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        serviceSelect.addEventListener('change', updatePriceCalculation);
        weightInput.addEventListener('input', updatePriceCalculation);
        suppliesRadios.forEach(radio => radio.addEventListener('change', updatePriceCalculation));

        updatePriceCalculation();
    });
</script>

</x-app-layout>