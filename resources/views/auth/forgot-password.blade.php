@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto text-gray-100">
    <div class="bg-gray-950/60 border border-gray-800 rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-white text-center mb-2">Forgot Password</h2>
        <p class="text-sm text-gray-400 text-center mb-4">Enter your email to receive a reset link.</p>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-green-800/50 bg-green-900/20 text-green-100 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 text-sm text-gray-300">Email</label>
                <input class="w-full px-3 py-2 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500"
                       type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-lg font-semibold transition">
                Send Password Reset Link
            </button>
        </form>
    </div>
</div>
@endsection