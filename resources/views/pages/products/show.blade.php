@extends('layouts.app')
@section('page', $product->name)
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
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

        <!-- Product Detail -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                <!-- Product Images -->
                <div>
                    @if($product->images && count($product->images) > 0)
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden mb-4">
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover" id="mainImage">
                        </div>
                        @if(count($product->images) > 1)
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($product->images as $index => $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" 
                                        class="aspect-square object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                        onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $image) }}'">
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="aspect-square bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center">
                            <i class="ph-fill ph-package text-9xl text-red-500"></i>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div>
                    <div class="mb-4">
                        <a href="{{ route('shops.show', $product->shop) }}" class="text-red-500 hover:text-red-700 font-semibold">
                            {{ $product->shop->name }}
                        </a>
                        <span class="text-gray-400 mx-2">|</span>
                        <span class="text-gray-600">{{ $product->category->name }}</span>
                    </div>
                    
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="ph-fill ph-star text-2xl {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <span class="text-lg font-semibold text-gray-700">{{ number_format($averageRating, 1) }}</span>
                        <span class="text-gray-500">({{ $product->reviews->count() }} reviews)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-red-600">${{ number_format($product->price, 2) }}</span>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>

                    <!-- Stock Status -->
                    <div class="mb-6">
                        @if($product->status == 'active' && $product->quantity > 0)
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">
                                <i class="ph-fill ph-check-circle mr-2"></i>
                                In Stock ({{ $product->quantity }} available)
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">
                                <i class="ph-fill ph-x-circle mr-2"></i>
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    <!-- Add to Cart (Only for clients) -->
                    @if($product->status == 'active' && $product->quantity > 0)
                        @if(auth()->check() && auth()->user()->role === 'client')
                            <form action="{{ route('cart.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                
                                <div class="flex items-center gap-4">
                                    <label class="font-semibold text-gray-700">Quantity:</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}" 
                                        class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                </div>
                                
                                <button type="submit" 
                                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-8 rounded-lg transition-colors duration-200 text-lg">
                                    <i class="ph-bold ph-shopping-cart"></i> Add to Cart
                                </button>
                            </form>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <p class="text-blue-700 font-semibold">
                                    <i class="ph-fill ph-info"></i> 
                                    @if(auth()->user()->role === 'vendor')
                                        Vendors cannot purchase products. Go to your <a href="{{ route('vendor.dashboard') }}" class="underline hover:text-blue-900">Dashboard</a> to manage your shop.
                                    @else
                                        Administrators cannot purchase products. Go to your <a href="{{ route('admin.dashboard') }}" class="underline hover:text-blue-900">Admin Panel</a> to manage the platform.
                                    @endif
                                </p>
                            </div>
                        @endif
                    @else
                        <button disabled class="w-full bg-gray-300 text-gray-600 font-bold py-4 px-8 rounded-lg cursor-not-allowed text-lg">
                            Unavailable
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Customer Reviews</h2>
            
            <!-- Rating Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 pb-8 border-b border-gray-200">
                <div class="text-center">
                    <div class="text-6xl font-bold text-gray-800 mb-2">{{ number_format($averageRating, 1) }}</div>
                    <div class="flex items-center justify-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ph-fill ph-star text-2xl {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <div class="text-gray-500">{{ $product->reviews->count() }} reviews</div>
                </div>
                
                <div class="md:col-span-2">
                    @foreach($ratingBreakdown as $star => $count)
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-sm font-semibold w-12">{{ $star }} star</span>
                            <div class="flex-1 bg-gray-200 rounded-full h-3">
                                <div class="bg-yellow-400 h-3 rounded-full" style="width: {{ $product->reviews->count() > 0 ? ($count / $product->reviews->count() * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-12">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Write Review Form -->
            @auth
                @if(!$product->reviews->where('user_id', auth()->id())->count())
                    <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Write a Review</h3>
                        <form action="{{ route('products.review.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                                <div class="flex gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required class="hidden peer">
                                        <label for="star{{ $i }}" class="cursor-pointer text-3xl text-gray-300 hover:text-yellow-400 peer-checked:text-yellow-400">
                                            <i class="ph-fill ph-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Comment (optional)</label>
                                <textarea name="comment" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Share your experience..."></textarea>
                            </div>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                Submit Review
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

            <!-- Reviews List -->
            <div class="space-y-6">
                @forelse($product->reviews->sortByDesc('created_at') as $review)
                    <div class="border-b border-gray-200 pb-6 last:border-0">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-semibold text-gray-800">{{ $review->user->name }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ph-fill ph-star text-lg {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)
                            <p class="text-gray-600 mt-2">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i class="ph-fill ph-chat-slash text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg">No reviews yet. Be the first to review this product!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Related Products</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $related)
                        <a href="{{ route('products.show', $related) }}" class="block group">
                            <div class="bg-gradient-to-b from-red-100 to-red-200 rounded-lg aspect-square flex items-center justify-center mb-3 overflow-hidden">
                                @if($related->images && count($related->images) > 0)
                                    <img src="{{ asset('storage/' . $related->images[0]) }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <i class="ph-fill ph-package text-6xl text-red-500"></i>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1 line-clamp-1">{{ $related->name }}</h3>
                            <p class="text-red-600 font-bold">${{ number_format($related->price, 2) }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
