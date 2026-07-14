<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Reports\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function data(Request $request, ReportService $reports)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $period = $request->query('period', 'daily');

        return response()->json([
            'visitors' => $reports->visitorsOverTime($period, $start, $end),
            'returning' => $reports->returningVisitors($start, $end),
            'followups' => $reports->followUpCompletion($start, $end),
            'prayer' => $reports->prayerRequests($start, $end),
            'gender' => $reports->genderDistribution($start, $end),
            'age' => $reports->ageDistribution($start, $end),
            'inviters' => $reports->topInviters($start, $end),
            'services' => $reports->peakServices($start, $end),
        ]);
    }

    public function exportCsv(Request $request, ReportService $reports)
    {
        $start = $request->query('start');
        $end = $request->query('end');
        $period = $request->query('period', 'daily');

        $data = $reports->visitorsOverTime($period, $start, $end);

        $filename = 'visitors_report_'.now()->format('Ymd_His').'.csv';

        $handle = fopen('php://memory', 'w');
        fputcsv($handle, ['period', 'count']);
        foreach ($data as $period => $count) {
            fputcsv($handle, [$period, $count]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportPdf(Request $request, ReportService $reports)
    {
        // Simple HTML export; integrate a PDF library (dompdf/ snappy) in production
        $start = $request->query('start');
        $end = $request->query('end');
        $period = $request->query('period', 'daily');

        $data = $reports->visitorsOverTime($period, $start, $end);

        return view('admin.reports.pdf', compact('data', 'start', 'end', 'period'));
    }
}
