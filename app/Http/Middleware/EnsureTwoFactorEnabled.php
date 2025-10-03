<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user?->hasRole('Admin') && blank($user->two_factor_confirmed_at)) {
            return redirect()->route('settings.security')
                ->with('status', 'Please enable 2FA to access admin features.');
        }

        return $next($request);
    }
}
