<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // For the new frontend, we'll provide some sample data
        // In a real application, you'd fetch this from your database
        $featuredProducts = Product::with(['reviews'])
            ->orderBy('average_rating', 'desc')
            ->take(8)
            ->get();
            
        $newProducts = Product::latest()->take(8)->get();
        
        return view('home', compact('featuredProducts', 'newProducts'));
    }
}
