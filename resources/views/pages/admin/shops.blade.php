@extends('layouts.app')
@section('page', 'ALL SHOPS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10 flex items-center justify-between">
            <div>
                <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">All Shops</h1>
                <p class="text-lg text-gray-600">Manage all shops on the platform</p>
            </div>
            <a href="{{ route('admin.shops.pending') }}" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                Pending Approvals
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($shops as $shop)
                        <tr>
                            <td class="px-6 py-4 font-semibold">{{ $shop->name }}</td>
                            <td class="px-6 py-4">{{ $shop->owner->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($shop->status == 'approved') bg-green-100 text-green-700
                                    @elseif($shop->status == 'pending') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst($shop->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.shops.destroy', $shop) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold"
                                        onclick="return confirm('Delete this shop?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-8">{{ $shops->links() }}</div>
    </div>
</main>
@endsection
