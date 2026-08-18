<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'min:8', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->symbols()],
        ], [
            'name.min' => 'Full Name must be at least 8 characters long.',
            'name.unique' => 'This name has already been registered. Please use your full name.',
            'phone.unique' => 'This phone number is already registered to another account.',
            'email.unique' => 'This email address is already registered to another account.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.symbols' => 'Password must contain at least one special symbol character (e.g. !@#$%^&*).',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        CustomerProfile::firstOrCreate([
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'admin' || $user->role === 'owner') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
