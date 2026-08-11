<x-app-layout>
    <div class="space-y-6 sm:space-y-8">
        
        <!-- Welcome Greeting Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Manage your laundry bookings, track live orders, and check machine availability.
                </p>
            </div>
            
            <a href="{{ route('laundry.create') }}" class="btn-ios-primary flex items-center justify-center gap-2">
                <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Book Laundry Order
            </a>
        </div>

        <!-- Live Shop Status Pills -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="app-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#007AFF]/10 text-[#007AFF] dark:text-[#0A84FF] flex items-center justify-center border border-[#007AFF]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L5.6 15.11a2 2 0 01-1.183-1.845V7.4a2 2 0 011.183-1.845l2.4-1.2a6 6 0 013.86-.517l.318.158a6 6 0 003.86.517l2.387-.477a2 2 0 011.022.547l2.4 2.4a2 2 0 01.586 1.414v7.172a2 2 0 01-.586 1.414l-2.4 2.4z"/></svg>
                </div>
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Washers Available</h5>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">12 Available</p>
                </div>
            </div>

            <div class="app-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center border border-sky-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 9.3a1 1 0 00-1.4 0l-4 4a1 1 0 001.4 1.4l4-4a1 1 0 000-1.4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                </div>
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Dryers Available</h5>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">8 Available</p>
                </div>
            </div>

            <div class="app-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                </div>
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Wash & Fold</h5>
                    <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400">5 Available</p>
                </div>
            </div>

            <div class="app-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h5 class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">Pickup Riders</h5>
                    <p class="text-sm font-bold text-amber-600 dark:text-amber-400">3 Active</p>
                </div>
            </div>

            <div class="col-span-2 lg:col-span-1 app-card p-4 flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Store Open
                    </span>
                    <p class="text-xs text-slate-900 dark:text-slate-200 font-semibold mt-0.5">6:00 AM – 10:00 PM</p>
                </div>
                <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Side: Order Progress Tracker & Recent Orders (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Track Your Current Order -->
                <div class="app-card p-4 sm:p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="px-2.5 py-0.5 rounded bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-[10px] font-bold uppercase tracking-wider">
                                ACTIVE LOAD
                            </span>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white mt-1">Live Order Progress</h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-mono">Order: #{{ $activeOrder->order_number ?? 'HW-884210' }}</span>
                            @if(isset($activeOrder) && in_array($activeOrder->order_status, ['pending', 'received']))
                                <form method="POST" action="{{ route('laundry.cancel', $activeOrder->id) }}" class="inline">
                                    @csrf
                                    <button onclick="return confirm('Are you sure you want to cancel this pending order?')" class="bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30 hover:bg-rose-500/25 px-2.5 py-1 rounded-lg text-xs font-bold transition">
                                        Cancel Order
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs bg-black/5 dark:bg-[#2C2C2E] p-4 rounded-xl border border-black/5 dark:border-white/10">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Selected Service</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $activeOrder->service->name ?? 'Wash & Dry (6.5kg)' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Est. Completion</span>
                            <span class="font-semibold text-slate-900 dark:text-slate-100">{{ now()->addHours(2)->format('M d, Y - h:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Assigned Machine</span>
                            <span class="font-semibold text-[#007AFF] dark:text-[#0A84FF]">{{ $activeOrder->machine->machine_name ?? 'Washer Machine #2' }}</span>
                        </div>
                    </div>

                    <!-- 5 Steps Horizontal Tracker Bar -->
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-700 dark:text-slate-300">
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">Step 2 of 5</span>
                            <span class="text-[#007AFF] dark:text-[#0A84FF] font-bold">Washing Cycle Active</span>
                        </div>
                        
                        <div class="w-full h-2.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden flex p-0.5">
                            <div class="h-full bg-[#007AFF] dark:bg-[#0A84FF] rounded-full w-[40%] transition-all duration-500"></div>
                        </div>                        <div class="grid grid-cols-5 text-center text-[10px] font-bold text-slate-500 dark:text-slate-400 pt-1">
                            <div class="text-emerald-600 dark:text-emerald-400">Received</div>
                            <div class="text-[#007AFF] dark:text-[#0A84FF]">● Washing</div>
                            <div>○ RINSING</div>
                            <div>○ DRYING</div>
                            <div>○ READY</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders History Table -->
                <div class="app-card p-4 sm:p-6 space-y-4 overflow-hidden">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white font-['Outfit']">Recent Order History</h3>
                        <a href="{{ route('my.orders') }}" class="text-xs text-[#007AFF] dark:text-[#0A84FF] font-semibold hover:underline">View All Orders →</a>
                    </div>

                    <div class="overflow-x-auto max-w-full">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="bg-black/5 dark:bg-white/5 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="p-3">Order Code</th>
                                    <th class="p-3">Service</th>
                                    <th class="p-3">Weight</th>
                                    <th class="p-3">Total Amount</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-right">Track</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/5 dark:divide-white/5">
                                @forelse($recentOrders as $order)
                                    <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                                        <td class="p-3 font-mono font-bold text-[#007AFF] dark:text-[#0A84FF]">#{{ $order->order_number }}</td>
                                        <td class="p-3 text-slate-900 dark:text-slate-100 font-medium">{{ $order->service->name ?? 'Standard Wash' }}</td>
                                        <td class="p-3 text-slate-500 font-mono">{{ $order->weight_kg }} kg</td>
                                        <td class="p-3 font-bold font-mono text-emerald-600 dark:text-emerald-400">₱{{ number_format($order->total_amount, 2) }}</td>
                                        <td class="p-3">
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                                {{ str_replace('_', ' ', $order->order_status) }}
                                            </span>
                                        </td>
                                        <td class="p-3 text-right">
                                            <a href="{{ route('laundry.track', $order->order_number) }}" class="btn-ios-secondary py-1 px-2.5 text-[11px]">Track</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-slate-500 text-xs">No recent laundry orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Loyalty Points & Notifications -->
            <div class="space-y-6">
                <!-- Loyalty Rewards Card -->
                <div class="app-card p-5 space-y-4 bg-gradient-to-br from-[#007AFF]/10 via-transparent to-purple-500/10 border border-[#007AFF]/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#007AFF] dark:text-[#0A84FF] tracking-wider">Loyalty Club</span>
                            <h4 class="text-base font-extrabold text-slate-900 dark:text-white font-['Outfit']">Your Rewards Balance</h4>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-[#007AFF] text-white text-xs font-bold font-mono shadow-sm">
                            {{ auth()->user()->customerProfile->loyalty_points ?? 250 }} Pts
                        </span>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-300">Earn 10 points for every laundry booking & review! Redeem points for instant discounts.</p>

                    <form action="{{ route('loyalty.redeem') }}" method="POST" class="space-y-2 pt-1">
                        @csrf
                        <div class="grid grid-cols-2 gap-2">
                            <button type="submit" name="points" value="100" class="btn-ios-secondary text-[11px] py-2 text-center w-full font-bold">
                                Redeem 100 pts<br><span class="text-[#007AFF] dark:text-[#0A84FF]">(₱20 Off)</span>
                            </button>
                            <button type="submit" name="points" value="200" class="btn-ios-primary text-[11px] py-2 text-center w-full font-bold">
                                Redeem 200 pts<br><span class="text-white">(₱50 Off)</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Customer Ratings & Feedback Review Form Card -->
                <div class="app-card p-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-black/5 dark:border-white/10 pb-3">
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">⭐ Leave a Store Review</h3>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">+10 Bonus Pts</span>
                    </div>

                    <form action="{{ route('feedback.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Overall Rating</label>
                            <select name="rating" class="w-full text-xs py-2">
                                <option value="5">⭐⭐⭐⭐⭐ 5 Stars - Excellent Service</option>
                                <option value="4">⭐⭐⭐⭐ 4 Stars - Very Good</option>
                                <option value="3">⭐⭐⭐ 3 Stars - Average</option>
                                <option value="2">⭐⭐ 2 Stars - Needs Improvement</option>
                                <option value="1">⭐ 1 Star - Poor</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 block mb-1">Your Feedback & Review</label>
                            <textarea name="comment" rows="3" placeholder="Share your experience with HourWash..." class="w-full text-xs" required></textarea>
                        </div>

                        <button type="submit" class="btn-ios-primary text-xs w-full text-center">
                            Submit Review & Claim Points
                        </button>
                    </form>
                </div>

                <!-- Notifications & Alerts Card -->
                <div class="app-card p-4 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Store Notifications</h3>
                        <span class="text-[10px] text-[#007AFF] dark:text-[#0A84FF] font-semibold">Live Updates</span>
                    </div>

                    <div class="space-y-3">
                        <div class="p-3 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-900 dark:text-white">
                                <span>🧺 Order Loaded</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">10 mins ago</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300">Your clothes have been placed in Washer #2 cycle.</p>
                        </div>

                        <div class="p-3 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-900 dark:text-white">
                                <span>🏷 QR Tag Verified</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400">25 mins ago</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300">Staff verified your drop-off item count.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
