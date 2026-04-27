@extends('layouts.app')

@section('page', 'Notifications')

@section('content')
<main class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800">
                <i class="ph-fill ph-bell text-red-500"></i> Notifications
            </h1>
            @if($unreadCount > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-xl shadow-lg p-6 {{ is_null($notification->read_at) ? 'border-l-4 border-red-500' : 'opacity-75' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($notification->type == 'success')
                                        <i class="ph-fill ph-check-circle text-2xl text-green-500"></i>
                                    @elseif($notification->type == 'warning')
                                        <i class="ph-fill ph-warning text-2xl text-yellow-500"></i>
                                    @elseif($notification->type == 'error')
                                        <i class="ph-fill ph-x-circle text-2xl text-red-500"></i>
                                    @else
                                        <i class="ph-fill ph-info text-2xl text-blue-500"></i>
                                    @endif
                                    <h3 class="text-xl font-bold text-gray-800">{{ $notification->title }}</h3>
                                </div>
                                <p class="text-gray-600 mb-3">{{ $notification->message }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span><i class="ph ph-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                    @if(!is_null($notification->read_at))
                                        <span><i class="ph ph-check"></i> Read {{ $notification->read_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                @if(is_null($notification->read_at))
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 font-semibold text-sm">
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-sm">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        @if(isset($notification->data['order_id']))
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <a href="{{ route('orders.show', $notification->data['order_id']) }}" class="text-red-500 hover:text-red-700 font-semibold">
                                    View Order #{{ $notification->data['order_id'] }} <i class="ph ph-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="ph ph-bell-slash text-6xl text-gray-400 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">No Notifications</h2>
                <p class="text-gray-600">You don't have any notifications yet.</p>
            </div>
        @endif
    </div>
</main>
@endsection
