<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products and shops
     */
    public function index(): View
    {
        // Get 5 latest active products
        $featuredProducts = Product::where('status', 'active')
            ->with(['shop.owner', 'category'])
            ->latest()
            ->take(5)
            ->get();

        // Get 5 latest approved shops
        $featuredShops = Shop::where('status', 'approved')
            ->withCount('products')
            ->latest()
            ->take(5)
            ->get();

        return view('welcome', compact('featuredProducts', 'featuredShops'));
    }
}
