<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Shop\StoreShopRequest;
use App\Models\Shop;
use App\Models\Category;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::where('status', 'approved')
            ->withCount('products')
            ->latest()
            ->paginate(12);
        return view('pages.shops', compact('shops'));
    }

    public function show(Shop $shop): View
    {
        // Only show approved shops
        if ($shop->status !== 'approved') {
            abort(404);
        }

        $shop->load(['categories', 'products.category']);
        
        // Get all products from this shop
        $products = $shop->products()
            ->where('status', 'active')
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = $shop->categories;

        return view('pages.shop-detail', compact('shop', 'products', 'categories'));
    }

    public function showByCategory(Shop $shop, Category $category): View
    {
        // Only show approved shops
        if ($shop->status !== 'approved') {
            abort(404);
        }

        // Verify category belongs to this shop
        if ($category->shop_id !== $shop->id) {
            abort(404);
        }

        $shop->load(['categories']);
        
        // Get products filtered by category
        $products = $shop->products()
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->with('category')
            ->latest()
            ->paginate(12);

        $categories = $shop->categories;
        $selectedCategory = $category;

        return view('pages.shop-detail', compact('shop', 'products', 'categories', 'selectedCategory'));
    }

    public function search(Shop $shop, Request $request): View
    {
        // Only show approved shops
        if ($shop->status !== 'approved') {
            abort(404);
        }

        $shop->load(['categories']);
        
        $query = $shop->products()
            ->where('status', 'active')
            ->with('category');

        // Search by product name or description
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by category if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->latest()->paginate(12);
        $categories = $shop->categories;
        $searchQuery = $request->input('search');
        $selectedCategoryId = $request->input('category_id');

        return view('pages.shop-detail', compact('shop', 'products', 'categories', 'searchQuery', 'selectedCategoryId'));
    }

    public function vendorShop()
    {
        $shop = auth()->user()->shop;
        
        if (!$shop) {
            return view('pages.vendor.create-shop');
        }
        
        $shop->load('products', 'categories');
        
        return view('pages.vendor.dashboard', compact('shop'));
    }

    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = auth()->id();
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shops', 'public');
            if ($path) {
                $data['logo'] = $path;
            }
        }
        
        Shop::create($data);
        
        return redirect()->route('vendor.dashboard')->with('status', 'Shop created successfully!');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->back();
    }
}
