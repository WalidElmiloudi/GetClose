<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display all products (public listing)
     */
    public function index(): View
    {
        $products = Product::where('status', 'active')
            ->with(['shop.owner', 'category'])
            ->latest()
            ->paginate(20);

        return view('pages.products', compact('products'));
    }

    /**
     * Display product detail page
     */
    public function show(Product $product): View
    {
        $product->load(['shop.owner', 'category', 'reviews.user']);
        
        // Calculate average rating
        $averageRating = $product->reviews()->avg('rating');
        $ratingBreakdown = [
            5 => $product->reviews()->where('rating', 5)->count(),
            4 => $product->reviews()->where('rating', 4)->count(),
            3 => $product->reviews()->where('rating', 3)->count(),
            2 => $product->reviews()->where('rating', 2)->count(),
            1 => $product->reviews()->where('rating', 1)->count(),
        ];
        
        // Get related products from same shop
        $relatedProducts = Product::where('shop_id', $product->shop_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();
        
        return view('pages.products.show', compact(
            'product',
            'averageRating',
            'ratingBreakdown',
            'relatedProducts'
        ));
    }

    /**
     * Store a new review
     */
    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
