<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display all users
     */
    public function index()
    {
        $users = User::with('customerProfile')->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Save new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'address' => 'nullable|string|max:255',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->filled('address')) {
            CustomerProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['address' => $request->address]
            );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $user = User::with('customerProfile')->findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($id)],
            'address' => ['nullable', 'string', 'max:255'],
            'role' => 'required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);

        if ($request->has('address')) {
            CustomerProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['address' => $request->address]
            );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account updated successfully');
    }

    /**
     * Delete user
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }
}
