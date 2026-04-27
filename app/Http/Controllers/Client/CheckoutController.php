<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payement;
use App\Models\ShippingMethod;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
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
            'payment_method' => 'required|string|in:cash,stripe',
            'stripe_payment_intent_id' => 'nullable|string',
        ]);

        $cart = Cart::where('client_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->count() == 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shippingMethod = ShippingMethod::find($request->shipping_method_id);

        // Calculate totals
        $subtotal = $cart->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $shippingPrice = $shippingMethod->price;
        $totalPrice = $subtotal + $shippingPrice;

        // Handle Stripe payment
        if ($request->payment_method === 'stripe') {
            if (!$request->stripe_payment_intent_id) {
                return back()->with('error', 'Payment not completed. Please complete the Stripe payment.');
            }

            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $paymentIntent = PaymentIntent::retrieve($request->stripe_payment_intent_id);
                
                if ($paymentIntent->status !== 'succeeded') {
                    return back()->with('error', 'Payment not successful. Please try again.');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Payment verification failed. Please try again.');
            }
        }

        try {
            DB::beginTransaction();

            // Create order
            $orderStatus = $request->payment_method === 'cash' ? 'pending' : 'paid';
            
            $order = Order::create([
                'client_id' => auth()->id(),
                'total_price' => $totalPrice,
                'shipping_price' => $shippingPrice,
                'status' => $orderStatus,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_zip' => $request->shipping_zip,
                'payment_method' => $request->payment_method,
                'shipping_method_id' => $shippingMethod->id,
            ]);

            // Create payment record for Stripe
            if ($request->payment_method === 'stripe' && $request->stripe_payment_intent_id) {
                Payement::create([
                    'order_id' => $order->id,
                    'amount' => $totalPrice,
                    'status' => 'completed',
                    'payment_method' => 'stripe',
                    'transaction_id' => $request->stripe_payment_intent_id,
                ]);
            }

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

            // Send notifications
            $notificationService = new NotificationService();
            $notificationService->notifyOrderPlaced($order);
            $notificationService->notifyAdminNewOrder($order);
            
            // Notify vendors whose products are in the order
            $vendorIds = $order->items->pluck('product.shop.owner_id')->unique();
            foreach ($vendorIds as $vendorId) {
                $notificationService->notifyVendorNewOrder($vendorId, $order);
            }

            return redirect()->route('orders.confirmation', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process order. Please try again.');
        }
    }

    /**
     * Create Stripe Payment Intent
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $paymentIntent = PaymentIntent::create([
                'amount' => intval($request->amount * 100), // Convert to cents
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
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

        $order->load(['items.product', 'shippingMethod', 'payment']);

        return view('pages.client.checkout-success', compact('order'));
    }
}
