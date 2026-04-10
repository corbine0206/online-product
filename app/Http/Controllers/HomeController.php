<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->with('images')
            ->take(8)
            ->get();

        $latestProducts = Product::where('is_active', true)
            ->with('images')
            ->latest()
            ->take(12)
            ->get();

        return view('home', compact('featuredProducts', 'latestProducts'));
    }
}
