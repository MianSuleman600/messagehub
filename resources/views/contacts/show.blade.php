@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-3xl font-semibold tracking-tight text-white">Contact</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Contact info --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg">
            <h3 class="text-lg font-semibold text-white">{{ $contact->name }}</h3>
            <div class="text-gray-400 mt-1">
                {{ $contact->email ?? '—' }} {{ $contact->phone ? '· '.$contact->phone : '' }}
            </div>
            <div class="mt-2 text-gray-300"><span class="font-semibold text-gray-400">Handle:</span> {{ $contact->handle ?? '—' }}</div>
            <div class="mt-2 text-gray-300"><span class="font-semibold text-gray-400">Last seen:</span> {{ optional($contact->last_seen_at)->diffForHumans() ?? '—' }}</div>
        </div>

        {{-- Recent conversations --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg">
            <h3 class="text-lg font-semibold mb-2 text-white">Recent conversations</h3>
            <div class="divide-y divide-gray-800">
                @forelse($contact->conversations as $conv)
                    <a href="{{ route('inbox.conversation', $conv) }}" class="flex justify-between items-start py-3 px-2 rounded-lg hover:bg-gray-900/60 transition-colors">
                        <div class="min-w-0">
                            <div class="font-medium text-white">{{ ucfirst($conv->channel) }}</div>
                            <div class="text-gray-400 text-sm truncate">
                                Assigned: {{ $conv->assignee?->name ?? 'Unassigned' }} · {{ str($conv->messages->first()->body ?? '—')->limit(120) }}
                            </div>
                        </div>
                        <div class="text-gray-400 text-sm whitespace-nowrap">
                            {{ optional($conv->last_message_at)->diffForHumans() }}
                        </div>
                    </a>
                @empty
                    <div class="text-gray-400 p-4">No conversations.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection