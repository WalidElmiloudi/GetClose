@extends('layouts.app')
@section('page', 'ORDER DETAILS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('vendor.orders') }}" class="text-red-600 hover:text-red-700 font-semibold mb-4 inline-block">
                ← Back to Orders
            </a>
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Order #{{ $order->id }}</h1>
            <p class="text-lg text-gray-600">Order placed on {{ $order->created_at->format('M d, Y - h:i A') }}</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Customer Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-semibold text-gray-800">{{ $order->client->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-gray-800">{{ $order->client->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Shipping Address</h2>
                    <div class="space-y-2">
                        <p class="text-gray-800">{{ $order->shipping_address }}</p>
                        <p class="text-gray-800">{{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
                        @if($order->shippingMethod)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500">Shipping Method</p>
                                <p class="font-semibold text-gray-800">{{ $order->shippingMethod->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items (Only vendor's products) -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Products in This Order</h2>
                    <div class="space-y-4">
                        @php
                            $vendorItems = $order->items->filter(function($item) {
                                return $item->product->shop_id === auth()->user()->shop->id;
                            });
                        @endphp
                        
                        @if($vendorItems->count() > 0)
                            @foreach($vendorItems as $item)
                                <div class="flex items-center gap-4 pb-4 border-b border-gray-100 last:border-0">
                                    <div class="h-20 w-20 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($item->product->images && count($item->product->images) > 0)
                                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="ph-fill ph-package text-3xl text-red-500"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-800">{{ $item->product->name }}</h4>
                                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-800">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                        <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center py-8">No products from your shop in this order.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Status -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Order Status</h2>
                    <form action="{{ route('vendor.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 mb-4">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="partialy_shipped" {{ $order->status == 'partialy_shipped' ? 'selected' : '' }}>Partially Shipped</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-lg transition-colors duration-200">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Payment Information</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-semibold text-gray-800">
                                @if($order->payment_method == 'cash')
                                    <i class="ph-fill ph-money text-green-600"></i> Cash on Delivery
                                @elseif($order->payment_method == 'stripe')
                                    <i class="ph-fill ph-credit-card text-blue-600"></i> Credit Card (Stripe)
                                @else
                                    {{ ucfirst($order->payment_method) }}
                                @endif
                            </p>
                        </div>
                        @if($order->payment)
                            <div>
                                <p class="text-sm text-gray-500">Payment Status</p>
                                <p class="font-semibold">
                                    @if($order->payment->status == 'completed')
                                        <span class="text-green-600"><i class="ph-fill ph-check-circle"></i> Paid</span>
                                    @elseif($order->payment->status == 'pending')
                                        <span class="text-yellow-600"><i class="ph-fill ph-clock"></i> Pending</span>
                                    @else
                                        <span class="text-gray-600">{{ ucfirst($order->payment->status) }}</span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Total -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Order Summary</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>Order Total</span>
                            <span>${{ number_format($order->total_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span>${{ number_format($order->shipping_price, 2) }}</span>
                        </div>
                        @php
                            $vendorTotal = $vendorItems->sum(function($item) {
                                return $item->price * $item->quantity;
                            });
                        @endphp
                        <div class="border-t border-gray-200 pt-2 flex justify-between text-xl font-bold text-gray-800">
                            <span>Your Products Total</span>
                            <span class="text-red-600">${{ number_format($vendorTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
