@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Contacts</h2>

<form method="GET" class="bg-gray-800 p-4 rounded-lg shadow-md flex flex-wrap gap-4 mb-6">
    <input type="search" name="q" placeholder="Search name, email, phone, handle..." value="{{ $q }}" class="input flex-1 min-w-[200px]" />
    <button type="submit" class="btn">Search</button>
    <a href="{{ route('contacts.index') }}" class="btn secondary">Reset</a>
</form>

<div class="bg-gray-800 p-4 rounded-lg shadow-md overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-700">
        <thead>
            <tr class="text-left text-gray-400 uppercase text-sm">
                <th class="px-3 py-2">Name</th>
                <th class="px-3 py-2">Handle</th>
                <th class="px-3 py-2">Email</th>
                <th class="px-3 py-2">Phone</th>
                <th class="px-3 py-2 text-right">Last seen</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($contacts as $c)
                <tr class="hover:bg-gray-700">
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
@endsection
