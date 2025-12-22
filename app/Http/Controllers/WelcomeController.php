<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories = Category::with([
            'products' => function ($query) {
                $query->where('is_active', true);
            }
        ])->get();

        $featuredProducts = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('welcome', compact('categories', 'featuredProducts'));
    }
}
