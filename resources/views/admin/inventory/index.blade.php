<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">
                    Store Inventory Management
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Track detergent supplies, fabric softeners, packaging, and machine maintenance parts</p>
            </div>
            
            <button onclick="document.getElementById('add-item-modal').classList.remove('hidden')" class="btn-ios-primary text-xs flex items-center gap-1.5 shadow-md hover:scale-105 transition-transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add Inventory Item
            </button>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="app-card p-5 space-y-1">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Total Supply Items</span>
                <div class="text-2xl font-extrabold text-slate-900 dark:text-white font-['Outfit']">{{ $totalItems }}</div>
                <p class="text-[11px] text-slate-400">Tracked in store warehouse</p>
            </div>

            <div class="app-card p-5 space-y-1">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Low Stock Alerts</span>
                <div class="text-2xl font-extrabold font-['Outfit'] {{ $lowStockCount > 0 ? 'text-amber-500' : 'text-emerald-500' }}">
                    {{ $lowStockCount }}
                </div>
                <p class="text-[11px] text-slate-400">Items below minimum threshold</p>
            </div>

            <div class="app-card p-5 space-y-1">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Estimated Inventory Value</span>
                <div class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 font-['Outfit']">
                    ₱{{ number_format($totalStockValue, 2) }}
                </div>
                <p class="text-[11px] text-slate-400">Based on unit acquisition cost</p>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="app-card p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.inventory.index') }}" class="w-full flex flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <svg class="w-4 h-4 absolute left-3 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search supplies, category..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10 text-slate-900 dark:text-white focus:outline-none focus:border-[#007AFF]">
                </div>

                <select name="category" onchange="this.form.submit()" class="w-full sm:w-48 py-2 px-3 text-xs rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10 text-slate-900 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                @if(request('search') || request('category'))
                    <a href="{{ route('admin.inventory.index') }}" class="text-xs text-rose-500 font-semibold hover:underline">Clear Filters</a>
                @endif
            </form>
        </div>

        <!-- Inventory Items Table Card -->
        <div class="app-card overflow-hidden">
            <div class="p-5 border-b border-black/10 dark:border-white/10 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Store Supply Register</h2>
                <span class="text-xs text-slate-500">{{ count($items) }} Records</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black/5 dark:bg-white/5 border-b border-black/10 dark:border-white/10 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-4">Item Name</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Stock Quantity</th>
                            <th class="p-4">Min. Threshold</th>
                            <th class="p-4">Unit Cost</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5 text-xs">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="p-4 font-bold text-slate-900 dark:text-white">
                                    {{ $item->name }}
                                </td>
                                <td class="p-4 text-slate-500 dark:text-slate-400">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-white/10 text-[11px] font-semibold">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="p-4 font-mono font-bold text-slate-900 dark:text-slate-100">
                                    {{ $item->quantity }} {{ $item->unit }}
                                </td>
                                <td class="p-4 text-slate-500 font-mono">
                                    {{ $item->minimum_stock }} {{ $item->unit }}
                                </td>
                                <td class="p-4 font-mono text-emerald-600 dark:text-emerald-400 font-semibold">
                                    ₱{{ number_format($item->unit_cost, 2) }}
                                </td>
                                <td class="p-4">
                                    @if($item->status === 'in_stock')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                            In Stock
                                        </span>
                                    @elseif($item->status === 'low_stock')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">
                                            Out of Stock
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Quick Adjust Stock Form -->
                                        <form method="POST" action="{{ route('admin.inventory.adjust', $item->id) }}" class="inline-flex items-center gap-1">
                                            @csrf
                                            <input type="number" name="amount" step="1" min="1" value="10" class="w-14 p-1 text-[11px] rounded-lg bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10 text-center font-mono">
                                            <button type="submit" name="adjustment_type" value="add" title="Restock +10" class="px-2 py-1 bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 hover:bg-emerald-500/25 rounded-lg text-[10px] font-bold">
                                                + Stock
                                            </button>
                                        </form>

                                        <!-- Edit Item Button -->
                                        <button onclick="document.getElementById('edit-item-modal-{{ $item->id }}').classList.remove('hidden')" class="p-1.5 text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="Edit Item Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <!-- Delete Item Form -->
                                        <form method="POST" action="{{ route('admin.inventory.destroy', $item->id) }}" class="inline" onsubmit="return confirm('Delete {{ $item->name }} from inventory?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="Delete Item">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Item Modal -->
                                    <div id="edit-item-modal-{{ $item->id }}" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 text-left">
                                        <div class="app-card max-w-md w-full p-6 space-y-4 shadow-2xl">
                                            <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                                                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Edit {{ $item->name }}</h3>
                                                <button type="button" onclick="document.getElementById('edit-item-modal-{{ $item->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                                            </div>

                                            <form method="POST" action="{{ route('admin.inventory.update', $item->id) }}" class="space-y-4 text-xs">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Item Name</label>
                                                    <input type="text" name="name" value="{{ $item->name }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Category</label>
                                                        <select name="category" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                            <option value="Detergents" {{ $item->category === 'Detergents' ? 'selected' : '' }}>Detergents</option>
                                                            <option value="Fabric Conditioners" {{ $item->category === 'Fabric Conditioners' ? 'selected' : '' }}>Fabric Conditioners</option>
                                                            <option value="Bleach & Stain Remover" {{ $item->category === 'Bleach & Stain Remover' ? 'selected' : '' }}>Bleach & Stain Remover</option>
                                                            <option value="Packaging & Bags" {{ $item->category === 'Packaging & Bags' ? 'selected' : '' }}>Packaging & Bags</option>
                                                            <option value="Machine Parts & Accessories" {{ $item->category === 'Machine Parts & Accessories' ? 'selected' : '' }}>Machine Parts & Accessories</option>
                                                            <option value="Cleaning Supplies" {{ $item->category === 'Cleaning Supplies' ? 'selected' : '' }}>Cleaning Supplies</option>
                                                            <option value="Supplies" {{ $item->category === 'Supplies' ? 'selected' : '' }}>Supplies</option>
                                                            <option value="Packaging" {{ $item->category === 'Packaging' ? 'selected' : '' }}>Packaging</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Unit of Measure</label>
                                                        <input type="text" name="unit" value="{{ $item->unit }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-3">
                                                    <div>
                                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Stock Qty</label>
                                                        <input type="number" name="quantity" step="0.5" min="0" value="{{ $item->quantity }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Min Threshold</label>
                                                        <input type="number" name="minimum_stock" step="0.5" min="0" value="{{ $item->minimum_stock }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                    </div>
                                                    <div>
                                                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Unit Cost (₱)</label>
                                                        <input type="number" name="unit_cost" step="0.5" min="0" value="{{ $item->unit_cost }}" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10 dark:border-white/10">
                                                    <button type="button" onclick="document.getElementById('edit-item-modal-{{ $item->id }}').classList.add('hidden')" class="btn-ios-secondary text-xs">Cancel</button>
                                                    <button type="submit" class="btn-ios-primary text-xs">Update Item</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500 text-xs">
                                    No inventory items found. Click "Add Inventory Item" to register laundry supplies.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div id="add-item-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="app-card max-w-md w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-black/10 dark:border-white/10 pb-3">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Add New Laundry Supply Item</h3>
                <button onclick="document.getElementById('add-item-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.inventory.store') }}" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Item Name</label>
                    <input type="text" name="name" placeholder="e.g. Liquid Detergent (Ariel 5L)" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Category</label>
                        <select name="category" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                            <option value="Detergents">Detergents</option>
                            <option value="Fabric Conditioners">Fabric Conditioners</option>
                            <option value="Bleach & Stain Remover">Bleach & Stain Remover</option>
                            <option value="Packaging & Bags">Packaging & Bags</option>
                            <option value="Machine Parts & Accessories">Machine Parts & Accessories</option>
                            <option value="Cleaning Supplies">Cleaning Supplies</option>
                            <option value="Supplies">Supplies</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Unit of Measure</label>
                        <input type="text" name="unit" placeholder="e.g. Liters, Gallons, Packs, Pieces" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Stock Qty</label>
                        <input type="number" name="quantity" step="0.5" min="0" value="50" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Min Threshold</label>
                        <input type="number" name="minimum_stock" step="0.5" min="0" value="10" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Unit Cost (₱)</label>
                        <input type="number" name="unit_cost" step="0.5" min="0" value="150" class="w-full p-2.5 rounded-xl bg-slate-100 dark:bg-[#2C2C2E] border border-black/10 dark:border-white/10" required>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-black/10 dark:border-white/10">
                    <button type="button" onclick="document.getElementById('add-item-modal').classList.add('hidden')" class="btn-ios-secondary text-xs">Cancel</button>
                    <button type="submit" class="btn-ios-primary text-xs">Save Inventory Item</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
