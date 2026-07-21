<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SpaceAllocation;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year from query, default to today
        $today = Carbon::today();
        $month = intval($request->input('month', $today->month));
        $year = intval($request->input('year', $today->year));

        // Create carbon instance for the selected month
        $selectedDate = Carbon::createFromDate($year, $month, 1);
        $monthName = $selectedDate->translatedFormat('F');

        // Fetch allocations (lease dates)
        $allocations = SpaceAllocation::with(['tenant', 'building'])
            ->whereNotNull('tenant_id')
            ->where(function($q) use ($year, $month) {
                $q->whereMonth('lease_start', $month)->whereYear('lease_start', $year)
                  ->orWhereMonth('lease_end', $month)->whereYear('lease_end', $year);
            })
            ->get();

        // Parse events into array grouped by day
        $eventsByDay = [];

        foreach ($allocations as $alloc) {
            if ($alloc->lease_start) {
                $startDate = Carbon::parse($alloc->lease_start);
                if ($startDate->month == $month && $startDate->year == $year) {
                    $day = $startDate->day;
                    $eventsByDay[$day][] = [
                        'type' => 'Masuk', // Tenant Masuk (Green/Inspeksi style)
                        'title' => 'Tenant Masuk: ' . ($alloc->tenant->company_name ?? 'Tenant'),
                        'detail' => ($alloc->building->name ?? '') . ' - ' . ($alloc->floor_number ? 'Lt. ' . $alloc->floor_number . ' - ' : '') . $alloc->unit_number
                    ];
                }
            }

            if ($alloc->lease_end) {
                $endDate = Carbon::parse($alloc->lease_end);
                if ($endDate->month == $month && $endDate->year == $year) {
                    $day = $endDate->day;
                    $eventsByDay[$day][] = [
                        'type' => 'Renewal', // Lease Berakhir (Orange style)
                        'title' => 'Lease Berakhir: ' . ($alloc->tenant->company_name ?? 'Tenant'),
                        'detail' => ($alloc->building->name ?? '') . ' - ' . ($alloc->floor_number ? 'Lt. ' . $alloc->floor_number . ' - ' : '') . $alloc->unit_number
                    ];
                }
            }
        }

        // Generate calendar grid
        $daysInMonth = $selectedDate->daysInMonth;
        
        // Find which day of week the 1st falls on
        $firstDayOfWeek = $selectedDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
        // Convert to Monday start (1 = Mon, 7 = Sun)
        $firstDayOfWeek = $firstDayOfWeek == 0 ? 7 : $firstDayOfWeek;
        
        // Leading empty slots
        $emptySlotsBefore = $firstDayOfWeek - 1;
        
        $calendarGrid = [];
        // Fill empty leading slots
        for ($i = 0; $i < $emptySlotsBefore; $i++) {
            $calendarGrid[] = null;
        }
        
        // Fill days of month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $calendarGrid[] = [
                'day' => $day,
                'events' => $eventsByDay[$day] ?? []
            ];
        }

        // Trailing empty slots to complete the week grid (multiple of 7)
        while (count($calendarGrid) % 7 !== 0) {
            $calendarGrid[] = null;
        }

        // Previous and Next month links
        $prevDate = $selectedDate->copy()->subMonth();
        $nextDate = $selectedDate->copy()->addMonth();

        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'monthName' => $monthName,
                'month' => $month,
                'year' => $year,
                'grid_html' => view('admin.calendar.partials.grid', compact('calendarGrid', 'month', 'year'))->render(),
                'agenda_html' => view('admin.calendar.partials.agenda', compact('calendarGrid', 'monthName'))->render(),
            ]);
        }

        return view('admin.calendar.index', compact(
            'month', 'year', 'monthName', 'calendarGrid', 'eventsByDay',
            'prevDate', 'nextDate'
        ));
    }
}
