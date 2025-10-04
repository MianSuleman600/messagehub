@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto text-gray-100">
    <div class="bg-gray-950/60 border border-gray-800 rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-white text-center mb-4">Two-Factor Authentication</h2>

        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1 text-sm text-gray-300">Authentication Code</label>
                <input class="w-full px-3 py-2 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500"
                       type="text" name="code" inputmode="numeric" autocomplete="one-time-code">
            </div>
            <div>
                <label class="block mb-1 text-sm text-gray-300">Recovery Code</label>
                <input class="w-full px-3 py-2 bg-gray-900/80 border border-gray-800 rounded-lg text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-500"
                       type="text" name="recovery_code" autocomplete="one-time-code">
            </div>

            @if ($errors->any())
                <div class="text-red-300 text-sm mt-2">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-lg font-semibold transition">
                Verify
            </button>
        </form>
    </div>
</div>
@endsection