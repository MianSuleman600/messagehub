@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Two-Factor Authentication</h2>
    <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block mb-1 font-medium">Authentication Code</label>
            <input class="w-full border-gray-300 rounded p-2 focus:ring-2 focus:ring-indigo-500" type="text" name="code" inputmode="numeric" autocomplete="one-time-code">
        </div>
        <div>
            <label class="block mb-1 font-medium">Recovery Code</label>
            <input class="w-full border-gray-300 rounded p-2 focus:ring-2 focus:ring-indigo-500" type="text" name="recovery_code" autocomplete="one-time-code">
        </div>

        @if ($errors->any())
            <div class="text-red-600 text-sm mt-2">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded font-semibold">Verify</button>
    </form>
</div>
@endsection
