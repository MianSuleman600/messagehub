{{-- resources/views/settings/connect-whatsapp.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-8 text-gray-100">
    <h1 class="text-2xl font-semibold mb-4">Connect WhatsApp Numbers</h1>

    {{-- Status message --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-900/40 text-green-100 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-900/40 text-red-100 px-4 py-3">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl bg-gray-900/60 border border-gray-800">
        <div class="px-5 py-4 border-b border-gray-800">
            <div class="text-sm text-gray-400">
                Numbers are pulled from your WABA (config/services.php → services.whatsapp).
            </div>
        </div>

        <div class="p-5">
            @if (empty($numbers))
                <div class="text-sm text-gray-400">
                    No numbers found. Check your <code>WHATSAPP_BUSINESS_ID</code> and <code>WHATSAPP_TOKEN</code>.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($numbers as $num)
                        <div class="flex items-center justify-between bg-gray-900/40 border border-gray-800 rounded-lg p-4">
                            <div>
                                <div class="font-medium">
                                    {{ $num['verified_name'] ?? 'WhatsApp Number' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Phone: {{ $num['display_phone_number'] ?? 'n/a' }} • ID: {{ $num['id'] }}
                                </div>
                            </div>

                            @if (in_array($num['id'], $connected))
                                <span class="text-xs px-2 py-1 rounded bg-green-800/60 border border-green-700">
                                    Connected
                                </span>
                            @else
                                <form method="POST" action="{{ route('oauth.whatsapp.connect') }}">
                                    @csrf
                                    <input type="hidden" name="phone_number_id" value="{{ $num['id'] }}">
                                    <button type="submit" class="px-3 py-2 bg-green-700 hover:bg-green-600 rounded-lg text-sm">
                                        Connect
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('settings.channels') }}" class="text-sm text-gray-300 hover:text-white underline">
            ← Back to Channels
        </a>
    </div>
</div>
@endsection
