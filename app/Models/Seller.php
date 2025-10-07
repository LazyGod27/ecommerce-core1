<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seller extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_registration_number',
        'tax_id',
        'phone',
        'email',
        'website',
        'description',
        'logo',
        'banner',
        'status',
        'verification_status',
        'commission_rate',
        'subscription_plan',
        'subscription_expires_at',
        'total_earnings',
        'total_orders',
        'rating',
        'review_count',
        'is_featured',
        'is_verified',
        'bank_account_details',
        'payment_methods',
        'shipping_policies',
        'return_policies',
        'business_address',
        'warehouse_addresses',
        'operating_hours',
        'languages_supported',
        'currency_preference',
        'timezone',
        'notification_preferences',
        'api_credentials',
        'integration_settings'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'total_orders' => 'integer',
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'bank_account_details' => 'array',
        'payment_methods' => 'array',
        'shipping_policies' => 'array',
        'return_policies' => 'array',
        'business_address' => 'array',
        'warehouse_addresses' => 'array',
        'operating_hours' => 'array',
        'languages_supported' => 'array',
        'notification_preferences' => 'array',
        'api_credentials' => 'array',
        'integration_settings' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Earning::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('status', 'active')->latest();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(SellerReview::class);
    }

    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getTotalEarningsAttribute()
    {
        return $this->earnings()->sum('amount');
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}

