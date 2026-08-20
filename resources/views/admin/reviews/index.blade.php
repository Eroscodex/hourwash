<x-app-layout>
    <div class="space-y-6 sm:space-y-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    Customer Ratings & Reviews Outbox
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Live feedback, ratings, and testimonials submitted by store customers.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="px-3.5 py-1.5 rounded-md bg-amber-500/15 text-amber-700 dark:text-amber-300 text-xs font-bold border border-amber-500/30">
                    ★ {{ $avgRating }} / 5.0 Average Rating
                </span>
                <span class="px-3.5 py-1.5 rounded-md bg-blue-600/15 text-blue-600 dark:text-blue-400 text-xs font-extrabold tracking-wider uppercase border border-blue-600/30">
                    {{ $totalReviews }} Total Reviews
                </span>
                @if($totalReviews > 0)
                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'confirm-clear-reviews')" class="px-3.5 py-1.5 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-600 dark:text-rose-400 text-xs font-bold border border-rose-500/30 transition">
                        Clear All Reviews
                    </button>

                    <x-modal name="confirm-clear-reviews" maxWidth="sm">
                        <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Clear All Customer Reviews?</h2>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                Are you sure you want to delete all customer ratings & review comments permanently?
                            </p>
                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                    Cancel
                                </button>
                                <form method="POST" action="{{ route('admin.reviews.clearAll') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                        Clear All Reviews
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-modal>
                @else
                    <button type="button" disabled class="px-3.5 py-1.5 rounded-lg bg-slate-500/10 text-slate-400 dark:text-zinc-500 text-xs font-bold border border-slate-500/20 opacity-60 cursor-not-allowed">
                        Clear All Reviews
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Reviews Outbox Feed -->
        <div class="space-y-4">
            @forelse($feedbacks as $fb)
                <div class="app-card p-4 sm:p-5 space-y-3 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 dark:border-zinc-700 pb-2.5">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-slate-900 dark:text-slate-100 text-sm">
                                {{ $fb->user->name ?? 'Store Customer' }}
                            </span>
                            @if($fb->order)
                                <span class="font-mono text-xs text-blue-600 dark:text-blue-400 font-bold">
                                    Order #{{ $fb->order->order_number }}
                                </span>
                            @endif
                            <div class="flex items-center text-amber-500 text-xs font-bold bg-amber-500/10 px-2 py-0.5 rounded border border-amber-500/20">
                                <span>★ {{ $fb->rating }}.0 Stars</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 font-mono">
                                {{ $fb->created_at->format('M d, Y h:i A') }} ({{ $fb->created_at->diffForHumans() }})
                            </span>

                            <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'delete-review-{{ $fb->id }}')" class="text-xs text-rose-600 dark:text-rose-400 hover:underline font-semibold">
                                Delete Review
                            </button>

                            <x-modal name="delete-review-{{ $fb->id }}" maxWidth="sm">
                                <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                    <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Delete Review?</h2>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                        Are you sure you want to delete this customer review?
                                    </p>
                                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                        <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                            Cancel
                                        </button>
                                        <form method="POST" action="{{ route('feedback.destroy', $fb->id) }}">
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
                    </div>

                    <div class="p-3.5 rounded-lg bg-slate-50 dark:bg-zinc-800/50 text-slate-900 dark:text-slate-100 text-xs leading-relaxed italic border border-slate-200 dark:border-zinc-700 shadow-xs">
                        "{{ $fb->comment }}"
                    </div>
                </div>
            @empty
                <div class="app-card p-8 text-center space-y-3 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">No Customer Reviews Submitted</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Ratings & reviews submitted by customers upon order completion will appear here.
                    </p>
                </div>
            @endforelse

            @if(method_exists($feedbacks, 'links'))
                <div class="mt-4">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
