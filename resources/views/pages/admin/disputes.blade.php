@extends('layouts.app')
@section('page', 'DISPUTES')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Disputes Management</h1>
            <p class="text-lg text-gray-600">Resolve customer disputes</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($disputes->count() > 0)
            <div class="space-y-6">
                @foreach($disputes as $dispute)
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Dispute #{{ $dispute->id }}</h3>
                                <p class="text-gray-600">Order #{{ $dispute->order_id }} - {{ $dispute->user->name }}</p>
                                <p class="text-gray-500 text-sm">{{ $dispute->created_at->format('M d, Y') }}</p>
                            </div>
                            <span class="px-4 py-2 rounded-full text-sm font-semibold
                                @if($dispute->status == 'resolved') bg-green-100 text-green-700
                                @elseif($dispute->status == 'open') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($dispute->status) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-700 mb-2">Reason</h4>
                            <p class="text-gray-600">{{ $dispute->reason }}</p>
                        </div>

                        @if($dispute->status == 'open')
                            <form action="{{ route('admin.disputes.resolve', $dispute) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Resolution</label>
                                    <textarea name="resolution" required rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                        placeholder="Enter your resolution..."></textarea>
                                </div>
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                    Resolve Dispute
                                </button>
                            </form>
                        @else
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2">Resolution</h4>
                                <p class="text-gray-600">{{ $dispute->resolution }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $disputes->links() }}</div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                <i class="ph-fill ph-check-circle text-8xl text-green-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-600 mb-2">No Disputes</h2>
                <p class="text-gray-500">All clear! No disputes to resolve.</p>
            </div>
        @endif
    </div>
</main>
@endsection
