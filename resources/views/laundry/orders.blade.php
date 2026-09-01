<x-app-layout>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">My Order History</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-zinc-400 mt-1">Review all your current and past laundry bookings, track status, and submit feedback.</p>
        </div>
        @if(\Illuminate\Support\Facades\Cache::get('store_status', 'open') === 'open' || (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isOwner() || auth()->user()->isStaff())))
            <a href="{{ route('laundry.create') }}" class="btn-primary py-2 px-4 text-xs font-bold w-full sm:w-auto text-center flex items-center justify-center shrink-0">Book New Order</a>
        @else
            <button disabled class="opacity-65 bg-rose-500/15 text-rose-600 dark:text-rose-400 border border-rose-500/30 px-4 py-2 rounded-lg text-xs font-bold cursor-not-allowed inline-flex items-center justify-center gap-1.5 w-full sm:w-auto shrink-0">
                🚫 Store Closed Today (Bookings Disabled)
            </button>
        @endif
    </div>

    <!-- Filter Tabs for Customer History Management -->
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 dark:border-zinc-800 pb-3" x-data="{ activeTab: 'all' }">
        <button type="button" @click="activeTab = 'all'; filterOrders('all')" :class="activeTab === 'all' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
            All Orders ({{ $orders->count() }})
        </button>
        <button type="button" @click="activeTab = 'active'; filterOrders('active')" :class="activeTab === 'active' ? 'bg-blue-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
            Active Orders ({{ $orders->where('order_status', '!=', 'completed')->count() }})
        </button>
        <button type="button" @click="activeTab = 'completed'; filterOrders('completed')" :class="activeTab === 'completed' ? 'bg-emerald-600 text-white font-bold' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs transition border border-slate-200 dark:border-zinc-700 cursor-pointer">
            Completed History ({{ $orders->where('order_status', 'completed')->count() }})
        </button>
    </div>

    @forelse($orders as $order)
        <div data-status="{{ $order->order_status }}" class="app-card p-5 space-y-4 shadow-sm hover:border-blue-600/40 transition customer-order-card">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-zinc-800 pb-3">
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="badge-status badge-blue">
                            LAUNDRY BAG ORDER
                        </span>
                        @if($order->machine)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                Assigned: {{ $order->machine->machine_name }} ({{ $order->machine->machine_code }})
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                Unassigned (Auto-Assign on Wash)
                            </span>
                        @endif
                    </div>
                    <h2 class="text-base sm:text-lg font-bold font-mono text-slate-900 dark:text-white mt-1">Order Code: #{{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-600 dark:text-zinc-400">{{ $order->service->name ?? 'Wash & Dry' }} (₱{{ number_format($order->service->price ?? 0, 2) }}/{{ $order->service->price_unit ?? 'kg' }})</p>
                </div>
                <div class="flex items-center gap-2 text-left sm:text-right">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-extrabold text-xl">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
                <div>
                    <span class="text-slate-500 dark:text-zinc-400 text-[11px] block">Laundry Weight</span>
                    <p class="text-slate-900 dark:text-zinc-100 font-semibold">{{ $order->weight_kg }} kg</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-zinc-400 text-[11px] block">Assigned Machine</span>
                    <p class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">
                        {{ $order->machine ? $order->machine->machine_name . ' (' . $order->machine->machine_code . ')' : 'Auto-Assign on Wash' }}
                    </p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-zinc-400 text-[11px] block">Status Stage</span>
                    <p class="text-blue-600 dark:text-blue-400 capitalize font-bold">{{ str_replace('_', ' ', $order->order_status) }}</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-zinc-400 text-[11px] block">Est. Completion</span>
                    <p class="text-slate-900 dark:text-zinc-200 font-semibold">{{ $order->estimated_completion?->format('M d, Y h:i A') ?? 'TBD' }}</p>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <span class="text-slate-500 dark:text-zinc-400 text-[11px] block">Tracking & Receipt</span>
                    <div class="flex items-center gap-2 mt-1">
                        <a href="{{ route('laundry.track', $order->qrCode->qr_token ?? $order->order_number) }}" class="btn-primary px-3 py-1.5 text-[11px] font-bold inline-flex items-center gap-1 shadow-sm">
                            Track Order
                        </a>
                        <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="btn-secondary px-3 py-1.5 text-[11px] font-bold inline-flex items-center gap-1 shadow-sm">
                            Receipt
                        </a>
                    </div>
                </div>
            </div>

            <!-- Completed Order Customer Review Section -->
            @if($order->order_status === 'completed')
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-zinc-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-slate-800 dark:text-zinc-200 uppercase tracking-wider flex items-center gap-1.5">
                            <span>⭐ Customer Review & Rating</span>
                        </h3>
                        @if($order->feedback)
                            <span class="badge-status badge-green">Published</span>
                        @else
                            <span class="badge-status badge-orange">Review Pending (+10 Points)</span>
                        @endif
                    </div>

                    @if($order->feedback)
                        <!-- Published Customer Review Card -->
                        <div class="p-4 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center text-amber-400 gap-1 text-sm">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < $order->feedback->rating) ★ @else ☆ @endif
                                    @endfor
                                    <span class="text-xs text-slate-500 dark:text-zinc-400 ml-1.5 font-sans font-semibold">({{ $order->feedback->rating }}/5)</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-zinc-200 leading-relaxed italic">"{{ $order->feedback->comment }}"</p>
                            </div>
                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'delete-order-feedback-{{ $order->feedback->id }}')" class="btn-danger py-1 px-3 text-xs">
                                Delete Review
                            </button>

                            <x-modal name="delete-order-feedback-{{ $order->feedback->id }}" maxWidth="sm">
                                <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                    <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Delete Review?</h2>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Are you sure you want to delete your feedback review for order #{{ $order->order_number }}?
                                    </p>
                                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                        <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                            Cancel
                                        </button>
                                        <form method="POST" action="{{ route('feedback.destroy', $order->feedback->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                                Delete Review
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </x-modal>
                        </div>
                    @else
                        <!-- Customer Review Submission Form -->
                        <form method="POST" action="{{ route('feedback.store') }}" class="p-4 rounded-lg bg-slate-50 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-800 space-y-3">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-zinc-300 block mb-1.5 uppercase">Select Star Rating</label>
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <select name="rating" class="w-full sm:w-64 py-2 px-3 rounded-lg bg-white dark:bg-[#141417] border border-slate-300 dark:border-zinc-700 text-slate-900 dark:text-zinc-100 text-xs font-bold focus:border-blue-600 focus:ring-1 focus:ring-blue-600 cursor-pointer shadow-sm" required>
                                            <option value="5">⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                                            <option value="4" selected>⭐⭐⭐⭐ (4 - Very Good)</option>
                                            <option value="3">⭐⭐⭐ (3 - Good)</option>
                                            <option value="2">⭐⭐ (2 - Fair)</option>
                                            <option value="1">⭐ (1 - Poor)</option>
                                        </select>
                                        <span class="text-[11px] text-slate-500 dark:text-zinc-400 font-medium hidden sm:inline">Rate your wash & delivery experience</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[11px] font-bold text-slate-700 dark:text-zinc-300 block mb-1 uppercase">Write Your Experience / Comment</label>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" name="comment" placeholder="Tell us about your wash, fold, or delivery experience..." class="flex-1 bg-white dark:bg-[#141417] border border-slate-300 dark:border-zinc-700 rounded-md px-3.5 py-2 text-xs focus:outline-none focus:border-blue-600 text-slate-900 dark:text-zinc-100" required>
                                        <button type="submit" class="btn-primary py-2 px-4 text-xs shrink-0">
                                            Publish Review
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="app-card p-8 text-center text-slate-500 dark:text-zinc-400 text-xs">
            No order history found. Book your first laundry service today!
        </div>
    @endforelse

</div>

<script>
    function filterOrders(type) {
        const cards = document.querySelectorAll('.customer-order-card');
        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            if (type === 'all') {
                card.style.display = '';
            } else if (type === 'active') {
                card.style.display = status !== 'completed' ? '' : 'none';
            } else if (type === 'completed') {
                card.style.display = status === 'completed' ? '' : 'none';
            }
        });
    }
</script>

</x-app-layout>