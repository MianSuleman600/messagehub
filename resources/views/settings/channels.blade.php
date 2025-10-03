{{-- resources/views/settings/channels.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8 text-gray-100">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Channels</h1>
    </div>

    {{-- Status / Errors --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-900/40 text-green-100 px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-900/40 text-red-100 px-4 py-3">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Connect new channel --}}
        <div class="bg-gray-900/60 border border-gray-800 rounded-xl p-5 space-y-5">
            <h2 class="text-lg font-medium mb-4">Connect a Channel</h2>

            {{-- Meta (Facebook + Instagram) --}}
            <div class="p-4 rounded-lg border border-gray-800 bg-gray-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Connect Facebook & Instagram</div>
                        <div class="text-xs text-gray-400 mt-1">Uses app secrets from config. No user keys needed.</div>
                    </div>
                    <a href="{{ route('oauth.meta.start') }}"
                       class="px-3 py-2 bg-blue-700 hover:bg-blue-600 rounded-lg text-sm">
                        Connect Meta
                    </a>
                </div>
            </div>

            {{-- TikTok --}}
            <div class="p-4 rounded-lg border border-gray-800 bg-gray-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Connect TikTok</div>
                        <div class="text-xs text-gray-400 mt-1">OAuth v2; tokens stored encrypted.</div>
                    </div>
                    <a href="{{ route('oauth.tiktok.start') }}"
                       class="px-3 py-2 bg-purple-700 hover:bg-purple-600 rounded-lg text-sm">
                        Connect TikTok
                    </a>
                </div>
            </div>

            {{-- WhatsApp Cloud --}}
            <div class="p-4 rounded-lg border border-gray-800 bg-gray-900/50">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium">Connect WhatsApp Cloud</div>
                        <div class="text-xs text-gray-400 mt-1">
                            Lists your WABA numbers using system token; no secrets entered by users.
                        </div>
                    </div>
                    <a href="{{ route('oauth.whatsapp.index') }}"
                       class="px-3 py-2 bg-green-700 hover:bg-green-600 rounded-lg text-sm">
                        Select Numbers
                    </a>
                </div>
            </div>

            {{-- Manual form (for other providers or key-based channels) --}}
            <form method="POST" action="{{ route('settings.channels.store') }}" class="space-y-4 mt-2">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Name</label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2"
                           placeholder="e.g. Support Inbox" />
                </div>

                <div>
                    <label class="block text-sm mb-1">Type</label>
                    <select name="type" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2">
                        <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select channel</option>
                        @foreach ($channelTypes as $type)
                            <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-2">Credentials (JSON)</label>
                    <textarea name="credentials_json" rows="5" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2"
                        placeholder='{"api_key":"...","secret":"..."}'>{{ old('credentials_json') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">
                        Use the buttons above for Meta/TikTok/WhatsApp. Or paste provider credentials here.
                    </p>
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-600 rounded-lg">Connect</button>
                </div>
            </form>
        </div>

        {{-- Existing channels --}}
        <div class="lg:col-span-2 bg-gray-900/60 border border-gray-800 rounded-xl p-5">
            <h2 class="text-lg font-medium mb-4">Connected Channels</h2>

            @if ($accounts->isEmpty())
                <div class="text-sm text-gray-400">No channels connected yet.</div>
            @else
                <div class="space-y-4">
                    @foreach ($accounts as $acc)
                        <div class="bg-gray-900/40 border border-gray-800 rounded-lg p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="font-semibold">{{ $acc->name }}</div>
                                    <div class="text-xs text-gray-400">
                                        Type: <span class="uppercase">{{ $acc->type ?? 'n/a' }}</span>
                                        <span class="mx-2">•</span>
                                        Status: @if ($acc->is_active)
                                            <span class="text-green-400">Active</span>
                                        @else
                                            <span class="text-red-400">Inactive</span>
                                        @endif
                                        @php $prov = $acc->credentials['provider'] ?? null; @endphp
                                        @if ($prov) <span class="mx-2">•</span> Provider: {{ strtoupper($prov) }} @endif
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    {{-- Toggle --}}
                                    <form method="POST" action="{{ route('settings.channels.toggle', $acc) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-2 py-1 text-xs rounded-lg {{ $acc->is_active ? 'bg-red-700 hover:bg-red-600' : 'bg-green-700 hover:bg-green-600' }}">
                                            {{ $acc->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('settings.channels.destroy', $acc) }}"
                                          onsubmit="return confirm('Disconnect channel {{ $acc->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 text-xs bg-red-700 hover:bg-red-600 rounded-lg">Delete</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Update form --}}
                            <form method="POST" action="{{ route('settings.channels.update', $acc) }}" class="mt-3 space-y-3">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs mb-1">Name</label>
                                        <input name="name" type="text" required value="{{ $acc->name }}"
                                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-sm" />
                                    </div>

                                    <div>
                                        <label class="block text-xs mb-1">Type</label>
                                        <select name="type" required
                                                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-sm">
                                            @foreach ($channelTypes as $type)
                                                <option value="{{ $type }}" @selected($acc->type === $type)>{{ ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs mb-1">Active</label>
                                        <select name="is_active" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-sm">
                                            <option value="1" @selected($acc->is_active)>Yes</option>
                                            <option value="0" @selected(!$acc->is_active)>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs mb-1">Credentials (JSON)</label>
                                    <textarea name="credentials_json" rows="3"
                                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-2 py-1 text-sm">{{ json_encode($acc->credentials ?? [], JSON_PRETTY_PRINT) }}</textarea>
                                </div>

                                <div>
                                    <button type="submit" class="px-3 py-1.5 bg-green-700 hover:bg-green-600 rounded-md text-sm">Save Changes</button>
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
