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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'province' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->symbols()],
        ], [
            'name.required' => 'Please enter your full name.',
            'name.min' => 'Full Name must be at least 3 characters long.',
            'email.required' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered to another account.',
            'phone.required' => 'Please fill up your contact phone number.',
            'phone.unique' => 'This phone number is already registered to another account.',
            'address.required' => 'Please fill up your street address or barangay.',
            'city.required' => 'Please fill up your city or municipality.',
            'province.required' => 'Please fill up your province.',
            'password.required' => 'Please create a password for your account.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.symbols' => 'Password must contain at least one special symbol character (e.g. !@#$%^&*).',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'status' => 'active',
        ]);

        CustomerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
            ]
        );

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'admin' || $user->role === 'owner') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }
}
