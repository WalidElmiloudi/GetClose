@extends('layouts.app')
@section('page', 'CHECKOUT')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    @if(session('error'))
        <div class="max-w-5xl mx-auto bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-5xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Checkout</h1>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Shipping & Payment Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Shipping Address</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Street Address *</label>
                                <input type="text" name="shipping_address" required value="{{ old('shipping_address') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                    placeholder="123 Main Street">
                                @error('shipping_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                                    <input type="text" name="shipping_city" required value="{{ old('shipping_city') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                        placeholder="New York">
                                    @error('shipping_city')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">ZIP Code *</label>
                                    <input type="text" name="shipping_zip" required value="{{ old('shipping_zip') }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                        placeholder="10001">
                                    @error('shipping_zip')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Shipping Method</h2>
                        
                        <div class="space-y-3">
                            @foreach($shippingMethods as $method)
                                <label class="flex items-center justify-between p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition-colors {{ $loop->first ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                    <div class="flex items-center gap-4">
                                        <input type="radio" name="shipping_method_id" value="{{ $method->id }}" required
                                            class="w-5 h-5 text-red-500" {{ $loop->first ? 'checked' : '' }}>
                                        <div>
                                            <div class="font-semibold text-gray-800">{{ $method->name }}</div>
                                            @if($method->description)
                                                <div class="text-sm text-gray-500">{{ $method->description }}</div>
                                            @endif
                                            @if($method->estimated_days)
                                                <div class="text-sm text-gray-500">{{ $method->estimated_days }} days delivery</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-xl font-bold text-red-600">${{ number_format($method->price, 2) }}</div>
                                </label>
                            @endforeach
                            @error('shipping_method_id')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Payment Method</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition-colors {{ old('payment_method') == 'cash' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="radio" name="payment_method" value="cash" required class="w-5 h-5 text-red-500" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                <div>
                                    <i class="ph-fill ph-money text-2xl text-green-600"></i>
                                    <span class="font-semibold text-gray-800 ml-2">Cash on Delivery</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition-colors {{ old('payment_method') == 'card' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="radio" name="payment_method" value="card" required class="w-5 h-5 text-red-500" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                <div>
                                    <i class="ph-fill ph-credit-card text-2xl text-blue-600"></i>
                                    <span class="font-semibold text-gray-800 ml-2">Credit/Debit Card</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition-colors {{ old('payment_method') == 'paypal' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="radio" name="payment_method" value="paypal" required class="w-5 h-5 text-red-500" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                <div>
                                    <i class="ph-fill ph-paypal-logo text-2xl text-indigo-600"></i>
                                    <span class="font-semibold text-gray-800 ml-2">PayPal</span>
                                </div>
                            </label>
                            @error('payment_method')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-40">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Summary</h2>
                        
                        <!-- Cart Items -->
                        <div class="space-y-4 mb-6 max-h-64 overflow-y-auto">
                            @foreach($cart->items as $item)
                                <div class="flex gap-3">
                                    <div class="w-16 h-16 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        @if($item->product->images && count($item->product->images) > 0)
                                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <i class="ph-fill ph-package text-2xl text-red-500"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-semibold text-gray-800 truncate">{{ $item->product->name }}</div>
                                        <div class="text-sm text-gray-500">Qty: {{ $item->quantity }}</div>
                                        <div class="text-red-600 font-bold">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Totals -->
                        <div class="border-t border-gray-200 pt-4 space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span id="shippingCost">Calculated at next step</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 flex justify-between text-xl font-bold text-gray-800">
                                <span>Total</span>
                                <span id="totalPrice" class="text-red-600">${{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors duration-200 text-lg mt-6">
                            Place Order
                        </button>

                        <a href="{{ route('cart') }}" class="block text-center text-gray-600 hover:text-red-500 font-semibold mt-4">
                            ← Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
// Update totals when shipping method changes
document.querySelectorAll('input[name="shipping_method_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const shippingCost = parseFloat(this.closest('label').querySelector('.text-red-600').textContent.replace('$', ''));
        const subtotal = {{ $subtotal }};
        const total = subtotal + shippingCost;
        
        document.getElementById('shippingCost').textContent = '$' + shippingCost.toFixed(2);
        document.getElementById('totalPrice').textContent = '$' + total.toFixed(2);
    });
});

// Trigger first shipping method if checked
const firstChecked = document.querySelector('input[name="shipping_method_id"]:checked');
if (firstChecked) {
    firstChecked.dispatchEvent(new Event('change'));
}
</script>
@endsection
