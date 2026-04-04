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

    public function vendorShop(User $user)
    {
        $shop = $user->shop();
        return view('pages.vendor.dashboard',compact('shop'));
    }

    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();
        $data['owner_id'] = auth()->id();
        Shop::create($data);
        return redirect()->back();
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return redirect()->back();
    }
}
