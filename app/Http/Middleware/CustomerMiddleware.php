<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     * Only allows users with role 'user' (customer) to pass through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role === 'admin') {
            // Admin trying to access customer area → redirect to admin dashboard
            return redirect()->route('admin.dashboard')
                ->with('error', 'Anda sedang login sebagai Admin.');
        }

        return $next($request);
    }
}
