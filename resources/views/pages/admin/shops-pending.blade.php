@extends('layouts.app')
@section('page', 'PENDING SHOPS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Pending Shop Approvals</h1>
            <p class="text-lg text-gray-600">Review and approve new shop registrations</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($shops->count() > 0)
            <div class="space-y-6">
                @foreach($shops as $shop)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start gap-4">
                                    @if($shop->logo)
                                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}" class="w-20 h-20 object-cover rounded-lg">
                                    @else
                                        <div class="w-20 h-20 bg-gradient-to-b from-red-100 to-red-200 rounded-lg flex items-center justify-center">
                                            <i class="ph-fill ph-storefront text-4xl text-red-500"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800">{{ $shop->name }}</h3>
                                        <p class="text-gray-600">Owner: {{ $shop->owner->name }}</p>
                                        <p class="text-gray-500 text-sm">{{ $shop->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            </div>

                            @if($shop->description)
                                <div class="mb-4">
                                    <h4 class="font-semibold text-gray-700 mb-2">Description</h4>
                                    <p class="text-gray-600">{{ $shop->description }}</p>
                                </div>
                            @endif

                            <div class="flex gap-3">
                                <form action="{{ route('admin.shops.approve', $shop) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                        <i class="ph-bold ph-check"></i> Approve Shop
                                    </button>
                                </form>
                                <form action="{{ route('admin.shops.reject', $shop) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors"
                                        onclick="return confirm('Are you sure you want to reject this shop?')">
                                        <i class="ph-bold ph-x"></i> Reject Shop
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $shops->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                <i class="ph-fill ph-check-circle text-8xl text-green-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Pending Shops</h2>
                <p class="text-gray-500 mb-6">All shops have been reviewed!</p>
                <a href="{{ route('admin.dashboard') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-lg transition-colors">
                    Back to Dashboard
                </a>
            </div>
        @endif
    </div>
</main>
@endsection
