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
        
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->with(['reviews'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('products.search', compact('products', 'query'));
    }

    public function category($category)
    {
        // Map category slugs to actual category names
        $categoryMap = [
            'gaming' => 'Gaming Products',
            'accessories' => 'Accessories',
            'clothing' => 'Clothing & Fashion',
            'beauty' => 'Beauty & Skincare',
            'jeans' => 'Jeans Collection',
            'make-up' => 'Makeup & Cosmetics'
        ];
        
        $categoryName = $categoryMap[$category] ?? ucfirst($category);
        
        // For now, we'll use the dynamic JavaScript generation
        // In a real application, you'd filter products by category from the database
        $products = collect(); // Empty collection since we're using JS generation
        
        return view('products.category', compact('products', 'categoryName', 'category'));
    }
}
