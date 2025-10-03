@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Forgot Password</h2>
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 font-medium">Email</label>
            <input class="w-full border-gray-300 rounded p-2 focus:ring-2 focus:ring-indigo-500" type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded font-semibold">Send Password Reset Link</button>
    </form>
</div>
@endsection
