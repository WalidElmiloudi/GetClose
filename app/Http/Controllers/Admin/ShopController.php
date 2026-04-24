<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::with('owner')->latest()->paginate(20);
        return view('pages.admin.shops', compact('shops'));
    }

    public function pending(): View
    {
        $shops = Shop::with('owner')->where('status', 'pending')->latest()->paginate(20);
        return view('pages.admin.shops-pending', compact('shops'));
    }

    public function approve(Shop $shop)
    {
        $shop->update(['status' => 'approved']);
        return back()->with('success', 'Shop approved successfully.');
    }

    public function reject(Shop $shop, Request $request)
    {
        $shop->update(['status' => 'rejected']);
        return back()->with('success', 'Shop rejected.');
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();
        return back()->with('success', 'Shop deleted successfully.');
    }
}
