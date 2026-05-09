<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->status === 'banned') {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account has been banned.']);
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
