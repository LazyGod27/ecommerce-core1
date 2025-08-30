<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageSearchController extends Controller
{
    public function searchByImage(Request $request)
    {
        $request->validate([
            'image_search' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
        ]);

        try {
            $imageFile = $request->file('image_search');
            
            // Analyze the image using OpenAI CLIP
            $imageDescription = $this->analyzeImage($imageFile);
            
            // Search products based on image description
            $products = $this->searchProductsByDescription($imageDescription);
            
            return response()->json([
                'success' => true,
                'image_description' => $imageDescription,
                'products' => $products,
                'message' => 'Image search completed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Image search failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Image search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    private function analyzeImage($imageFile)
    {
        try {
            // Check if OpenAI API key is configured
            $openaiKey = config('services.openai.key');
            if (!$openaiKey) {
                throw new \Exception('OpenAI API key not configured');
            }

            // Convert image to base64
            $imageData = base64_encode(file_get_contents($imageFile));
            
            // Use OpenAI's GPT-4 Vision to analyze the image
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $openaiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-vision-preview',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Analyze this image and provide a detailed description of what you see. Focus on objects, colors, style, and any identifiable products. Be specific about the type of items, materials, and visual characteristics. This description will be used to search for similar products in an e-commerce store.'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:image/jpeg;base64,' . $imageData
                                ]
                            ]
                        ]
                    ]
                ],
                'max_tokens' => 300
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return $result['choices'][0]['message']['content'] ?? 'Product image';
            } else {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Image analysis failed: ' . $e->getMessage());
            throw new \Exception('Image analysis failed: ' . $e->getMessage());
        }
    }

    private function searchProductsByDescription($description)
    {
        try {
            // Extract key terms from the description
            $keywords = $this->extractKeywords($description);
            
            // Search products using the keywords
            $query = Product::query();
            
            foreach ($keywords as $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%')
                      ->orWhere('description', 'like', '%' . $keyword . '%')
                      ->orWhere('category', 'like', '%' . $keyword . '%');
                });
            }
            
            $products = $query->with(['reviews'])
                            ->orderBy('created_at', 'desc')
                            ->limit(20)
                            ->get();
            
            // If no products found with exact keywords, try broader search
            if ($products->isEmpty()) {
                $products = $this->fallbackSearch($description);
            }
            
            return $products;
            
        } catch (\Exception $e) {
            Log::error('Product search by description failed: ' . $e->getMessage());
            return collect();
        }
    }

    private function extractKeywords($description)
    {
        // Convert to lowercase and remove common words
        $text = strtolower($description);
        
        // Remove common stop words
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'must', 'can', 'this', 'that', 'these', 'those'];
        
        foreach ($stopWords as $word) {
            $text = str_replace(' ' . $word . ' ', ' ', $text);
        }
        
        // Extract words (3+ characters)
        preg_match_all('/\b[a-z]{3,}\b/', $text, $matches);
        
        // Get unique words and limit to top 10
        $keywords = array_unique($matches[0]);
        $keywords = array_slice($keywords, 0, 10);
        
        return $keywords;
    }

    private function fallbackSearch($description)
    {
        // Fallback: search for any products that might be related
        $query = Product::query();
        
        // Split description into words and search for any matches
        $words = explode(' ', strtolower($description));
        $words = array_filter($words, function($word) {
            return strlen($word) > 2;
        });
        
        foreach ($words as $word) {
            $query->orWhere('name', 'like', '%' . $word . '%')
                  ->orWhere('description', 'like', '%' . $word . '%')
                  ->orWhere('category', 'like', '%' . $word . '%');
        }
        
        return $query->with(['reviews'])
                    ->orderBy('created_at', 'desc')
                    ->limit(15)
                    ->get();
    }
}
