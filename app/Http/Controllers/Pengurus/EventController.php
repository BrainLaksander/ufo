<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    use PengurusControllerTrait;

    public function index(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);

        $events = $this->loadEventCards($organizationId);

        $activeEvents = array_values(array_filter($events, static function (array $event): bool {
            return !in_array($event['raw_status'], ['completed', 'selesai', 'cancelled'], true);
        }));

        $completedEvents = array_values(array_filter($events, static function (array $event): bool {
            return in_array($event['raw_status'], ['completed', 'selesai'], true);
        }));

        return view('portal.pengurus.events', [
            'activeEvents' => $activeEvents,
            'completedEvents' => $completedEvents,
        ]);
    }

    public function detail(Request $request, int $id): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);
        $event = collect($this->loadEventCards($organizationId))->firstWhere('id', $id);

        abort_if($event === null, 404);

        return view('portal.pengurus.events.detail', [
            'event' => $event,
        ]);
    }

    public function form(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        return view('portal.pengurus.events.form', [
            'hasApprovedIzin' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);
        if (!$context['organization_id']) {
            return back()->with('error', 'Konteks organisasi tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'location' => 'required|string|max:200',
            'quota' => 'required|integer|min:1',
        ]);

        return back()->with('success', 'Event berhasil dibuat.');
    }

    public function submit(Request $request, int $id): RedirectResponse
    {
        return back()->with('success', 'Event berhasil disubmit.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        return back()->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadEventCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('events')) {
            return [];
        }

        $participantColumn = Schema::hasColumn('events', 'current_participants')
            ? 'current_participants'
            : (Schema::hasColumn('events', 'participants_count') ? 'participants_count' : null);

        $query = DB::table('events')
            ->where('organization_id', $organizationId)
            ->orderByDesc('start_date')
            ->select(['id', 'name', 'description', 'start_date', 'end_date', 'location', 'quota', 'banner', 'status']);

        if ($participantColumn) {
            $query->addSelect($participantColumn . ' as participants_total');
        } else {
            $query->selectRaw('0 as participants_total');
        }

        return $query->get()->map(function ($row) {
            $startDate = Carbon::parse((string) $row->start_date);
            $endDate = !empty($row->end_date) ? Carbon::parse((string) $row->end_date) : $startDate->copy();
            $rawStatus = Str::lower((string) ($row->status ?? 'draft'));
            $participants = (int) ($row->participants_total ?? 0);

            return [
                'id' => (int) $row->id,
                'title' => (string) $row->name,
                'description' => (string) ($row->description ?? ''),
                'date' => $startDate->translatedFormat('d M Y'),
                'raw_date' => $startDate->toDateString(),
                'time' => $startDate->format('H:i') . ' - ' . $endDate->format('H:i'),
                'location' => (string) ($row->location ?? '-'),
                'quota' => (int) ($row->quota ?? 0),
                'registrants' => $participants,
                'participants' => $participants,
                'status' => $this->eventStatusLabel($rawStatus),
                'raw_status' => $rawStatus,
                'pill' => $this->eventStatusPill($rawStatus),
                'banner' => trim((string) ($row->banner ?? '')),
                'has_news' => in_array($rawStatus, ['completed', 'selesai'], true),
            ];
        })->all();
    }

    private function eventStatusLabel(string $status): string
    {
        return match ($status) {
            'draft' => 'Draft',
            'approved' => 'Disetujui',
            'ongoing', 'berjalan' => 'Berlangsung',
            'completed', 'selesai' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => Str::title(str_replace('_', ' ', $status)),
        };
    }

    private function eventStatusPill(string $status): string
    {
        return match ($status) {
            'approved' => 'approved',
            'ongoing', 'berjalan' => 'pending',
            'completed', 'selesai' => 'approved',
            'cancelled' => 'rejected',
            default => 'draft',
        };
    }
}
