@extends('layouts.app')
@section('page', 'ADMIN DASHBOARD')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Admin Dashboard</h1>
            <p class="text-lg text-gray-600">Manage your e-commerce platform</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Users</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $totalUsers }}</p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-users text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Shops</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $totalShops }}</p>
                        @if($pendingShops > 0)
                            <a href="{{ route('admin.shops.pending') }}" class="text-sm text-red-500 hover:text-red-700">
                                {{ $pendingShops }} pending →
                            </a>
                        @endif
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-storefront text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total Products</p>
                        <p class="text-4xl font-bold text-gray-800">{{ $totalProducts }}</p>
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
                        <p class="text-sm text-green-600 font-semibold">Revenue: ${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-shopping-cart text-3xl text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('admin.shops.pending') }}" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                <div class="flex items-center gap-4">
                    <i class="ph-fill ph-check-circle text-4xl"></i>
                    <div>
                        <h3 class="text-xl font-bold">Approve Shops</h3>
                        <p class="text-red-100">{{ $pendingShops }} pending approval</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.users') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                <div class="flex items-center gap-4">
                    <i class="ph-fill ph-users text-4xl"></i>
                    <div>
                        <h3 class="text-xl font-bold">Manage Users</h3>
                        <p class="text-purple-100">{{ $totalUsers }} total users</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.orders') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                <div class="flex items-center gap-4">
                    <i class="ph-fill ph-receipt text-4xl"></i>
                    <div>
                        <h3 class="text-xl font-bold">Manage Orders</h3>
                        <p class="text-blue-100">View all orders</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.disputes') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-xl shadow-lg p-6 block transition-all">
                <div class="flex items-center gap-4">
                    <i class="ph-fill ph-warning text-4xl"></i>
                    <div>
                        <h3 class="text-xl font-bold">Resolve Disputes</h3>
                        <p class="text-yellow-100">Handle customer issues</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Recent Orders</h2>
                <a href="{{ route('admin.orders') }}" class="text-red-500 hover:text-red-700 font-semibold">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentOrders as $order)
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Shops -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Recent Shops</h2>
                <a href="{{ route('admin.shops') }}" class="text-red-500 hover:text-red-700 font-semibold">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentShops as $shop)
                            <tr>
                                <td class="px-6 py-4 font-semibold">{{ $shop->name }}</td>
                                <td class="px-6 py-4">{{ $shop->owner->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($shop->status == 'approved') bg-green-100 text-green-700
                                        @elseif($shop->status == 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700
                                        @endif">
                                        {{ ucfirst($shop->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
