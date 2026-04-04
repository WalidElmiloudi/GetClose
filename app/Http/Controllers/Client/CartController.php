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
        $cart = auth()->user()->cart();

        $items = $cart->items();

        return view('pages.client.cart',compact('items'));
    }

    public function store(StoreItemsRequest $request)
    {
        $data = $request->validated();

        $cart = Cart::firstOrCreate([
            'client_id'=>auth()->id()
        ]);
        $cart->items()->create($data);

        return redirect()->back();
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
}
