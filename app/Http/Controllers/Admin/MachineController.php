<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MachineController extends Controller
{
    public function index()
    {
        $machines = Machine::orderBy('id', 'asc')->paginate(15);

        return view('admin.machines.index', compact('machines'));
    }

    public function create()
    {
        return view('admin.machines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'machine_name' => 'required|string|max:100',
            'machine_code' => 'required|string|max:50|unique:machines',
            'machine_type' => ['required', Rule::in(['washer', 'dryer', 'washer_dryer'])],
            'status' => ['required', Rule::in(['idle', 'washing', 'rinsing', 'drying', 'maintenance', 'offline'])],
        ]);

        Machine::create($request->only(['machine_name', 'machine_code', 'machine_type', 'status']));

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine added successfully');
    }

    public function edit(Machine $machine)
    {
        return view('admin.machines.edit', compact('machine'));
    }

    public function update(Request $request, Machine $machine)
    {
        $request->validate([
            'machine_name' => 'required|string|max:100',
            'machine_code' => ['required', 'string', 'max:50', Rule::unique('machines')->ignore($machine->id)],
            'machine_type' => ['required', Rule::in(['washer', 'dryer', 'washer_dryer'])],
            'status' => ['required', Rule::in(['idle', 'washing', 'rinsing', 'drying', 'maintenance', 'offline'])],
        ]);

        $machine->update($request->only(['machine_name', 'machine_code', 'machine_type', 'status']));

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine updated');
    }

    public function destroy(Machine $machine)
    {
        $machine->delete();

        return back()
            ->with('success', 'Machine deleted');
    }
}
