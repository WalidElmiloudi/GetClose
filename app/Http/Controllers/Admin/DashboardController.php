<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalShops = Shop::count();
        $pendingShops = Shop::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $recentOrders = Order::with('client')->latest()->take(10)->get();
        $recentShops = Shop::with('owner')->latest()->take(10)->get();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('pages.admin.dashboard', compact(
            'totalUsers',
            'totalShops',
            'pendingShops',
            'totalProducts',
            'totalOrders',
            'recentOrders',
            'recentShops',
            'totalRevenue'
        ));
    }
}
