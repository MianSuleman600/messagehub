@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Reports</h2>

{{-- Filters Form --}}
<form method="GET" class="bg-gray-800 p-4 rounded-lg shadow-md flex flex-wrap gap-4 mb-6 items-end">
    <div class="flex flex-col">
        <label class="text-gray-400 mb-1">From</label>
        <input type="date" name="from" value="{{ request('from', $summary['from']) }}" class="input">
    </div>
    <div class="flex flex-col">
        <label class="text-gray-400 mb-1">To</label>
        <input type="date" name="to" value="{{ request('to', $summary['to']) }}" class="input">
    </div>
    <button type="submit" class="btn">Update</button>
</form>

{{-- Report Cards --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Messages per channel --}}
    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-3">Messages per channel</h3>
        <canvas id="perChannel" class="w-full h-64"></canvas>
    </div>

    {{-- Staff outbound --}}
    <div class="bg-gray-800 p-4 rounded-lg shadow-md overflow-x-auto">
        <h3 class="text-lg font-semibold mb-3">
            Staff outbound ({{ $summary['from'] }} → {{ $summary['to'] }})
        </h3>
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr class="text-left text-gray-400 uppercase text-sm">
                    <th class="px-3 py-2">User</th>
                    <th class="px-3 py-2 text-right">Sent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($summary['staff'] as $name => $sent)
                    <tr class="hover:bg-gray-700">
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
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endsection
