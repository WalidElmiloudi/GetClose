<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\VendorPayoutService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('client')->latest()->paginate(20);
        return view('pages.admin.orders', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['client', 'items.product.shop', 'payment', 'shippingMethod', 'refunds']);
        return view('pages.admin.order-detail', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $oldStatus = $order->status;
        
        $request->validate([
            'status' => 'required|in:pending,paid,processing,partialy_shipped,shipped,completed,cancelled,refunded'
        ]);

        $order->update(['status' => $request->status]);

        // Process vendor earnings if order is now paid or completed
        if (in_array($request->status, ['paid', 'completed'])) {
            try {
                $payoutService = new VendorPayoutService();
                $payoutService->processOrderPayment($order);
            } catch (\Exception $e) {
                \Log::error("Failed to process vendor payout: " . $e->getMessage());
                // Don't fail the request, payout can be retried
            }
        }

        // Process refund if order is refunded
        if ($request->status === 'refunded' && $oldStatus !== 'refunded') {
            try {
                $refundAmount = $order->total_price - $order->refunded_amount;
                $payoutService = new VendorPayoutService();
                $payoutService->processRefund($order, $refundAmount, 'full');
            } catch (\Exception $e) {
                \Log::error("Failed to process vendor refund: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Order status updated.');
    }
}
