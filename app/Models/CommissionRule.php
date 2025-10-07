<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionRule extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'commission_rate',
        'min_amount',
        'max_amount',
        'subscription_plan_id',
        'priority',
        'is_active',
        'effective_from',
        'effective_to'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'effective_from' => 'datetime',
        'effective_to' => 'datetime'
    ];

    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeEffective($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('effective_from')
              ->orWhere('effective_from', '<=', now());
        })->where(function ($q) {
            $q->whereNull('effective_to')
              ->orWhere('effective_to', '>=', now());
        });
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category)
                    ->orWhere('category', '*');
    }

    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }
}