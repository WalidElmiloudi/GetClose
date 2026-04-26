<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Cart\StoreItemsRequest;
use App\Http\Requests\Client\Cart\UpdateItemsRequest;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index():View
    {
        $user = auth()->user();
        $cart = $user->cart()->with('items.product')->first();

        $items = $cart ? $cart->items : collect();

        return view('pages.client.cart',compact('items'));
    }

    public function store(StoreItemsRequest $request)
    {
        $data = $request->validated();

        $cart = Cart::firstOrCreate([
            'client_id'=>auth()->id()
        ]);

        // Check if product already exists in cart
        $existingItem = $cart->items()->where('product_id', $data['product_id'])->first();

        if ($existingItem) {
            // Update quantity instead of creating new item
            $existingItem->update([
                'quantity' => $existingItem->quantity + $data['quantity']
            ]);
        } else {
            // Create new cart item
            $cart->items()->create($data);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }

    public function update(UpdateItemsRequest $request,CartItem $item)
    {
        $data = $request->validated();

        $item->update($data);

        return redirect()->back();
    }

    public function destroy(CartItem $item)
    {
        $item->delete();
        
        return redirect()->back();
    }

    public function getCount(): int
    {
        if (!auth()->check()) {
            return 0;
        }

        $cart = auth()->user()->cart;
        
        if (!$cart) {
            return 0;
        }

        return $cart->items()->sum('quantity');
    }
}
