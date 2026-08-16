<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Show reset-password page.
     */
    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Reset the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        $email = strtolower(trim($request->email));

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (! $resetRecord) {
            throw ValidationException::withMessages([
                'email' => 'This password reset link is invalid or has expired.',
            ]);
        }

        if (! $resetRecord->created_at || now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            throw ValidationException::withMessages([
                'email' => 'This password reset link has expired.',
            ]);
        }

        $inputHash = hash('sha256', $request->token);
        Log::info('Password reset hash comparison', [
            'email' => $email,
            'input_token_length' => strlen($request->token),
            'input_hash' => $inputHash,
            'db_hash' => $resetRecord->token,
            'matches' => hash_equals($resetRecord->token, $inputHash),
        ]);

        if (! hash_equals($resetRecord->token, $inputHash)) {
            throw ValidationException::withMessages([
                'email' => 'This password reset link is invalid.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => 'We could not find that account.',
            ]);
        }

        if (Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'New password cannot be the same as your previous password.',
            ]);
        }

        $user->password = $request->password;
        $user->setRememberToken(Str::random(60));
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        Log::info("Password reset completed for {$email}");

        return redirect()
            ->route('login')
            ->with('status', 'Your password has been reset successfully.');
    }
}
