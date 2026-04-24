<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Shop\StoreShopRequest;
use App\Models\Shop;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::all();
        return view('pages.shops',compact('shops'));
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
            $data['logo'] = $request->file('logo')->store('shops', 'public');
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
