<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use Illuminate\Http\Request;

class MachineController extends Controller
{

    public function index()
    {
        $machines = Machine::latest()->paginate(10);

        return view('admin.machines.index', compact('machines'));
    }


    public function create()
    {
        return view('admin.machines.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'machine_name'=>'required',
            'machine_code'=>'required|unique:machines',
            'status'=>'required'
        ]);


        Machine::create([
            'machine_name'=>$request->machine_name,
            'machine_code'=>$request->machine_code,
            'status'=>$request->status
        ]);


        return redirect()
            ->route('admin.machines.index')
            ->with('success','Machine added successfully');

    }



    public function edit(Machine $machine)
    {
        return view('admin.machines.edit', compact('machine'));
    }



    public function update(Request $request, Machine $machine)
    {

        $request->validate([
            'machine_name'=>'required',
            'machine_code'=>'required',
            'status'=>'required'
        ]);


        $machine->update($request->all());


        return redirect()
            ->route('admin.machines.index')
            ->with('success','Machine updated');

    }



    public function destroy(Machine $machine)
    {

        $machine->delete();


        return back()
            ->with('success','Machine deleted');

    }

}