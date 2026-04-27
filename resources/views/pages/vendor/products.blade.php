@extends('layouts.app')
@section('page', 'MY PRODUCTS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">My Products</h1>
                <p class="text-lg text-gray-600">Manage your shop's products</p>
            </div>
            <a href="{{ route('vendor.products.create') }}" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                <i class="ph-bold ph-plus"></i> Add Product
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($products->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 font-semibold">{{ $product->name }}</td>
                                <td class="px-6 py-4">{{ $product->category->name }}</td>
                                <td class="px-6 py-4 font-bold text-red-600">MAD {{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 {{ $product->quantity <= 5 ? 'text-red-600 font-bold' : '' }}">{{ $product->quantity }}</td>
                                <td class="px-6 py-4 capitalize">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($product->status == 'active') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="{{ route('vendor.products.edit', $product) }}" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</a>
                                    <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold"
                                            onclick="return confirm('Delete this product?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                <i class="ph-fill ph-package text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Products Yet</h2>
                <p class="text-gray-500 mb-6">Start adding products to your shop!</p>
                <a href="{{ route('vendor.products.create') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                    Add Your First Product
                </a>
            </div>
        @endif
    </div>
</main>
@endsection
