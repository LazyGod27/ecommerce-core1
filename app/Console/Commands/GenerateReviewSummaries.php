<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Services\ReviewSummaryService;

class GenerateReviewSummaries extends Command
{
    protected $signature = 'app:generate-review-summaries';
    protected $description = 'Generate or update product review summaries';

    public function handle()
    {
        // Resolve service only when the command actually runs
        $reviewSummaryService = app(ReviewSummaryService::class);

        $products = Product::has('reviews')
            ->where(function($query) {
                $query->whereNull('review_summary')
                      ->orWhere('review_summary_updated_at', '<', now()->subDays(7));
            })
            ->with(['reviews'])
            ->cursor();

        foreach ($products as $product) {
            $summary = $reviewSummaryService->generateSummary($product);

            $this->info("Generated summary for product: {$product->name}");
            $this->line($summary ?? 'No summary generated.');
            $this->line(str_repeat('-', 50));
        }
    }
}
