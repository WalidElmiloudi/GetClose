@extends('layouts.app')
@section('page', 'ORDER TRACKING')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">Order #{{ $order->id }}</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Order Status Timeline -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Order Status</h2>
            
            @php
                $statusSteps = ['pending', 'paid', 'processing', 'shipped', 'completed'];
                $cancelledSteps = ['cancelled'];
                $currentStatus = $order->status;
            @endphp

            @if(in_array($currentStatus, $cancelledSteps))
                <!-- Cancelled Order -->
                <div class="text-center py-8">
                    <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ph-fill ph-x-circle text-6xl text-red-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Order Cancelled</h3>
                    <p class="text-gray-600">This order has been cancelled.</p>
                </div>
            @elseif(in_array($currentStatus, $statusSteps))
                <!-- Active Order Timeline -->
                <div class="relative">
                    <div class="absolute left-0 right-0 top-6 h-1 bg-gray-200"></div>
                    <div class="relative flex justify-between">
                        @foreach($statusSteps as $index => $step)
                            @php
                                $isActive = in_array($step, array_slice($statusSteps, 0, array_search($currentStatus, $statusSteps) + 1));
                                $isCurrent = $step === $currentStatus;
                            @endphp
                            <div class="flex flex-col items-center" style="width: {{ 100 / count($statusSteps) }}%">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center relative z-10 {{ $isActive ? 'bg-red-500' : 'bg-gray-200' }}">
                                    @if($isActive)
                                        <i class="ph-fill ph-check text-white text-xl"></i>
                                    @else
                                        <div class="w-4 h-4 bg-gray-400 rounded-full"></div>
                                    @endif
                                </div>
                                <div class="mt-3 text-center">
                                    <div class="font-semibold text-sm {{ $isActive ? 'text-red-600' : 'text-gray-500' }}">
                                        {{ ucfirst($step) }}
                                    </div>
                                    @if($isCurrent)
                                        <div class="text-xs text-gray-500 mt-1">Current</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Items -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Order Items</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex gap-3">
                            <div class="w-16 h-16 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                @if($item->product->images && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <i class="ph-fill ph-package text-2xl text-red-500"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">{{ $item->product->name }}</div>
                                <div class="text-sm text-gray-500">Qty: {{ $item->quantity }}</div>
                                <div class="text-red-600 font-bold">MAD {{ number_format($item->price * $item->quantity, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Shipping Information</h3>
                <div class="space-y-3 text-gray-600">
                    <div>
                        <div class="font-semibold text-gray-800 mb-1">Address:</div>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_zip }}</p>
                    </div>
                    @if($order->shippingMethod)
                        <div>
                            <div class="font-semibold text-gray-800 mb-1">Method:</div>
                            <p>{{ $order->shippingMethod->name }}</p>
                            @if($order->shippingMethod->estimated_days)
                                <p class="text-sm text-gray-500">Estimated: {{ $order->shippingMethod->estimated_days }} days</p>
                            @endif
                        </div>
                    @endif
                    <div>
                        <div class="font-semibold text-gray-800 mb-1">Payment:</div>
                        <p class="capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>MAD {{ number_format($order->total_price - $order->shipping_price, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping</span>
                    <span>MAD {{ number_format($order->shipping_price, 2) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-2 flex justify-between text-2xl font-bold text-gray-800">
                    <span>Total</span>
                    <span class="text-red-600">MAD {{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
            <a href="{{ route('orders') }}" class="flex-1 bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-lg transition-colors text-center text-lg">
                Back to Orders
            </a>
            @if($order->status == 'pending')
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 rounded-lg transition-colors text-lg">
                        Cancel Order
                    </button>
                </form>
            @endif
        </div>
        @php
            $hasDispute = $order->dispute ? true : false;
        @endphp

        @if($order->status == 'completed' && !$hasDispute)
            <!-- Create Dispute Section -->
            <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-warning text-4xl text-orange-500"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Issue with Your Order?</h2>
                        <p class="text-gray-600">If you're experiencing any problems with your order, you can create a dispute and our support team will help resolve it.</p>
                    </div>
                </div>

                <button onclick="document.getElementById('dispute-modal').classList.remove('hidden')" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 rounded-lg transition-colors text-lg">
                    <i class="ph-bold ph-warning-circle"></i> Create Dispute
                </button>
            </div>

            <!-- Dispute Modal -->
            <div id="dispute-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-bold text-gray-800">Create Dispute for Order #{{ $order->id }}</h3>
                            <button onclick="document.getElementById('dispute-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                <i class="ph-bold ph-x text-2xl"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('orders.dispute', $order) }}" method="POST" class="p-6">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Dispute *</label>
                            <textarea name="reason" required rows="6" maxlength="1000"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                placeholder="Please describe your issue in detail. Include any relevant information that will help us resolve your dispute..."></textarea>
                            <p class="text-sm text-gray-500 mt-2">Maximum 1000 characters</p>
                            @error('reason')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                            <p class="text-sm text-orange-800">
                                <i class="ph-fill ph-info"></i> 
                                <strong>Note:</strong> Once submitted, our support team will review your dispute and contact you within 24-48 hours. Please provide as much detail as possible.
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <button type="button" onclick="document.getElementById('dispute-modal').classList.add('hidden')" 
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition-colors">
                                Submit Dispute
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($order->status == 'completed' && $hasDispute)
            <!-- Dispute Already Exists -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl shadow-lg p-6 mt-8">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="ph-fill ph-info text-2xl text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Dispute Already Created</h3>
                        <p class="text-blue-700">A dispute has already been created for this order. Our team is reviewing it and will contact you soon.</p>
                        <p class="text-sm text-blue-600 mt-2">Status: <strong>{{ ucfirst(str_replace('_', ' ', $order->dispute->status)) }}</strong></p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
