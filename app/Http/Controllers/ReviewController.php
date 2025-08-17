<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ReviewController extends Controller
{
    public function getProductReviews($productId)
    {
        $reviews = Review::where('product_id', $productId)->get();

        // Cache summary per product for 6 hours
        $summary = Cache::remember("product_{$productId}_summary", now()->addHours(6), function () use ($reviews) {
            return $this->generateAISummary($reviews);
        });

        return response()->json([
            'reviews'        => $reviews,
            'summary'        => $summary,
            'average_rating' => $reviews->avg('rating'),
        ]);
    }

    private function generateAISummary($reviews)
    {
        if ($reviews->isEmpty()) {
            return null;
        }

        $text = $reviews->pluck('comment')->implode("\n");

        try {
            // HuggingFace summarization
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.huggingface.key'),
                'Accept'        => 'application/json',
            ])->post('https://api-inference.huggingface.co/models/facebook/bart-large-cnn', [
                'inputs' => $text,
            ]);

            if ($response->successful() && isset($response->json()[0]['summary_text'])) {
                return $response->json()[0]['summary_text'];
            }

            // Fallback if HuggingFace fails
            return $this->simpleFallbackSummary($text);

        } catch (\Exception $e) {
            Log::error('AI Summary API failed: ' . $e->getMessage());
            return $this->simpleFallbackSummary($text);
        }
    }

    private function simpleFallbackSummary($text, $maxSentences = 3)
    {
        // Split into sentences
        $sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        if (count($sentences) <= $maxSentences) {
            return implode(' ', $sentences);
        }

        // Count word frequency
        $words = str_word_count(strtolower($text), 1);
        $freq = array_count_values($words);

        // Score each sentence
        $scores = [];
        foreach ($sentences as $i => $sentence) {
            $sentenceWords = str_word_count(strtolower($sentence), 1);
            $score = 0;
            foreach ($sentenceWords as $word) {
                if (isset($freq[$word])) {
                    $score += $freq[$word];
                }
            }
            $scores[$i] = $score;
        }

        // Sort sentences by score
        arsort($scores);
        $topSentences = array_slice(array_keys($scores), 0, $maxSentences);

        // Keep original order
        sort($topSentences);
        $summary = [];
        foreach ($topSentences as $i) {
            $summary[] = $sentences[$i];
        }

        return implode(' ', $summary);
    }
}
