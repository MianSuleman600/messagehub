@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto text-gray-100">
    <div class="bg-gray-950/60 border border-gray-800 rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-white text-center mb-2">Verify Your Email</h2>
        <p class="text-sm text-gray-400 mb-4 text-center">
            We sent a verification link to <strong class="text-gray-200">{{ auth()->user()->email }}</strong>.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 rounded-xl border border-green-800/50 bg-green-900/20 text-green-100 px-4 py-3 text-sm">
                A new verification link has been sent.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-3">
            @csrf
            <button class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-lg font-semibold transition">
                Resend Verification Email
            </button>
        </form>
    </div>
</div>
@endsection