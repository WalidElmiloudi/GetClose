@extends('layouts.app')
@section('page', 'VENDOR DASHBOARD')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">{{ $shop->name }}</h1>
            <p class="text-lg text-gray-600">Manage your shop and track performance</p>
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

        @if($shop->status === 'pending')
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ph-fill ph-warning text-4xl text-yellow-400"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-yellow-800">Shop Pending Approval</h3>
                        <p class="text-yellow-700 mt-1">
                            Your shop is currently under review. You will be able to add products once an admin approves your shop.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($shop->status === 'refused')
            <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ph-fill ph-x-circle text-4xl text-red-400"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-bold text-red-800">Shop Application Rejected</h3>
                        <p class="text-red-700 mt-1">
                            Unfortunately, your shop application has been rejected. Please contact support for more information.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Products</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $totalProducts }}</p>
                        <p class="text-sm text-green-600">{{ $activeProducts }} active</p>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-package text-3xl text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Orders</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $totalOrders }}</p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-shopping-bag text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Revenue</p>
                        <p class="text-4xl font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-currency-dollar text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Low Stock Items</p>
                        <p class="text-4xl font-bold text-red-600">{{ $lowStockProducts->count() }}</p>
                    </div>
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-warning text-3xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @if($shop->status === 'approved')
                <a href="{{ route('vendor.products.create') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-plus-circle text-4xl"></i>
                        <div>
                            <h3 class="text-xl font-bold">Add Product</h3>
                            <p class="text-purple-100">List a new item</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('vendor.products') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-list text-4xl"></i>
                        <div>
                            <h3 class="text-xl font-bold">Manage Products</h3>
                            <p class="text-blue-100">View all products</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('vendor.orders') }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                    <div class="flex items-center gap-4">
                        <i class="ph-fill ph-receipt text-4xl"></i>
                        <div>
                            <h3 class="text-xl font-bold">View Orders</h3>
                            <p class="text-green-100">Manage orders</p>
                        </div>
                    </div>
                </a>
            @else
                <div class="col-span-3 bg-gray-100 rounded-xl p-6 text-center">
                    <i class="ph-fill ph-lock text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-600 font-semibold">Product management is disabled until your shop is approved</p>
                </div>
            @endif
        </div>

        @if($lowStockProducts->count() > 0)
            <!-- Low Stock Alert -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 mb-8">
                <h3 class="text-xl font-bold text-yellow-800 mb-4">
                    <i class="ph-fill ph-warning"></i> Low Stock Alert
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($lowStockProducts as $product)
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="font-semibold text-gray-800">{{ $product->name }}</h4>
                            <p class="text-red-600 font-bold">{{ $product->quantity }} left in stock</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Recent Orders</h2>
                <a href="{{ route('vendor.orders') }}" class="text-red-500 hover:text-red-700 font-semibold">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 font-semibold">#{{ $order->id }}</td>
                                <td class="px-6 py-4">{{ $order->client->name }}</td>
                                <td class="px-6 py-4 font-bold text-red-600">${{ number_format($order->total_price, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($order->status == 'completed') bg-green-100 text-green-700
                                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-700
                                        @else bg-blue-100 text-blue-700
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No orders yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
