<x-app-layout>

<div class="max-w-5xl mx-auto space-y-3.5 sm:space-y-4">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">Book Laundry Order</h1>
            <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 mt-0.5">Select service package, supplies option, input weight, and choose an available machine.</p>
        </div>
        <div class="flex items-center gap-1.5 shrink-0 text-[10.5px] sm:text-[11px] font-bold">
            <span class="px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                *Detergent & Fabcon optional
            </span>
            <span class="px-2 py-0.5 rounded-full bg-blue-600/15 text-blue-600 dark:text-blue-400 border border-blue-600/30">
                ⏱ Cut-Off: 4:30 PM
            </span>
        </div>
    </div>

    <div class="px-3 py-2 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-slate-300 text-[11px] sm:text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><strong>Store Hours:</strong> 7:30 AM – 6:00 PM (Monday – Sunday)</span>
        </div>
    </div>

    @if(\Illuminate\Support\Facades\Cache::get('store_status', 'open') === 'closed')
        <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 px-3.5 py-2.5 rounded-lg text-xs font-bold flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-rose-500 flex-shrink-0"></span>
            <span>NOTICE: HourWash Store is currently CLOSED TODAY. New bookings will be queued for processing on the next business day.</span>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-3.5 py-2.5 rounded-lg text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 px-3.5 py-2.5 rounded-lg text-xs font-semibold">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="app-card p-3.5 sm:p-4">

        <form id="laundry-order-form" method="POST" action="{{ route('laundry.store') }}" onsubmit="const btn = this.querySelector('button[type=submit]'); if (btn) { btn.disabled = true; btn.innerText = 'Submitting Order...'; }">
            @csrf

            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff()))
                <!-- WALK-IN CUSTOMER SELECTION & INSTANT REGISTRATION (Admin & Staff Only) -->
                <div class="mb-3.5 p-3 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:dark:border-zinc-700 space-y-3" x-data="{ customerMode: '{{ old('customer_mode', 'select') }}' }">
                    <div class="flex items-center justify-between border-b border-slate-200 dark:dark:border-zinc-700 pb-2">
                        <label class="block text-[11px] font-bold text-slate-900 dark:text-white uppercase tracking-wider">
                            Customer Assignment (Walk-In / Order Owner)
                        </label>
                        <span class="px-2 py-0.5 rounded text-[9.5px] font-extrabold uppercase bg-sky-500/15 text-sky-600 dark:text-sky-400">
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
                    <div x-show="customerMode === 'select'" class="space-y-1">
                        <label for="customer_id" class="block text-[10.5px] font-semibold text-slate-600 dark:text-slate-400">Registered Customer Account</label>
                        <select id="customer_id" name="customer_id" class="w-full text-xs py-1.5">
                            <option value="">-- Select Registered Customer --</option>
                            @foreach($customers ?? [] as $c)
                                <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->email }}{{ $c->phone ? ' • '.$c->phone : '' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Option B: Register New Walk-in Customer -->
                    <div x-show="customerMode === 'new'" x-cloak class="space-y-2 pt-2 border-t border-black/5 dark:border-white/5">
                        <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400">
                            Instant Registration for New Customer:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 text-xs">
                            <div>
                                <label for="new_customer_name" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Full Name:</label>
                                <input type="text" id="new_customer_name" name="new_customer_name" value="{{ old('new_customer_name') }}" placeholder="e.g. Your Name" class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_email" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Email Address</label>
                                <input type="email" id="new_customer_email" name="new_customer_email" value="{{ old('new_customer_email') }}" placeholder="Auto-generated if blank" class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_phone" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Phone Number</label>
                                <input type="text" id="new_customer_phone" name="new_customer_phone" value="{{ old('new_customer_phone') }}" placeholder="e.g. 09XXXXXXXXX" class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_address" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">House No. / Street</label>
                                <input type="text" id="new_customer_address" name="new_customer_address" value="{{ old('new_customer_address') }}" placeholder="e.g. #123 Magallanes St." class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_barangay" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Barangay</label>
                                <input type="text" id="new_customer_barangay" name="new_customer_barangay" value="{{ old('new_customer_barangay') }}" placeholder="e.g. Brgy. Orosite" class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_city" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">City / Municipality</label>
                                <input type="text" id="new_customer_city" name="new_customer_city" value="{{ old('new_customer_city', 'Legazpi City') }}" placeholder="e.g. Legazpi City" class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_province" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Province</label>
                                <input type="text" id="new_customer_province" name="new_customer_province" value="{{ old('new_customer_province', 'Albay') }}" placeholder="e.g. Albay" class="w-full py-1.5 text-xs">
                            </div>
                            <div>
                                <label for="new_customer_password" class="block font-semibold text-slate-700 dark:text-slate-300 mb-0.5 text-[11px]">Account Password</label>
                                <input type="password" id="new_customer_password" name="new_customer_password" placeholder="Default: password" class="w-full py-1.5 text-xs">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2-COLUMN LAYOUT ON DESKTOP & LAPTOP (lg:grid lg:grid-cols-12) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 sm:gap-4 lg:gap-5 items-start">

                <!-- LEFT COLUMN: Primary Selection Inputs -->
                <div class="lg:col-span-7 space-y-3.5">

                    <!-- Service Package Selection -->
                    <div class="relative" x-data="{
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
                        <label class="block text-[11px] font-extrabold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1">Service Package</label>
                        <select id="service_id" name="service_id" class="hidden">
                            @foreach($services->reject(fn($s) => \Illuminate\Support\Str::contains(strtolower($s->name), ['pickup', 'delivery']) || $s->service_type === 'pickup_delivery') as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-unit="{{ $service->price_unit }}" {{ (old('service_id', $services->first()?->id) == $service->id) ? 'selected' : '' }}>
                                    {{ $service->name }} — ₱{{ number_format($service->price, 2) }}/{{ $service->price_unit }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" @click="open = !open" class="w-full bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 rounded-lg text-xs py-2 px-3 flex items-center justify-between transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            <span class="truncate font-semibold text-left" x-text="selectedLabel"></span>
                            <svg class="w-4 h-4 text-slate-500 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" x-cloak x-transition class="absolute z-50 top-full left-0 right-0 mt-1 w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl max-h-52 overflow-y-auto py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                            <template x-for="s in services" :key="s.id">
                                <button type="button" @click="select(s)" class="w-full text-left px-3 py-2 text-xs font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="selectedId == s.id ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                                    <span x-text="s.label" class="truncate"></span>
                                    <span x-show="selectedId == s.id" class="font-bold shrink-0 ml-2">✓</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Weight (kg) Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-[11px] font-extrabold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                                Weight (kg)
                            </label>
                            <span id="load-count-badge" class="text-[9.5px] font-extrabold uppercase px-2 py-0.5 rounded bg-blue-600/15 text-blue-600 dark:text-blue-400">
                                1 Machine Load (7-8kg Max)
                            </span>
                        </div>
                        <input id="weight_kg" type="number" name="weight_kg" value="{{ old('weight_kg', 7) }}" min="0.5" max="24.0" step="0.5" class="w-full py-1.5 text-xs">
                        <p id="weight-hint" class="text-[10.5px] text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1 font-medium">
                            Standard commercial machine load capacity is 7kg to 8kg max per load.
                        </p>
                    </div>

                    <!-- Machine Selection -->
                    <div class="relative" x-data="{
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
                        <label class="block text-[11px] font-extrabold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1">
                            Select Machine (Available Units: {{ count($availableMachines ?? []) }})
                        </label>
                        <input type="hidden" name="machine_id" :value="selectedId">

                        <button type="button" @click="open = !open" class="w-full bg-white dark:bg-[#18181B] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 rounded-lg text-xs py-2 px-3 flex items-center justify-between transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            <span class="truncate font-semibold text-left" x-text="selectedLabel"></span>
                            <svg class="w-4 h-4 text-slate-500 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" x-cloak x-transition class="absolute z-50 top-full left-0 right-0 mt-1 w-full bg-white dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 rounded-lg shadow-xl max-h-52 overflow-y-auto py-1 divide-y divide-slate-100 dark:divide-zinc-800/60">
                            <template x-for="m in machines" :key="m.id">
                                <button type="button" @click="select(m)" class="w-full text-left px-3 py-2 text-xs font-medium transition flex items-center justify-between hover:bg-blue-600 hover:text-white" :class="selectedId == m.id ? 'bg-blue-600/15 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-800 dark:text-zinc-200'">
                                    <span x-text="m.label" class="truncate"></span>
                                    <span x-show="selectedId == m.id" class="font-bold shrink-0 ml-2">✓</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Customer Remarks / Special Instructions -->
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-700 dark:text-slate-200 uppercase tracking-wider mb-1">Remarks / Special Instructions</label>
                        <textarea name="remarks" rows="2" class="w-full py-1.5 text-xs" placeholder="Example: Separate white clothes, gentle cycle">{{ old('remarks') }}</textarea>
                    </div>

                    <!-- Machine Cycle Guide (Collapsible) -->
                    <div class="p-2.5 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 space-y-2" x-data="{ showGuide: false }">
                        <div class="flex items-center justify-between cursor-pointer" @click="showGuide = !showGuide">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[11px] font-bold text-slate-900 dark:text-white uppercase tracking-wider">Machine Cycle Guide</span>
                            </div>
                            <button type="button" class="text-[10.5px] text-blue-600 dark:text-blue-400 font-bold hover:underline">
                                <span x-text="showGuide ? 'Hide Guide ▲' : 'View Guide ▼'">View Guide ▼</span>
                            </button>
                        </div>

                        <div x-show="showGuide" x-transition class="space-y-3 pt-2 border-t border-slate-200 dark:border-zinc-700 text-xs">
                            <!-- Dryer Buttons -->
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1 text-[10.5px] uppercase tracking-wider">
                                    Dryer Panel Buttons
                                </h4>
                                <div class="grid grid-cols-3 gap-1.5">
                                    <div class="p-2 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                        <span class="font-bold text-slate-900 dark:text-white block text-[10.5px]">Whites & Colors</span>
                                        <span class="text-[9.5px] text-slate-500 dark:text-slate-400 leading-tight">High heat (~40 mins).</span>
                                    </div>
                                    <div class="p-2 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                        <span class="font-bold text-slate-900 dark:text-white block text-[10.5px]">Perm Press</span>
                                        <span class="text-[9.5px] text-slate-500 dark:text-slate-400 leading-tight">Medium heat anti-wrinkle.</span>
                                    </div>
                                    <div class="p-2 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                        <span class="font-bold text-slate-900 dark:text-white block text-[10.5px]">Delicates</span>
                                        <span class="text-[9.5px] text-slate-500 dark:text-slate-400 leading-tight">Low heat gentle dry.</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Washer Buttons -->
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1 text-[10.5px] uppercase tracking-wider">
                                    Washer Panel Buttons
                                </h4>
                                <div class="grid grid-cols-3 gap-1.5">
                                    <div class="p-2 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                        <span class="font-bold text-slate-900 dark:text-white block text-[10.5px]">Whites</span>
                                        <span class="text-[9.5px] text-slate-500 dark:text-slate-400 leading-tight">Warm/hot wash.</span>
                                    </div>
                                    <div class="p-2 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                        <span class="font-bold text-slate-900 dark:text-white block text-[10.5px]">Colors</span>
                                        <span class="text-[9.5px] text-slate-500 dark:text-slate-400 leading-tight">Color-safe wash.</span>
                                    </div>
                                    <div class="p-2 rounded bg-white dark:bg-[#141417] border border-slate-200 dark:border-zinc-800">
                                        <span class="font-bold text-slate-900 dark:text-white block text-[10.5px]">Quick Cycle</span>
                                        <span class="text-[9.5px] text-slate-500 dark:text-slate-400 leading-tight">Fast 20-25 min wash.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: Add-ons, Loyalty Reward & Price Breakdown Summary -->
                <div class="lg:col-span-5 space-y-3.5">

                    <!-- Add-On Supplies & Detergents -->
                    <div class="p-3 rounded-lg bg-blue-600/5 dark:bg-blue-600/10 border border-blue-600/20 space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-[11px] font-extrabold text-slate-900 dark:text-white uppercase tracking-wider">
                                Add-On Supplies & Detergents
                            </label>
                            <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-blue-600/20 text-blue-600 dark:text-blue-400">
                                STORE PRODUCTS
                            </span>
                        </div>
                        <p class="text-[10.5px] text-slate-600 dark:text-slate-300">
                            Select optional detergent, bleach, or fabric softener sachets (or bring your own):
                        </p>

                        <div class="grid grid-cols-2 gap-2 pt-0.5">
                            <label class="flex flex-col p-2 rounded-lg border border-slate-200 dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-blue-600 transition">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white">Breeze</span>
                                    <input type="checkbox" name="supplies[]" value="breeze" data-price="20" class="supply-checkbox text-blue-600 rounded">
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10.5px] font-bold text-blue-600 dark:text-blue-400">+₱20.00</span>
                                    <span class="text-[9px] text-slate-500 dark:text-slate-400">Powder</span>
                                </div>
                            </label>

                            <label class="flex flex-col p-2 rounded-lg border border-slate-200 dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-blue-600 transition">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white">Champion</span>
                                    <input type="checkbox" name="supplies[]" value="champion" data-price="18" class="supply-checkbox text-blue-600 rounded">
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10.5px] font-bold text-blue-600 dark:text-blue-400">+₱18.00</span>
                                    <span class="text-[9px] text-slate-500 dark:text-slate-400">Powder</span>
                                </div>
                            </label>

                            <label class="flex flex-col p-2 rounded-lg border border-slate-200 dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-blue-600 transition">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white">Tide</span>
                                    <input type="checkbox" name="supplies[]" value="tide" data-price="13" class="supply-checkbox text-blue-600 rounded">
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10.5px] font-bold text-blue-600 dark:text-blue-400">+₱13.00</span>
                                    <span class="text-[9px] text-slate-500 dark:text-slate-400">Powder</span>
                                </div>
                            </label>

                            <label class="flex flex-col p-2 rounded-lg border border-slate-200 dark:border-zinc-700 bg-white dark:bg-[#18181B] cursor-pointer hover:border-blue-600 transition">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-bold text-xs text-slate-900 dark:text-white">Zonrox</span>
                                    <input type="checkbox" name="supplies[]" value="zonrox" data-price="10" class="supply-checkbox text-blue-600 rounded">
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10.5px] font-bold text-blue-600 dark:text-blue-400">+₱10.00</span>
                                    <span class="text-[9px] text-slate-500 dark:text-slate-400">Bleach</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    @if(auth()->check() && auth()->user()->hasDiscountReward())
                        <div class="p-3 rounded-lg bg-pink-500/10 dark:bg-pink-950/40 border border-pink-500/30 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[11px] font-bold text-pink-700 dark:text-pink-300 block">
                                    🎁 12-Stamp Loyalty Reward Available!
                                </span>
                                <span class="text-[10px] text-pink-800 dark:text-pink-400">
                                    Claim <strong>1 FREE Load</strong> on this order.
                                </span>
                            </div>
                            <label class="flex items-center gap-1.5 cursor-pointer font-bold text-[11px] text-pink-700 dark:text-pink-300 bg-white dark:bg-pink-900/60 px-2.5 py-1 rounded border border-pink-400 shrink-0">
                                <input type="checkbox" name="apply_loyalty_discount" value="1" class="text-pink-600 rounded focus:ring-pink-500">
                                Apply FREE Load
                            </label>
                        </div>
                    @endif

                    <!-- Price Breakdown Summary Box -->
                    <div class="p-3 rounded-lg bg-black/5 dark:bg-[#18181B] border border-black/5 dark:border-zinc-700 space-y-1.5 text-xs">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300 text-[11px]">
                            <span>Est. Machine Loads:</span>
                            <span id="summary-loads" class="font-bold text-blue-600 dark:text-blue-400">1 Load (7-8kg)</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-300 text-[11px]">
                            <span>Service Subtotal:</span>
                            <span id="summary-subtotal" class="font-semibold text-slate-900 dark:text-white">₱0.00</span>
                        </div>
                        <div class="flex justify-between text-blue-600 dark:text-blue-400 text-[11px] font-semibold">
                            <span>Add-on Supplies:</span>
                            <span id="summary-supplies">+₱0.00</span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-slate-900 dark:text-white pt-1.5 border-t border-slate-200 dark:border-zinc-700">
                            <span>Total Amount:</span>
                            <span id="summary-total" class="text-blue-600 dark:text-blue-400">₱0.00</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        @if(\Illuminate\Support\Facades\Cache::get('store_status', 'open') === 'open' || (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff())))
                            <button type="submit" class="btn-primary w-full text-center py-2 text-xs">Create Order</button>
                        @else
                            <button type="button" disabled class="opacity-65 bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 px-4 py-2 rounded-lg text-xs font-bold cursor-not-allowed w-full text-center">
                                🚫 Store Closed Today (Bookings Disabled)
                            </button>
                        @endif
                        <a href="{{ route('my.orders') }}" class="btn-secondary w-full text-center py-2 text-xs">My Orders</a>
                    </div>

                </div>

            </div>
        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const weightInput = document.getElementById('weight_kg');
        const supplyCheckboxes = document.querySelectorAll('.supply-checkbox');

        const loadBadge = document.getElementById('load-count-badge');
        const weightHint = document.getElementById('weight-hint');
        const summaryLoads = document.getElementById('summary-loads');

        const summarySubtotal = document.getElementById('summary-subtotal');
        const summarySupplies = document.getElementById('summary-supplies');
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

            let suppliesAddons = 0;
            supplyCheckboxes.forEach(cb => {
                if (cb.checked) {
                    suppliesAddons += parseFloat(cb.getAttribute('data-price')) || 0;
                }
            });

            const total = Math.max(0, subtotal + suppliesAddons);

            summarySubtotal.textContent = '₱' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            summarySupplies.textContent = '+₱' + suppliesAddons.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            summaryTotal.textContent = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        serviceSelect.addEventListener('change', updatePriceCalculation);
        weightInput.addEventListener('input', updatePriceCalculation);
        supplyCheckboxes.forEach(cb => cb.addEventListener('change', updatePriceCalculation));

        updatePriceCalculation();
    });
</script>

</x-app-layout>
