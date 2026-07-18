<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VisitorExportController extends Controller
{
    public function __invoke(Request $request)
    {
        $visitors = Visitor::query()
            ->with(['church', 'registrations.churchService'])
            ->withCount(['registrations', 'assignments', 'notes'])
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $search = trim((string) $request->query('q'));

                $query->where(function (Builder $query) use ($search) {
                    $query
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nearest_bus_stop', 'like', "%{$search}%")
                        ->orWhere('occupation', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('sex'), fn (Builder $query) => $query->where('sex', $request->query('sex')))
            ->when($request->filled('membership'), fn (Builder $query) => $query->where('wants_membership', $request->query('membership') === 'yes'))
            ->when($request->filled('bornAgain'), fn (Builder $query) => $query->where('born_again', $request->query('bornAgain') === 'yes'))
            ->when($request->filled('registered'), function (Builder $query) use ($request) {
                $query->whereHas('registrations', function (Builder $query) use ($request) {
                    match ($request->query('registered')) {
                        'today' => $query->whereDate('registered_on', today()),
                        'week' => $query->whereDate('registered_on', '>=', now()->startOfWeek()->toDateString()),
                        'month' => $query->whereDate('registered_on', '>=', now()->startOfMonth()->toDateString()),
                        default => null,
                    };
                });
            })
            ->latest()
            ->get();

        $handle = fopen('php://memory', 'w');

        fputcsv($handle, [
            'First Name',
            'Last Name',
            'Gender',
            'Date of Birth',
            'Phone',
            'Email',
            'Residential Address',
            'Nearest Bus Stop',
            'Occupation',
            'Invited By',
            'Born Again',
            'Wants Membership',
            'Status',
            'Church',
            'Registrations',
            'Assignments',
            'Notes',
            'Last Registered Service',
            'Last Registered At',
            'Created At',
        ]);

        foreach ($visitors as $visitor) {
            $lastRegistration = $visitor->registrations->sortByDesc('created_at')->first();

            fputcsv($handle, [
                $visitor->first_name,
                $visitor->last_name,
                ucfirst($visitor->sex),
                $visitor->date_of_birth?->toDateString(),
                $visitor->phone,
                $visitor->email,
                $visitor->residential_address,
                $visitor->nearest_bus_stop,
                $visitor->occupation,
                $visitor->invited_by,
                $visitor->born_again ? 'Yes' : 'No',
                $visitor->wants_membership ? 'Yes' : 'No',
                ucfirst($visitor->status),
                $visitor->church?->name,
                $visitor->registrations_count,
                $visitor->assignments_count,
                $visitor->notes_count,
                $lastRegistration?->churchService?->name,
                $lastRegistration?->created_at?->format('Y-m-d H:i:s'),
                $visitor->created_at?->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $filename = 'visitors_'.now()->format('Ymd_His').'.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
