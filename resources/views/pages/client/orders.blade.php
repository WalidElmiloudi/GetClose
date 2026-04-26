@extends('layouts.app')
@section('page', 'ORDERS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">My Orders</h1>
            <p class="text-lg text-gray-600">Track and manage your orders</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Order #{{ $order->id }}</h3>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y - h:i A') }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('orders.show', $order) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                        Track Order
                                    </a>
                                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                                        @if($order->status == 'completed') bg-green-100 text-green-700
                                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                        @else bg-blue-100 text-blue-700
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    @if($order->status == 'pending')
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200"
                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($order->items as $item)
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
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-gray-800">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                            <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-4 mb-4">
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
                                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                    <span class="text-xl font-bold text-gray-800">Total</span>
                                    <span class="text-2xl font-bold text-red-600">${{ number_format($order->total_price, 2) }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-xl shadow-lg">
                <i class="ph-fill ph-receipt text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Orders Yet</h2>
                <p class="text-gray-500 mb-6">Start shopping to see your orders here!</p>
                <a href="{{ route('products') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</main>
@endsection
