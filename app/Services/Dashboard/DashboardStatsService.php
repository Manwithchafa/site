<?php

namespace App\Services\Dashboard;

use App\Models\Visitor;
use App\Models\VisitorRegistration;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardStatsService
{
    public function summary(): array
    {
        $today = today();

        return [
            'total_visitors' => Visitor::query()->count(),
            'today_registrations' => VisitorRegistration::query()->whereBetween('created_at', [$today->startOfDay(), $today->endOfDay()])->count(),
            'membership_interest' => Visitor::query()->where('wants_membership', true)->count(),
            'prayer_requests' => VisitorRegistration::query()->whereNotNull('prayer_request')->where('prayer_request', '!=', '')->count(),
        ];
    }

    public function monthlyVisitors(int $months = 12): array
    {
        $start = CarbonImmutable::now()->startOfMonth()->subMonths($months - 1);

        $rows = VisitorRegistration::query()
            ->where('created_at', '>=', $start->toDateString())
            ->get(['created_at'])
            ->groupBy(fn (VisitorRegistration $registration) => $registration->created_at->format('Y-m'))
            ->map->count();

        return collect(range(0, $months - 1))
            ->map(fn (int $offset) => $start->addMonths($offset))
            ->map(fn (CarbonImmutable $date) => [
                'label' => $date->format('M Y'),
                'value' => (int) ($rows[$date->format('Y-m')] ?? 0),
            ])
            ->values()
            ->all();
    }

    public function dailyVisitors(int $days = 30): array
    {
        $start = CarbonImmutable::now()->startOfDay()->subDays($days - 1);

        $rows = VisitorRegistration::query()
            ->where('created_at', '>=', $start->toDateString())
            ->get(['created_at'])
            ->groupBy(fn (VisitorRegistration $registration) => $registration->created_at->format('Y-m-d'))
            ->map->count();

        return collect(range(0, $days - 1))
            ->map(fn (int $offset) => $start->addDays($offset))
            ->map(fn (CarbonImmutable $date) => [
                'label' => $date->format('M j'),
                'value' => (int) ($rows[$date->format('Y-m-d')] ?? 0),
            ])
            ->values()
            ->all();
    }

    public function weeklyVisitors(int $weeks = 8): array
    {
        $start = CarbonImmutable::now()->startOfWeek()->subWeeks($weeks - 1);

        return collect(range(0, $weeks - 1))
            ->map(function (int $offset) use ($start) {
                $weekStart = $start->addWeeks($offset);
                $weekEnd = $weekStart->endOfWeek();

                return [
                    'label' => $weekStart->format('M j'),
                    'value' => VisitorRegistration::query()
                        ->whereBetween('created_at', [$weekStart->startOfDay(), $weekEnd->endOfDay()])
                        ->count(),
                ];
            })
            ->values()
            ->all();
    }

    public function genderDistribution(): array
    {
        return Visitor::query()
            ->select('sex', DB::raw('count(*) as aggregate'))
            ->groupBy('sex')
            ->orderBy('sex')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->sex ? ucfirst($row->sex) : 'Unknown',
                'value' => (int) $row->aggregate,
            ])
            ->values()
            ->all();
    }

    public function ageDistribution(): array
    {
        $buckets = [
            'Under 18' => [null, 17],
            '18–25' => [18, 25],
            '26–35' => [26, 35],
            '36–45' => [36, 45],
            '46+' => [46, null],
            'Unknown' => null,
        ];

        return collect($buckets)
            ->map(function (?array $range, string $label) {
                $query = Visitor::query();

                if ($range === null) {
                    $query->whereNull('date_of_birth');
                } else {
                    [$min, $max] = $range;

                    $query->whereNotNull('date_of_birth');

                    if ($min !== null) {
                        $query->whereDate('date_of_birth', '<=', now()->subYears($min)->toDateString());
                    }

                    if ($max !== null) {
                        $query->whereDate('date_of_birth', '>=', now()->subYears($max + 1)->addDay()->toDateString());
                    }
                }

                return [
                    'label' => $label,
                    'value' => $query->count(),
                ];
            })
            ->values()
            ->all();
    }

    public function recentRegistrations(int $limit = 8): Collection
    {
        return VisitorRegistration::query()
            ->with(['visitor', 'qrCode'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function recentActivity(int $limit = 10): Collection
    {
        return VisitorRegistration::query()
            ->with(['visitor'])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (VisitorRegistration $registration) => [
                'title' => "{$registration->visitor->full_name} registered",
                'description' => 'First Timer registration completed',
                'timestamp' => $registration->created_at,
            ]);
    }

    public function prayerRequests(int $limit = 6): Collection
    {
        return VisitorRegistration::query()
            ->with('visitor')
            ->whereNotNull('prayer_request')
            ->where('prayer_request', '!=', '')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function chartPayload(): array
    {
        return [
            'monthlyVisitors' => $this->monthlyVisitors(),
            'weeklyVisitors' => $this->weeklyVisitors(),
            'dailyVisitors' => $this->dailyVisitors(),
            'genderDistribution' => $this->genderDistribution(),
            'ageDistribution' => $this->ageDistribution(),
        ];
    }
}

