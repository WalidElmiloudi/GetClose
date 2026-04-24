<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Shop;
use App\Http\Requests\Vendor\Shop\StoreCategoryRequest;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Shop $shop): View
    {
        $categories = $shop->categories()->get();
        return view('pages.categories', compact('categories'));
    }

    public function store(StoreCategoryRequest $request) 
    {
        $user = auth()->user();
        $shop = $user->shop;
        
        if (!$shop) {
            return redirect()->back()->with('error', 'You need to create a shop first.');
        }
        
        $data = $request->validated();
        $data['shop_id'] = $shop->id;
        Category::create($data);
        return redirect()->back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back();
    }
}
