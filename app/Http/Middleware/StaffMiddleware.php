<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && ($user->isStaff() || $user->isAdmin() || $user->isOwner())) {
            return $next($request);
        }

        if ($user && $user->isRider()) {
            return redirect()->route('rider.dashboard');
        }

        if ($user) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('login');
    }
}
