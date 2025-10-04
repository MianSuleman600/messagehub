@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto text-gray-100">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-3xl font-semibold tracking-tight text-white">Reports</h2>
    </div>

    {{-- Filters Form --}}
    <form method="GET" class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg flex flex-wrap gap-4 items-end mb-8">
        <div class="flex flex-col">
            <label class="text-gray-400 text-sm mb-1">From</label>
            <input type="date" name="from" value="{{ request('from', $summary['from']) }}"
                   class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
        </div>
        <div class="flex flex-col">
            <label class="text-gray-400 text-sm mb-1">To</label>
            <input type="date" name="to" value="{{ request('to', $summary['to']) }}"
                   class="px-3 py-2 rounded-lg bg-gray-900/80 border border-gray-800 text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-600">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-white font-semibold transition-colors">Update</button>
    </form>

    {{-- Report Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Messages per channel --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg">
            <h3 class="text-lg font-semibold mb-3">Messages per channel</h3>
            <canvas id="perChannel" class="w-full h-64"></canvas>
        </div>

        {{-- Staff outbound --}}
        <div class="bg-gray-950/60 border border-gray-800 rounded-2xl p-5 shadow-lg overflow-x-auto">
            <h3 class="text-lg font-semibold mb-3">
                Staff outbound ({{ $summary['from'] }} → {{ $summary['to'] }})
            </h3>
            <table class="min-w-full divide-y divide-gray-800">
                <thead>
                    <tr class="text-left text-gray-400 uppercase text-xs">
                        <th class="px-3 py-2">User</th>
                        <th class="px-3 py-2 text-right">Sent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($summary['staff'] as $name => $sent)
                        <tr class="hover:bg-gray-900/60">
                            <td class="px-3 py-2">{{ $name }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($sent) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-3 py-4 text-gray-400 text-center">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('perChannel').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($summary['perChannel'])) !!},
            datasets: [{
                label: 'Messages',
                data: {!! json_encode(array_values($summary['perChannel'])) !!},
                backgroundColor: '#2563eb'
            }]
        },
        options: {
            plugins: { legend: { display: false }},
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>
</div>
@endsection