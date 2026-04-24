@extends('layouts.app')
@section('page', 'PROFILE')
@section('content')
<main class="min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-4xl mx-auto">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="h-32 bg-gradient-to-r from-red-400 to-red-600 relative">
                <div class="absolute -bottom-16 left-8">
                    <div class="h-32 w-32 bg-red-300 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                        <h1 class="text-white font-bold text-5xl">{{ ucfirst(substr($user->name, 0, 1)) }}</h1>
                    </div>
                </div>
            </div>
            <div class="pt-20 pb-8 px-8">
                <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $user->email }}</p>
                <span class="inline-block mt-3 px-4 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-700">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Profile Information</h2>
            
            @if (session('status') === 'profile-updated')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    Profile updated successfully!
                </div>
            @endif
            
            <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="name">Name</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                        type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">Email</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                        type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Update Profile
                </button>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h2>
            @if (session('status') === 'password-updated')
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    Password updated successfully!
                </div>
            @endif
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="current_password">Current Password</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                        type="password" name="current_password" required placeholder="Enter current password">
                    @error('current_password', 'updatePassword')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">New Password</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                        type="password" name="password" required placeholder="Enter new password">
                    @error('password', 'updatePassword')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password_confirmation">Confirm New Password</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                        type="password" name="password_confirmation" required placeholder="Confirm new password">
                </div>
                
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Change Password
                </button>
            </form>
        </div>

        <!-- Delete Account Form -->
        <div class="bg-red-50 rounded-xl shadow-lg p-8 mb-8 border-2 border-red-200">
            <h2 class="text-2xl font-bold text-red-600 mb-4">Delete Account</h2>
            <p class="text-gray-600 mb-6">Once your account is deleted, all of its resources and data will be permanently deleted. Please proceed with caution.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" 
                onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="password">Confirm Password</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" 
                        type="password" name="password" required placeholder="Enter your password to confirm">
                    @error('password', 'userDeletion')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
                
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Delete Account
                </button>
            </form>
        </div>

        <!-- Logout Button (Mobile) -->
        <form method="POST" action="{{ route('logout') }}" class="lg:hidden mb-8">
            @csrf
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-lg transition-colors text-xl">
                Logout
            </button>
        </form>
    </div>
</main>
@endsection
