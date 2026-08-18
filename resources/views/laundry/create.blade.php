<x-app-layout>

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Book Laundry Order</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Select your service package, laundry supplies option, input weight, and choose an available machine.</p>
    </div>

    @if(\Illuminate\Support\Facades\Cache::get('store_status', 'open') === 'closed')
        <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 px-4 py-3 rounded-lg text-xs font-bold flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 flex-shrink-0"></span>
            <span>⚠️ NOTICE: HourWash Store is currently CLOSED TODAY. New bookings will be queued for processing on the next business day.</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
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

            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff()))
                <!-- WALK-IN CUSTOMER SELECTION & INSTANT REGISTRATION (Admin & Staff Only) -->
                <div class="mb-6 p-4 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 space-y-4" x-data="{ customerMode: '{{ old('customer_mode', 'select') }}' }">
                    <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-2.5">
                        <label class="block text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            Customer Assignment (Walk-In / Order Owner)
                        </label>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-sky-500/15 text-sky-600 dark:text-sky-400">
                            Staff / Admin Mode
                        </span>
                    </div>

                    <!-- Mode Switchers -->
                    <div class="flex flex-wrap items-center gap-4 text-xs font-semibold">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-900 dark:text-white">
                            <input type="radio" name="customer_mode" value="select" x-model="customerMode" class="text-blue-600">
                            <span>Select Existing Customer</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-slate-900 dark:text-white">
                            <input type="radio" name="customer_mode" value="new" x-model="customerMode" class="text-blue-600">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">Register New Walk-in Customer</span>
                        </label>
                    </div>

                    <!-- Option A: Select Existing Registered Customer -->
                    <div x-show="customerMode === 'select'" class="space-y-1.5">
                        <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400">Registered Customer Account</label>
                        <select name="customer_id" class="w-full text-xs">
                            <option value="">-- Select Registered Customer --</option>
                            @foreach($customers ?? [] as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->email }}{{ $c->phone ? ' • '.$c->phone : '' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[10.5px] text-slate-500 dark:text-slate-400">
                            Pumili sa listahan ng mga nakarehistrong customer. Hindi na kailangang i-fill up ang pangalan kapag rehistrado na!
                        </p>
                    </div>

                    <!-- Option B: Register New Walk-in Customer -->
                    <div x-show="customerMode === 'new'" x-cloak class="space-y-3 pt-2 border-t border-black/5 dark:border-white/5">
                        <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400">
                            Instant Registration for New Customer:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="new_customer_name" value="{{ old('new_customer_name') }}" placeholder="e.g. Juan Dela Cruz" class="w-full">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Email Address (Optional)</label>
                                <input type="email" name="new_customer_email" value="{{ old('new_customer_email') }}" placeholder="e.g. juan@gmail.com (Auto-generated if blank)" class="w-full">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Phone Number (Optional)</label>
                                <input type="text" name="new_customer_phone" value="{{ old('new_customer_phone') }}" placeholder="e.g. 09171234567" class="w-full">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Street Address (Optional)</label>
                                <input type="text" name="new_customer_address" value="{{ old('new_customer_address') }}" placeholder="e.g. Magallanes St., Legazpi City" class="w-full">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Account Password (Optional)</label>
                                <input type="password" name="new_customer_password" placeholder="Default: password (if left blank)" class="w-full">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                    <span id="load-count-badge" class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-blue-600/15 text-blue-600 dark:text-blue-400">
                        1 Machine Load (7-8kg Max)
                    </span>
                </div>
                <input id="weight_kg" type="number" name="weight_kg" value="{{ old('weight_kg', 7) }}" min="0.5" max="24.0" step="0.5" class="w-full">
                <p id="weight-hint" class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5 font-medium">
                    Standard commercial machine load capacity is 7kg to 8kg max. (Max limit: 24.0kg / 3 loads per booking).
                </p>
            </div>

            <!-- Detergent / Powder Supplies Tipid Discount Option -->
            <div class="mb-5 p-4 rounded-lg bg-blue-600/5 dark:bg-blue-600/10 border border-blue-600/20 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                        Detergent & Supplies Option (Tipid Discount)
                    </label>
                    <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">
                        SAVE MONEY
                    </span>
                </div>
                <p class="text-[11.5px] text-slate-600 dark:text-slate-300">
                    Bring your own powder/detergent or fabric softener to get an instant discount on your order!
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                    <label class="flex items-start gap-2.5 p-3 rounded-lg border border-slate-200 dark:dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-blue-600 transition">
                        <input type="radio" name="supplies_option" value="store_provided" class="mt-0.5 text-blue-600" {{ old('supplies_option', 'store_provided') === 'store_provided' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-slate-900 dark:text-white block">Store Detergent & Softener</span>
                            <span class="text-[10.5px] text-slate-500 dark:text-slate-400">Standard Service (No discount)</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 p-3 rounded-lg border border-slate-200 dark:dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="supplies_option" value="own_detergent" class="mt-0.5 text-emerald-500" {{ old('supplies_option') === 'own_detergent' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Bring Own Powder / Detergent</span>
                            <span class="text-[10.5px] font-bold text-emerald-500 block">-₱15.00 Discount</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 p-3 rounded-lg border border-slate-200 dark:dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-emerald-500 transition">
                        <input type="radio" name="supplies_option" value="own_softener" class="mt-0.5 text-emerald-500" {{ old('supplies_option') === 'own_softener' ? 'checked' : '' }}>
                        <div class="text-xs">
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Bring Own Fabric Softener</span>
                            <span class="text-[10.5px] font-bold text-emerald-500 block">-₱10.00 Discount</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 p-3 rounded-lg border border-slate-200 dark:dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-emerald-500 transition">
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
            <div class="mb-6 p-4 rounded-lg bg-black/5 dark:bg-[#18181B] border border-black/5 dark:dark:border-zinc-700 space-y-2 text-xs">
                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Est. Commercial Machine Loads:</span>
                    <span id="summary-loads" class="font-bold text-blue-600 dark:text-blue-400">1 Load (7-8kg capacity)</span>
                </div>
                <div class="flex justify-between text-slate-600 dark:text-slate-300">
                    <span>Service Subtotal:</span>
                    <span id="summary-subtotal" class="font-semibold text-slate-900 dark:text-white">₱0.00</span>
                </div>
                <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-semibold">
                    <span>Supplies Discount (Tipid Option):</span>
                    <span id="summary-discount">-₱0.00</span>
                </div>
                <div class="flex justify-between text-sm font-bold text-slate-900 dark:text-white pt-2 border-t border-slate-200 dark:dark:border-zinc-700">
                    <span>Total Amount:</span>
                    <span id="summary-total" class="text-blue-600 dark:text-blue-400">₱0.00</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                @if(\Illuminate\Support\Facades\Cache::get('store_status', 'open') === 'open' || (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff())))
                    <button type="submit" class="btn-primary w-full sm:w-auto text-center">Create Order</button>
                @else
                    <button type="button" disabled class="opacity-65 bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 px-5 py-2.5 rounded-lg text-xs font-bold cursor-not-allowed w-full sm:w-auto text-center">
                        🚫 Store Closed Today (Bookings Disabled)
                    </button>
                @endif
                <a href="{{ route('my.orders') }}" class="btn-secondary w-full sm:w-auto text-center">My Orders</a>
            </div>
        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const weightInput = document.getElementById('weight_kg');
        const suppliesRadios = document.querySelectorAll('input[name="supplies_option"]');
        
        const loadBadge = document.getElementById('load-count-badge');
        const weightHint = document.getElementById('weight-hint');
        const summaryLoads = document.getElementById('summary-loads');

        const summarySubtotal = document.getElementById('summary-subtotal');
        const summaryDiscount = document.getElementById('summary-discount');
        const summaryTotal = document.getElementById('summary-total');

        function updatePriceCalculation() {
            const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
            if (!selectedOption) return;

            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const unit = selectedOption.getAttribute('data-unit') || 'kg';
            const weight = parseFloat(weightInput.value) || 0;

            const loadCount = Math.ceil(weight / 8) || 1;

            if (weight > 24) {
                loadBadge.className = "text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-rose-500/20 text-rose-600 dark:text-rose-400";
                loadBadge.textContent = "Exceeds 24kg Max Limit";
                weightHint.className = "text-[11px] text-rose-600 dark:text-rose-400 mt-1.5 flex items-center gap-1.5 font-bold";
                weightHint.textContent = "⚠️ Maximum weight limit per booking is 24 kg (3 Loads max). Please enter 24kg or less.";
                summaryLoads.textContent = loadCount + " Loads (Exceeds Max Booking Limit)";
            } else if (loadCount === 1) {
                loadBadge.className = "text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-blue-600/15 text-blue-600 dark:text-blue-400";
                loadBadge.textContent = "1 Machine Load (7-8kg Max)";
                weightHint.className = "text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5 font-medium";
                weightHint.textContent = "Standard commercial machine load capacity is 7kg to 8kg max. (7kg–8kg = 1 Load).";
                summaryLoads.textContent = "1 Machine Load (7-8kg max)";
            } else {
                loadBadge.className = "text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-amber-500/20 text-amber-700 dark:text-amber-300";
                loadBadge.textContent = loadCount + " Machine Loads Required";
                weightHint.className = "text-[11px] text-amber-600 dark:text-amber-400 mt-1.5 flex items-center gap-1.5 font-bold";
                weightHint.textContent = "📦 " + weight + " kg requires " + loadCount + " Commercial Machine Loads (7-8kg capacity per machine load).";
                summaryLoads.textContent = loadCount + " Machine Loads Required (" + weight + "kg total)";
            }

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