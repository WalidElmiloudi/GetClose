<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShippingMethodController extends Controller
{
    public function index(): View
    {
        $shippingMethods = ShippingMethod::latest()->get();
        return view('pages.admin.shipping-methods', compact('shippingMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
        ]);

        ShippingMethod::create($request->all());
        return back()->with('success', 'Shipping method created.');
    }

    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $shippingMethod->update($request->all());
        return back()->with('success', 'Shipping method updated.');
    }

    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();
        return back()->with('success', 'Shipping method deleted.');
    }
}
