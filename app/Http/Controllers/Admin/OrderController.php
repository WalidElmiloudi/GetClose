<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('client')->latest()->paginate(20);
        return view('pages.admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,partialy_shipped,shipped,completed,cancelled,refunded'
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }
}
