<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;

class MahasiswaController extends Controller
{
    use \App\Traits\CalendarHelper;

    public function showOrganization($id)
    {
        $organization = Organization::where('status', 'Aktif')->findOrFail($id);

        $events = \App\Models\Event::with('submission')
            ->where('organization_id', $id)
            ->whereNotIn('category', ['Libur', 'Tidak Boleh Berkegiatan'])
            ->where(function ($q) {
                $q->whereHas('submission', function ($sq) {
                    $sq->whereIn('status', ['approved', 'disetujui']);
                })->orWhereNull('submission_id');
            })
            ->orderBy('start_at', 'desc')
            ->get();

        return view('mahasiswa.organisasi.show', compact('organization', 'events'));
    }

    public function calendar(\Illuminate\Http\Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('n'));
        
        $calendarData = $this->generateCalendarData($year, $month);

        return view('mahasiswa.calendar.index', $calendarData);
    }
}
