<?php

namespace App\Traits;

trait CalendarHelper
{
    protected function generateCalendarData($year, $month)
    {
        $events = \App\Models\CalendarEvent::orderBy('start_date')->get();

        $carbonDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $carbonDate->daysInMonth;
        $firstDayOfWeek = $carbonDate->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
        
        $calendarGrid = [];
        $dayCounter = 1;
        $week = 0;
        
        // Offset for the first day of the month
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendarGrid[$week][] = null;
        }

        // Show approved/upcoming/ongoing/selesai UKM events on the calendar
        // Also include draft events with approved submissions (for sync with kemahasiswaan approval flow)
        $ukmEvents = \App\Models\Event::with('submission')
            ->where(function ($q) {
                $q->whereIn('status', ['upcoming', 'ongoing', 'selesai', 'approved', 'berlangsung'])
                  ->orWhereHas('submission', function ($sq) {
                      $sq->whereIn('status', ['approved', 'disetujui']);
                  });
            })
            ->get();

        while ($dayCounter <= $daysInMonth) {
            $currentDateStr = sprintf('%04d-%02d-%02d', $year, $month, $dayCounter);
            
            $dayEvents = $events->filter(function($e) use ($currentDateStr) {
                return $e->start_date <= $currentDateStr && $e->end_date >= $currentDateStr;
            });
            $dayUkmEvents = $ukmEvents->filter(function($e) use ($currentDateStr) {
                $start = $e->start_at ? $e->start_at->toDateString() : null;
                $end = $e->end_at ? $e->end_at->toDateString() : $start;
                return $start && $start <= $currentDateStr && $end >= $currentDateStr;
            });
            
            $calendarGrid[$week][] = [
                'day' => $dayCounter,
                'date' => $currentDateStr,
                'events' => $dayEvents,
                'ukmEvents' => $dayUkmEvents
            ];
            
            if (count($calendarGrid[$week]) == 7) {
                $week++;
            }
            $dayCounter++;
        }
        
        // Fill the rest of the last week
        while (isset($calendarGrid[$week]) && count($calendarGrid[$week]) < 7) {
            $calendarGrid[$week][] = null;
        }

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        return [
            'events' => $events,
            'ukmEvents' => $ukmEvents,
            'calendarGrid' => $calendarGrid,
            'currentYear' => $year,
            'currentMonth' => $month,
            'monthName' => $monthName,
        ];
    }
}
