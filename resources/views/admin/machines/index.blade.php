<x-app-layout>

    <div class="space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">
                    Machine Management
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">Configure commercial washers, dryers, live statuses, and scannable machine QR tags.</p>
            </div>
            <a href="{{ route('admin.machines.create') }}" class="btn-primary w-full sm:w-fit text-center flex items-center justify-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add New Machine
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 px-4 py-3 rounded-lg text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Machine Cycle Guide (Washer & Dryer Button Definitions) -->
        <div class="p-4 rounded-lg bg-slate-100 dark:bg-[#18181B] border border-slate-200 dark:border-zinc-700 space-y-3" x-data="{ showGuide: false }">
            <div class="flex items-center justify-between cursor-pointer" @click="showGuide = !showGuide">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">💡 Physical Machine Control Panel Cycle Guide</span>
                </div>
                <button type="button" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">
                    <span x-text="showGuide ? 'Hide Cycle Definitions ▲' : 'View Cycle Definitions ▼'">View Cycle Definitions ▼</span>
                </button>
            </div>

            <div x-show="showGuide" x-collapse class="space-y-4 pt-2 border-t border-slate-200 dark:border-zinc-700 text-xs">
                <!-- Dryer Buttons -->
                <div class="space-y-2">
                    <h4 class="font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 text-[11px] uppercase tracking-wider">
                        <span>♨️ Dryer Panel Buttons</span>
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
                        <span>🧺 Washer Panel Buttons</span>
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

        <div class="app-card overflow-hidden">
            <div class="overflow-x-auto max-w-full">
                <table class="w-full text-left text-xs sm:text-sm whitespace-nowrap min-w-[600px]">
                    <thead class="bg-slate-100 dark:bg-[#18181B] text-slate-700 dark:text-slate-300 uppercase text-[10px] tracking-wider border-b border-slate-200 dark:dark:border-zinc-700">
                        <tr>
                            <th class="px-4 sm:px-6 py-3.5">Machine Tag QR</th>
                            <th class="px-4 sm:px-6 py-3.5">Machine Name</th>
                            <th class="px-4 sm:px-6 py-3.5">Machine Code</th>
                            <th class="px-4 sm:px-6 py-3.5">Type</th>
                            <th class="px-4 sm:px-6 py-3.5">Status</th>
                            <th class="px-4 sm:px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-slate-900 dark:text-slate-200">
                    @forelse($machines as $machine)
                        <tr class="hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <td class="px-4 sm:px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $machine->machine_code }}" 
                                         alt="Machine Tag {{ $machine->machine_code }}" 
                                         class="w-10 h-10 bg-white p-0.5 rounded-lg border border-slate-200 shadow-sm">
                                    <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400">Scan</span>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $machine->machine_name }}</td>
                            <td class="px-4 sm:px-6 py-4 text-slate-600 dark:text-slate-400 font-mono text-xs font-semibold">{{ $machine->machine_code }}</td>
                            <td class="px-4 sm:px-6 py-4 text-slate-700 dark:text-slate-300 capitalize">{{ str_replace('_', ' ', $machine->machine_type) }}</td>
                            <td class="px-4 sm:px-6 py-4">
                                @if($machine->status === 'idle')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">Idle</span>
                                @elseif(in_array($machine->status, ['washing', 'rinsing', 'drying']))
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-teal-500/15 text-teal-700 dark:text-teal-300 border border-teal-500/30">{{ ucfirst($machine->status) }}</span>
                                @elseif($machine->status === 'maintenance')
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">Maintenance</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">Offline</span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.machines.edit', $machine) }}" class="p-1.5 text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="Edit Machine">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button type="button" x-data="" x-on:click="$dispatch('open-modal', 'delete-machine-{{ $machine->id }}')" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete Machine">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>

                                    <x-modal name="delete-machine-{{ $machine->id }}" maxWidth="sm">
                                        <div class="p-6 bg-white dark:bg-[#141417] text-slate-900 dark:text-zinc-100 space-y-4 rounded-lg text-left">
                                            <h2 class="text-base font-bold text-rose-600 dark:text-rose-400">Delete Machine?</h2>
                                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                                                Are you sure you want to delete unit <strong>{{ $machine->machine_name }}</strong> ({{ $machine->machine_code }})?
                                            </p>
                                            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-zinc-800">
                                                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary text-xs py-1.5 px-3">
                                                    Cancel
                                                </button>
                                                <form action="{{ route('admin.machines.destroy', $machine) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-danger text-xs py-1.5 px-3">
                                                        Delete Unit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </x-modal>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-slate-500">No machines configured in database.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200 dark:dark:border-zinc-700">{{ $machines->links() }}</div>
        </div>
    </div>

</x-app-layout>