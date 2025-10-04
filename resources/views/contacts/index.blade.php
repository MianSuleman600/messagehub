@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-3xl font-semibold tracking-tight text-white">Contacts</h2>
    </div>

    <form method="GET" class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg flex flex-wrap gap-4 mb-6">
        <input type="search" name="q" placeholder="Search name, email, phone, handle..." value="{{ $q }}"
               class="flex-1 min-w-[240px] px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 placeholder-gray-500 focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600" />
        <div class="flex items-center gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-semibold transition-colors">Search</button>
            <a href="{{ route('contacts.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-gray-100 font-semibold transition-colors">Reset</a>
        </div>
    </form>

    <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-4 shadow-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-800">
            <thead>
                <tr class="text-left text-gray-400 uppercase text-xs">
                    <th class="px-3 py-2">Name</th>
                    <th class="px-3 py-2">Handle</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Phone</th>
                    <th class="px-3 py-2 text-right">Last seen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($contacts as $c)
                    <tr class="hover:bg-gray-900/60">
                        <td class="px-3 py-2">
                            <a href="{{ route('contacts.show', $c) }}" class="font-semibold text-white hover:underline">
                                {{ $c->name }}
                            </a>
                        </td>
                        <td class="px-3 py-2 text-gray-400">{{ $c->handle ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-400">{{ $c->email ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-400">{{ $c->phone ?? '—' }}</td>
                        <td class="px-3 py-2 text-gray-400 text-right">{{ optional($c->last_seen_at)->diffForHumans() ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-gray-400 text-center">No contacts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection