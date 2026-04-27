@extends('layouts.app')
@section('page', 'PRODUCTS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Discover Products You'll Love</h1>
            <p class="text-lg text-gray-600">Browse through our amazing collection of products from local vendors</p>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @php
                        $isInCart = $cartHelper && $cartHelper->isInCart($product->id);
                        $cartItem = $isInCart ? $cartHelper->getCartItem($product->id) : null;
                    @endphp
                    
                    <a href="{{ route('products.show', $product) }}" class="block group">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 relative">
                        @if($isInCart)
                            <div class="absolute top-3 right-3 z-10">
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    <i class="ph-fill ph-check-circle"></i> In Cart ({{ $cartItem->quantity }})
                                </span>
                            </div>
                        @endif
                        
                        <div class="h-56 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative">
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="ph-fill ph-package text-8xl text-red-500"></i>
                            @endif
                            @if($product->status == 'inactive')
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">Out of Stock</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $product->description }}</p>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-red-600">MAD {{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">{{ $product->quantity }} left</span>
                            </div>
                            @if($product->status == 'active' && $product->quantity > 0)
                                @if(auth()->check())
                                    @if(auth()->user()->role === 'client')
                                        @if($isInCart)
                                            <!-- Product in cart - Show Remove Button -->
                                            <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" class="mt-3" onclick="event.stopPropagation()">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                                    <i class="ph-bold ph-trash"></i> Remove from Cart
                                                </button>
                                            </form>
                                        @else
                                            <!-- Product not in cart - Show Add to Cart Button -->
                                            <form action="{{ route('cart.store') }}" method="POST" class="mt-3" onclick="event.stopPropagation()">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <input type="hidden" name="price" value="{{ $product->price }}">
                                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                                    <i class="ph-bold ph-shopping-cart"></i> Add to Cart
                                                </button>
                                            </form>
                                        @endif
                                    @elseif(auth()->user()->role === 'vendor')
                                        <button disabled class="w-full bg-blue-100 text-blue-600 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                            <i class="ph-bold ph-storefront"></i> Vendor Account
                                        </button>
                                    @elseif(auth()->user()->role === 'admin')
                                        <button disabled class="w-full bg-purple-100 text-purple-600 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                            <i class="ph-bold ph-shield"></i> Admin Account
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-center block">
                                        <i class="ph-bold ph-sign-in"></i> Login to Add
                                    </a>
                                @endif
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-600 font-semibold py-2 px-4 rounded-lg cursor-not-allowed">
                                    Unavailable
                                </button>
                            @endif
                        </div>
                    </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <i class="ph-fill ph-package text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Products Available</h2>
                <p class="text-gray-500">Check back later for new products!</p>
            </div>
        @endif
    </div>
</main>
@endsection
