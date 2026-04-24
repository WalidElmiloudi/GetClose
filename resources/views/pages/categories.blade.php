@extends('layouts.app')
@section('page', 'CATEGORIES')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Shop Categories</h1>
            <p class="text-lg text-gray-600">Browse products by category</p>
        </div>

        @if($categories->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $category->name }}</h2>
                                <p class="text-gray-600">{{ $category->description }}</p>
                            </div>
                            <div class="h-16 w-16 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center ml-4">
                                <i class="ph-fill ph-folder text-3xl text-red-500"></i>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <span class="text-sm text-gray-500">
                                <i class="ph-bold ph-package"></i> {{ $category->products->count() }} products
                            </span>
                            <a href="#" class="text-red-500 hover:text-red-700 font-semibold">
                                View Products <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <i class="ph-fill ph-folder text-8xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Categories Available</h2>
                <p class="text-gray-500">Check back later for new categories!</p>
            </div>
        @endif
    </div>
</main>
@endsection
