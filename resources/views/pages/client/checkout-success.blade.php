@extends('layouts.app')
@section('page', 'ORDER CONFIRMED')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-3xl mx-auto">
        <!-- Success Message -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8 text-center">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ph-fill ph-check-circle text-6xl text-green-500"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Order Confirmed!</h1>
            <p class="text-xl text-gray-600 mb-2">Thank you for your purchase!</p>
            <p class="text-gray-500">Order #{{ $order->id }}</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Details</h2>
            
            <!-- Items -->
            <div class="space-y-4 mb-6">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 pb-4 border-b border-gray-200">
                        <div class="w-20 h-20 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($item->product->images && count($item->product->images) > 0)
                                <img src="{{ asset('storage/' . $item->product->images[0]) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i class="ph-fill ph-package text-3xl text-red-500"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-xl font-bold text-red-600">MAD {{ number_format($item->price * $item->quantity, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="border-t border-gray-200 pt-4 space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>MAD {{ number_format($order->total_price - $order->shipping_price, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span>MAD {{ number_format($order->shipping_price, 2) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex justify-between text-2xl font-bold text-gray-800">
                    <span>Total</span>
                    <span class="text-red-600">MAD {{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="ph-fill ph-map-pin text-red-500"></i> Shipping Address
                </h3>
                <div class="text-gray-600 space-y-1">
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="ph-fill ph-truck text-red-500"></i> Shipping Method
                </h3>
                <div class="text-gray-600 space-y-1">
                    <p class="font-semibold">{{ $order->shippingMethod->name }}</p>
                    @if($order->shippingMethod->estimated_days)
                        <p>Estimated delivery: {{ $order->shippingMethod->estimated_days }} days</p>
                    @endif
                    <p class="text-red-600 font-bold">MAD {{ number_format($order->shipping_price, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="ph-fill ph-credit-card text-red-500"></i> Payment Method
            </h3>
            <div class="text-gray-600">
                @if($order->payment_method == 'cash')
                    <p class="flex items-center gap-2">
                        <i class="ph-fill ph-money text-2xl text-green-600"></i>
                        Cash on Delivery
                    </p>
                @elseif($order->payment_method == 'card')
                    <p class="flex items-center gap-2">
                        <i class="ph-fill ph-credit-card text-2xl text-blue-600"></i>
                        Credit/Debit Card
                    </p>
                @else
                    <p class="flex items-center gap-2">
                        <i class="ph-fill ph-paypal-logo text-2xl text-indigo-600"></i>
                        PayPal
                    </p>
                @endif
            </div>
        </div>

        <!-- Order Status -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Order Status</h3>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                    <i class="ph-fill ph-clock mr-2"></i>
                    {{ ucfirst($order->status) }}
                </span>
                <span class="text-gray-500">Placed on {{ $order->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('orders') }}" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors text-center text-lg">
                View My Orders
            </a>
            <a href="{{ route('products') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg transition-colors text-center text-lg">
                Continue Shopping
            </a>
        </div>
    </div>
</main>
@endsection
