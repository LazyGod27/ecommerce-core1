<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return view('products.search', ['products' => collect(), 'query' => '']);
        }
        
        // Enhanced search with category-based logic
        $products = Product::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->with(['reviews'])
            ->orderByRaw("
                CASE 
                    WHEN name LIKE ? THEN 1
                    WHEN category LIKE ? THEN 2
                    WHEN description LIKE ? THEN 3
                    ELSE 4
                END
            ", ["%{$query}%", "%{$query}%", "%{$query}%"])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('products.search', compact('products', 'query'));
    }

    public function category($category)
    {
        // Map category slugs to actual category names in database
        $categoryMap = [
            'gaming' => 'gaming',
            'electronics' => 'electronics',
            'fashion' => 'fashion',
            'beauty' => 'beauty',
            'sports' => 'sports',
            'toys' => 'toys',
            'home' => 'home',
            'groceries' => 'groceries',
            'jeans' => 'jeans',
            'make-up' => 'make-up',
            'accessories' => 'accessories',
            'best-sellers' => 'best',
            'new-arrivals' => 'new'
        ];
        
        $actualCategory = $categoryMap[$category] ?? $category;
        $categoryName = ucfirst(str_replace('-', ' ', $category));
        
        // Filter products by category from database
        $products = Product::where('category', $actualCategory)
            ->with(['reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        return view('products.category', compact('products', 'categoryName', 'category'));
    }
}
