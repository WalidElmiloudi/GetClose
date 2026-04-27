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

        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
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
                                    <div class="text-xl font-bold text-red-600">MAD {{ number_format($method->price, 2) }}</div>
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
                            <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition-colors {{ old('payment_method', 'cash') == 'cash' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="radio" name="payment_method" value="cash" required class="w-5 h-5 text-red-500" {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }} onchange="togglePaymentFields()">
                                <div>
                                    <i class="ph-fill ph-money text-2xl text-green-600"></i>
                                    <span class="font-semibold text-gray-800 ml-2">Cash on Delivery</span>
                                    <p class="text-sm text-gray-500 mt-1">Pay when you receive your order</p>
                                </div>
                            </label>

                            <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:border-red-500 transition-colors {{ old('payment_method') == 'stripe' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="radio" name="payment_method" value="stripe" required class="w-5 h-5 text-red-500" {{ old('payment_method') == 'stripe' ? 'checked' : '' }} onchange="togglePaymentFields()">
                                <div>
                                    <i class="ph-fill ph-credit-card text-2xl text-blue-600"></i>
                                    <span class="font-semibold text-gray-800 ml-2">Credit/Debit Card (Stripe)</span>
                                    <p class="text-sm text-gray-500 mt-1">Secure payment with Stripe</p>
                                </div>
                            </label>
                            @error('payment_method')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stripe Card Element -->
                        <div id="stripe-payment-section" class="mt-6 {{ old('payment_method') == 'stripe' ? '' : 'hidden' }}">
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Card Details</h3>
                                <div id="card-element" class="p-4 border border-gray-300 rounded-lg bg-gray-50">
                                    <!-- Stripe Card Element will be inserted here -->
                                </div>
                                <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
                                <input type="hidden" name="stripe_payment_intent_id" id="stripe-payment-intent-id">
                            </div>
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
                                        <div class="text-red-600 font-bold">MAD {{ number_format($item->price * $item->quantity, 2) }}</div>
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

                        <button type="submit" id="submit-button" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors duration-200 text-lg mt-6">
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

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
// Toggle payment fields
function togglePaymentFields() {
    const stripeSection = document.getElementById('stripe-payment-section');
    const stripeRadio = document.querySelector('input[name="payment_method"][value="stripe"]');
    
    if (stripeRadio.checked) {
        stripeSection.classList.remove('hidden');
    } else {
        stripeSection.classList.add('hidden');
    }
}

// Stripe integration
let stripe;
let cardElement;
let paymentIntentId = null;

@if(config('services.stripe.key'))
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Stripe
    stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    // Create card element
    cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
            invalid: {
                color: '#9e2146',
            },
        },
    });
    
    // Mount card element
    cardElement.mount('#card-element');
    
    // Handle validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
});
@endif

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

// Handle form submission
const checkoutForm = document.getElementById('checkout-form');
const submitButton = document.getElementById('submit-button');

checkoutForm.addEventListener('submit', async function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    // If Stripe payment, process payment first
    if (paymentMethod === 'stripe') {
        e.preventDefault();
        
        if (!stripe || !cardElement) {
            alert('Stripe is not initialized. Please check your connection.');
            return;
        }
        
        submitButton.disabled = true;
        submitButton.textContent = 'Processing Payment...';
        
        try {
            // Get total price
            const totalPriceText = document.getElementById('totalPrice').textContent.replace('$', '').replace(',', '');
            const totalAmount = parseFloat(totalPriceText);
            
            // Create payment intent
            const response = await fetch('{{ route('checkout.create-payment-intent') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ amount: totalAmount })
            });
            
            const data = await response.json();
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            // Confirm card payment
            const { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: cardElement,
                }
            });
            
            if (error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                submitButton.disabled = false;
                submitButton.textContent = 'Place Order';
            } else {
                // Payment successful
                if (paymentIntent.status === 'succeeded') {
                    document.getElementById('stripe-payment-intent-id').value = paymentIntent.id;
                    // Submit the form
                    checkoutForm.submit();
                }
            }
        } catch (error) {
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message || 'An error occurred';
            submitButton.disabled = false;
            submitButton.textContent = 'Place Order';
        }
    }
});
</script>
@endsection
