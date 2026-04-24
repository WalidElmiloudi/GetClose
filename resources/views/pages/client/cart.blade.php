@extends('layouts.app')
@section('page', 'CART')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Shopping Cart</h1>
            <p class="text-lg text-gray-600">Review your items before checkout</p>
        </div>

        @if($items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex gap-4">
                                <div class="h-32 w-32 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                    @if($item->product->images && count($item->product->images) > 0)
                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <i class="ph-fill ph-package text-5xl text-red-500"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->product->name }}</h3>
                                    <p class="text-gray-600 text-sm mb-3">{{ $item->product->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold text-red-600">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                        <span class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-200 flex items-center justify-between">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <label class="text-sm text-gray-600">Quantity:</label>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->quantity }}" 
                                        class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                        Update
                                    </button>
                                </form>
                                
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">
                                        <i class="ph-bold ph-trash"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-40">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span class="text-green-600">Free</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3 flex justify-between text-xl font-bold text-gray-800">
                                <span>Total</span>
                                <span class="text-red-600">${{ number_format($items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('checkout') }}" class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 text-lg text-center">
                            Proceed to Checkout
                        </a>
                        
                        <a href="{{ route('products') }}" class="block mt-4 text-center text-red-500 hover:text-red-700 font-semibold">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-xl shadow-lg">
                <i class="ph-fill ph-shopping-cart text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">Your Cart is Empty</h2>
                <p class="text-gray-500 mb-6">Start adding some products to your cart!</p>
                <a href="{{ route('products') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</main>
@endsection
