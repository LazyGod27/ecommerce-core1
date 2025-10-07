<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerMiddleware
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

        if (!$request->user()->isSeller()) {
            return response()->json(['error' => 'Access denied. Seller account required.'], 403);
        }

        $seller = $request->user()->seller;
        
        if ($seller->status !== 'active') {
            return response()->json(['error' => 'Seller account is not active'], 403);
        }

        return $next($request);
    }
}