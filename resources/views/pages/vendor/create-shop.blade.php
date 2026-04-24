@extends('layouts.app')
@section('page', 'CREATE SHOP')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Create Your Shop</h1>
                <p class="text-lg text-gray-600">Start selling your products by creating your shop</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vendor.shop.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="name">Shop Name</label>
                    <input type="text" name="name" id="name" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="My Awesome Shop">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Tell customers about your shop..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="logo">Shop Logo</label>
                    <input type="file" name="logo" id="logo" accept="image/*" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                    <p class="text-sm text-gray-500 mt-1">Upload a logo for your shop</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="ph-bold ph-info"></i> 
                        Your shop will be created with "pending" status. An admin will review and approve it before it becomes visible to customers.
                    </p>
                </div>

                <button type="submit" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 px-6 rounded-lg transition-colors duration-200 text-lg">
                    Create Shop
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/" class="text-red-500 hover:text-red-700 font-semibold">
                    <i class="ph-bold ph-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
