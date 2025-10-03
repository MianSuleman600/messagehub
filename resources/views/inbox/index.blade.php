@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Inbox</h2>

<form method="GET" class="bg-gray-800 p-4 rounded-lg shadow-md flex flex-wrap gap-4 mb-6">
    <div class="flex flex-col">
        <label class="text-gray-400 text-sm mb-1">Channel</label>
        <select name="channel" class="input">
            <option value="">All</option>
            @foreach($channels as $ch)
                <option value="{{ $ch }}" @selected(($filters['channel'] ?? '') === $ch)>{{ ucfirst($ch) }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label class="text-gray-400 text-sm mb-1">Status</label>
        <select name="status" class="input">
            <option value="">All</option>
            @foreach($statuses as $st)
                <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label class="text-gray-400 text-sm mb-1">Assignee</label>
        <select name="assignee" class="input">
            @foreach($assignees as $as)
                <option value="{{ $as }}" @selected(($filters['assignee'] ?? 'all') === $as)>{{ ucfirst($as) }}</option>
            @endforeach
        </select>
    </div>

    <input type="search" name="q" placeholder="Search contact/message..." value="{{ $filters['q'] ?? '' }}" class="input flex-1 min-w-[200px]" />
    <button type="submit" class="btn">Apply</button>
    <a href="{{ route('inbox.index') }}" class="btn secondary">Reset</a>
</form>

<div class="bg-gray-800 p-4 rounded-lg shadow-md">
    <div class="divide-y divide-gray-700">
        @forelse($conversations as $conv)
            <a href="{{ route('inbox.conversation', $conv) }}" class="flex justify-between items-start py-3 hover:bg-gray-700 rounded-md px-2">
                <div>
                    <div class="flex items-center gap-2">
                        <strong>{{ $conv->contact->name ?? 'Unknown' }}</strong>
                        <span class="text-gray-400">· {{ ucfirst($conv->channel) }}</span>
                    </div>
                    <div class="text-gray-400 text-sm mt-1">
                        Assigned: {{ $conv->assignee?->name ?? 'Unassigned' }} · Status: {{ ucfirst($conv->status) }}
                    </div>
                </div>
                <div class="text-gray-400 text-sm whitespace-nowrap">
                    {{ optional($conv->last_message_at)->diffForHumans() }}
                </div>
            </a>
        @empty
            <div class="text-gray-400 p-4">No conversations found.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $conversations->links() }}
    </div>
</div>
@endsection
