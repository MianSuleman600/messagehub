@extends('layouts.app')

@section('fullScreenLayout')
@endsection

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Left Image -->
    <div class="hidden md:flex w-1/2 bg-cover bg-center relative overflow-hidden" style="background-image: url('{{ asset('2.jpg') }}')">
        <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
        <div class="relative z-10 flex items-center justify-center p-8 text-white text-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 drop-shadow-lg">Welcome Back</h1>
                <p class="text-lg md:text-xl max-w-md mx-auto leading-relaxed">Sign in to manage your account and explore exclusive features.</p>
            </div>
        </div>
    </div>

    <!-- Right Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-4 sm:p-6 md:p-8 bg-gray-50">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 space-y-6 transition-all duration-300 hover:shadow-2xl">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Sign In</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your credentials to access your account</p>
            </div>

            @if(session('status'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-green-700 text-sm">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border text-black border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 transition">Forgot password?</a>
                    </div>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-black duration-200 placeholder-gray-400"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me + Submit -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign In
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-800 transition">Register now</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection