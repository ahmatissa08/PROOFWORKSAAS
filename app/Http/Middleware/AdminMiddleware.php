<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('proofwork_admin') === true) {
            return $next($request);
        }

        return redirect()->route('admin.login');
    }
}