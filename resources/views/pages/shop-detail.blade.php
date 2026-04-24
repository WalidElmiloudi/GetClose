@extends('layouts.app')
@section('page', strtoupper($shop->name))
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <!-- Shop Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-red-500 to-red-600 h-48 relative">
                @if($shop->logo)
                    <div class="absolute -bottom-16 left-8">
                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}" 
                            class="w-32 h-32 object-cover rounded-full border-4 border-white shadow-lg">
                    </div>
                @endif
            </div>
            <div class="pt-20 pb-8 px-8">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $shop->name }}</h1>
                        <p class="text-lg text-gray-600 mb-4">{{ $shop->description }}</p>
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span>
                                <i class="ph-fill ph-package"></i> 
                                {{ $shop->products()->where('status', 'active')->count() }} Products
                            </span>
                            <span>
                                <i class="ph-fill ph-folder"></i> 
                                {{ $categories->count() }} {{ Str::plural('Category', $categories->count()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form action="{{ route('shops.search', $shop) }}" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl"></i>
                            <input type="text" name="search" value="{{ $searchQuery ?? '' }}" 
                                placeholder="Search products in this shop..."
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="md:w-64">
                        <select name="category_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                    {{ (isset($selectedCategoryId) && $selectedCategoryId == $category->id) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <div class="flex gap-2">
                        <button type="submit" 
                            class="flex-1 md:flex-none bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            <i class="ph-bold ph-magnifying-glass"></i> Search
                        </button>
                        @if(isset($searchQuery) || isset($selectedCategoryId))
                            <a href="{{ route('shops.show', $shop) }}" 
                                class="flex-1 md:flex-none bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors text-center">
                                <i class="ph-bold ph-x"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Active Filters Display -->
            @if(isset($searchQuery) || isset($selectedCategoryId))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-2">Active filters:</p>
                    <div class="flex flex-wrap gap-2">
                        @if(isset($searchQuery))
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                                <i class="ph-bold ph-magnifying-glass"></i>
                                Search: "{{ $searchQuery }}"
                            </span>
                        @endif
                        @if(isset($selectedCategoryId))
                            @php
                                $selectedCat = $categories->firstWhere('id', $selectedCategoryId);
                            @endphp
                            @if($selectedCat)
                                <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                                    <i class="ph-bold ph-folder"></i>
                                    Category: {{ $selectedCat->name }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Category Quick Links -->
        @if($categories->count() > 0)
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Browse by Category</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('shops.show', $shop) }}" 
                        class="px-4 py-2 rounded-full font-semibold transition-colors
                            {{ !isset($selectedCategory) ? 'bg-red-500 text-white' : 'bg-white text-gray-700 hover:bg-red-50 border border-gray-300' }}">
                        All Products
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('shops.category', [$shop, $category]) }}" 
                            class="px-4 py-2 rounded-full font-semibold transition-colors
                                {{ (isset($selectedCategory) && $selectedCategory->id == $category->id) ? 'bg-red-500 text-white' : 'bg-white text-gray-700 hover:bg-red-50 border border-gray-300' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="mb-4">
                <p class="text-gray-600">
                    Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} products
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="block group">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="h-56 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative">
                                @if($product->images && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <i class="ph-fill ph-package text-8xl text-red-500"></i>
                                @endif
                                @if($product->quantity == 0)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <span class="text-white font-bold text-xl">Out of Stock</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                                @if($product->category)
                                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded mb-2">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-red-600">${{ number_format($product->price, 2) }}</span>
                                    @if($product->quantity > 0)
                                        <span class="text-sm text-green-600 font-semibold">In Stock</span>
                                    @else
                                        <span class="text-sm text-red-600 font-semibold">Out of Stock</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @else
            <!-- No Products Message -->
            <div class="text-center py-20 bg-white rounded-xl shadow-lg">
                <i class="ph-fill ph-package text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">
                    @if(isset($searchQuery) || isset($selectedCategoryId))
                        No Products Found
                    @else
                        No Products Available
                    @endif
                </h2>
                <p class="text-gray-500 mb-6">
                    @if(isset($searchQuery) || isset($selectedCategoryId))
                        Try adjusting your search or filters
                    @else
                        This shop doesn't have any products yet. Check back later!
                    @endif
                </p>
                @if(isset($searchQuery) || isset($selectedCategoryId))
                    <a href="{{ route('shops.show', $shop) }}" 
                        class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                        <i class="ph-bold ph-arrow-left"></i> Back to All Products
                    </a>
                @endif
            </div>
        @endif
    </div>
</main>
@endsection