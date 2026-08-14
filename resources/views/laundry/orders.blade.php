<x-app-layout>

<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold font-['Outfit'] text-slate-900 dark:text-white">My Order History</h1>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Review all your current and past laundry bookings and track status using QR code tags.</p>
        </div>
        <a href="{{ route('laundry.create') }}" class="btn-ios-primary w-fit">+ Book New Order</a>
    </div>

    @forelse($orders as $order)
        <div class="app-card p-5 space-y-4 shadow-sm hover:border-[#007AFF]/30 transition">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-black/10 dark:border-white/10 pb-3">
                <div>
                    <span class="px-2 py-0.5 rounded bg-[#007AFF]/15 text-[#007AFF] dark:text-[#0A84FF] text-[10px] font-bold uppercase tracking-wider">
                        LAUNDRY BAG ORDER
                    </span>
                    <h2 class="text-lg font-bold font-mono text-slate-900 dark:text-white mt-0.5">Order Code: #{{ $order->order_number }}</h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ $order->service->name ?? 'Wash & Dry' }} (₱{{ number_format($order->service->price ?? 0, 2) }}/{{ $order->service->price_unit ?? 'kg' }})</p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-emerald-600 dark:text-emerald-400 font-extrabold text-xl font-['Outfit']">₱{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Laundry Weight</span>
                    <p class="text-slate-900 dark:text-slate-100 font-semibold">{{ $order->weight_kg }} kg</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Cleaning Stage</span>
                    <p class="text-[#007AFF] dark:text-[#0A84FF] capitalize font-bold">{{ str_replace('_', ' ', $order->order_status) }}</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">Est. Completion</span>
                    <p class="text-slate-900 dark:text-slate-200 font-semibold">{{ $order->estimated_completion?->format('M d, Y h:i A') ?? 'TBD' }}</p>
                </div>
                <div>
                    <span class="text-slate-500 dark:text-slate-400 text-[11px] block">QR Code Tracking & Receipt</span>
                    <div class="flex items-center gap-2 mt-1">
                        <a href="{{ route('laundry.track', $order->qrCode->qr_token ?? $order->order_number) }}" class="btn-ios-primary px-3 py-1.5 text-[11px] font-bold inline-flex items-center gap-1 shadow-sm">
                            Track Order
                        </a>
                        <a href="{{ route('laundry.receipt', $order->id) }}" target="_blank" class="btn-ios-secondary px-3 py-1.5 text-[11px] font-bold inline-flex items-center gap-1 shadow-sm">
                            Receipt Order
                        </a>
                    </div>
                </div>
            </div>

            @if($order->order_status === 'completed')
                <div class="mt-4 pt-4 border-t border-black/5 dark:border-white/5 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Customer Feedback & Rating</h4>
                        @if($order->feedback)
                            <span class="text-[10px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Submitted</span>
                        @else
                            <span class="text-[10px] bg-amber-500/10 text-amber-600 dark:text-amber-400 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">Feedback Pending</span>
                        @endif
                    </div>

                    @if($order->feedback)
                        <!-- Display Feedback & Delete Option -->
                        <div class="p-3.5 rounded-xl bg-black/5 dark:bg-[#2C2C2E] border border-black/5 dark:border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center text-amber-400 gap-0.5">
                                    @for($i = 0; $i < $order->feedback->rating; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 italic">"{{ $order->feedback->comment }}"</p>
                            </div>
                            <form method="POST" action="{{ route('feedback.destroy', $order->feedback->id) }}" onsubmit="return confirm('Delete this feedback?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-bold px-3 py-1.5 rounded-lg hover:bg-rose-500/10 transition">
                                    Delete Feedback
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Leave Feedback Form -->
                        <form method="POST" action="{{ route('feedback.store') }}" class="space-y-3">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="w-full sm:w-1/4">
                                    <label class="text-[10px] text-slate-500 block mb-1 uppercase font-bold">Rating</label>
                                    <select name="rating" class="w-full py-1.5 px-3 rounded-xl bg-white dark:bg-[#1C1C1E] border border-black/10 dark:border-white/10 text-slate-900 dark:text-white" required>
                                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                        <option value="3">⭐⭐⭐ (3/5)</option>
                                        <option value="2">⭐⭐ (2/5)</option>
                                        <option value="1">⭐ (1/5)</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="text-[10px] text-slate-500 block mb-1 uppercase font-bold">Comment / Review</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="comment" placeholder="How was your laundry experience?" class="flex-1 bg-white dark:bg-[#1C1C1E] border border-black/10 dark:border-white/10 rounded-xl px-3.5 py-1.5 text-xs focus:outline-none focus:border-[#007AFF] text-slate-900 dark:text-white" required>
                                        <button type="submit" class="btn-ios-primary py-1.5 px-4 text-xs font-bold">
                                            Submit Review
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
        <div class="app-card p-8 text-center text-slate-500 text-xs">
            No order history found. Book your first laundry service today!
        </div>
    @endforelse

</div>

</x-app-layout>