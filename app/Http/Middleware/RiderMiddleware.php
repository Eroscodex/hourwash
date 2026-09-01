<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RiderMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isRider()) {
            return $next($request);
        }

        if ($user && ($user->isAdmin() || $user->isOwner())) {
            return redirect()->route('admin.dashboard');
        }

        if ($user && $user->isStaff()) {
            return redirect()->route('staff.dashboard');
        }

        if ($user) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
