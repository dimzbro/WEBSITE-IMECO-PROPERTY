<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SpaceAllocation;
use App\Models\CalendarEvent;
use App\Services\NotificationService;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Run check & generate notifications
        NotificationService::checkAndGenerateCalendarNotifications();

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
                        'id' => 'alloc_start_' . $alloc->id,
                        'is_custom' => false,
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
                        'id' => 'alloc_end_' . $alloc->id,
                        'is_custom' => false,
                        'type' => 'Renewal', // Lease Berakhir (Orange style)
                        'title' => 'Lease Berakhir: ' . ($alloc->tenant->company_name ?? 'Tenant'),
                        'detail' => ($alloc->building->name ?? '') . ' - ' . ($alloc->floor_number ? 'Lt. ' . $alloc->floor_number . ' - ' : '') . $alloc->unit_number
                    ];
                }
            }
        }

        // Fetch custom agenda/calendar events for this month & year
        $customEvents = CalendarEvent::whereMonth('event_date', $month)
            ->whereYear('event_date', $year)
            ->orderBy('event_date', 'asc')
            ->get();

        foreach ($customEvents as $cEvent) {
            $eventDate = Carbon::parse($cEvent->event_date);
            $day = $eventDate->day;
            $eventsByDay[$day][] = [
                'id' => $cEvent->id,
                'is_custom' => true,
                'type' => $cEvent->category, // Meeting, Inspeksi, Maintenance, Acara, Lainnya
                'title' => $cEvent->title,
                'detail' => ($cEvent->location ? $cEvent->location . ' - ' : '') . $eventDate->format('H:i') . ' WIB' . ($cEvent->notes ? ' (' . $cEvent->notes . ')' : ''),
                'event_date' => $cEvent->event_date ? $cEvent->event_date->format('Y-m-d\TH:i') : null,
                'reminder_time' => $cEvent->reminder_time,
                'location' => $cEvent->location,
                'notes' => $cEvent->notes,
                'category' => $cEvent->category,
            ];
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

        $targetEventId = $request->input('event_id');

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
            'prevDate', 'nextDate', 'targetEventId'
        ));
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category'      => 'required|string|in:Meeting,Inspeksi,Maintenance,Acara,Lainnya',
            'event_date'    => 'required|date',
            'location'      => 'nullable|string|max:255',
            'reminder_time' => 'required|string|in:same_time,15_min_before,30_min_before,1_hour_before,1_day_before,2_days_before',
            'notes'         => 'nullable|string',
        ], [
            'title.required'         => 'Judul agenda wajib diisi.',
            'category.required'      => 'Kategori agenda wajib dipilih.',
            'event_date.required'    => 'Tanggal & waktu agenda wajib diisi.',
            'reminder_time.required' => 'Waktu pengingat wajib dipilih.',
        ]);

        $event = CalendarEvent::create($validated);

        // Instantly generate event created notification
        NotificationService::createEventCreatedNotification($event, false);
        NotificationService::checkAndGenerateCalendarNotifications();

        $eventTime = Carbon::parse($event->event_date);

        return redirect()->route('admin.calendar.index', [
            'month' => $eventTime->month,
            'year'  => $eventTime->year,
        ])->with('success', "Agenda '{$event->title}' berhasil ditambahkan ke kalender.");
    }

    public function updateEvent(Request $request, $id)
    {
        $event = CalendarEvent::findOrFail($id);

        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'category'      => 'required|string|in:Meeting,Inspeksi,Maintenance,Acara,Lainnya',
            'event_date'    => 'required|date',
            'location'      => 'nullable|string|max:255',
            'reminder_time' => 'required|string|in:same_time,15_min_before,30_min_before,1_hour_before,1_day_before,2_days_before',
            'notes'         => 'nullable|string',
        ]);

        $event->update($validated);

        // Generate update notification
        NotificationService::createEventCreatedNotification($event, true);
        NotificationService::checkAndGenerateCalendarNotifications();

        $eventTime = Carbon::parse($event->event_date);

        return redirect()->route('admin.calendar.index', [
            'month' => $eventTime->month,
            'year'  => $eventTime->year,
        ])->with('success', "Agenda '{$event->title}' berhasil diperbarui.");
    }

    public function destroyEvent($id)
    {
        $event = CalendarEvent::findOrFail($id);
        $title = $event->title;
        $event->delete();

        return redirect()->back()->with('success', "Agenda '{$title}' telah dihapus dari kalender.");
    }
}
