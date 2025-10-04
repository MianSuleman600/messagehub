@extends('layouts.app')

@section('fullScreenLayout')
@endsection

@section('content')
<div class="flex min-h-dvh bg-gray-950 text-gray-100">
    {{-- Left Image --}}
    <div class="hidden md:flex w-1/2 bg-cover bg-center relative overflow-hidden" style="background-image: url('{{ asset('2.jpg') }}')">
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/20"></div>
        <div class="relative z-10 flex items-center justify-center p-8 text-white text-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Join Us Today</h1>
                <p class="text-base md:text-lg max-w-md mx-auto leading-relaxed text-gray-200">Create your account and start managing your optical experience.</p>
            </div>
        </div>
    </div>

    {{-- Right Form --}}
    <div class="w-full md:w-1/2 flex items-center justify-center p-4 sm:p-6 md:p-8">
        <div class="w-full max-w-md bg-gray-950/60 border border-gray-800 rounded-2xl shadow-xl p-8 space-y-6">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white">Create Account</h2>
                <p class="mt-2 text-sm text-gray-400">Sign up to get started</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition"
                           placeholder="John Doe">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-3 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500 transition"
                           placeholder="••••••••">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Terms --}}
                <div class="flex items-start gap-2">
                    <input id="terms" type="checkbox" name="terms" required
                           class="mt-1 h-4 w-4 text-indigo-500 focus:ring-indigo-500 border-gray-700 rounded bg-gray-900/80">
                    <div class="text-sm">
                        <label for="terms" class="text-gray-300">
                            I agree to the
                            <a href="#" class="text-indigo-400 hover:text-indigo-300 underline">Terms of Service</a>
                            and
                            <a href="#" class="text-indigo-400 hover:text-indigo-300 underline">Privacy Policy</a>
                        </label>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-lg shadow-lg transition focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-indigo-500">
                    Create Account
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-400">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-indigo-400 hover:text-indigo-300 transition">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection