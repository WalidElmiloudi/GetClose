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
        $categories = $shop->categories();
        return view('pages.catgories', compact('categories'));
    }

    public function store(StoreCategoryRequest $request) 
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['shop_id'] = $user->shop()->id();
        Category::create($data);
        return redirect()->back();
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back();
    }
}
