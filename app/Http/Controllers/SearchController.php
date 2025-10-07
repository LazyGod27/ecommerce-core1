<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    /**
     * Get search suggestions based on query
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = $this->generateSuggestions($query);
        
        return response()->json([
            'suggestions' => $suggestions,
            'query' => $query
        ]);
    }

    /**
     * Enhanced search with category-based recommendations
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return view('products.search', ['products' => collect(), 'query' => '']);
        }

        // Get base search results
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->with(['reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get category-based suggestions
        $suggestions = $this->generateSuggestions($query);
        $categorySuggestions = $this->getCategorySuggestions($query);

        return view('products.search', compact('products', 'query', 'suggestions', 'categorySuggestions'));
    }

    /**
     * Generate intelligent search suggestions
     */
    private function generateSuggestions(string $query): array
    {
        $query = strtolower(trim($query));
        $suggestions = [];

        // Category-based suggestions
        $categoryMappings = [
            'gaming' => [
                'gaming mouse', 'gaming keyboard', 'gaming headset', 'gaming chair', 
                'gaming monitor', 'gaming laptop', 'gaming controller', 'gaming desk',
                'gaming mousepad', 'gaming speakers', 'gaming webcam', 'gaming microphone'
            ],
            'shoes' => [
                'running shoes', 'sneakers', 'basketball shoes', 'dress shoes', 
                'casual shoes', 'boots', 'sandals', 'high heels', 'loafers',
                'athletic shoes', 'formal shoes', 'comfort shoes'
            ],
            'accessories' => [
                'phone case', 'laptop bag', 'watch', 'sunglasses', 'jewelry', 
                'belt', 'wallet', 'backpack', 'handbag', 'earrings', 'necklace',
                'bracelet', 'ring', 'scarf', 'hat', 'cap'
            ],
            'bags' => [
                'handbag', 'backpack', 'laptop bag', 'tote bag', 'crossbody bag',
                'clutch', 'messenger bag', 'duffel bag', 'travel bag', 'shopping bag',
                'gym bag', 'briefcase', 'purse', 'satchel'
            ],
            'clothing' => [
                't-shirt', 'jeans', 'dress', 'shirt', 'pants', 'jacket', 'sweater',
                'hoodie', 'blouse', 'skirt', 'shorts', 'coat', 'blazer', 'cardigan'
            ],
            'electronics' => [
                'smartphone', 'laptop', 'tablet', 'headphones', 'speakers', 'camera',
                'smartwatch', 'charger', 'cable', 'adapter', 'power bank', 'bluetooth'
            ],
            'beauty' => [
                'makeup', 'skincare', 'lipstick', 'foundation', 'mascara', 'eyeshadow',
                'moisturizer', 'serum', 'cleanser', 'toner', 'perfume', 'nail polish'
            ],
            'home' => [
                'furniture', 'decor', 'lamp', 'candle', 'vase', 'picture frame',
                'rug', 'curtains', 'pillow', 'blanket', 'mirror', 'artwork'
            ]
        ];

        // Find matching categories
        foreach ($categoryMappings as $category => $items) {
            if (strpos($category, $query) !== false || strpos($query, $category) !== false) {
                $suggestions = array_merge($suggestions, array_slice($items, 0, 6));
            }
        }

        // Add exact matches from product names
        $productSuggestions = Product::where('name', 'like', "%{$query}%")
            ->limit(5)
            ->pluck('name')
            ->toArray();

        $suggestions = array_merge($suggestions, $productSuggestions);

        // Add category suggestions
        $categorySuggestions = Product::where('category', 'like', "%{$query}%")
            ->distinct()
            ->limit(3)
            ->pluck('category')
            ->map(function($category) {
                return $category . ' products';
            })
            ->toArray();

        $suggestions = array_merge($suggestions, $categorySuggestions);

        // Remove duplicates and limit results
        $suggestions = array_unique($suggestions);
        $suggestions = array_slice($suggestions, 0, 10);

        return $suggestions;
    }

    /**
     * Get category-based suggestions for display
     */
    private function getCategorySuggestions(string $query): array
    {
        $query = strtolower(trim($query));
        
        $categoryData = [
            'gaming' => [
                'title' => 'Gaming Products',
                'icon' => 'ri-gamepad-line',
                'suggestions' => ['Gaming Mouse', 'Gaming Keyboard', 'Gaming Headset', 'Gaming Chair', 'Gaming Monitor']
            ],
            'shoes' => [
                'title' => 'Shoes & Footwear',
                'icon' => 'ri-shopping-bag-line',
                'suggestions' => ['Running Shoes', 'Sneakers', 'Basketball Shoes', 'Dress Shoes', 'Boots']
            ],
            'accessories' => [
                'title' => 'Accessories',
                'icon' => 'ri-shopping-bag-3-line',
                'suggestions' => ['Phone Case', 'Watch', 'Sunglasses', 'Jewelry', 'Belt']
            ],
            'bags' => [
                'title' => 'Bags & Luggage',
                'icon' => 'ri-suitcase-line',
                'suggestions' => ['Handbag', 'Backpack', 'Laptop Bag', 'Tote Bag', 'Crossbody Bag']
            ],
            'clothing' => [
                'title' => 'Clothing & Fashion',
                'icon' => 'ri-shirt-line',
                'suggestions' => ['T-Shirt', 'Jeans', 'Dress', 'Jacket', 'Hoodie']
            ],
            'electronics' => [
                'title' => 'Electronics',
                'icon' => 'ri-smartphone-line',
                'suggestions' => ['Smartphone', 'Laptop', 'Headphones', 'Camera', 'Smartwatch']
            ],
            'beauty' => [
                'title' => 'Beauty & Skincare',
                'icon' => 'ri-makeup-line',
                'suggestions' => ['Makeup', 'Skincare', 'Lipstick', 'Foundation', 'Perfume']
            ],
            'home' => [
                'title' => 'Home & Decor',
                'icon' => 'ri-home-line',
                'suggestions' => ['Furniture', 'Lamp', 'Candle', 'Vase', 'Rug']
            ]
        ];

        $matchedCategories = [];

        foreach ($categoryData as $category => $data) {
            if (strpos($category, $query) !== false || strpos($query, $category) !== false) {
                $matchedCategories[] = $data;
            }
        }

        return $matchedCategories;
    }

    /**
     * Get trending search terms
     */
    public function trending(): JsonResponse
    {
        $trending = [
            'gaming mouse',
            'wireless headphones',
            'smartphone case',
            'running shoes',
            'laptop bag',
            'makeup set',
            'bluetooth speaker',
            'fitness tracker'
        ];

        return response()->json(['trending' => $trending]);
    }

    /**
     * Get popular recommendations
     */
    public function popular(): JsonResponse
    {
        $popular = [
            ['text' => 'Best Sellers', 'icon' => 'ri-trophy-line', 'category' => 'best-sellers'],
            ['text' => 'New Arrivals', 'icon' => 'ri-star-smile-line', 'category' => 'new-arrivals'],
            ['text' => 'Electronics', 'icon' => 'ri-smartphone-line', 'category' => 'electronics'],
            ['text' => 'Fashion', 'icon' => 'ri-shirt-line', 'category' => 'fashion'],
            ['text' => 'Home & Living', 'icon' => 'ri-home-line', 'category' => 'home'],
            ['text' => 'Beauty & Health', 'icon' => 'ri-heart-line', 'category' => 'beauty'],
            ['text' => 'Sports & Outdoor', 'icon' => 'ri-football-line', 'category' => 'sports'],
            ['text' => 'Toys & Games', 'icon' => 'ri-gamepad-line', 'category' => 'toys']
        ];

        return response()->json(['popular' => $popular]);
    }
}
