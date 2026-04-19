@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <div class="text-center mb-6">
            <p class="text-4xl">🎓</p>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Welcome Back</h1>
        </div>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('email') border-red-400 @enderror">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-xl hover:bg-blue-700 font-semibold text-sm">
                Login
            </button>
        </form>
        <p class="text-center text-sm text-gray-500 mt-4">
            No account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
        </p>
        <div class="mt-4 bg-blue-50 rounded-xl p-3 text-xs text-blue-700">
            <p class="font-semibold mb-1">Demo Accounts:</p>
            <p>fahim@campus.com / password</p>
            <p>hrithik@campus.com / password</p>
            <p>admin@campus.com / password</p>
        </div>
    </div>
</div>
@endsection
