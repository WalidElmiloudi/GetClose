@extends('layouts.app')
@section('page','HOME')
@section('content')
<main class="w-full">
    <section
        class=" 2xl:h-[calc(100vh-160px)] h-[calc(100vh-80px)] w-full bg-linear-to-b from-red-50 to-white pl-4 lg:pl-30 lg:grid grid-cols-2">
        <div class="-mt-15 lg:mt-0 col-span-1 gap-8 flex flex-col justify-center h-full">
            <h1 class="text-red-700 text-5xl font-bold lg:text-8xl 2xl:text-9xl">GetClose.</span></h1>
            <h2 class="text-4xl text-red-950 font-bold lg:text-6xl lg:w-150 2xl:text-7xl 2xl:w-250">Discover Shops &
                Products</h2>
            <h3 class="text-3xl text-black font-bold lg:text-5xl lg:w-150 2xl:text-6xl 2xl:w-300">High quality local
                vendors at your fingertips.</h3>
            <div class="w-full mt-3 lg:mt-10 gap-2 lg:gap-4 flex">
                <a href="{{ route('shops') }}"
                    class="text-xl lg:text-3xl 2xl:text-4xl font-bold px-4 py-2 2xl:py-4 2xl:px-6 bg-red-500 text-white rounded-full hover:bg-red-700 ease-in-out duration-150 hover:scale-110">Browse
                    Shops</a>
                <a href="{{ route('products') }}"
                    class="text-xl lg:text-3xl 2xl:text-4xl font-bold px-2 py-2 2xl:py-4 2xl:px-6 border-4 border-red-500 rounded-full text-red-500 hover:bg-red-500 hover:text-white ease-in-out duration-150 hover:scale-105">Explore
                    Products</a>
            </div>
        </div>
        <div class="hidden col-span-1 lg:flex h-full w-full items-center justify-center relative">
            <div
                class="absolute border-4 border-white top-40 z-10 left-30 w-100 h-100 bg-linear-to-b from-white to-red-100 rounded-full 2xl:w-150 2xl:h-150 shadow-xl">
            </div>
            <div
                class="absolute border-4 border-white top-2 z-20 -left-2 w-50 h-50 bg-white rounded-full 2xl:w-100 2xl:h-100 flex items-center justify-center -rotate-30 shadow-xl">
                <img class="opacity-30" src="{{ asset('images/logo.jpg') }}" alt="GetClose logo">
            </div>
            <div
                class="absolute border-4 border-white bottom-10 z-10 left-10 w-25 h-25 bg-linear-to-b from-white to-red-100 rounded-full 2xl:w-75 2xl:h-75 shadow-xl">
            </div>
            <div
                class="absolute border-4 border-white bottom-10 z-10 right-20 w-12 h-12 bg-linear-to-b from-white to-red-100 rounded-full 2xl:w-62 2xl:h-62 shadow-xl">
            </div>
        </div>
    </section>
    <section class="h-130 2xl:h-200 w-full lg:pl-30 2xl:pl-0 relative">
        <div class="relative">
            <h1 class="text-2xl font-bold text-red-950 pl-4 lg:text-4xl 2xl:pl-25">Discover Products You'll Love</h1>
            <a href="{{ route('products') }}"
                class="arrow h-10 w-10 rounded-full border-2 absolute lg:top-0 lg:right-15 2xl:right-25 top-110 right-3 flex pr-2 justify-center items-center hover:text-white hover:bg-red-600 overflow-hidden gap-2"><span
                    class="shrink-0 hidden label text-xl font-semibold text-center lg:text-2xl">See all</span><i
                    class="ph-thin ph-arrow-right text-3xl font-bold text-end"></i></a>
        </div>
        <div class="carousel my-10 mx-auto flex w-[90%] 2xl:w-[80%] overflow-x-hidden rounded-lg">
            <div class="group1 flex justify-center items-center gap-4">
                @foreach($featuredProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="grow-0 shrink-0 basis-80 h-80 lg:h-100 lg:basis-100 2xl:h-150 2xl:basis-150 bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-40 lg:h-50 2xl:h-70 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph-fill ph-package text-6xl lg:text-7xl 2xl:text-8xl text-red-500"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">MAD {{ number_format($product->price, 2) }}</span>
                            <span class="text-xs text-gray-500">{{ $product->quantity }} left</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div aria-hidden="true" class="group1 flex justify-center items-center gap-4">
                @foreach($featuredProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="grow-0 shrink-0 basis-80 h-80 lg:h-100 lg:basis-100 2xl:h-150 2xl:basis-150 bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-40 lg:h-50 2xl:h-70 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph-fill ph-package text-6xl lg:text-7xl 2xl:text-8xl text-red-500"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-red-600">MAD {{ number_format($product->price, 2) }}</span>
                            <span class="text-xs text-gray-500">{{ $product->quantity }} left</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    <section class="h-150 2xl:h-200 w-full lg:pl-30 2xl:pl-0 relative">
        <div class="relative">
            <h1 class="text-2xl font-bold text-red-950 pl-4 lg:text-4xl 2xl:pl-25">Explore Trusted Shops</h1>
            <a href="{{ route('shops') }}"
                class="arrow h-10 w-10 rounded-full border-2 absolute lg:top-0 lg:right-15 2xl:right-25 top-110 right-3 flex pr-2 justify-center items-center hover:text-white hover:bg-red-600 overflow-hidden gap-2"><span
                    class="shrink-0 hidden label text-xl font-semibold text-center lg:text-2xl">Explore all</span><i
                    class="ph-thin ph-arrow-right text-3xl font-bold text-end"></i></a>
        </div>
        <div class="carousel my-10 mx-auto flex w-[90%] 2xl:w-[80%] overflow-x-hidden rounded-lg">
            <div class="group2 flex justify-center items-center gap-4">
                @foreach($featuredShops as $shop)
                <a href="{{ route('shops.show', $shop) }}" class="grow-0 shrink-0 basis-80 h-80 lg:h-100 lg:basis-100 2xl:h-150 2xl:basis-150 bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-40 lg:h-50 2xl:h-70 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative">
                        @if($shop->logo)
                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph-fill ph-storefront text-6xl lg:text-7xl 2xl:text-8xl text-red-500"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $shop->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $shop->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                {{ $shop->products_count }} {{ Str::plural('Product', $shop->products_count) }}
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div aria-hidden="true" class="group2 flex justify-center items-center gap-4">
                @foreach($featuredShops as $shop)
                <a href="{{ route('shops.show', $shop) }}" class="grow-0 shrink-0 basis-80 h-80 lg:h-100 lg:basis-100 2xl:h-150 2xl:basis-150 bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="h-40 lg:h-50 2xl:h-70 bg-gradient-to-b from-red-100 to-red-200 flex items-center justify-center relative">
                        @if($shop->logo)
                            <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph-fill ph-storefront text-6xl lg:text-7xl 2xl:text-8xl text-red-500"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg lg:text-xl font-bold text-gray-800 mb-2 line-clamp-1">{{ $shop->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $shop->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                {{ $shop->products_count }} {{ Str::plural('Product', $shop->products_count) }}
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection
