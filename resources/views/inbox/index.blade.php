@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-3xl font-semibold tracking-tight text-white">Inbox</h2>
    </div>

    <form method="GET" class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg flex flex-wrap gap-4 mb-6">
        <div class="flex flex-col">
            <label class="text-gray-400 text-sm mb-1">Channel</label>
            <select name="channel" class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
                <option value="">All</option>
                @foreach($channels as $ch)
                    <option value="{{ $ch }}" @selected(($filters['channel'] ?? '') === $ch)>{{ ucfirst($ch) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label class="text-gray-400 text-sm mb-1">Status</label>
            <select name="status" class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
                <option value="">All</option>
                @foreach($statuses as $st)
                    <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label class="text-gray-400 text-sm mb-1">Assignee</label>
            <select name="assignee" class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
                @foreach($assignees as $as)
                    <option value="{{ $as }}" @selected(($filters['assignee'] ?? 'all') === $as)>{{ ucfirst($as) }}</option>
                @endforeach
            </select>
        </div>

        <input type="search" name="q" placeholder="Search contact/message..." value="{{ $filters['q'] ?? '' }}"
               class="flex-1 min-w-[220px] px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600" />

        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-semibold transition-colors">Apply</button>
            <a href="{{ route('inbox.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-100 font-semibold transition-colors">Reset</a>
        </div>
    </form>

    <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-4 shadow-lg">
        <div class="divide-y divide-gray-800">
            @forelse($conversations as $conv)
                <a href="{{ route('inbox.conversation', $conv) }}" class="flex justify-between items-start py-3 px-2 rounded-lg hover:bg-gray-900/60 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <strong class="text-white truncate">{{ $conv->contact->name ?? 'Unknown' }}</strong>
                            <span class="text-gray-400">· {{ ucfirst($conv->channel) }}</span>
                        </div>
                        <div class="text-gray-400 text-sm mt-1 truncate">
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
</div>
@endsection