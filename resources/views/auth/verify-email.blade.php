@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Verify Your Email</h2>
    <p class="text-sm mb-4">We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4 text-sm">A new verification link has been sent.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded font-semibold">Resend Verification Email</button>
    </form>
</div>
@endsection
