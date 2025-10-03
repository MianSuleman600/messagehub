<?php

namespace App\Domain\Reports\Services;

use App\Domain\Messaging\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Generate a summary report of messages per channel and per staff
     *
     * @param string|null $from Start date (Y-m-d)
     * @param string|null $to   End date (Y-m-d)
     * @return array<string, mixed>
     */
    public function summary(?string $from = null, ?string $to = null): array
    {
        $fromDate = $from ? Carbon::parse($from)->startOfDay() : now()->subDays(7)->startOfDay();
        $toDate   = $to   ? Carbon::parse($to)->endOfDay() : now()->endOfDay();

        $key = "report.summary:{$fromDate->toDateString()}:{$toDate->toDateString()}";

        return Cache::remember($key, 300, function () use ($fromDate, $toDate) {

            // Messages count per channel
            $perChannel = Message::query()
                ->select('channel', DB::raw('count(*) as cnt'))
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('channel')
                ->pluck('cnt', 'channel')
                ->toArray();

            // Outbound messages count per staff
            $staffMetrics = Message::query()
                ->select('user_id', DB::raw('count(*) as sent'))
                ->where('direction', 'outbound')
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('user_id')
                ->get()
                ->mapWithKeys(fn ($row) => [$row->user_id => (int) $row->sent]);

            // Fetch staff names
            $users = User::whereIn('id', $staffMetrics->keys())
                ->get(['id', 'name'])
                ->mapWithKeys(fn ($u) => [$u->id => $u->name]);

            return [
                'from' => $fromDate->toDateString(),
                'to' => $toDate->toDateString(),
                'perChannel' => $perChannel,
                'staff' => $staffMetrics->mapWithKeys(fn ($cnt, $uid) => [$users[$uid] ?? "User#{$uid}" => $cnt])->toArray(),
                'total' => array_sum($perChannel),
            ];
        });
    }
}
