@extends('layouts.app')

@section('content')
@php($email = old('email', request()->email))
<div class="bg-white p-8 rounded-lg shadow-md max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Reset Password</h2>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div>
            <label class="block mb-1 font-medium">Email</label>
            <input class="w-full border-gray-300 rounded p-2 focus:ring-2 focus:ring-indigo-500" type="email" name="email" value="{{ $email }}" required>
        </div>

        <div>
            <label class="block mb-1 font-medium">New Password</label>
            <input class="w-full border-gray-300 rounded p-2 focus:ring-2 focus:ring-indigo-500" type="password" name="password" required>
        </div>

        <div>
            <label class="block mb-1 font-medium">Confirm Password</label>
            <input class="w-full border-gray-300 rounded p-2 focus:ring-2 focus:ring-indigo-500" type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded font-semibold">Reset Password</button>
    </form>
</div>
@endsection
