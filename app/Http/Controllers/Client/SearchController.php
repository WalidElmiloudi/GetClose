<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Search products with filters
     */
    public function index(Request $request): View
    {
        $query = Product::query()->where('status', 'active');

        // Search by keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by shop
        if ($request->filled('shop')) {
            $query->where('shop_id', $request->shop);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by minimum rating
        if ($request->filled('min_rating')) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->selectRaw('product_id, AVG(rating) as avg_rating')
                  ->groupBy('product_id')
                  ->having('avg_rating', '>=', $request->min_rating);
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'popular':
                $query->withCount('reviews')->orderByDesc('reviews_count');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();
        
        // Get data for filters
        $categories = Category::all();
        $shops = Shop::where('status', 'approved')->get();

        return view('pages.search', compact(
            'products',
            'categories',
            'shops'
        ));
    }
}
