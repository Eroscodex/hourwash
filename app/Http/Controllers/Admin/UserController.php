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
    /**
     * Display all users with search & role filter
     */
    public function index(Request $request)
    {
        $query = User::with('customerProfile');

        if ($request->filled('role')) {
            if ($request->role === 'customer') {
                $query->whereIn('role', ['customer', 'user']);
            } else {
                $query->where('role', $request->role);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        $totalUsers = User::count();
        $adminCount = User::whereIn('role', ['owner', 'admin'])->count();
        $staffCount = User::where('role', 'staff')->count();
        $riderCount = User::where('role', 'rider')->count();
        $customerCount = User::whereIn('role', ['customer', 'user'])->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'adminCount',
            'staffCount',
            'riderCount',
            'customerCount'
        ));
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
            'role' => ['required', 'string', Rule::in(['owner', 'admin', 'staff', 'rider', 'customer', 'user'])],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        if ($request->filled('address')) {
            CustomerProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['address' => $request->address]
            );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User account for '{$user->name}' created successfully as ".strtoupper($user->role).'!');
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
            'role' => ['required', 'string', Rule::in(['owner', 'admin', 'staff', 'rider', 'customer', 'user'])],
            'password' => ['nullable', 'min:8'],
            'status' => ['nullable', 'string', Rule::in(['active', 'inactive', 'blocked'])],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        $user->update($updateData);

        if ($request->has('address')) {
            CustomerProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['address' => $request->address]
            );
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User account '{$user->name}' updated successfully!");
    }

    /**
     * Delete user
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $name = $user->name;

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User account '{$name}' deleted successfully!");
    }
}
