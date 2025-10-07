<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');

        if (!$apiKey || !$apiSecret) {
            return response()->json([
                'success' => false,
                'message' => 'API key and secret are required',
                'error' => 'missing_credentials'
            ], 401);
        }

        $key = ApiKey::where('key', $apiKey)->first();

        if (!$key) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API key',
                'error' => 'invalid_key'
            ], 401);
        }

        if (!$key->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'API key is inactive',
                'error' => 'inactive_key'
            ], 401);
        }

        if ($key->expires_at && $key->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'API key has expired',
                'error' => 'expired_key'
            ], 401);
        }

        if (!hash_equals($key->secret, hash('sha256', $apiSecret))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API secret',
                'error' => 'invalid_secret'
            ], 401);
        }

        // Check IP restrictions
        $clientIp = $request->ip();
        if (!$key->isIpAllowed($clientIp)) {
            return response()->json([
                'success' => false,
                'message' => 'IP address not allowed',
                'error' => 'ip_not_allowed'
            ], 403);
        }

        // Check domain restrictions
        $referer = $request->header('Referer');
        if ($referer && !$key->isDomainAllowed(parse_url($referer, PHP_URL_HOST))) {
            return response()->json([
                'success' => false,
                'message' => 'Domain not allowed',
                'error' => 'domain_not_allowed'
            ], 403);
        }

        // Check permission
        if ($permission && !$key->hasPermission($permission)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient permissions',
                'error' => 'insufficient_permissions'
            ], 403);
        }

        // Record usage
        $key->recordUsage();

        // Add API key info to request
        $request->merge(['api_key' => $key]);

        return $next($request);
    }
}