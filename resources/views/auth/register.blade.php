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
                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 drop-shadow-lg">Join Us Today</h1>
                <p class="text-lg md:text-xl max-w-md mx-auto leading-relaxed">Create your account and start managing your optical experience.</p>
            </div>
        </div>
    </div>

    <!-- Right Form -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-4 sm:p-6 md:p-8 bg-gray-50">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 space-y-6 transition-all duration-300 hover:shadow-2xl">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Create Account</h2>
                <p class="mt-2 text-sm text-gray-600">Sign up to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           class="w-full text-black px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                           placeholder="John Doe">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full text-black px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full text-black px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full text-black px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                           placeholder="••••••••">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input id="terms" type="checkbox" name="terms" required
                           class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <div class="ml-2 text-sm">
                        <label for="terms" class="text-gray-700">
                            I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms of Service</a> and <a href="#" class="text-indigo-600 hover:underline">Privacy Policy</a>
                        </label>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create Account
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-800 transition">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection