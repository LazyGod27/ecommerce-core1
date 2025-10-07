<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (!$request->user()->isAdmin()) {
            return response()->json(['error' => 'Access denied. Admin account required.'], 403);
        }

        $admin = $request->user()->admin;
        
        if (!$admin->is_active) {
            return response()->json(['error' => 'Admin account is not active'], 403);
        }

        return $next($request);
    }
}