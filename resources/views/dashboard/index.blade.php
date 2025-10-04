@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100 space-y-6">
    {{-- Welcome panel --}}
    <div class="bg-gray-950/60 border border-gray-800 text-gray-100 p-6 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-semibold mb-2">Welcome, {{ auth()->user()->name }}</h2>

        @role('Admin')
            <p class="mb-3 text-gray-300">You are logged in as <strong>Admin</strong>.</p>
            <ul class="list-disc pl-5 space-y-1 text-gray-300">
                <li>View team activity</li>
                <li>Manage roles and permissions</li>
                <li>Assign conversations</li>
            </ul>
        @else
            <p class="mb-3 text-gray-300">You are logged in as <strong>Staff</strong>.</p>
            <ul class="list-disc pl-5 space-y-1 text-gray-300">
                <li>Handle assigned conversations</li>
                <li>Reply from the inbox</li>
            </ul>
        @endrole
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-gray-950/60 border border-gray-800 text-gray-100 p-6 rounded-2xl shadow-lg">
            <div class="text-gray-400 mb-1">Total messages</div>
            <div class="text-2xl font-bold text-white">{{ number_format($summary['total'] ?? 0) }}</div>
        </div>
        <div class="bg-gray-950/60 border border-gray-800 text-gray-100 p-6 rounded-2xl shadow-lg">
            <div class="text-gray-400 mb-1">Date range</div>
            <div class="text-gray-200">{{ $summary['from'] ?? '—' }} → {{ $summary['to'] ?? '—' }}</div>
        </div>
        <div class="bg-gray-950/60 border border-gray-800 text-gray-100 p-6 rounded-2xl shadow-lg">
            <div class="text-gray-400 mb-1">Active channels</div>
            <div class="text-gray-200">{{ implode(', ', array_keys($summary['perChannel'] ?? [])) ?: '—' }}</div>
        </div>
    </div>

    {{-- Detailed metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-gray-950/60 border border-gray-800 text-gray-100 p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold mb-2">Per channel</h3>
            <ul class="space-y-2">
                @forelse($summary['perChannel'] ?? [] as $channel => $count)
                    <li class="flex justify-between items-center">
                        <span class="flex items-center gap-2">
                            <span>🔹</span>
                            <strong class="capitalize text-white">{{ $channel }}</strong>
                        </span>
                        <span class="text-gray-200">{{ number_format($count) }}</span>
                    </li>
                @empty
                    <li class="text-gray-400">No data</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-gray-950/60 border border-gray-800 text-gray-100 p-6 rounded-2xl shadow-lg">
            <h3 class="text-lg font-semibold mb-2">Staff sent (last 7 days)</h3>
            <ul class="space-y-2">
                @forelse($summary['staff'] ?? [] as $name => $sent)
                    <li class="flex justify-between">
                        <span class="text-white">{{ $name }}</span>
                        <span class="text-gray-200">{{ number_format($sent) }}</span>
                    </li>
                @empty
                    <li class="text-gray-400">No data</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection