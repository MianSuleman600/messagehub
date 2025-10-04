@extends('layouts.app')

@section('content')
@php($email = old('email', request()->email))
<div class="max-w-md mx-auto text-gray-100">
    <div class="bg-gray-950/60 border border-gray-800 rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-white text-center mb-2">Reset Password</h2>
        <p class="text-sm text-gray-400 text-center mb-4">Enter your new password below.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div>
                <label class="block mb-1 text-sm text-gray-300">Email</label>
                <input class="w-full px-3 py-2 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500"
                       type="email" name="email" value="{{ $email }}" required>
                @error('email') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">New Password</label>
                <input class="w-full px-3 py-2 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500"
                       type="password" name="password" required>
                @error('password') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block mb-1 text-sm text-gray-300">Confirm Password</label>
                <input class="w-full px-3 py-2 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500"
                       type="password" name="password_confirmation" required>
                @error('password_confirmation') <p class="mt-1 text-sm text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-lg font-semibold transition">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection