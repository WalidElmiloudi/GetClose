@extends('layouts.app')
@section('page', 'EDIT PRODUCT')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-3xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Edit Product</h1>
            <p class="text-lg text-gray-600">Update product information</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" required value="{{ old('name', $product->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                        <textarea name="description" required rows="5"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Price (MAD) *</label>
                            <input type="number" name="price" required step="0.01" min="0" value="{{ old('price', $product->price) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                            <input type="number" name="quantity" required min="0" value="{{ old('quantity', $product->quantity) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                            @error('quantity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                        @if($categories->count() == 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                                <p class="text-yellow-700">
                                    <i class="ph-fill ph-warning"></i> 
                                    You don't have any categories yet. Please create one first!
                                </p>
                            </div>
                        @endif
                        <div class="flex gap-2">
                            <select name="category_id" id="categorySelect" required
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" onclick="document.getElementById('categoryModal').classList.remove('hidden')" 
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-6 rounded-lg transition-colors">
                                <i class="ph-bold ph-plus"></i> New
                            </button>
                        </div>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Images</label>
                        
                        @if($product->images && count($product->images) > 0)
                            <div class="mb-4">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Current Images:</p>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach($product->images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $image) }}" class="w-full h-32 object-cover rounded-lg border">
                                        </div>
                                    @endforeach
                                </div>
                                <p class="text-sm text-gray-500 mt-2">New images will be added to existing ones</p>
                            </div>
                        @endif

                        <input type="file" name="images[]" multiple accept="image/*"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <p class="text-sm text-gray-500 mt-1">Upload additional images (JPEG, PNG, JPG, WebP, max 2MB each)</p>
                        @error('images.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors text-lg">
                        Update Product
                    </button>
                    <a href="{{ route('vendor.products') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg transition-colors text-center text-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Create New Category</h2>
                <button onclick="document.getElementById('categoryModal').classList.add('hidden')" 
                    class="text-gray-500 hover:text-gray-700">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
            </div>
            
            <form id="categoryForm" action="{{ route('vendor.categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name *</label>
                    <input type="text" name="name" id="newCategoryName" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="e.g., Electronics, Clothing, Food">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description (optional)</label>
                    <textarea name="description" id="newCategoryDescription" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                        placeholder="Brief description of this category"></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-lg transition-colors">
                        Create Category
                    </button>
                    <button type="button" onclick="document.getElementById('categoryModal').classList.add('hidden')" 
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Handle category form submission via AJAX
        document.getElementById('categoryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route('vendor.categories.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new category to select dropdown
                    const select = document.getElementById('categorySelect');
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    option.selected = true;
                    select.appendChild(option);
                    
                    // Close modal and clear form
                    document.getElementById('categoryModal').classList.add('hidden');
                    document.getElementById('categoryForm').reset();
                    
                    // Show success message
                    alert('Category created successfully!');
                } else {
                    alert('Error creating category: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error creating category. Please try again.');
            });
        });
    </script>
</main>
@endsection
