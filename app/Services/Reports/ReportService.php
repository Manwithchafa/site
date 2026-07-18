<?php

namespace App\Services\Reports;

use App\Models\Visitor;
use App\Models\VisitorRegistration;
use App\Models\FollowUp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    protected int $cacheTtl;

    public function __construct()
    {
        $this->cacheTtl = config('reports.cache_ttl', 300); // seconds
    }

    public function visitorsOverTime(string $period = 'daily', ?string $start = null, ?string $end = null): array
    {
        $key = "reports.visitors_over_time.{$period}.{$start}.{$end}";

        return Cache::remember($key, $this->cacheTtl, function () use ($period, $start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subDays(30);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $query = VisitorRegistration::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()]);

            $groupFormat = match ($period) {
                'daily' => 'YYYY-MM-DD',
                'weekly' => 'IYYY-IW',
                'monthly' => 'YYYY-MM',
                'yearly' => 'YYYY',
                default => 'YYYY-MM-DD',
            };

            // Use PostgreSQL to_char format via to_char in selectRaw
            $format = match ($period) {
                'daily' => 'YYYY-MM-DD',
                'weekly' => 'IYYY-IW',
                'monthly' => 'YYYY-MM',
                'yearly' => 'YYYY',
                default => 'YYYY-MM-DD',
            };

            $rows = $query->selectRaw("to_char(created_at, '{$format}') as period, count(*) as total")
                ->groupBy('period')
                ->orderBy('period')
                ->get()
                ->pluck('total', 'period')
                ->toArray();

            return $rows;
        });
    }

    public function returningVisitors(?string $start = null, ?string $end = null): array
    {
        $key = "reports.returning.{$start}.{$end}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            // Visitors with more than 1 registration in the period
            $total = VisitorRegistration::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()])
                ->count();

            $unique = VisitorRegistration::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()])
                ->distinct('visitor_id')
                ->count('visitor_id');

            return [
                'total_registrations' => $total,
                'unique_visitors' => $unique,
                'returning_visitors' => max(0, $total - $unique),
            ];
        });
    }

    public function followUpCompletion(?string $start = null, ?string $end = null): array
    {
        $key = "reports.followup.{$start}.{$end}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $total = FollowUp::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()])
                ->count();

            $completed = FollowUp::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()])
                ->whereNotNull('completed_at')
                ->count();

            return [
                'total' => $total,
                'completed' => $completed,
                'completion_rate' => $total > 0 ? round($completed / $total * 100, 2) : 0,
            ];
        });
    }

    public function prayerRequests(?string $start = null, ?string $end = null): array
    {
        $key = "reports.prayer.{$start}.{$end}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $count = VisitorRegistration::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()])
                ->whereNotNull('prayer_request')
                ->count();

            return ['prayer_requests' => $count];
        });
    }

    public function genderDistribution(?string $start = null, ?string $end = null): array
    {
        $key = "reports.gender.{$start}.{$end}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $rows = Visitor::query()
                ->whereHas('registrations', function ($q) use ($startDt, $endDt) {
                    $q->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()]);
                })
                ->selectRaw('gender, count(*) as total')
                ->groupBy('gender')
                ->get()
                ->pluck('total', 'gender')
                ->toArray();

            return $rows;
        });
    }

    public function ageDistribution(?string $start = null, ?string $end = null): array
    {
        $key = "reports.age.{$start}.{$end}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $visitors = Visitor::query()
                ->whereHas('registrations', function ($q) use ($startDt, $endDt) {
                    $q->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()]);
                })
                ->whereNotNull('date_of_birth')
                ->get(['date_of_birth']);

            $buckets = [
                '0-17' => 0,
                '18-24' => 0,
                '25-34' => 0,
                '35-44' => 0,
                '45-54' => 0,
                '55+' => 0,
            ];

            foreach ($visitors as $v) {
                $age = Carbon::parse($v->date_of_birth)->age;
                if ($age < 18) $buckets['0-17']++;
                elseif ($age < 25) $buckets['18-24']++;
                elseif ($age < 35) $buckets['25-34']++;
                elseif ($age < 45) $buckets['35-44']++;
                elseif ($age < 55) $buckets['45-54']++;
                else $buckets['55+']++;
            }

            return $buckets;
        });
    }

    public function topInviters(?string $start = null, ?string $end = null, int $limit = 10): array
    {
        $key = "reports.inviters.{$start}.{$end}.{$limit}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end, $limit) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $rows = Visitor::query()
                ->whereHas('registrations', function ($q) use ($startDt, $endDt) {
                    $q->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()]);
                })
                ->selectRaw("invited_by, count(*) as total")
                ->groupBy('invited_by')
                ->orderByDesc('total')
                ->limit($limit)
                ->get()
                ->pluck('total', 'invited_by')
                ->toArray();

            return $rows;
        });
    }

    public function peakServices(?string $start = null, ?string $end = null): array
    {
        $key = "reports.services.{$start}.{$end}";
        return Cache::remember($key, $this->cacheTtl, function () use ($start, $end) {
            $startDt = $start ? Carbon::parse($start) : Carbon::now()->subMonths(6);
            $endDt = $end ? Carbon::parse($end) : Carbon::now();

            $rows = VisitorRegistration::query()
                ->whereBetween('created_at', [$startDt->startOfDay(), $endDt->endOfDay()])
                ->selectRaw('church_service_id, count(*) as total')
                ->groupBy('church_service_id')
                ->orderByDesc('total')
                ->get()
                ->mapWithKeys(function ($r) {
                    return [$r->church_service_id => $r->total];
                })->toArray();

            return $rows;
        });
    }
}
