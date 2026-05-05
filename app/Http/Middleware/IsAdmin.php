<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ❌ Not logged in
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', '⚠ Please login first');
        }

        $user = auth()->user();

        // ❌ No role column (safety check)
        if (!isset($user->role)) {
            abort(403, '⚠ Role not defined for this user');
        }

        // ❌ Not admin
        if ($user->role !== 'admin') {

            // 🔥 If API request → return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized (Admin only)'
                ], 403);
            }

            // 🔁 Redirect with message
            return redirect()->route('dashboard')
                ->with('error', '⛔ Access denied! Admin only');
        }

        // ✅ Allow access
        return $next($request);
    }
}