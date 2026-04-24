@extends('layouts.app')
@section('page', 'ADD PRODUCT')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Add New Product</h1>
            <p class="text-lg text-gray-600">List a new product in your shop</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                        <textarea name="description" required rows="5"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price *</label>
                            <input type="number" name="price" required step="0.01" min="0" value="{{ old('price') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                            <input type="number" name="quantity" required min="0" value="{{ old('quantity', 0) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Images</label>
                        <input type="file" name="images[]" multiple accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <p class="text-sm text-gray-500 mt-1">You can upload multiple images (JPEG, PNG, JPG, WebP, max 2MB each)</p>
                        @error('images.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors text-lg">
                        Add Product
                    </button>
                    <a href="{{ route('vendor.products') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg transition-colors text-center text-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
