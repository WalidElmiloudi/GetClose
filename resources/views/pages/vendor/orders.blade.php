@extends('layouts.app')
@section('page', 'MY ORDERS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">My Orders</h1>
            <p class="text-lg text-gray-600">Manage orders for your shop</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 font-semibold">#{{ $order->id }}</td>
                                <td class="px-6 py-4">{{ $order->client->name }}</td>
                                <td class="px-6 py-4 font-bold text-red-600">${{ number_format($order->total_price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($order->status == 'completed') bg-green-100 text-green-700
                                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                        @elseif($order->status == 'paid') bg-blue-100 text-blue-700
                                        @elseif($order->status == 'processing') bg-purple-100 text-purple-700
                                        @elseif($order->status == 'refunded') bg-gray-100 text-gray-700
                                        @else bg-blue-100 text-blue-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('vendor.orders.show', $order) }}" class="text-blue-500 hover:text-blue-700 font-semibold">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">{{ $orders->links() }}</div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                <i class="ph-fill ph-shopping-bag text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Orders Yet</h2>
                <p class="text-gray-500">Orders will appear here when customers purchase your products.</p>
            </div>
        @endif
    </div>
</main>
@endsection
