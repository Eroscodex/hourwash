<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->input('email');

        // Find the user
        $user = User::where('email', $email)->first();

        if (! $user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        // Create token manually
        try {
            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            Log::info("Password reset token created for {$email}");
        } catch (\Throwable $e) {
            Log::error("Failed to create password reset token: {$e->getMessage()}");

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to process password reset. Please try again.']);
        }

        // Send email via Brevo HTTP API directly
        try {
            EmailNotificationService::sendPasswordResetEmail($email, $token);
            Log::info("Password reset email dispatch completed for {$email}");
        } catch (\Throwable $e) {
            Log::error("Password reset email failed for {$email}: {$e->getMessage()}");

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Unable to send reset email. Please try again.']);
        }

        return back()->with('status', __('passwords.sent'));
    }
}
