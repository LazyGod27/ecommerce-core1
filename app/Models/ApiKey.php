<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'name',
        'key',
        'secret',
        'type',
        'permissions',
        'allowed_ips',
        'allowed_domains',
        'is_active',
        'last_used_at',
        'usage_count',
        'expires_at',
        'description'
    ];

    protected $casts = [
        'permissions' => 'array',
        'allowed_ips' => 'array',
        'allowed_domains' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    protected $hidden = [
        'secret'
    ];

    /**
     * Generate a new API key
     */
    public static function generate($name, $type = 'external', $permissions = [], $expiresAt = null)
    {
        $key = 'ek_' . Str::random(32);
        $secret = Str::random(64);

        return self::create([
            'name' => $name,
            'key' => $key,
            'secret' => hash('sha256', $secret),
            'type' => $type,
            'permissions' => $permissions,
            'expires_at' => $expiresAt
        ]);
    }

    /**
     * Verify API key and secret
     */
    public static function verify($key, $secret)
    {
        $apiKey = self::where('key', $key)
                     ->where('is_active', true)
                     ->first();

        if (!$apiKey) {
            return false;
        }

        if ($apiKey->expires_at && $apiKey->expires_at->isPast()) {
            return false;
        }

        return hash_equals($apiKey->secret, hash('sha256', $secret));
    }

    /**
     * Record API key usage
     */
    public function recordUsage()
    {
        $this->update([
            'last_used_at' => now(),
            'usage_count' => $this->usage_count + 1
        ]);
    }

    /**
     * Check if API key has permission
     */
    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions) || in_array('*', $this->permissions);
    }

    /**
     * Check if IP is allowed
     */
    public function isIpAllowed($ip)
    {
        if (!$this->allowed_ips) {
            return true;
        }

        return in_array($ip, $this->allowed_ips);
    }

    /**
     * Check if domain is allowed
     */
    public function isDomainAllowed($domain)
    {
        if (!$this->allowed_domains) {
            return true;
        }

        return in_array($domain, $this->allowed_domains);
    }

    /**
     * Scope for active keys
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for external keys
     */
    public function scopeExternal($query)
    {
        return $query->where('type', 'external');
    }

    /**
     * Scope for non-expired keys
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}


























