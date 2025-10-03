@extends('layouts.app')

@section('content')
<div class="bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-2">Security</h2>
    <p class="text-sm mb-4">Manage two-factor authentication (TOTP) for your account.</p>

    @if(session('status') === 'two-factor-authentication-enabled')
        <div class="bg-green-100 text-green-800 p-2 rounded mb-2 text-sm">Two-factor authentication enabled. Scan the QR code below.</div>
    @endif
    @if(session('status') === 'two-factor-authentication-disabled')
        <div class="bg-red-100 text-red-800 p-2 rounded mb-2 text-sm">Two-factor authentication disabled.</div>
    @endif

    @if(!auth()->user()->two_factor_secret)
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded font-semibold">Enable 2FA</button>
        </form>
    @else
        <div class="space-y-4">
            <div class="flex space-x-2">
                <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded">Disable 2FA</button>
                </form>
                <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
                    @csrf
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded">Regenerate Recovery Codes</button>
                </form>
            </div>

            <div>
                <h3 class="font-medium mb-2">QR Code</h3>
                <div id="qr"></div>
            </div>

            <div>
                <h3 class="font-medium mb-2">Recovery Codes</h3>
                <pre id="codes" class="bg-gray-100 p-3 rounded text-sm">Loading...</pre>
            </div>
        </div>

        <script>
            fetch('{{ url('/two-factor-qr-code') }}', { headers: { 'Accept': 'application/json' }, credentials:'same-origin'})
                .then(r=>r.json()).then(({svg}) => document.getElementById('qr').innerHTML = svg);

            fetch('{{ url('/two-factor-recovery-codes') }}', { headers: { 'Accept': 'application/json' }, credentials:'same-origin'})
                .then(r=>r.json()).then(codes => document.getElementById('codes').textContent = codes.join("\n"));
        </script>
    @endif
</div>
@endsection
