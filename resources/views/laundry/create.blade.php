<x-app-layout>

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white">Book Laundry Order</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">Select your service package, laundry supplies option, input weight, and choose an available machine.</p>
    </div>

    <div class="p-3.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2 shadow-xs">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><strong>Store Hours:</strong> 7:30 AM – 6:00 PM (Monday – Sunday)</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-0.5 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30 text-[11px] font-bold">
                *Detergent, Fabcon & Bleach not included
            </span>
            <span class="px-2 py-0.5 rounded-full bg-blue-600/15 text-blue-600 dark:text-blue-400 border border-blue-600/30 text-[11px] font-bold">
                ⏱ Cut-Off: 4:30 PM
            </span>
        </div>
    </div>

    @if(\Illuminate\Support\Facades\Cache::get('store_status', 'open') === 'closed')
        <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 px-4 py-3 rounded-lg text-xs font-bold flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 flex-shrink-0"></span>
            <span>NOTICE: HourWash Store is currently CLOSED TODAY. New bookings will be queued for processing on the next business day.</span>
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

            <div class="mb-5 relative" x-data="{
                open: false,
                selectedId: '{{ old('service_id', $services->first()?->id ?? '') }}',
                selectedLabel: '',
                services: [
                    @foreach($services as $service)
                        { id: '{{ $service->id }}', label: '{{ $service->name }} — ₱{{ number_format($service->price, 2) }}/{{ $service->price_unit }}', price: {{ $service->price }}, unit: '{{ $service->price_unit }}' },
                    @endforeach
                ],
                init() {
                    const found = this.services.find(s => s.id == this.selectedId) || this.services[0];
                    if (found) {
                        this.selectedId = found.id;
                        this.selectedLabel = found.label;
                    }
                },
                select(s) {
                    this.selectedId = s.id;
                    this.selectedLabel = s.label;
                    this.open = false;
                    const hiddenInput = document.getElementById('service_id');
                    if (hiddenInput) {
                        hiddenInput.value = s.id;
                        hiddenInput.dispatchEvent(new Event('change'));
                    }
                }
            }" @click.outside="open = false">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">Service Package</label>
                <select id="service_id" name="service_id" class="hidden">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->price_unit }}" {{ (old('service_id', $services->first()?->id) == $service->id) ? 'selected' : '' }}>
                            {{ $service->name }} — ₱{{ number_format($service->price, 2) }}/{{ $service->price_unit }}
                        </option>
                    @endforeach
                </select>

                <button type="button" @click="open = !open" class="w-full bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 rounded-lg text-xs sm:text-sm py-2.5 px-3 flex items-center justify-between transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    <span class="truncate font-semibold text-left" x-text="selectedLabel"></span>
                    <svg class="w-4 h-4 text-slate-500 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" x-cloak x-transition class="absolute z-50 top-full left-0 right-0 mt-1 w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl max-h-60 overflow-y-auto py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                    <template x-for="s in services" :key="s.id">
                        <button type="button" @click="select(s)" class="w-full text-left px-3.5 py-2.5 text-xs sm:text-sm font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="selectedId == s.id ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                            <span x-text="s.label" class="truncate"></span>
                            <span x-show="selectedId == s.id" class="font-bold shrink-0 ml-2">✓</span>
                        </button>
                    </template>
                </div>
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

            @if(auth()->check() && auth()->user()->hasDiscountReward())
                <div class="mb-5 p-4 rounded-lg bg-pink-500/10 dark:bg-pink-950/40 border border-pink-500/30 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">

                        <div>
                            <span class="text-xs font-bold text-pink-700 dark:text-pink-300 block">
                                Frequent User Loyalty Reward Available!
                            </span>
                            <span class="text-[11px] text-pink-800 dark:text-pink-400">
                                You completed a 12-stamp Frequent User Card! Apply your ₱50.00 OFF loyalty discount on this order.
                            </span>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-pink-700 dark:text-pink-300 bg-white dark:bg-pink-900/60 px-3 py-1.5 rounded-lg border border-pink-400 shrink-0">
                        <input type="checkbox" name="apply_loyalty_discount" value="1" class="text-pink-600 rounded focus:ring-pink-500">
                        Apply -₱50.00 OFF
                    </label>
                </div>
            @endif

            <!-- Machine Cycle Guide (Washer & Dryer Button Definitions) -->
            <div class="mb-5 p-4 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 space-y-3" x-data="{ showGuide: false }">
                <div class="flex items-center justify-between cursor-pointer" @click="showGuide = !showGuide">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Machine Cycle Guide (Washer & Dryer Button Functions)</span>
                    </div>
                    <button type="button" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">
                        <span x-text="showGuide ? 'Hide Guide ▲' : 'View Guide ▼'">View Guide ▼</span>
                    </button>
                </div>

                <div x-show="showGuide" x-collapse class="space-y-4 pt-2 border-t border-slate-200 dark:border-zinc-700 text-xs">
                    <!-- Dryer Buttons -->
                    <div class="space-y-2">
                        <h4 class="font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 text-[11px] uppercase tracking-wider">
                            <span>Dryer Panel Buttons</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Whites & Colors</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">High heat for towels, jeans, white cottons & heavy clothes (~40 mins).</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Perm Press</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Medium heat for dress shirts, uniforms & synthetic fabrics (anti-wrinkle).</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Delicates</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Low heat gentle dry for activewear, silk, lace & sensitive fabrics.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Washer Buttons -->
                    <div class="space-y-2">
                        <h4 class="font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 text-[11px] uppercase tracking-wider">
                            <span>Washer Panel Buttons</span>
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Whites</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Warm/hot water wash for white clothes & deep stain removal.</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Colors</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Regular color-safe wash for daily colored garments.</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Brights</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Anti-fade wash for bright, vibrant colored clothing.</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Perm Press</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Medium spin for workwear, slacks & wrinkle-resistant items.</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Delicates & Knits</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Gentle agitation for sweaters, knitted clothes & underwear.</span>
                            </div>
                            <div class="p-2.5 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                <span class="font-bold text-slate-900 dark:text-white block">Quick Cycle</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Fast 20–25 min wash for lightly soiled small loads.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 relative" x-data="{
                open: false,
                selectedId: '{{ old('machine_id', '') }}',
                selectedLabel: '-- Auto-Assign First Available Idle Machine --',
                machines: [
                    { id: '', label: '-- Auto-Assign First Available Idle Machine --' },
                    @foreach($availableMachines ?? [] as $mach)
                        { id: '{{ $mach->id }}', label: '{{ $mach->machine_name }} ({{ $mach->machine_code }}) • Idle & Ready' },
                    @endforeach
                ],
                init() {
                    const found = this.machines.find(m => m.id == this.selectedId);
                    if (found) this.selectedLabel = found.label;
                },
                select(m) {
                    this.selectedId = m.id;
                    this.selectedLabel = m.label;
                    this.open = false;
                }
            }" @click.outside="open = false">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-2">
                    Select Machine (Available Idle Units: {{ count($availableMachines ?? []) }})
                </label>
                <input type="hidden" name="machine_id" :value="selectedId">

                <button type="button" @click="open = !open" class="w-full bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 rounded-lg text-xs sm:text-sm py-2.5 px-3 flex items-center justify-between transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    <span class="truncate font-semibold text-left" x-text="selectedLabel"></span>
                    <svg class="w-4 h-4 text-slate-500 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" x-cloak x-transition class="absolute z-50 top-full left-0 right-0 mt-1 w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl max-h-60 overflow-y-auto py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                    <template x-for="m in machines" :key="m.id">
                        <button type="button" @click="select(m)" class="w-full text-left px-3.5 py-2.5 text-xs sm:text-sm font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="selectedId == m.id ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                            <span x-text="m.label" class="truncate"></span>
                            <span x-show="selectedId == m.id" class="font-bold shrink-0 ml-2">✓</span>
                        </button>
                    </template>
                </div>
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
            const unit = selectedOption.getAttribute('data-unit') || 'load';
            const weight = parseFloat(weightInput.value) || 0;

            const loadCount = Math.ceil(weight / 7) || 1;

            if (weight > 24) {
                loadBadge.className = "text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-rose-500/20 text-rose-600 dark:text-rose-400";
                loadBadge.textContent = "Exceeds 24kg Max Limit";
                weightHint.className = "text-[11px] text-rose-600 dark:text-rose-400 mt-1.5 flex items-center gap-1.5 font-bold";
                weightHint.textContent = "Maximum weight limit per booking is 24 kg (3 Loads max). Please enter 24kg or less.";
                summaryLoads.textContent = loadCount + " Loads (Exceeds Max Booking Limit)";
            } else if (loadCount === 1) {
                loadBadge.className = "text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-blue-600/15 text-blue-600 dark:text-blue-400";
                loadBadge.textContent = "1 Machine Load (7kg Max)";
                weightHint.className = "text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 flex items-center gap-1.5 font-medium";
                weightHint.textContent = "Standard commercial machine load capacity is 7kg max per load.";
                summaryLoads.textContent = "1 Machine Load (7kg max)";
            } else {
                loadBadge.className = "text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-amber-500/20 text-amber-700 dark:text-amber-300";
                loadBadge.textContent = loadCount + " Machine Loads Required";
                weightHint.className = "text-[11px] text-amber-600 dark:text-amber-400 mt-1.5 flex items-center gap-1.5 font-bold";
                weightHint.textContent = weight + " kg requires " + loadCount + " Machine Loads (7kg capacity per machine load).";
                summaryLoads.textContent = loadCount + " Machine Loads Required (" + weight + "kg total)";
            }

            let subtotal = price;
            if (unit === 'kg') {
                subtotal = price * weight;
            } else {
                subtotal = price * loadCount;
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
