@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Contact</h2>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Contact info --}}
    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold">{{ $contact->name }}</h3>
        <div class="text-gray-400 mt-1">
            {{ $contact->email ?? '—' }} {{ $contact->phone ? '· '.$contact->phone : '' }}
        </div>
        <div class="mt-2 text-gray-400"><span class="font-semibold text-gray-400">Handle:</span> {{ $contact->handle ?? '—' }}</div>
        <div class="mt-2 text-gray-400"><span class="font-semibold text-gray-400">Last seen:</span> {{ optional($contact->last_seen_at)->diffForHumans() ?? '—' }}</div>
    </div>

    {{-- Recent conversations --}}
    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-2">Recent conversations</h3>
        <div class="divide-y divide-gray-700">
            @forelse($contact->conversations as $conv)
                <a href="{{ route('inbox.conversation', $conv) }}" class="flex justify-between items-start py-3 hover:bg-gray-700 rounded-md px-2">
                    <div>
                        <div class="font-medium">{{ ucfirst($conv->channel) }}</div>
                        <div class="text-gray-400 text-sm">
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
@endsection
