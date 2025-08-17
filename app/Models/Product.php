<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use App\Services\ReviewSummaryService;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'review_summary',
        'review_summary_updated_at',
        'average_rating',
        'review_count',
        'positive_review_count',
        'negative_review_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'average_rating' => 'decimal:1',
        'review_count' => 'integer',
        'positive_review_count' => 'integer',
        'negative_review_count' => 'integer',
        'review_summary_updated_at' => 'datetime'
    ];

    protected $appends = [
        'rating_distribution',
        'in_stock'
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function updateSummary(): void
    {
        $summaryService = app(ReviewSummaryService::class);
        
        // Update all review statistics
        $this->updateRatingStats();
        
        // Generate AI summary
        $this->review_summary = $summaryService->generateSummary($this);
        $this->review_summary_updated_at = now();
        $this->save();
        
        // Clear any cached product data
        Cache::forget("product_{$this->id}_reviews");
        Cache::forget("product_{$this->id}_summary");
    }

    public function updateRatingStats(): void
    {
        $reviews = $this->reviews()->approved()->get();
        
        $this->review_count = $reviews->count();
        $this->average_rating = $reviews->avg('rating') ?? 0;
        $this->positive_review_count = $reviews->where('rating', '>=', 4)->count();
        $this->negative_review_count = $reviews->where('rating', '<=', 2)->count();
        
        $this->save();
    }

    public function getRatingDistributionAttribute(): array
    {
        return Cache::remember("product_{$this->id}_rating_distribution", 3600, function() {
            return $this->reviews()
                ->approved()
                ->selectRaw('rating, count(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->pluck('count', 'rating')
                ->toArray();
        });
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function scopePopular($query)
    {
        return $query->orderBy('review_count', 'desc');
    }

    public function scopeHighlyRated($query, $minRating = 4)
    {
        return $query->where('average_rating', '>=', $minRating)
                    ->where('review_count', '>', 5);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    protected static function booted()
    {
        static::updated(function ($product) {
            if ($product->isDirty('stock')) {
                // Trigger stock change events if needed
            }
        });
    }
}