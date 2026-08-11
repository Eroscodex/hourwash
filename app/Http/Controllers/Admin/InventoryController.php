<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('name', 'asc')->get();

        $totalItems = InventoryItem::count();
        $lowStockCount = InventoryItem::where('status', 'low_stock')->orWhere('status', 'out_of_stock')->count();
        $totalStockValue = InventoryItem::all()->sum(function ($item) {
            return $item->quantity * $item->unit_cost;
        });

        $categories = InventoryItem::distinct()->pluck('category')->filter()->values();

        return view('admin.inventory.index', compact('items', 'totalItems', 'lowStockCount', 'totalStockValue', 'categories'));
    }

    /**
     * Store a newly created inventory item in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'unit' => 'required|string|max:30',
            'quantity' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $status = 'in_stock';
        if ($validated['quantity'] <= 0) {
            $status = 'out_of_stock';
        } elseif ($validated['quantity'] <= $validated['minimum_stock']) {
            $status = 'low_stock';
        }

        $validated['status'] = $status;

        InventoryItem::create($validated);

        return redirect()->route('admin.inventory.index')->with('success', "Inventory item '{$validated['name']}' added successfully!");
    }

    /**
     * Update the specified inventory item in storage.
     */
    public function update(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'unit' => 'required|string|max:30',
            'quantity' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
        ]);

        $status = 'in_stock';
        if ($validated['quantity'] <= 0) {
            $status = 'out_of_stock';
        } elseif ($validated['quantity'] <= $validated['minimum_stock']) {
            $status = 'low_stock';
        }

        $validated['status'] = $status;

        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')->with('success', "Inventory item '{$inventory->name}' updated successfully!");
    }

    /**
     * Remove the specified inventory item from storage.
     */
    public function destroy(InventoryItem $inventory)
    {
        $name = $inventory->name;
        $inventory->delete();

        return redirect()->route('admin.inventory.index')->with('success', "Inventory item '{$name}' deleted successfully!");
    }

    /**
     * Adjust stock level for an inventory item.
     */
    public function adjust(Request $request, InventoryItem $inventory)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,deduct',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = (float) $validated['amount'];

        if ($validated['adjustment_type'] === 'add') {
            $newQuantity = $inventory->quantity + $amount;
        } else {
            $newQuantity = max(0, $inventory->quantity - $amount);
        }

        $status = 'in_stock';
        if ($newQuantity <= 0) {
            $status = 'out_of_stock';
        } elseif ($newQuantity <= $inventory->minimum_stock) {
            $status = 'low_stock';
        }

        $inventory->update([
            'quantity' => $newQuantity,
            'status' => $status,
        ]);

        $actionText = $validated['adjustment_type'] === 'add' ? 'restocked' : 'deducted';

        return redirect()->route('admin.inventory.index')->with('success', "Stock {$actionText} for '{$inventory->name}'. New quantity: {$newQuantity} {$inventory->unit}.");
    }
}
