<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReviewSummaryService
{
    protected $apiKey;
    protected $apiEndpoint;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiEndpoint = config('services.openai.endpoint', 'https://api.openai.com/v1/chat/completions');
    }

    public function generateSummary(Product $product): string
    {
        try {
            $reviews = $product->reviews()->approved()->get();
            
            if ($reviews->isEmpty()) {
                return 'No reviews available for this product yet.';
            }

            $reviewTexts = $reviews->map(function ($review) {
                return "Rating: {$review->rating}/5 - {$review->comment}";
            })->join("\n");

            $prompt = $this->buildPrompt($product->name, $reviewTexts);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiEndpoint, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that summarizes product reviews. Provide concise, helpful summaries that highlight key points from customer feedback.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 200,
                'temperature' => 0.7
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Unable to generate summary.';
            }

            throw new \Exception('Failed to generate review summary: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Review summary generation failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            // Fallback to manual summary
            return $this->generateFallbackSummary($product);
        }
    }

    private function buildPrompt(string $productName, string $reviewTexts): string
    {
        return "Please provide a concise summary of customer reviews for the product '{$productName}'. Focus on the most common themes, overall sentiment, and key feedback points. Keep it under 100 words.\n\nReviews:\n{$reviewTexts}";
    }

    private function generateFallbackSummary(Product $product): string
    {
        $reviews = $product->reviews()->approved()->get();
        
        if ($reviews->isEmpty()) {
            return 'No reviews available for this product yet.';
        }

        $avgRating = $reviews->avg('rating');
        $totalReviews = $reviews->count();
        $positiveReviews = $reviews->where('rating', '>=', 4)->count();
        $negativeReviews = $reviews->where('rating', '<=', 2)->count();

        $sentiment = $positiveReviews > $negativeReviews ? 'positive' : 
                    ($negativeReviews > $positiveReviews ? 'negative' : 'mixed');

        return "Based on {$totalReviews} reviews with an average rating of {$avgRating}/5. Overall customer sentiment is {$sentiment}. " .
               "{$positiveReviews} customers gave positive feedback, while {$negativeReviews} had concerns.";
    }

    public function generateDetailedAnalysis(Product $product): array
    {
        try {
            $reviews = $product->reviews()->approved()->get();
            
            if ($reviews->isEmpty()) {
                return [
                    'summary' => 'No reviews available',
                    'sentiment' => 'neutral',
                    'key_themes' => [],
                    'recommendations' => []
                ];
            }

            $prompt = $this->buildDetailedPrompt($product->name, $reviews);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->apiEndpoint, [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a product analyst. Analyze customer reviews and provide structured insights including sentiment analysis, key themes, and actionable recommendations.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.5
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                
                return $this->parseDetailedResponse($content);
            }

            throw new \Exception('Failed to generate detailed analysis: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('Detailed review analysis failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);

            return $this->generateFallbackDetailedAnalysis($product);
        }
    }

    private function buildDetailedPrompt(string $productName, $reviews): string
    {
        $reviewTexts = $reviews->map(function ($review) {
            return "Rating: {$review->rating}/5 - {$review->comment}";
        })->join("\n");

        return "Analyze customer reviews for '{$productName}' and provide:\n" .
               "1. Overall sentiment (positive/negative/mixed)\n" .
               "2. Key themes mentioned by customers\n" .
               "3. Actionable recommendations for improvement\n" .
               "4. Summary of strengths and weaknesses\n\n" .
               "Reviews:\n{$reviewTexts}";
    }

    private function parseDetailedResponse(string $content): array
    {
        // Simple parsing - in production you might want more sophisticated parsing
        return [
            'summary' => $content,
            'sentiment' => $this->extractSentiment($content),
            'key_themes' => $this->extractThemes($content),
            'recommendations' => $this->extractRecommendations($content)
        ];
    }

    private function extractSentiment(string $content): string
    {
        $content = strtolower($content);
        if (strpos($content, 'positive') !== false) return 'positive';
        if (strpos($content, 'negative') !== false) return 'negative';
        return 'mixed';
    }

    private function extractThemes(string $content): array
    {
        // Simple theme extraction - could be enhanced with NLP
        $themes = ['quality', 'price', 'design', 'performance', 'customer service'];
        $foundThemes = [];
        
        foreach ($themes as $theme) {
            if (strpos(strtolower($content), $theme) !== false) {
                $foundThemes[] = $theme;
            }
        }
        
        return $foundThemes;
    }

    private function extractRecommendations(string $content): array
    {
        // Simple recommendation extraction
        $recommendations = [];
        $lines = explode("\n", $content);
        
        foreach ($lines as $line) {
            if (strpos(strtolower($line), 'recommend') !== false || 
                strpos(strtolower($line), 'improve') !== false ||
                strpos(strtolower($line), 'should') !== false) {
                $recommendations[] = trim($line);
            }
        }
        
        return array_slice($recommendations, 0, 3); // Limit to 3 recommendations
    }

    private function generateFallbackDetailedAnalysis(Product $product): array
    {
        $reviews = $product->reviews()->approved()->get();
        $avgRating = $reviews->avg('rating');
        
        return [
            'summary' => "Product has {$reviews->count()} reviews with average rating {$avgRating}/5",
            'sentiment' => $avgRating >= 4 ? 'positive' : ($avgRating <= 2 ? 'negative' : 'mixed'),
            'key_themes' => ['overall satisfaction', 'product quality'],
            'recommendations' => ['Continue monitoring customer feedback', 'Address any recurring concerns']
        ];
    }
}