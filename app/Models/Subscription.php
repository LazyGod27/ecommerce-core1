<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'seller_id',
        'plan_id',
        'plan_name',
        'plan_type',
        'price',
        'currency',
        'billing_cycle',
        'status',
        'start_date',
        'end_date',
        'trial_ends_at',
        'cancelled_at',
        'features',
        'limits',
        'usage',
        'auto_renew',
        'payment_method',
        'next_billing_date',
        'last_payment_date',
        'payment_history',
        'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'next_billing_date' => 'datetime',
        'last_payment_date' => 'datetime',
        'features' => 'array',
        'limits' => 'array',
        'usage' => 'array',
        'auto_renew' => 'boolean',
        'payment_history' => 'array'
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeTrial($query)
    {
        return $query->where('status', 'trial');
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date > now();
    }

    public function isExpired()
    {
        return $this->end_date < now();
    }

    public function isTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at > now();
    }

    public function canUseFeature($feature)
    {
        if (!isset($this->features[$feature])) {
            return false;
        }

        if (!isset($this->limits[$feature])) {
            return true;
        }

        $currentUsage = $this->usage[$feature] ?? 0;
        return $currentUsage < $this->limits[$feature];
    }

    public function incrementUsage($feature, $amount = 1)
    {
        $currentUsage = $this->usage[$feature] ?? 0;
        $this->usage = array_merge($this->usage ?? [], [$feature => $currentUsage + $amount]);
        $this->save();
    }
}

