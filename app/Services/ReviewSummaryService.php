<?php

namespace App\Services;

use App\Models\Product;

class ReviewSummaryService
{
    public function generateSummary(Product $product)
    {
        $reviews = $product->reviews()
            ->select('rating', 'comment')
            ->whereNotNull('comment')
            ->get();
            
        if ($reviews->isEmpty()) {
            return null;
        }

        // Check if API key exists
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            // ✅ Fallback: Local summarization
            $comments = $reviews->pluck('comment')->toArray();
            $avgRating = number_format($reviews->avg('rating'), 1);
            $totalReviews = count($comments);

            // Take up to 3 example comments
            $examples = array_slice($comments, 0, 3);
            $summary = "Average rating: {$avgRating} ({$totalReviews} reviews)\n"
                     . "Some feedback:\n- " . implode("\n- ", $examples);

            // Update product
            $product->update([
                'review_summary' => $summary,
                'review_summary_updated_at' => now()
            ]);

            return $summary;
        }

        // ✅ OpenAI API mode
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant that summarizes product reviews.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Summarize these product reviews in 3-5 bullet points:\n\n" .
                            $reviews->pluck('comment')->implode("\n\n")
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 200
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        $product->update([
            'review_summary' => $result['choices'][0]['message']['content'],
            'review_summary_updated_at' => now()
        ]);

        return $result['choices'][0]['message']['content'];
    }
}
