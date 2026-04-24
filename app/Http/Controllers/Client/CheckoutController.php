<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index(): View
    {
        $cart = Cart::where('client_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->count() == 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shippingMethods = ShippingMethod::where('is_active', true)->get();
        
        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return view('pages.client.checkout', compact('cart', 'shippingMethods', 'subtotal'));
    }

    /**
     * Process checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_zip' => 'required|string|max:20',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'payment_method' => 'required|string|in:cash,card,paypal',
        ]);

        $cart = Cart::where('client_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->count() == 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shippingMethod = ShippingMethod::find($request->shipping_method_id);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cart->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            $shippingPrice = $shippingMethod->price;
            $totalPrice = $subtotal + $shippingPrice;

            // Create order
            $order = Order::create([
                'client_id' => auth()->id(),
                'total_price' => $totalPrice,
                'shipping_price' => $shippingPrice,
                'status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_zip' => $request->shipping_zip,
                'payment_method' => $request->payment_method,
                'shipping_method_id' => $shippingMethod->id,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                // Reduce product stock
                $item->product->decrement('quantity', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.confirmation', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation(Order $order): View
    {
        // Verify order belongs to user
        if ($order->client_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.product', 'shippingMethod']);

        return view('pages.client.checkout-success', compact('order'));
    }
}
