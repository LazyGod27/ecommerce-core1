<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'role',
        'permissions',
        'department',
        'access_level',
        'is_super_admin',
        'is_active',
        'last_login_at',
        'login_count',
        'preferences',
        'assigned_sellers',
        'assigned_categories',
        'assigned_regions',
        'notification_settings',
        'dashboard_settings',
        'api_access',
        'audit_log_settings'
    ];

    protected $casts = [
        'is_super_admin' => 'boolean',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'login_count' => 'integer',
        'permissions' => 'array',
        'preferences' => 'array',
        'assigned_sellers' => 'array',
        'assigned_categories' => 'array',
        'assigned_regions' => 'array',
        'notification_settings' => 'array',
        'dashboard_settings' => 'array',
        'api_access' => 'array',
        'audit_log_settings' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function platformSettings(): HasMany
    {
        return $this->hasMany(PlatformSetting::class);
    }

    public function commissionRules(): HasMany
    {
        return $this->hasMany(CommissionRule::class);
    }

    public function subscriptionPlans(): HasMany
    {
        return $this->hasMany(SubscriptionPlan::class);
    }

    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSuperAdmin($query)
    {
        return $query->where('is_super_admin', true);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function hasPermission($permission)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function canAccessSeller($sellerId)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return in_array($sellerId, $this->assigned_sellers ?? []);
    }

    public function canAccessCategory($categoryId)
    {
        if ($this->is_super_admin) {
            return true;
        }

        return in_array($categoryId, $this->assigned_categories ?? []);
    }
}

