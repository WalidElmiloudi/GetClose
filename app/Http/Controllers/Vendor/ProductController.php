<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Shop\StoreProductRequest;
use App\Http\Requests\Vendor\Shop\UpdateProductRequest;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
  public function index(): View
  {
    $products = Product::all();
    return view('pages.products', compact('products'));
  }

  public function productShop(Shop $shop)
  {
    $products = $shop->products();
  }

  public function store(StoreProductRequest $request)
  {
    $shop = auth()->user()->shop();

    $data = $request->validated();
    $data['shop_id'] = $shop->id();

    $paths = [];

    if ($request->hasFile('images')) {
      foreach (($request->file('images')) as $image) {
        $paths[] = $image->store('products', 'public');
      }
    }

    $data['images'] = $paths;

    Product::create($data);

    return redirect()->back();
  }

  public function update(UpdateProductRequest $request, Product $product)
  {
      $data = $request->validated();
      $product->update($data);
      return redirect()->back();
  }

  public function destroy(Product $product)
  {
     $product->delete();
     return redirect()->back();
  }
}
