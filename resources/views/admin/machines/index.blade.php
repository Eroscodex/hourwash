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
                                    <form action="{{ route('admin.machines.destroy', $machine) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Delete machine?')" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete Machine">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
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