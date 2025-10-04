@extends('layouts.app')

@section('content')

@php
// Social media channels to connect
$connectableChannels = [
    [
        'name' => 'Facebook & Instagram',
        'description' => 'Connect your Meta Pages and Instagram Business Accounts.',
        'color' => 'bg-[#1877F2]', // Facebook Blue
        'route' => route('oauth.meta.start'),
        'button_text' => 'Connect Meta',
    ],
    [
        'name' => 'TikTok',
        'description' => 'OAuth v2 integration: tokens stored encrypted.',
        'color' => 'bg-black/90', // TikTok Black
        'route' => route('oauth.tiktok.start'),
        'button_text' => 'Connect TikTok',
    ],
    [
        'name' => 'WhatsApp Cloud',
        'description' => 'List and select your WABA numbers using system token.',
        'color' => 'bg-[#25D366]', // WhatsApp Green
        'route' => route('oauth.whatsapp.index'),
        'button_text' => 'Connect WhatsApp',
    ],
];

// Render SVG logos
function renderChannelIcon($channelName) {
    $icons = [
        'Facebook & Instagram' => '
            <svg class="w-10 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.12 8.44 9.88v-6.99H7.9v-2.89h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.89h-2.34v6.99C18.34 21.12 22 16.99 22 12z"/>
            </svg>
        ',
        'TikTok' => '
            <svg class="w-10 h-6 text-white" fill="currentColor" viewBox="0 0 48 48">
                <path d="M35 17c-3.33 0-6-2.67-6-6h-4c0 5.52 4.48 10 10 10v-4z"/>
            </svg>
        ',
        'WhatsApp Cloud' => '
            <svg class="w-10 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.671.15-.198.297-.767.967-.94 1.164-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.654-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.173.198-.297.298-.495.099-.198.05-.372-.025-.521-.074-.148-.671-1.611-.919-2.205-.242-.579-.487-.5-.671-.51-.173-.008-.372-.01-.571-.01s-.52.074-.792.372c-.273.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.214 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.261.489 1.692.625.71.226 1.356.194 1.866.118.569-.085 1.758-.719 2.006-1.413.248-.694.248-1.29.173-1.414-.074-.124-.273-.198-.57-.347z"/>
            </svg>
        ',
    ];
    return $icons[$channelName] ?? '<svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>';
}
@endphp

<div class="max-w-7xl mx-auto text-gray-100 px-6 py-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Channel Integration Hub</h1>
            <p class="mt-1 text-base text-gray-400">Connect, manage, and activate all your messaging and social media channels.</p>
        </div>
    </div>

    {{-- Status / Errors --}}
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-green-800/50 bg-green-900/20 text-green-100 px-4 py-3 shadow-sm">
            {{ session('status') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-800/50 bg-red-900/30 text-red-100 px-4 py-3 shadow-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Connect New Channel --}}
        <div class="lg:col-span-1 bg-gray-950/60 border border-gray-800 rounded-2xl p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">Connect a New Channel</h2>
                <span class="text-xs px-3 py-1 rounded-full bg-blue-900/40 border border-blue-800 text-blue-300">New Integrations</span>
            </div>

            <div class="space-y-4">
                @foreach ($connectableChannels as $channel)
                    <div class="p-4 rounded-xl border border-gray-800 bg-gray-900/60 hover:bg-gray-900/70 transition-colors duration-200 shadow-md">
                        <div class="flex items-center justify-between gap-4">
                            {{-- Channel Info & Icon --}}
                            <div class="min-w-0 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $channel['color'] }} shadow-lg">
                                    {!! renderChannelIcon($channel['name']) !!}
                                </div>
                                <div class="truncate">
                                    <div class="font-medium text-white text-base leading-tight">{{ $channel['name'] }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5 truncate">{{ $channel['description'] }}</div>
                                </div>
                            </div>

                            {{-- Connect Button --}}
                            <a href="{{ $channel['route'] }}"
                                class="flex-shrink-0 px-4 py-2 {{ $channel['color'] }} hover:opacity-90 rounded-lg text-sm text-white font-semibold transition-opacity duration-200 shadow-lg min-w-[160px] text-center">
                                {{ $channel['button_text'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Existing Channels --}}
        <div class="lg:col-span-2 bg-gray-950/60 border border-gray-800 rounded-2xl p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-semibold text-white">Connected Channels ({{ $accounts->count() }})</h2>
                <span class="text-xs text-gray-500">Manage, activate, and update credentials</span>
            </div>

            @if ($accounts->isEmpty())
                <div class="text-sm text-gray-400 p-8 text-center border border-gray-800 rounded-xl">No channels connected yet. Use the panel on the left to start connecting new services.</div>
            @else
                <div class="space-y-6">
                    @foreach ($accounts as $acc)
                        @php $prov = $acc->credentials['provider'] ?? 'manual'; @endphp
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-gray-700/30 border border-gray-700/50">
                                            <span class="sr-only">{{ ucfirst($prov) }}</span>
                                        </span>
                                        <div class="font-semibold text-white truncate text-base">{{ $acc->name }}</div>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] uppercase tracking-wide
                                            {{ $acc->is_active ? 'bg-green-900/30 text-green-300 border border-green-800/50' : 'bg-red-900/30 text-red-300 border border-red-800/50' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $acc->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                            {{ $acc->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1 truncate">
                                        Type: <span class="uppercase">{{ $acc->type ?? 'n/a' }}</span>
                                        <span class="mx-2">•</span>
                                        Provider: <span class="uppercase">{{ $prov }}</span>
                                        <span class="mx-2">•</span>
                                        ID: {{ $acc->id }}
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-2 flex-shrink-0">
                                    <form method="POST" action="{{ route('settings.channels.toggle', $acc) }}">
                                        @csrf
                                        @php
                                            $color = $acc->is_active
                                                ? 'bg-red-700 hover:bg-red-600 text-white'
                                                : 'bg-green-700 hover:bg-green-600 text-white';
                                        @endphp
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $color }} shadow-md">
                                            {{ $acc->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('settings.channels.destroy', $acc) }}" onsubmit="return confirm('Disconnect channel {{ $acc->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-700 hover:bg-gray-600 text-white border border-gray-600/70 transition-colors shadow-md">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('settings.channels.update', $acc) }}" class="mt-4 space-y-4 pt-4 border-t border-gray-900">
                                @csrf
                                @method('PATCH')
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs mb-1 text-gray-300">Name</label>
                                        <input name="name" type="text" required value="{{ $acc->name }}"
                                            class="w-full bg-gray-900/80 border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-600 focus:outline-none" />
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-xs mb-1 text-gray-300">Provider/Type (Read-only)</label>
                                        <input type="text" readonly value="{{ ucfirst($prov) }} / {{ ucfirst($acc->type) }}"
                                            class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-400 cursor-not-allowed focus:outline-none" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs mb-1 text-gray-300">Credentials (JSON)</label>
                                    <textarea name="credentials_json" rows="4"
                                        class="w-full bg-gray-900/80 border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-600 focus:outline-none font-mono">{{ json_encode($acc->credentials ?? [], JSON_PRETTY_PRINT) }}</textarea>
                                </div>

                                <div>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-semibold transition-colors shadow-md">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

@endsection
