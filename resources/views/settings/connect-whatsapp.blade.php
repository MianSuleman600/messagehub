@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto text-gray-100">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold tracking-tight">Connect WhatsApp Numbers</h1>
        <p class="text-sm text-gray-400 mt-1">Choose numbers from your WABA to connect</p>
    </div>

    {{-- Status message --}}
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-green-800/50 bg-green-900/20 text-green-100 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-800/50 bg-red-900/30 text-red-100 px-4 py-3">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl bg-gray-950/60 border border-gray-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-800/70">
            <div class="text-sm text-gray-400">
                Numbers are pulled from your WABA (config/services.php → services.whatsapp).
            </div>
        </div>

        <div class="p-5">
            @if (empty($numbers))
                <div class="text-sm text-gray-400">
                    No numbers found. Check your <code class="font-mono text-gray-300">WHATSAPP_BUSINESS_ID</code> and <code class="font-mono text-gray-300">WHATSAPP_TOKEN</code>.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($numbers as $num)
                        <div class="flex items-center justify-between bg-gray-900/50 border border-gray-800 rounded-xl p-4">
                            <div class="min-w-0">
                                <div class="font-medium text-white truncate">
                                    {{ $num['verified_name'] ?? 'WhatsApp Number' }}
                                </div>
                                <div class="text-xs text-gray-400 mt-1 truncate">
                                    Phone: {{ $num['display_phone_number'] ?? 'n/a' }} • ID: {{ $num['id'] }}
                                </div>
                            </div>

                            @if (in_array($num['id'], $connected))
                                <span class="text-xs px-2 py-1 rounded-md bg-green-900/30 border border-green-800/60 text-green-300">
                                    Connected
                                </span>
                            @else
                                <form method="POST" action="{{ route('oauth.whatsapp.connect') }}">
                                    @csrf
                                    <input type="hidden" name="phone_number_id" value="{{ $num['id'] }}">
                                    <button type="submit" class="px-3 py-2 bg-[#25D366] hover:bg-[#1DA851] rounded-lg text-sm text-white font-medium transition-colors">
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
        <a href="{{ route('settings.channels') }}" class="inline-flex items-center gap-1 text-sm text-gray-300 hover:text-white transition-colors">
            <x-heroicon-o-arrow-left class="w-4 h-4" />
            Back to Channels
        </a>
    </div>
</div>
@endsection