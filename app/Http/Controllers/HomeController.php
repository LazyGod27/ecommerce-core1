<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['reviews'])
            ->orderBy('average_rating', 'desc')
            ->take(8)
            ->get();
            
        $newProducts = Product::latest()->take(8)->get();
        
        return view('home', compact('featuredProducts', 'newProducts'));
    }
}
