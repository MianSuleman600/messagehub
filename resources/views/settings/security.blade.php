@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto text-gray-100">
    <div class="rounded-2xl bg-gray-950/60 border border-gray-800 p-6 shadow-lg">
        <h2 class="text-2xl font-semibold tracking-tight text-white mb-1">Security</h2>
        <p class="text-sm text-gray-400 mb-5">Manage two-factor authentication (TOTP) for your account.</p>

        @if(session('status') === 'two-factor-authentication-enabled')
            <div class="mb-4 rounded-xl border border-green-800/50 bg-green-900/20 text-green-100 px-4 py-3 text-sm">
                Two-factor authentication enabled. Scan the QR code below.
            </div>
        @endif
        @if(session('status') === 'two-factor-authentication-disabled')
            <div class="mb-4 rounded-xl border border-red-800/50 bg-red-900/30 text-red-100 px-4 py-3 text-sm">
                Two-factor authentication disabled.
            </div>
        @endif

        @if(!auth()->user()->two_factor_secret)
            <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-semibold">
                    Enable 2FA
                </button>
            </form>
        @else
            <div class="space-y-5">
                <div class="flex flex-wrap gap-2">
                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-100 rounded-lg">
                            Disable 2FA
                        </button>
                    </form>
                    <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg">
                            Regenerate Recovery Codes
                        </button>
                    </form>
                </div>

                <div>
                    <h3 class="font-medium text-gray-200 mb-2">QR Code</h3>
                    <div id="qr" class="bg-gray-900/60 border border-gray-800 rounded-lg p-4"></div>
                </div>

                <div>
                    <h3 class="font-medium text-gray-200 mb-2">Recovery Codes</h3>
                    <pre id="codes" class="bg-gray-900/60 border border-gray-800 rounded-lg p-4 text-sm text-gray-100">Loading...</pre>
                </div>
            </div>

            <script>
                fetch('{{ url('/two-factor-qr-code') }}', { headers: { 'Accept': 'application/json' }, credentials:'same-origin'})
                    .then(r => r.json()).then(({svg}) => document.getElementById('qr').innerHTML = svg);

                fetch('{{ url('/two-factor-recovery-codes') }}', { headers: { 'Accept': 'application/json' }, credentials:'same-origin'})
                    .then(r => r.json()).then(codes => document.getElementById('codes').textContent = codes.join("\n"));
            </script>
        @endif
    </div>
</div>
@endsection