<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display forgot-password page.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        $email = strtolower(trim($request->input('email')));

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::whereRaw(
            'LOWER(email) = ?',
            [$email]
        )->first();

        Log::info('Password reset user lookup', [
            'email' => $email,
            'found' => (bool) $user,
            'user_id' => $user?->id,
        ]);

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => __('passwords.user'),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete old reset token
        |--------------------------------------------------------------------------
        */

        try {
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();
        } catch (\Throwable $e) {
            Log::error(
                'Unable to clean old password reset token',
                [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]
            );

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Unable to process password reset. Please try again.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate new token
        |--------------------------------------------------------------------------
        */

        $token = Str::random(64);

        try {
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]);

            Log::info(
                "Password reset token created for {$email}"
            );
        } catch (\Throwable $e) {
            Log::error(
                'Failed to create password reset token',
                [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]
            );

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Unable to process password reset. Please try again.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Send email
        |--------------------------------------------------------------------------
        */

        try {
            $user->sendPasswordResetNotification($token);

            Log::info(
                "Password reset email dispatch completed for {$email}"
            );
        } catch (\Throwable $e) {
            Log::error(
                'Password reset email failed',
                [
                    'email' => $email,
                    'error' => $e->getMessage(),
                ]
            );

            /*
             * Remove token if email failed.
             * This prevents a token from remaining valid when
             * the user never received the email.
             */
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Unable to send reset email. Please try again later.',
                ]);
        }

        return back()->with(
            'status',
            'We have emailed your password reset link.'
        );
    }
}
