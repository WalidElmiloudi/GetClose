@extends('layouts.app')
@section('page', 'SHOPS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Explore Trusted Shops</h1>
            <p class="text-lg text-gray-600">Discover amazing local vendors and their unique offerings</p>
        </div>

        @if($shops->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($shops as $shop)
                    <a href="{{ route('shops.show', $shop) }}" class="block group">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="h-48 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center">
                            @if($shop->logo)
                                <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}" class="w-32 h-32 object-cover rounded-full group-hover:scale-110 transition-transform duration-300">
                            @else
                                <i class="ph-fill ph-storefront text-8xl text-red-500"></i>
                            @endif
                        </div>
                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $shop->name }}</h2>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $shop->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                    {{ $shop->products_count }} {{ Str::plural('Product', $shop->products_count) }}
                                </span>
                                <span class="text-red-500 group-hover:text-red-700 font-semibold">
                                    View Shop <i class="ph-bold ph-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $shops->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <i class="ph-fill ph-storefront text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Shops Available</h2>
                <p class="text-gray-500">Check back later for new shops!</p>
            </div>
        @endif
    </div>
</main>
@endsection
