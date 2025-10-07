<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'cover_image',
        'category',
        'subcategory',
        'status',
        'is_featured',
        'is_verified',
        'rating',
        'review_count',
        'total_products',
        'total_sales',
        'total_earnings',
        'followers_count',
        'social_links',
        'contact_info',
        'shipping_info',
        'return_policy',
        'warranty_policy',
        'custom_fields',
        'seo_settings',
        'theme_settings',
        'layout_settings',
        'analytics_settings',
        'integration_settings'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'review_count' => 'integer',
        'total_products' => 'integer',
        'total_sales' => 'integer',
        'total_earnings' => 'decimal:2',
        'followers_count' => 'integer',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'social_links' => 'array',
        'contact_info' => 'array',
        'shipping_info' => 'array',
        'return_policy' => 'array',
        'warranty_policy' => 'array',
        'custom_fields' => 'array',
        'seo_settings' => 'array',
        'theme_settings' => 'array',
        'layout_settings' => 'array',
        'analytics_settings' => 'array',
        'integration_settings' => 'array'
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ShopReview::class);
    }

    public function followers(): HasMany
    {
        return $this->hasMany(ShopFollower::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ShopCategory::class);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(ShopPromotion::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getTotalProductsAttribute()
    {
        return $this->products()->count();
    }

    public function getTotalSalesAttribute()
    {
        return $this->orders()->where('status', 'completed')->count();
    }

    public function getTotalEarningsAttribute()
    {
        return $this->orders()->where('status', 'completed')->sum('total');
    }
}

