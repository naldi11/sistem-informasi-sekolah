<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FirstLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->is_first_login && !$request->is('ganti-password*')) {
            return redirect()->route('ganti-password');
        }
        return $next($request);
    }
}
