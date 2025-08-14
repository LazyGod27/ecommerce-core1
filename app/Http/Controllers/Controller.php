<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function search()
    {
        return view('products.search');
    }
    
}
