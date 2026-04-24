@extends('layouts.app')
@section('page', 'SEARCH')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Search Products</h1>
            <p class="text-lg text-gray-600">{{ $products->total() }} products found</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <form action="{{ route('search') }}" method="GET" class="bg-white rounded-xl shadow-lg p-6 space-y-6 sticky top-40">
                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Search</h3>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Category</h3>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Shop</h3>
                        <select name="shop" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">All Shops</option>
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}" {{ request('shop') == $shop->id ? 'selected' : '' }}>
                                    {{ $shop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Price Range</h3>
                        <div class="space-y-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-800 mb-3">Minimum Rating</h3>
                        <select name="min_rating" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Any Rating</option>
                            <option value="4" {{ request('min_rating') == 4 ? 'selected' : '' }}>4+ Stars</option>
                            <option value="3" {{ request('min_rating') == 3 ? 'selected' : '' }}>3+ Stars</option>
                            <option value="2" {{ request('min_rating') == 2 ? 'selected' : '' }}>2+ Stars</option>
                            <option value="1" {{ request('min_rating') == 1 ? 'selected' : '' }}>1+ Stars</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-lg transition-colors">
                        Apply Filters
                    </button>
                    
                    @if(request()->anyFilled(['q', 'category', 'shop', 'min_price', 'max_price', 'min_rating']))
                        <a href="{{ route('search') }}" class="block text-center text-gray-600 hover:text-red-500 font-semibold">
                            Clear All Filters
                        </a>
                    @endif
                </form>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Sort Options -->
                <div class="bg-white rounded-xl shadow-lg p-4 mb-6 flex items-center justify-between">
                    <span class="text-gray-600">Sort by:</span>
                    <form action="{{ route('search') }}" method="GET" class="flex gap-2">
                        @foreach(request()->except('sort', 'page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </form>
                </div>

                <!-- Products -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($products as $product)
                            <a href="{{ route('products.show', $product) }}" class="block group">
                                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                    <div class="h-64 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative overflow-hidden">
                                        @if($product->images && count($product->images) > 0)
                                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
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
                                        <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                                        
                                        <!-- Rating -->
                                        @if($product->reviews->count() > 0)
                                            <div class="flex items-center gap-2 mb-3">
                                                <div class="flex items-center">
                                                    @php $avgRating = $product->reviews->avg('rating'); @endphp
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="ph-fill ph-star text-sm {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-500">({{ $product->reviews->count() }})</span>
                                            </div>
                                        @endif
                                        
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl font-bold text-red-600">${{ number_format($product->price, 2) }}</span>
                                            <span class="text-sm text-gray-500">{{ $product->shop->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                        <i class="ph-fill ph-magnifying-glass text-8xl text-gray-300 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-600 mb-2">No Products Found</h2>
                        <p class="text-gray-500 mb-6">Try adjusting your search filters or browse all products</p>
                        <a href="{{ route('products') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                            Browse All Products
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
