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

        $order = $user->orders();

        return view('pages.client.orders', compact('orders'));
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
        $order->update([
            'status'=>'cancelled'
        ]);
        return redirect()->back();
    }
}
