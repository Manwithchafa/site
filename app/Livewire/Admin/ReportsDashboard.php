<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\Reports\ReportService;

class ReportsDashboard extends Component
{
    public ?string $start = null;
    public ?string $end = null;
    public string $period = 'daily';

    public array $data = [];

    public function mount(ReportService $reports)
    {
        $this->load($reports);
    }

    public function load(ReportService $reports)
    {
        $this->data = [
            'visitors' => $reports->visitorsOverTime($this->period, $this->start, $this->end),
            'returning' => $reports->returningVisitors($this->start, $this->end),
            'followups' => $reports->followUpCompletion($this->start, $this->end),
            'prayer' => $reports->prayerRequests($this->start, $this->end),
            'gender' => $reports->genderDistribution($this->start, $this->end),
            'age' => $reports->ageDistribution($this->start, $this->end),
            'inviters' => $reports->topInviters($this->start, $this->end),
            'services' => $reports->peakServices($this->start, $this->end),
        ];
    }

    public function render()
    {
        return view('livewire.admin.reports-dashboard');
    }
}
