<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Validate request parameters
        $validator = Validator::make($request->all(), [
            'search' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0|gt:min_price',
            'sort' => 'sometimes|string|in:name,price,created_at,updated_at',
            'direction' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Product::query();
        
        // Eager load relationships if needed
        $query->with(['category', 'reviews']); // Example relationships
        
        // Search by name or description
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('description', 'like', '%'.$searchTerm.'%');
            });
        }
        
        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Price range filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // In stock filter
        if ($request->boolean('in_stock')) {
            $query->where('in_stock', true);
        }
        
        // Sorting with fallback to multiple columns
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        
        // More sophisticated sorting logic
        if ($sort === 'popularity') {
            $query->withCount('orders')->orderBy('orders_count', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }
        
        // Pagination with configurable items per page
        $perPage = $request->input('per_page', 10);
        
        return response()->json([
            'data' => $query->paginate($perPage)
        ]);
    }
}