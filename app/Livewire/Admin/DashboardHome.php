<?php

namespace App\Livewire\Admin;

use App\Services\Dashboard\DashboardStatsService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DashboardHome extends Component
{
    public function render(DashboardStatsService $stats): View
    {
        return view('livewire.admin.dashboard-home', [
            'summary' => $stats->summary(),
            'charts' => $stats->chartPayload(),
            'recentRegistrations' => $stats->recentRegistrations(),
            'recentActivity' => $stats->recentActivity(),
            'prayerRequests' => $stats->prayerRequests(),
        ])->layout('components.layouts.admin', [
            'title' => 'Dashboard',
            'pageTitle' => 'Dashboard',
            'pageDescription' => 'Visitor intelligence, service activity, and follow-up signals.',
        ]);
    }
}
