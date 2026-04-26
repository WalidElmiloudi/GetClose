<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $orders = $user->orders()->with(['items.product.shop', 'payment'])->latest()->get();

        return view('pages.client.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->client_id !== auth()->id()) {
            abort(403);
        }
        
        $order->load(['items.product', 'shippingMethod']);
        return view('pages.client.order-tracking', compact('order'));
    }

    public function checkout()
    {
        $cart = Cart::with('items.product')->where('client_id', auth()->id());

        DB::transaction(function () use ($cart) {

            $order = Order::create([
                'client_id' => auth()->id(),
                'total_price' => 0
            ]);

            $total = 0;

            foreach ($cart->items as $item) {

                $price = $item->product->price;

                $order->items()->create([
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'product_id' => $item->product->id
                ]);

                $total += $price * $item->quantity;

            }

            $order->update([
                'total_price' => $total
            ]);

            $cart->items()->delete();
        });

        return redirect()->back();
    }

    public function cancel(Order $order)
    {
        if ($order->client_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status != 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $order->update(['status' => 'cancelled']);
        
        // Restore product stock
        foreach ($order->items as $item) {
            $item->product->increment('quantity', $item->quantity);
        }

        return back()->with('success', 'Order cancelled successfully.');
    }
}
