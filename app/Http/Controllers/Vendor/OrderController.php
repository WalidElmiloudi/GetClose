<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $shop = auth()->user()->shop;

        if (!$shop) {
            return redirect()->route('vendor.dashboard')->with('error', 'Create a shop first.');
        }

        $orders = Order::whereHas('items', function($query) use ($shop) {
                $query->whereHas('product', function($q) use ($shop) {
                    $q->where('shop_id', $shop->id);
                });
            })
            ->with('client')
            ->latest()
            ->paginate(20);

        return view('pages.vendor.orders', compact('orders'));
    }

    public function show(Order $order): View
    {
        $shop = auth()->user()->shop;

        // Verify order belongs to vendor's shop
        $hasShopProducts = $order->items()->whereHas('product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->exists();

        if (!$hasShopProducts) {
            abort(403);
        }

        $order->load(['client', 'items.product']);

        return view('pages.vendor.order-detail', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $shop = auth()->user()->shop;

        // Verify order belongs to vendor's shop
        $hasShopProducts = $order->items()->whereHas('product', function($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->exists();

        if (!$hasShopProducts) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,paid,processing,partialy_shipped,shipped,completed,cancelled,refunded'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
