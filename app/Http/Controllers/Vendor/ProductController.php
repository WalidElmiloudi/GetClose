<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index()
    {
        $shop = auth()->user()->shop;
        
        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        if ($shop->status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('error', 'Your shop is currently ' . $shop->status . '. Please wait for admin approval before managing products.');
        }

        $products = Product::where('shop_id', $shop->id)
            ->with('category')
            ->latest()
            ->paginate(20);

        return view('pages.vendor.products', compact('products'));
    }

    public function create(): View
    {
        $shop = auth()->user()->shop;
        
        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        if ($shop->status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('error', 'Your shop is currently ' . $shop->status . '. Please wait for admin approval before adding products.');
        }

        $categories = Category::all();
        
        return view('pages.vendor.products-create', compact('shop', 'categories'));
    }

    public function store(Request $request)
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        if ($shop->status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('error', 'Your shop is currently ' . $shop->status . '. Please wait for admin approval before adding products.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'quantity', 'category_id']);
        $data['shop_id'] = $shop->id;
        $data['status'] = 'active';

        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                if ($path) {
                    $images[] = $path;
                }
            }
            $data['images'] = !empty($images) ? $images : null;
        } else {
            $data['images'] = null;
        }

        Product::create($data);

        return redirect()->route('vendor.products')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $shop = auth()->user()->shop;
        
        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        if ($shop->status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('error', 'Your shop is currently ' . $shop->status . '. Please wait for admin approval before editing products.');
        }

        if ($product->shop_id !== $shop->id) {
            abort(403);
        }

        $categories = Category::all();
        return view('pages.vendor.products-edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $shop = auth()->user()->shop;
        
        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        if ($shop->status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('error', 'Your shop is currently ' . $shop->status . '. Please wait for admin approval before updating products.');
        }

        if ($product->shop_id !== $shop->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['name', 'description', 'price', 'quantity', 'category_id', 'status']);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $images = $product->images ?? [];
            if (!is_array($images)) {
                $images = [];
            }
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                if ($path) {
                    $images[] = $path;
                }
            }
            $data['images'] = !empty($images) ? $images : null;
        }

        $product->update($data);

        return redirect()->route('vendor.products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $shop = auth()->user()->shop;
        
        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        if ($shop->status !== 'approved') {
            return redirect()->route('vendor.dashboard')->with('error', 'Your shop is currently ' . $shop->status . '. Please wait for admin approval before deleting products.');
        }

        if ($product->shop_id !== $shop->id) {
            abort(403);
        }

        $product->delete();
        return back()->with('success', 'Product deleted successfully.');
    }
}
