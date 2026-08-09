<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'asc')->get();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'service_type' => ['required', Rule::in(['wash', 'dry', 'wash_dry', 'wash_dry_fold', 'blanket', 'pickup_delivery', 'other'])],
            'price' => 'required|numeric|min:0',
            'price_unit' => ['required', Rule::in(['kg', 'load', 'item', 'service'])],
            'estimated_minutes' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        Service::create($request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service package created successfully!');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:1000',
            'service_type' => ['required', Rule::in(['wash', 'dry', 'wash_dry', 'wash_dry_fold', 'blanket', 'pickup_delivery', 'other'])],
            'price' => 'required|numeric|min:0',
            'price_unit' => ['required', Rule::in(['kg', 'load', 'item', 'service'])],
            'estimated_minutes' => 'required|integer|min:1',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $service->update($request->all());

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service package updated successfully!');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service package deleted successfully!');
    }
}
