<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return view('pages.vendor.create-shop');
        }

        // Get shop statistics
        $totalProducts = Product::where('shop_id', $shop->id)->count();
        $activeProducts = Product::where('shop_id', $shop->id)->where('status', 'active')->count();
        $totalOrders = Order::whereHas('items', function($query) use ($shop) {
            $query->whereHas('product', function($q) use ($shop) {
                $q->where('shop_id', $shop->id);
            });
        })->count();
        
        $totalRevenue = Order::where('status', 'completed')
            ->whereHas('items', function($query) use ($shop) {
                $query->whereHas('product', function($q) use ($shop) {
                    $q->where('shop_id', $shop->id);
                });
            })
            ->sum('total_price');

        $recentOrders = Order::whereHas('items', function($query) use ($shop) {
                $query->whereHas('product', function($q) use ($shop) {
                    $q->where('shop_id', $shop->id);
                });
            })
            ->with('client')
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::where('shop_id', $shop->id)
            ->where('quantity', '<=', 5)
            ->where('status', 'active')
            ->get();

        return view('pages.vendor.dashboard', compact(
            'shop',
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
